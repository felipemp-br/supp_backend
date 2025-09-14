<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Pessoa/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Pessoa;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Pessoa\Message\IndexacaoMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Coloca a pessoa na fila de indexação do elasticsearch!
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
            Pessoa::class => [
                'afterCreate',
                'afterUpdate',
                'afterPatch',
            ],
        ];
    }

    /**
     * @param Pessoa|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $this->transactionManager->addAsyncDispatch(new IndexacaoMessage($entity->getUuid()), $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
