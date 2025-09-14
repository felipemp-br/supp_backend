<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\StatusBarramento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\StatusBarramento\Message\SincronizaBarramentoMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Dispara a sincronização de componentes digitais do barramento em segundo plano.
 * @classeSwagger=Trigger0001
 */
class Trigger0001 implements TriggerInterface
{
    /**
     * Trigger0001 constructor.
     * @param TransactionManager $transactionManager
     */
    public function __construct(
        private TransactionManager $transactionManager,
        private TokenStorageInterface $tokenStorage
    ) {
    }

    public function supports(): array
    {
        return [
            Processo::class => [
                'beforeSincronizaBarramento',
            ],
        ];
    }

    /**
     * @param Processo|RestDtoInterface|null $restDto
     * @param ProcessoEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(
        RestDtoInterface | Processo | null $restDto,
        EntityInterface | ProcessoEntity $entity,
        string $transactionId
    ): void {
        $message = new SincronizaBarramentoMessage(
            (string) $entity->getId(),
            $this->tokenStorage->getToken()->getUserIdentifier(),
        );

        $this->transactionManager->addAsyncDispatch($message, $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
