<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoWorkflow;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoWorkflowResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0012.
 *
 * @descSwagger=Seta a espécie tarefa e cria a vinculação inicial com workflow no caso de tarefa de workflow
 * @classeSwagger=Trigger0012
 *
 */
class Trigger0012 implements TriggerInterface
{
    /**
     * Trigger0012 constructor.
     */
    public function __construct(private VinculacaoWorkflowResource $vinculacaoWorkflowResource) {
    }

    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param TarefaDTO|RestDtoInterface|null $restDto
     * @param TarefaEntity|EntityInterface $entity
     * @param string $transactionId
     * @throws Exception
     */
    public function execute(
        TarefaDTO|RestDtoInterface|null $restDto,
        TarefaEntity|EntityInterface $entity,
        string $transactionId
    ): void
    {
        if ($restDto->getWorkflow() && !$restDto->getVinculacaoWorkflow()) {

            $restDto->setEspecieTarefa($restDto->getWorkflow()->getEspecieTarefaInicial());

            $vinculacaoWorkflowDTO = (new VinculacaoWorkflow())
                ->setTarefaInicial($entity)
                ->setTarefaAtual($entity)
                ->setWorkflow($restDto->getWorkflow())
                ->setConcluido(false);

            $vinculacaoWorkflowEntity = $this->vinculacaoWorkflowResource->create(
                $vinculacaoWorkflowDTO,
                $transactionId
            );

            $restDto->setVinculacaoWorkflow($vinculacaoWorkflowEntity);
        }

    }

    public function getOrder(): int
    {
        return 1;
    }
}
