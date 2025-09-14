<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0011.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Counter\Message\PushMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0011.
 *
 * @descSwagger=Faz o push da quantidade de tarefas após redistribuição ou conclusão!
 * @classeSwagger=Trigger0011
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0011 implements TriggerInterface
{
    private TransactionManager $transactionManager;

    /**
     * Trigger0011 constructor.
     */
    public function __construct(
        TransactionManager $transactionManager
    ) {
        $this->transactionManager = $transactionManager;
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'beforeUpdate',
                'beforePatch',
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
        if (($restDto->getDataHoraConclusaoPrazo() && !$entity->getDataHoraConclusaoPrazo()) ||
            ($restDto->getUsuarioResponsavel()->getId() !== $entity->getUsuarioResponsavel()->getId())) {
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
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
