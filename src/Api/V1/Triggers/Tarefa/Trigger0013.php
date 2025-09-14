<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Historico as HistoricoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\HistoricoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0013.
 *
 * @descSwagger=Cria o Histórico quando é criado uma Tarefa
 * @classeSwagger=Trigger0013
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Trigger0013 implements TriggerInterface
{
    private HistoricoResource $historicoResource;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(HistoricoResource $historicoResource)
    {
        $this->historicoResource = $historicoResource;
    }

    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param TarefaDTO|RestDtoInterface|null $restDto
     * @param TarefaEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $historicoDto = new HistoricoDTO();
        $historicoDto->setProcesso($entity->getProcesso());
        $historicoDto->setDescricao(sprintf('TAREFA CRIADA (UUID %s)', $entity->getUuid()));
        $this->historicoResource->create($historicoDto, $transactionId);
    }

    public function getOrder(): int
    {
        return 5;
    }
}
