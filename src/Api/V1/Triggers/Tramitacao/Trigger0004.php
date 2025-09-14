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
 * Class Trigger0004.
 *
 * @descSwagger=Criação do Histórico após o update da Tramitação
 * @classeSwagger=Trigger0004
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Trigger0004 implements TriggerInterface
{
    private HistoricoResource $historicoResource;

    /**
     * Trigger0004 constructor.
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
                'afterUpdate',
                'afterPatch',
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
        $historicoDto->setDescricao(sprintf('TRAMITAÇÃO ATUALIZADA (UUID %s)', $entity->getUuid()));
        $this->historicoResource->create($historicoDto, $transactionId);
    }

    public function getOrder(): int
    {
        return 2;
    }
}
