<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/ComponenteDigital/Pipe0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\ComponenteDigital;

use Exception;
use Knp\Snappy\Pdf;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Utils\HTMLPurifier;
use Throwable;

/**
 * Class Pipe0003.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0003 implements PipeInterface
{
    private HTMLPurifier $sanitizer;
    private Pdf $pdfManager;

    /**
     * Pipe0003 constructor.
     */
    public function __construct(
        HTMLPurifier $sanitizer,
        Pdf $pdfManager
    ) {
        $this->sanitizer = $sanitizer;
        $this->pdfManager = $pdfManager;
    }

    public function supports(): array
    {
        return [
            ComponenteDigitalDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param ComponenteDigitalDTO|RestDtoInterface|null $restDto
     * @param ComponenteDigitalEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if ($restDto->getConteudo() &&
            ('text/html' === $restDto->getMimetype() ||
                'html' === $restDto->getExtensao() ||
                'htm' === $restDto->getExtensao()) &&
                $entity->getDocumento()?->getJuntadaAtual() &&
                !$entity->getAllowUnsafe()) {
            try {
                $conteudo = $restDto->getConteudo();
                $conteudo = $this->sanitizer->sanitize(
                    $conteudo
                );
                $restDto->setConteudo(
                    $conteudo
                );
            } catch (Throwable) {
                $conteudo = $restDto->getConteudo();
                $conteudo = $this->sanitizer->sanitizePdf(
                    $conteudo
                );
                $restDto->setUnsafe(true);
                $restDto->setHashAntigo($restDto->getHash());
                $restDto->setConteudo(
                    $this->pdfManager->getOutputFromHtml(
                        $conteudo
                    )
                );
                $restDto->setHash(hash('SHA256', $restDto->getConteudo()));
                $restDto->setMimetype('application/pdf');
                $restDto->setExtensao('pdf');
                $restDto->setConvertidoPdf(true);
                $restDto->setEditavel(false);
                $restDto->setTamanho(strlen($restDto->getConteudo()));
                $restDto->setFileName(
                    str_replace('.html', '.pdf', str_replace('.HTML', '.pdf', $restDto->getFileName()))
                );
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
