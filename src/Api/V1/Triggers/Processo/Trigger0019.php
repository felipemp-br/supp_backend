<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assunto as AssuntoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Interessado as InteressadoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso as VinculacaoProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo\Message\IndexacaoMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Assunto as AssuntoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Interessado as InteressadoEntity;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta as VinculacaoEtiquetaEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso as VinculacaoProcessoEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0019.
 *
 * @descSwagger=Criação do Message de Indexação no Elastic em creates/updates
 * @classeSwagger=Trigger0019
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Trigger0019 implements TriggerInterface
{
    private TransactionManager $transactionManager;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        TransactionManager $transactionManager
    ) {
        $this->transactionManager = $transactionManager;
    }

    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'afterCreate',
                'afterUpdate',
                'afterPatch',
            ],
            AssuntoDTO::class => [
                'afterCreate',
            ],
            VinculacaoProcessoDTO::class => [
                'afterCreate',
            ],
            InteressadoDTO::class => [
                'afterCreate',
                'afterUpdate',
                'afterPatch',
            ],
            VinculacaoEtiquetaDTO::class => [
                'afterCreate',
            ],
            VinculacaoEtiquetaEntity::class => [
                'afterDelete',
            ]
        ];
    }

    /**
     * @param ProcessoDTO|AssuntoDTO|VinculacaoProcessoDTO|RestDtoInterface|InteressadoDTO|null        $restDto
     * @param ProcessoEntity|AssuntoEntity|VinculacaoProcessoEntity|EntityInterface|InteressadoEntity| $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto instanceof ProcessoDTO) {
            $this->transactionManager->addAsyncDispatch(new IndexacaoMessage($entity->getUuid()), $transactionId);
        } elseif ($entity->getProcesso() && $entity->getProcesso()->getId()) {
            $this->transactionManager->addAsyncDispatch(
                new IndexacaoMessage($entity->getProcesso()->getUuid()),
                $transactionId
            );
        }
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
