<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tramitacao;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Historico as HistoricoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao as TramitacaoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\HistoricoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tramitacao;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger=Criação do Histórico após o create da Tramitação
 * @classeSwagger=Trigger0003
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Trigger0003 implements TriggerInterface
{
    private HistoricoResource $historicoResource;

    /**
     * Trigger0003 constructor.
     */
    public function __construct(HistoricoResource $historicoResource)
    {
        $this->historicoResource = $historicoResource;
    }

    /**
     * @return array|string[]
     */
    public function supports(): array
    {
        return [
            TramitacaoDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|TramitacaoDTO|null $restDto
     * @param EntityInterface|Tramitacao          $entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $historicoDto = new HistoricoDTO();
        $historicoDto->setProcesso($restDto->getProcesso());
        $historicoDto->setDescricao(sprintf('TRAMITAÇÃO CRIADA (UUID %s)', $entity->getUuid()));
        $this->historicoResource->create($historicoDto, $transactionId);
    }

    public function getOrder(): int
    {
        return 2;
    }
}
