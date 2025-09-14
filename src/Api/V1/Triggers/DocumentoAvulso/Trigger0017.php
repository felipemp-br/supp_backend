<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0017.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\DocumentoAvulso;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso as DocumentoAvulsoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as DocumentoAvulsoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0017.
 *
 * @descSwagger=Após restaurar documento avulso, restaura o documento!
 *
 * @classeSwagger=Trigger0017
 */
class Trigger0017 implements TriggerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DocumentoResource $documentoResource
    ) {
    }

    public function supports(): array
    {
        return [
            DocumentoAvulsoEntity::class => [
                'afterUndelete',
            ],
        ];
    }

    /**
     * @param DocumentoAvulsoDTO|RestDtoInterface|null $restDto
     * @param DocumentoAvulsoEntity|EntityInterface    $entity
     * @param string                                   $transactionId
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (array_key_exists('softdeleteable', $this->entityManager->getFilters()->getEnabledFilters())) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }

        $documento = $entity->getDocumentoRemessa();

        if ($documento) {
            $this->documentoResource->undelete($documento->getId(), $transactionId);
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
