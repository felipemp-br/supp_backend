<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0023.php.
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
 * Class Trigger0023.
 *
 * @descSwagger=Faz o push da quantidade de compartilhamento do usuario ao excluir!
 * @classeSwagger=Trigger0023
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0023 implements TriggerInterface
{
    private TransactionManager $transactionManager;

    /**
     * Trigger0023 constructor.
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
        //tarefas compartilhadas para mim
        foreach ($entity->getCompartilhamentos() as $compartilhamento) {
            $pushMessage = new PushMessage();
            $pushMessage->setIdentifier(
                'tarefas_compartilhadas_'.mb_strtolower(
                    $compartilhamento->getTarefa()->getEspecieTarefa()->getGeneroTarefa()->getNome()
                )
            );
            $pushMessage->setChannel($compartilhamento->getUsuario()->getUsername());
            $pushMessage->setResource(TarefaResource::class);
            $pushMessage->setCriteria(
                [
                    'compartilhamentos.usuario.id' => 'eq:'.$compartilhamento->getUsuario()->getId(),
                    'compartilhamentos.apagadoEm' => 'isNull',
                    'dataHoraConclusaoPrazo' => 'isNull',
                    'especieTarefa.generoTarefa.id' => 'eq:'.$compartilhamento
                            ->getTarefa()
                            ->getEspecieTarefa()
                            ->getGeneroTarefa()
                            ->getId(),
                ]
            );
            $this->transactionManager->addAsyncDispatch($pushMessage, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
