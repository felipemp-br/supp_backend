<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Documento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Apaga eventual documento avulso remessa vinculado ao apagar documento!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private DocumentoAvulsoResource $documentoAvulsoResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        DocumentoAvulsoResource $documentoAvulsoResource
    ) {
        $this->documentoAvulsoResource = $documentoAvulsoResource;
    }

    public function supports(): array
    {
        return [
            Documento::class => [
                'afterDelete',
            ],
        ];
    }

    /**
     * @param \SuppCore\AdministrativoBackend\Api\V1\DTO\Documento|RestDtoInterface|null $restDto
     * @param Documento|EntityInterface                                                  $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($entity->getDocumentoAvulsoRemessa() &&
            $entity->getDocumentoAvulsoRemessa()->getId()) {
            $this->documentoAvulsoResource->delete($entity->getDocumentoAvulsoRemessa()->getId(), $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
