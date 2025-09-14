<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Elastic;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Document\ComponenteDigital as ComponenteDigitalDocument;
use SuppCore\AdministrativoBackend\Document\Usuario;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Utils\OCRInterface;
use SuppCore\AdministrativoBackend\Utils\PDFToolsInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ComponenteDigitalDocumentService.
 */
class ComponenteDigitalDocumentService
{
    public const DEFAULT_OCR_ENABLED = false;
    public const DEFAULT_OCR_PIPELINE_TIMEOUT = 300;

    private array $configs = [
        'ocr_enabled' => self::DEFAULT_OCR_ENABLED,
        'ocr_pipeline_timeout' => self::DEFAULT_OCR_PIPELINE_TIMEOUT,
    ];

    /**
     * ComponenteDigitalDocumentService constructor.
     *
     * @param PDFToolsInterface $pdfTools
     * @param OCRInterface      $ocr
     * @param LoggerInterface   $logger
     */
    public function __construct(
        private PDFToolsInterface $pdfTools,
        private OCRInterface $ocr,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @param array $configs
     */
    public function setConfigs(array $configs): void
    {
        foreach (array_keys($configs) as $key) {
            if (!in_array($key, array_keys($this->configs))) {
                throw new InvalidArgumentException("Atributo de configurtação [$key] inválido.");
            }
        }
        $this->configs = array_merge($this->configs, $configs);
    }

    /**
     * @param ComponenteDigitalEntity $componenteDigitalEntity
     *
     * @return ComponenteDigitalDocument
     */
    public function convertToDocument(ComponenteDigitalEntity $componenteDigitalEntity): ComponenteDigitalDocument
    {
        $componenteDigitalDocument = new ComponenteDigitalDocument();
        $componenteDigitalDocument->setId($componenteDigitalEntity->getId());
        $componenteDigitalDocument->setFileName($componenteDigitalEntity->getFileName());
        $componenteDigitalDocument->setExtensao($componenteDigitalEntity->getExtensao());
        $componenteDigitalDocument->setCriadoEm($componenteDigitalEntity->getCriadoEm());
        $componenteDigitalDocument->setEditavel($componenteDigitalEntity->getEditavel());

        if ($componenteDigitalEntity->getDocumento()->getNumeroUnicoDocumento()) {
            $componenteDigitalDocument->setNumeroUnicoDocumento(
                $componenteDigitalEntity->getDocumento()->getNumeroUnicoDocumento()->geraNumeroUnico()
            );
        }

        if ($componenteDigitalEntity->getCriadoPor()) {
            $usuarioDocument = new Usuario();
            $usuarioDocument->setId(
                $componenteDigitalEntity->getCriadoPor()->getId()
            );
            $usuarioDocument->setNome(
                $componenteDigitalEntity->getCriadoPor()->getNome()
            );
            $componenteDigitalDocument->setCriadoPor($usuarioDocument);
        }

        $conteudo = $componenteDigitalEntity->getConteudo();

        $this->logger->debug(
            sprintf('OCR: Status: %s', $this->configs['ocr_enabled'] ? 'Enabled' : 'Disabled')
        );

        if ($this->configs['ocr_enabled'] && ('application/pdf' === $componenteDigitalEntity->getMimetype())) {
            $temp = rtrim(sys_get_temp_dir());
            $pdfFilename = $temp.DIRECTORY_SEPARATOR.uniqid().'.pdf';
            $this->storeFile($pdfFilename, $conteudo);

            if (!$this->pdfTools->hasText($pdfFilename) && $this->pdfTools->hasImages($pdfFilename)) {
                $this->logger->debug(
                    sprintf(
                        'OCR: Processing Componente Digital ID: %s, with timeout: %s',
                        $componenteDigitalEntity->getId(),
                        $this->configs['ocr_pipeline_timeout']
                    )
                );
                $imageFilename = $temp.DIRECTORY_SEPARATOR.uniqid().'.png';
                $this->pdfTools->setTimeout($this->configs['ocr_pipeline_timeout']);
                $this->pdfTools->pdfToImage($pdfFilename, $imageFilename);

                $this->ocr->setTimeout($this->configs['ocr_pipeline_timeout']);
                $text = $this->ocr->getText($imageFilename);
                $this->logger->debug(sprintf('OCR: Extrar text: %s', $text));
                $this->removeFile($imageFilename);

                $conteudo = $this->pdfTools->textToPDF(mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8'));
                $this->logger->debug(
                    sprintf('OCR: Componente Digital ID: %s, processed.', $componenteDigitalEntity->getId())
                );
            }

            $this->removeFile($pdfFilename);
        }

        $componenteDigitalDocument->setConteudo(base64_encode($conteudo));

        return $componenteDigitalDocument;
    }

    /**
     * Grava fisicamente o arquivo no filesystem.
     *
     * @param string $filename
     * @param string $data
     */
    private function storeFile(string $filename, string $data): void
    {
        $this->logger->debug(sprintf('OCR: Store temporary file %s', $filename));
        file_put_contents($filename, $data);
    }

    private function removeFile(string $filename): void
    {
        $this->logger->debug(sprintf('OCR: Removing temporary file %s', $filename));
        $filesystem = new Filesystem();
        $filesystem->remove($filename);
    }
}
