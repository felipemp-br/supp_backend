<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoWorkflow;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoWorkflowResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0017.
 *
 * @descSwagger=Atualiza a tarefa atual da vinculação com workflow das tarefas de workflow
 * @classeSwagger=Trigger0017
 */
class Trigger0017 implements TriggerInterface
{

    public function __construct(private VinculacaoWorkflowResource $vinculacaoWorkflowResource) {
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
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getVinculacaoWorkflow()?->getId()) {
            $vinculacaoWorkflowDTO = new VinculacaoWorkflow();
            $vinculacaoWorkflowDTO->setTarefaAtual($entity);
            $this->vinculacaoWorkflowResource->update(
                $restDto->getVinculacaoWorkflow()->getId(),
                $vinculacaoWorkflowDTO,
                $transactionId,
                true
            );
        }
    }

    public function getOrder(): int
    {
        return 4;
    }
}
