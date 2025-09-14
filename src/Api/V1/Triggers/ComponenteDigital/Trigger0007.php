<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0007.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoDocumentoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0007.
 *
 * @descSwagger=Caso informados documentos no request para responder o ofício, o ofício sejá respondido!
 * @classeSwagger=Trigger0007
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0007 implements TriggerInterface
{
    private DocumentoResource $documentoResource;

    private ComponenteDigitalResource $componenteDigitalResource;

    private VinculacaoDocumentoResource $vinculacaoDocumentoResource;

    private DocumentoAvulsoResource $documentoAvulsoResource;

    /**
     * Trigger0007 constructor.
     */
    public function __construct(
        DocumentoResource $documentoResource,
        ComponenteDigitalResource $componenteDigitalResource,
        VinculacaoDocumentoResource $vinculacaoDocumentoResource,
        DocumentoAvulsoResource $documentoAvulsoResource
    ) {
        $this->documentoResource = $documentoResource;
        $this->componenteDigitalResource = $componenteDigitalResource;
        $this->vinculacaoDocumentoResource = $vinculacaoDocumentoResource;
        $this->documentoAvulsoResource = $documentoAvulsoResource;
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param ComponenteDigital|RestDtoInterface|null $restDto
     * @param ComponenteDigitalEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getDocumentoAvulsoOrigem()) {
            $documentoAvulsoDTO = new DocumentoAvulso();
            $documentoAvulsoDTO->setDocumentoResposta($restDto->getDocumento());

            $this->documentoAvulsoResource->responder(
                $restDto->getDocumentoAvulsoOrigem()->getId(),
                $documentoAvulsoDTO,
                $transactionId
            );
        }
    }

    public function getOrder(): int
    {
        return 7;
    }
}
