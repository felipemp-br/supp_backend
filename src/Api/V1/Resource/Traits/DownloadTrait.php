<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Resource\Traits;

use RuntimeException;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Utils\ZipStream;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Disponibiliza um método para realizar o Download de Componentes Digitais.
 *
 * Trait DownloadAsPdfTrait
 */
trait DownloadTrait
{
    protected ComponenteDigitalResource $componenteDigitalResource;

    protected ZipStream $zipStream;

    /**
     * @param ComponenteDigitalResource $componenteDigitalResource
     */
    #[Required]
    public function setComponenteDigitalResource(ComponenteDigitalResource $componenteDigitalResource): void
    {
        $this->componenteDigitalResource = $componenteDigitalResource;
    }

    /**
     * Generic method to Download specified entity with new data.
     *
     * @param int    $id
     * @param string $transactionId
     * @param bool   $asPdf
     *
     * @return EntityInterface
     */
    public function download(int $id, string $transactionId, bool $asPdf = true): EntityInterface
    {
        // Fetch entity
        $entity = $this->getEntity($id);

        return $this->downloadComponentesDigitais($this->getComponentesDigitais($entity), $transactionId, $asPdf);
    }

    /**
     * @param array  $componentesDigitais
     * @param string $transactionId
     * @param bool   $asPdf
     *
     * @return ComponenteDigital
     */
    protected function downloadComponentesDigitais(
        array $componentesDigitais,
        string $transactionId,
        bool $asPdf = true
    ): ComponenteDigital {
        $conteudo = $asPdf ?
            $this->juntarComponentesDigitaisAsPdf($componentesDigitais, $transactionId) :
            $this->juntarComponentesDigitaisAsZip($componentesDigitais, $transactionId);

        return (new ComponenteDigital())
            ->setConteudo($conteudo)
            ->setEditavel(false)
            ->setExtensao($asPdf ? 'pdf' : 'zip')
            ->setMimetype($asPdf ? 'application/pdf' : 'application/zip')
            ->setTamanho(strlen($conteudo))
            ->setFileName($asPdf ? 'documentos.pdf' : 'documentos.zip')
            ->setDocumento(new Documento());
    }

    /**
     * @param array  $componentesDigitais
     * @param string $transactionId
     *
     * @return string
     */
    protected function juntarComponentesDigitaisAsZip(array $componentesDigitais, string $transactionId): string
    {
        /* @var ComponenteDigital $componenteDigital */
        $this->zipStream = new ZipStream();
        foreach ($componentesDigitais as $componenteDigital) {
            $conteudo = $this
                ->componenteDigitalResource
                ->download($componenteDigital->getId(), $transactionId)->getConteudo();

            $this->zipStream->addFile($conteudo, $componenteDigital->getFileName());
        }
        $this->zipStream->setComment('Download de documentos do Sistema SUPP');
        $conteudo = $this->zipStream->getZipData();
        if (!$conteudo) {
            throw new RuntimeException('Erro! Não foi possível gerar o ZIP completo!');
        }

        return $conteudo;
    }

    /**
     * @param ComponenteDigital[] $componentesDigitais
     * @param string              $transactionId
     *
     * @return string
     */
    protected function juntarComponentesDigitaisAsPdf(array $componentesDigitais, string $transactionId): string
    {
        $files = array_map(
            function (ComponenteDigital $c) use ($transactionId) {
                $c = $this->componenteDigitalResource->download(
                    $c->getId(),
                    $transactionId,
                    true,
                    true,
                    null,
                    true
                );
                $tempFilename = tempnam(sys_get_temp_dir(), 'compDigital_');
                $handle = fopen($tempFilename, 'w+');
                if (false === fwrite($handle, $c->getConteudo())) {
                    throw new RuntimeException('Erro ao gerar o PDF dos componentes Digitais.');
                }

                return $tempFilename;
            },
            $componentesDigitais
        );

        $out = tempnam(sys_get_temp_dir(), 'compDigitais_');
        $cmd = sprintf('gs -dNOPAUSE -sDEVICE=pdfwrite -sOUTPUTFILE=%s -dBATCH %s', $out, implode(' ', $files));
        shell_exec($cmd);
        $conteudo = file_get_contents($out);

        // Apaga os arquivos temporários
        $files[] = $out;
        foreach ($files as $file) {
            unlink($file);
        }

        if (!$conteudo) {
            throw new RuntimeException('Não foi possível gerar o PDF!');
        }

        return $conteudo;
    }

    /**
     * @param EntityInterface $entity
     *
     * @return array
     */
    abstract protected function getComponentesDigitais(EntityInterface $entity): array;
}
