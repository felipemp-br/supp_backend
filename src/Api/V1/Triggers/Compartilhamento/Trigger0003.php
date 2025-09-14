<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Compartilhamento/Trigger0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Compartilhamento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Compartilhamento;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Counter\Message\PushMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Compartilhamento as CompartilhamentoEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger  =Faz o push da quantidade de compartilhamento do usuario!
 * @classeSwagger=Trigger0003
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
    private TransactionManager $transactionManager;

    /**
     * Trigger0003 constructor.
     */
    public function __construct(
        TransactionManager $transactionManager
    ) {
        $this->transactionManager = $transactionManager;
    }

    public function supports(): array
    {
        return [
            Compartilhamento::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Compartilhamento|RestDtoInterface|null $restDto
     * @param CompartilhamentoEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$restDto->getTarefa()) {
            return;
        }

        //tarefas compartilhadas para mim
        $pushMessage = new PushMessage();
        $pushMessage->setIdentifier(
            'tarefas_compartilhadas_'.mb_strtolower(
                $entity->getTarefa()->getEspecieTarefa()->getGeneroTarefa()->getNome()
            )
        );
        $pushMessage->setChannel($entity->getUsuario()->getUsername());
        $pushMessage->setResource(TarefaResource::class);
        $pushMessage->setCriteria(
            [
                'compartilhamentos.usuario.id' => 'eq:'.$entity->getUsuario()->getId(),
                'compartilhamentos.apagadoEm' => 'isNull',
                'dataHoraConclusaoPrazo' => 'isNull',
                'especieTarefa.generoTarefa.id' => 'eq:'.$entity->getTarefa()->getEspecieTarefa()->
                    getGeneroTarefa()->getId(),
            ]
        );

        $this->transactionManager->addAsyncDispatch($pushMessage, $transactionId);
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
