<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Atividade;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Counter\Message\PushMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Atividade as AtividadeEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0010.
 *
 * @descSwagger  =Faz o push da quantidade de compartilhamento do usuario!
 * @classeSwagger=Trigger0010
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
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
            Atividade::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Atividade|RestDtoInterface|null $restDto
     * @param AtividadeEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        foreach ($entity->getTarefa()->getCompartilhamentos() as $compartilhamento) {
            //tarefas compartilhadas para mim
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
                    'especieTarefa.generoTarefa.id' => 'eq:'.$compartilhamento->getTarefa()->getEspecieTarefa(
                        )->getGeneroTarefa()->getId(),
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
