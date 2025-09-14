<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Relatorio/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Relatorio;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Relatorio;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Relatorio\Message\CreateMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Cria o documento e o componente digital do relatório em segundo plano!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private TransactionManager $transactionManager;

    private TokenStorageInterface $tokenStorage;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        TransactionManager $transactionManager,
        TokenStorageInterface $tokenStorage
    ) {
        $this->transactionManager = $transactionManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(): array
    {
        return [
            Relatorio::class => [
                'afterCreate',
            ],
        ];
    }

    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $createMessage = new CreateMessage();

        $workload = serialize(
            [
                'parametros' => $restDto->getParametrosAsArray(),
                'formato' => $restDto->getFormato(),
                'usuario' => $this->tokenStorage->getToken()->getUser()->getUserIdentifier(),
            ]
        );

        $createMessage->setUuid($entity->getUuid());
        $createMessage->setParametrosDTO($workload);
        $createMessage->setAction('relatorio_create');
        $this->transactionManager->addAsyncDispatch($createMessage, $transactionId);
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
