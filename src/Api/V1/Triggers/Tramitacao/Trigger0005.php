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
use SuppCore\AdministrativoBackend\Entity\Tramitacao as TramitacaoEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0005.
 *
 * @descSwagger=Criação do Histórico após o delete da Tramitação
 * @classeSwagger=Trigger0005
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Trigger0005 implements TriggerInterface
{
    private HistoricoResource $historicoResource;

    /**
     * Trigger0005 constructor.
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
            TramitacaoEntity::class => [
                'afterDelete',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|TramitacaoDTO|null $restDto
     * @param EntityInterface|TramitacaoEntity    $entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $historicoDto = new HistoricoDTO();
        $historicoDto->setProcesso($entity->getProcesso());
        $historicoDto->setDescricao(sprintf('TRAMITAÇÃO EXCLUÍDA (UUID %s)', $entity->getUuid()));
        $this->historicoResource->create($historicoDto, $transactionId);
    }

    public function getOrder(): int
    {
        return 2;
    }
}
