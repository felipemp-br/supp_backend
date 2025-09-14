<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Desentranhamento;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Desentranhamento as DesentranhamentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Historico as HistoricoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\HistoricoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Desentranhamento as DesentranhamentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0004.
 *
 * @descSwagger=Cria o Historico quando é criado um desentranhamento.
 * @classeSwagger=Trigger0004
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Trigger0004 implements TriggerInterface
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
            DesentranhamentoDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param DesentranhamentoDTO|RestDtoInterface|null $restDto
     * @param DesentranhamentoEntity|EntityInterface    $entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $historicoDto = new HistoricoDTO();
        $historicoDto->setProcesso($entity->getJuntada()->getVolume()->getProcesso());
        $historicoDto->setDescricao(
            sprintf(
                'DESENTRANHAMENTO NO NUP %s',
                $entity->getJuntada()->getVolume()->getProcesso()->getNUPFormatado()
            )
        );
        $this->historicoResource->create($historicoDto, $transactionId);
    }

    public function getOrder(): int
    {
        return 2;
    }
}
