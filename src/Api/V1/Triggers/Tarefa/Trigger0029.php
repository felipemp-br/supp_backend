<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0028.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Repository\EtiquetaRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0028.
 *
 * @descSwagger=Desarquiva o NUP
 * @classeSwagger=Trigger0028
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0029 implements TriggerInterface
{
    /**
     * Trigger0028 constructor.
     */
    public function __construct(
        private TransactionManager $transactionManager,
        protected ProcessoResource $processoResource,
    ) {
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'afterCreate',
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
        if ($this->transactionManager->getContext('desarquivar', $transactionId)) {
            /** @var Processo $processo */
            $processo = $tarefaDto->getProcesso();

            $processo->setSetorAtual($tarefaDto->getSetorResponsavel());
            $processo->setDataHoraDesarquivamento(null);

            $processoDTO = $this->processoResource->getDtoForEntity(
                $processo->getId(),
                Processo::class,
                null,
                $processo
            );

            $this->processoResource->update(
                $processo->getId(),
                $processoDTO,
                $transactionId
            );
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
