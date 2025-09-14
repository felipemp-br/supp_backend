<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0026.php.
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
 * Class Trigger0026.
 *
 * @descSwagger=Coloca a tarefa na caixa de entrada em caso de tarefa redistribuída!
 * @classeSwagger=Trigger0026
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0026 implements TriggerInterface
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
        if ($entity->getUsuarioResponsavel()->getId() !== $restDto->getUsuarioResponsavel()->getId()) {
            $restDto->setFolder(null);
        }
    }

    public function getOrder(): int
    {
        return 3;
    }
}
