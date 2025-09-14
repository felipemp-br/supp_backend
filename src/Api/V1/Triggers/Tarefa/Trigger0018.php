<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0018.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0018.
 *
 * @descSwagger=Seta a data da distribuição no caso de redistribuição!
 * @classeSwagger=Trigger0018
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0018 implements TriggerInterface
{
    public function supports(): array
    {
        return [
            Tarefa::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Tarefa|RestDtoInterface|null $tarefaDto
     * @param TarefaEntity|EntityInterface $tarefaEntity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $tarefaDto, EntityInterface $tarefaEntity, string $transactionId): void
    {
        $tarefaDto->setDataHoraDistribuicao(new DateTime());
    }

    public function getOrder(): int
    {
        return 1;
    }
}
