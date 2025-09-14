<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0031.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0031.
 *
 * @descSwagger  =Caso esteja desarquivando, set null da data de desarquivação automatica.
 * @classeSwagger=Trigger0031
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0031 implements TriggerInterface
{
    /**
     * Trigger0031 constructor.
     */
    public function __construct(
        private TransactionManager $transactionManager,
        protected ProcessoResource $processoResource,
    ) {
    }

    public function supports(): array
    {
        return [
            Processo::class => [
                'beforeUpdate',
            ],
        ];
    }

    /**
     * @param Tarefa|RestDtoInterface|null $processoDto
     * @param TarefaEntity|EntityInterface $processoEntity
     *
     * @throws Exception
     */
    public function execute(
        ?RestDtoInterface $processoDto,
        EntityInterface $processoEntity,
        string $transactionId
    ): void {
        if ($processoDto?->getSetorAtual()?->getNome() !== 'ARQUIVO' && $processoEntity->getSetorAtual()?->getNome(
            ) === 'ARQUIVO') {
            $processoDto->setDataHoraDesarquivamento(null);
        }
    }

    public function getOrder(): int
    {
        return 0;
    }
}
