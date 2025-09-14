<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Historico as HistoricoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\HistoricoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0018.
 *
 * @descSwagger=Cria o Historico quando é criado um Processo.
 * @classeSwagger=Trigger0018
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Trigger0018 implements TriggerInterface
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
            ProcessoDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param ProcessoDTO|RestDtoInterface|null $restDto
     * @param ProcessoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $historicoDto = new HistoricoDTO();
        $historicoDto->setProcesso($entity);
        $historicoDto->setDescricao(
            sprintf(
                'NUP CRIADO (UUID %s)',
                $entity->getUuid()
            )
        );
        $this->historicoResource->create($historicoDto, $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
