<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0009.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\GeneroTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Counter\Message\PushMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Repository\TarefaRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0009.
 *
 * @descSwagger=Faz o push da quantidade de tarefas após criação!
 * @classeSwagger=Trigger0009
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0009 implements TriggerInterface
{
    private TransactionManager $transactionManager;
    private GeneroTarefaResource $generoTarefaResource;

    /**
     * Trigger0009 constructor.
     */
    public function __construct(
        TransactionManager $transactionManager,
        GeneroTarefaResource $generoTarefaResource
    ) {
        $this->transactionManager = $transactionManager;
        $this->generoTarefaResource = $generoTarefaResource;
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Tarefa|RestDtoInterface|null $restDto
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

        //Recupera a quantidade das tarefas da caixa de entrada
        $pushMessage = new PushMessage();
        $pushMessage->setIdentifier('caixa_entrada_'.mb_strtolower(
            $entity->getEspecieTarefa()->getGeneroTarefa()->getNome()
        ));
        $pushMessage->setChannel($entity->getUsuarioResponsavel()->getUsername());
        $pushMessage->setResource(TarefaResource::class);
        $pushMessage->setCriteria(
            [
                'usuarioResponsavel.username' => 'eq:'.$entity->getUsuarioResponsavel()->getUsername(),
                'dataHoraConclusaoPrazo' => 'isNull',
                'folder' => 'isNull',
                'especieTarefa.generoTarefa.id' => 'eq:'.$entity->getEspecieTarefa()->getGeneroTarefa()->getId(),
            ]
        );
        $this->transactionManager->addAsyncDispatch($pushMessage, $transactionId);
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
