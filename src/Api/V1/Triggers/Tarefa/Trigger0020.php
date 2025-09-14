<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0020.php.
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
use SuppCore\AdministrativoBackend\Entity\EspecieTarefa;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0020.
 *
 * @descSwagger=Atualiza o contador de tarefas
 * @classeSwagger=Trigger0020
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0020 implements TriggerInterface
{
    private TransactionManager $transactionManager;

    /**
     * Trigger0020 constructor.
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
     * @param EntityInterface|TarefaEntity $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ((!$restDto->getFolder() && $entity->getFolder()) ||
            ($restDto->getFolder() && !$entity->getFolder()) ||
            ($restDto->getFolder() && $entity->getFolder() &&
                ($restDto->getFolder()->getId() !== $entity->getFolder()->getId())) ||
            ($restDto->getUsuarioResponsavel() && $entity->getUsuarioResponsavel()) ||
            ($restDto->getUsuarioResponsavel() &&
                ($restDto->getUsuarioResponsavel()->getId() !== $entity->getUsuarioResponsavel()->getId()))
        ) {
            $especieTarefa = $restDto->getEspecieTarefa();
            if (!$especieTarefa) {
                $especieTarefa = $entity->getEspecieTarefa();
            }

            $this->contarTarefas($restDto, $especieTarefa, $transactionId);
            $this->contarTarefas($entity, $especieTarefa, $transactionId);
        }
    }

    private function contarTarefas(object $objeto, EspecieTarefa $especieTarefa, string $transactionId): void
    {
        $pushMessage = new PushMessage();
        $pushMessage->setChannel($objeto->getUsuarioResponsavel()->getUsername());
        $pushMessage->setResource(TarefaResource::class);
        $arrayCriteria = [
            'usuarioResponsavel.username' => 'eq:'.$objeto->getUsuarioResponsavel()->getUsername(),
            'dataHoraConclusaoPrazo' => 'isNull',
            'especieTarefa.generoTarefa.id' => 'eq:'.$especieTarefa->getGeneroTarefa()->getId(),
        ];
        // Entra para verificar a caixa de entrada
        if (null == $objeto->getFolder()) {
            $pushMessage->setIdentifier(
                'caixa_entrada_'.mb_strtolower($especieTarefa->getGeneroTarefa()->getNome())
            );
            $arrayCriteria['folder'] = 'isNull';
        } else {
            // Entra para verificar as pastas
            $pushMessage->setIdentifier(
                'folder_'.mb_strtolower($especieTarefa->getGeneroTarefa()->getNome()).'_'.mb_strtolower(
                    $objeto->getFolder()->getNome()
                )
            );
            $arrayCriteria['folder.id'] = 'eq:'.$objeto->getFolder()->getId();
        }
        $pushMessage->setCriteria($arrayCriteria);
        $this->transactionManager->addAsyncDispatch($pushMessage, $transactionId);
    }

    public function getOrder(): int
    {
        return 100;
    }
}
