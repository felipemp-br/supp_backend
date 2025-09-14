<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Repositorio/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Repositorio;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Repositorio;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Repositorio\Message\IndexacaoMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Coloca o repositório na fila de indexação do elasticsearch!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private TransactionManager $transactionManager;

    /**
     * Trigger0010 constructor.
     */
    public function __construct(
        TransactionManager $transactionManager
    ) {
        $this->transactionManager = $transactionManager;
    }

    public function supports(): array
    {
        return [
            Repositorio::class => [
                'afterCreate',
                'afterUpdate',
                'afterPatch',
            ],
            ComponenteDigital::class => [
                'afterUpdate',
                'afterPatch',
            ],
        ];
    }

    /**
     * @param Repositorio|RestDtoInterface|null       $restDto
     * @param ComponenteDigitalEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto instanceof Repositorio) {
            $this->transactionManager->addAsyncDispatch(new IndexacaoMessage($entity->getUuid()), $transactionId);
        } elseif ($entity->getDocumento()->getRepositorio() && $entity->getDocumento()->getRepositorio()->getId()) {
            $this->transactionManager->addAsyncDispatch(
                new IndexacaoMessage($entity->getDocumento()->getRepositorio()->getUuid()),
                $transactionId
            );
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
