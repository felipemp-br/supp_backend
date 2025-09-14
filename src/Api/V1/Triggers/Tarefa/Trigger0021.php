<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0021.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use DateTime;
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
 * Class Trigger0021.
 *
 * @descSwagger  =Atualiza o contador de tarefas
 * @classeSwagger=Trigger0021
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0021 implements TriggerInterface
{
    private TransactionManager $transactionManager;

    /**
     * Trigger0021 constructor.
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
                'beforeDelete',
                'beforeUndelete',
            ],
        ];
    }

    /**
     * @param Tarefa|RestDtoInterface|null $restDto
     * @param EntityInterface|TarefaEntity $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $this->contarTarefas($entity, $transactionId);
    }

    private function contarTarefas(object $objeto, string $transactionId): void
    {
        $pushMessage = new PushMessage();
        $pushMessage->setChannel($objeto->getUsuarioResponsavel()->getUsername());
        $pushMessage->setResource(TarefaResource::class);

        if (null == $objeto->getFolder()) { // Entra para verificar a caixa de entrada
            $pushMessage->setIdentifier(
                'caixa_entrada_'.mb_strtolower($objeto->getEspecieTarefa()->getGeneroTarefa()->getNome())
            );
            $arrayCriteria = [
                'usuarioResponsavel.username' => 'eq:'.$objeto->getUsuarioResponsavel()->getUsername(),
                'dataHoraConclusaoPrazo' => 'isNull',
                'especieTarefa.generoTarefa.id' => 'eq:'.$objeto->getEspecieTarefa()->getGeneroTarefa()->getId(),
                'folder' => 'isNull',
            ];
        } else { // Entra para verificar as pastas
            $pushMessage->setIdentifier(
                'folder_'.mb_strtolower($objeto->getEspecieTarefa()->getGeneroTarefa()->getNome()).'_'.mb_strtolower(
                    $objeto->getFolder()->getNome()
                )
            );
            $arrayCriteria = [
                'usuarioResponsavel.username' => 'eq:'.$objeto->getUsuarioResponsavel()->getUsername(),
                'dataHoraConclusaoPrazo' => 'isNull',
                'especieTarefa.generoTarefa.id' => 'eq:'.$objeto->getEspecieTarefa()->getGeneroTarefa()->getId(),
                'folder.id' => 'eq:'.$objeto->getFolder()->getId(),
            ];
        }
        $pushMessage->setCriteria($arrayCriteria);
        $this->transactionManager->addAsyncDispatch($pushMessage, $transactionId);

        $data = new DateTime();
        $pushMessage = new PushMessage();
        $pushMessage->setChannel($objeto->getUsuarioResponsavel()->getUsername());
        $pushMessage->setResource(TarefaResource::class);
        $pushMessage->setIdentifier(
            'lixeira_'.mb_strtolower($objeto->getEspecieTarefa()->getGeneroTarefa()->getNome())
        );
        $pushMessage->setDesabilitaSoftDeleteable(true);
        $arrayCriteria = [
            'usuarioResponsavel.username' => 'eq:'.$objeto->getUsuarioResponsavel()->getUsername(),
            'dataHoraConclusaoPrazo' => 'isNull',
            'especieTarefa.generoTarefa.id' => 'eq:'.$objeto->getEspecieTarefa()->getGeneroTarefa()->getId(),
            'apagadoEm' => 'gt:'.$data->modify('-10 days')->format('Y-m-d\TH:i:s'),
        ];
        $pushMessage->setCriteria($arrayCriteria);
        $this->transactionManager->addAsyncDispatch($pushMessage, $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
