<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Atividade;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade as AtividadeDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Historico as HistoricoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\HistoricoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Atividade as AtividadeEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0007.
 *
 * @descSwagger=Cria o Histórico de criação de atividade
 * @classeSwagger=Trigger0007
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Trigger0007 implements TriggerInterface
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
            AtividadeDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param AtividadeDTO|RestDtoInterface|null $restDto
     * @param AtividadeEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $historicoDto = new HistoricoDTO();
        $historicoDto->setProcesso($restDto->getTarefa()->getProcesso());
        $historicoDto->setDescricao(sprintf('ATIVIDADE CRIADA NA TAREFA DE UUID %s', $restDto->getTarefa()->getUuid()));
        $this->historicoResource->create($historicoDto, $transactionId);
    }

    public function getOrder(): int
    {
        return 2;
    }
}
