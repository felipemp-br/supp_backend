<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use Knp\Snappy\Pdf;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use function mb_strlen;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Fields\RendererManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger=Caso informado o id de um modelo, o conteudo é processado!
 * @classeSwagger=Trigger0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
    /**
     * Trigger0003 constructor.
     */
    public function __construct(
        private RendererManager $rendererManager,
        private Pdf $pdfManager,
        private TransactionManager $transactionManager
    ) {
    }

    public function supports(): array
    {
        return [
            ComponenteDigitalDTO::class => [
                'beforeCreate',
                'beforeAprovar',
            ],
        ];
    }

    /**
     * @param ComponenteDigitalDTO|RestDtoInterface|null $restDto
     * @param ComponenteDigitalEntity|EntityInterface    $entity
     * @param string                                     $transactionId
     */
    public function execute(
        ComponenteDigitalDTO|RestDtoInterface|null $restDto,
        ComponenteDigitalEntity|EntityInterface $entity,
        string $transactionId
    ): void {
        /** @var ComponenteDigitalDTO $componenteDigitalDTO */
        $componenteDigitalDTO = $restDto;

        if ($componenteDigitalDTO->getModelo() &&
            !$this->transactionManager->getContext('copia_documento', $transactionId) &&
            !$this->transactionManager->getContext('respostaDocumentoAvulso', $transactionId)) {
            $fileName = $componenteDigitalDTO->getModelo()->getDocumento()->getComponentesDigitais()[0]->getFileName();
            $fileName = mb_strtoupper(preg_replace('/(\.html|\.pdf)$/i', '', $fileName), 'UTF8');

            $componenteDigitalDTO->setEditavel(!$restDto->getGeraModeloEmPdf());
            $componenteDigitalDTO->setFileName(
                $restDto->getGeraModeloEmPdf() ?
                "{$fileName}.PDF" :
                "{$fileName}.HTML"
            );

            $conteudo = $this->rendererManager->renderModelo(
                $componenteDigitalDTO,
                $transactionId,
                $componenteDigitalDTO->getModelo()->getContextoEspecifico() ?: []
            );

            $conteudo = $restDto->getGeraModeloEmPdf() ? $this->pdfManager->getOutputFromHtml($conteudo) : $conteudo;

            $componenteDigitalDTO->setConteudo($conteudo);
            $componenteDigitalDTO->setTamanho(mb_strlen($conteudo));
            $componenteDigitalDTO->setHash(hash('SHA256', $conteudo));
            $componenteDigitalDTO->setMimetype(
                $restDto->getGeraModeloEmPdf() ? 'application/pdf' : 'text/html'
            );
            $componenteDigitalDTO->setNivelComposicao(3);
            $componenteDigitalDTO->setExtensao($restDto->getGeraModeloEmPdf() ? 'pdf' : 'html');
            $componenteDigitalDTO->setConvertidoPdf((bool) $restDto->getGeraModeloEmPdf());
        }
    }

    public function getOrder(): int
    {
        return 3;
    }
}
