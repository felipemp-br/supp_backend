<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Mercure\Message\PushMessage;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0016.
 *
 * @descSwagger=Faz o push do contador de tarefas
 * @classeSwagger=Trigger0016
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0016 implements TriggerInterface
{
    /**
     * Trigger0016 constructor.
     */
    public function __construct(private TransactionManager $transactionManager)
    {
    }

    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param TarefaDTO|RestDtoInterface|null $restDto
     * @param TarefaEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $this->transactionManager->addAsyncDispatch(
            new PushMessage(
                $restDto->getUsuarioResponsavel()->getUsername(),
                [
                    'nova_tarefa' => [
                        'genero' => mb_strtolower($restDto->getEspecieTarefa()->getGeneroTarefa()->getNome()),
                    ],
                ]
            ),
            $transactionId
        );
    }

    public function getOrder(): int
    {
        return 5;
    }
}
