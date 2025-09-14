<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Documento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoDocumentoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Apaga eventual vinculação de documento ao apagar documento!
 * @classeSwagger=Trigger0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
    private VinculacaoDocumentoResource $vinculacaoDocumentoResource;

    /**
     * Trigger0003constructor.
     */
    public function __construct(
        VinculacaoDocumentoResource $vinculacaoDocumentoResource
    ) {
        $this->vinculacaoDocumentoResource = $vinculacaoDocumentoResource;
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
        $vinculacaoDocumento = $this->vinculacaoDocumentoResource->getRepository()->findByDocumento($entity->getId());
        if ($vinculacaoDocumento) {
            $this->vinculacaoDocumentoResource->delete($vinculacaoDocumento->getId(), $transactionId);

            return;
        }
        $vinculacaoDocumentoVinculado = $this->vinculacaoDocumentoResource->getRepository()->findByDocumentoVinculado(
            $entity->getId()
        );
        if ($vinculacaoDocumentoVinculado) {
            $this->vinculacaoDocumentoResource->delete($vinculacaoDocumentoVinculado->getId(), $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
