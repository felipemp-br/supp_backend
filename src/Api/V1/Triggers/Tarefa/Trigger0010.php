<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0010.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Counter\Message\PushMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0010.
 *
 * @descSwagger=Faz o push da quantidade de tarefas pendentes após apagar!
 * @classeSwagger=Trigger0010
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0010 implements TriggerInterface
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
            TarefaEntity::class => [
                'afterDelete',
                'afterUndelete',
            ],
        ];
    }

    /**
     * @param TarefaEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $pushMessage = new PushMessage();
        $pushMessage->setChannel(
            $entity->getUsuarioResponsavel()->getUsername()
        );
        $pushMessage->setResource(
            TarefaResource::class
        );
        $pushMessage->setIdentifier(
            'tarefas_pendentes_'.mb_strtolower($entity->getEspecieTarefa()->getGeneroTarefa()->getNome())
        );
        $pushMessage->setCriteria(
            [
                'especieTarefa.generoTarefa.nome' => 'eq:'.$entity->getEspecieTarefa()->getGeneroTarefa()->getNome(),
                'usuarioResponsavel.username' => 'eq:'.$entity->getUsuarioResponsavel()->getUsername(),
                'dataHoraConclusaoPrazo' => 'isNull',
            ]
        );

        $this->transactionManager->addAsyncDispatch($pushMessage, $transactionId);
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
