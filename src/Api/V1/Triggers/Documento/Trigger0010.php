<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0010.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Documento;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0010.
 *
 * @descSwagger=Após restaurar documento, restaura a vinculacao da etiqueta caso exista!
 * @classeSwagger=Trigger0010
 */
class Trigger0010 implements TriggerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource
    ) {
    }

    public function supports(): array
    {
        return [
            Documento::class => [
                'afterUndelete',
            ],
        ];
    }

    /**
     * @param Documento|RestDtoInterface|null $restDto
     * @param DocumentoEntity|EntityInterface $entity
     * @param string                          $transactionId
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (array_key_exists('softdeleteable', $this->entityManager->getFilters()->getEnabledFilters())) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }

        if ($entity->getTarefaOrigem()) {
            foreach ($entity->getTarefaOrigem()->getVinculacoesEtiquetas() as $vinculacaoEtiqueta) {
                if ($entity->getUuid() === $vinculacaoEtiqueta->getObjectUuid() &&
                        DocumentoEntity::class === $vinculacaoEtiqueta->getObjectClass()) {
                    $this->vinculacaoEtiquetaResource->undelete($vinculacaoEtiqueta->getId(), $transactionId);
                    break;
                }
            }
        }

        if (!array_key_exists('softdeleteable', $this->entityManager->getFilters()->getEnabledFilters())) {
            $this->entityManager->getFilters()->enable('softdeleteable');
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
