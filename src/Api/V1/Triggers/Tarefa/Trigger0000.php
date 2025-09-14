<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0000.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0000.
 *
 * @descSwagger=Garantir que a propriedade vinculacaoWorkflow não seja apagada incorretamente durante uma atualização!
 * @classeSwagger=Trigger0000
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0000 implements TriggerInterface
{
    /**
     * Trigger0000 constructor.
     *
     */
    public function __construct()
    {
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
     * @param string $transactionId
     */
    public function execute(
        Tarefa | RestDtoInterface | null $restDto,
        TarefaEntity | EntityInterface $entity,
        string $transactionId
    ): void {
        if (!$restDto->getWorkflow() && !$restDto->getVinculacaoWorkflow() && $entity->getVinculacaoWorkflow()) {
            $restDto->setVinculacaoWorkflow($entity->getVinculacaoWorkflow());
        }
    }

    public function getOrder(): int
    {
        return 0;
    }
}
