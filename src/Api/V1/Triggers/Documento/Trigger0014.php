<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0014.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Documento;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0014.
 *
 * @descSwagger=Remove a etiqueta da minuta na tarefa
 * @classeSwagger=Trigger0014
 */
class Trigger0014 implements TriggerInterface
{
    public function __construct(
        private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
    ) {
    }

    public function supports(): array
    {
        return [
            Documento::class => [
                'beforeConverteMinutaEmAnexo',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface       $entity
     * @param string                $transactionId
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($entity->getTarefaOrigem()) {
            foreach ($entity->getTarefaOrigem()->getVinculacoesEtiquetas() as $vinculacaoEtiqueta) {
                if ($entity->getUuid() === $vinculacaoEtiqueta->getObjectUuid() &&
                    DocumentoEntity::class === $vinculacaoEtiqueta->getObjectClass()) {
                    $this->vinculacaoEtiquetaResource->delete($vinculacaoEtiqueta->getId(), $transactionId);
                    break;
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
