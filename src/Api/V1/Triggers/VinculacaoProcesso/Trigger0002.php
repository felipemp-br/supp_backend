<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\VinculacaoProcesso;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Historico as HistoricoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso as VinculacaoProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\HistoricoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso as VinculacaoProcessoEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Cria o Histórico quando é feito uma vinculação de processos
 * @classeSwagger=Trigger0002
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Trigger0002 implements TriggerInterface
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
            VinculacaoProcessoDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param VinculacaoProcessoDTO|RestDtoInterface|null $restDto
     * @param VinculacaoProcessoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $historicoDto = new HistoricoDTO();
        $historicoDto->setProcesso($entity->getProcesso());
        $historicoDto->setDescricao(
            sprintf(
                'VINCULAÇÃO DOS NUPS %s e %s',
                $entity->getProcesso()->getNUPFormatado(),
                $entity->getProcessoVinculado()->getNUPFormatado()
            )
        );
        $this->historicoResource->create($historicoDto, $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
