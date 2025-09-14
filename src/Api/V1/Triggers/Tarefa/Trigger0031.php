<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0031.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;

/**
 * Class Trigger0031.
 *
 * @descSwagger=Preserva o campo tarefa_origem_id durante redistribuição de tarefas!
 * @classeSwagger=Trigger0031
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0031 implements TriggerInterface
{
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
     * @param string                       $transactionId
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        // Se a tarefa está sendo redistribuída e tem tarefa origem na entity,
        // preserva o campo tarefa_origem_id no DTO
        if ($entity->getTarefaOrigem() && !$restDto->getTarefaOrigem()) {
            $restDto->setTarefaOrigem($entity->getTarefaOrigem());
        }
    }

    public function getOrder(): int
    {
        return 1; // Executa antes do Trigger0026 para garantir que o campo seja preservado
    }
}