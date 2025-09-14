<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/VinculacaoModelo/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\VinculacaoModelo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoModelo as VinculacaoModeloDTO;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Modelo\Message\IndexacaoMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Modelo;
use SuppCore\AdministrativoBackend\Entity\VinculacaoModelo as VinculacaoModeloEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger  =Atualizar modelo no elastic apos salvar uma especieSetor no vinculacaoModelo
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private TransactionManager $transactionManager;

    /**
     * Trigger0010 constructor.
     */
    public function __construct(
        TransactionManager $transactionManager
    ) {
        $this->transactionManager = $transactionManager;
    }

    public function supports(): array
    {
        return [
            VinculacaoModeloDTO::class => [
                'afterCreate',
                'afterUpdate',
            ],
            VinculacaoModeloEntity::class => [
                'afterDelete',
            ],
        ];
    }

    /**
     * @param VinculacaoModeloDTO|RestDtoInterface|null $vinculacaoModeloDTO
     * @param VinculacaoModeloEntity|EntityInterface    $vinculacaoModeloEntity
     *
     * @throws Exception
     */
    public function execute(
        ?RestDtoInterface $vinculacaoModeloDTO,
        EntityInterface $vinculacaoModeloEntity,
        string $transactionId
    ): void {
        if ($vinculacaoModeloEntity->getModelo() instanceof Modelo) {
            $this->transactionManager->addAsyncDispatch(
                new IndexacaoMessage($vinculacaoModeloEntity->getModelo()->getUuid(), 'Modelo'),
                $transactionId
            );
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
