<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Triggers/Tese/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tese;

use Exception;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tese;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoMetadados;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoOrgaoCentralMetadados;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Tese\Message\IndexacaoMessage;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Criação do Message de Indexação no Elastic
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    /**
     * Trigger0001 constructor.
     *
     * @param TransactionManager $transactionManager
     */
    public function __construct(
        private readonly TransactionManager $transactionManager
    ) {
    }

    /**
     * @return array
     */
    public function supports(): array
    {
        return [
            Tese::class => [
                'afterCreate',
                'afterUpdate',
                'afterPatch',
            ],
            VinculacaoMetadados::class => [
                'afterCreate',
                'afterUpdate',
                'afterPatch',
                'afterDelete',
            ],
            VinculacaoOrgaoCentralMetadados::class => [
                'afterCreate',
                'afterUpdate',
                'afterPatch',
                'afterDelete',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return void
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $uuIds = [];
        switch (true) {
            case $restDto instanceof Tese:
                $uuIds[] = $entity->getUuid();
                break;
            case $restDto instanceof VinculacaoMetadados:
            case $restDto instanceof VinculacaoOrgaoCentralMetadados:
                $uuIds[] = $entity->getTese()->getUuid();
                break;
        }
        
        foreach ($uuIds as $uuId) {
            $this->transactionManager->addAsyncDispatch(new IndexacaoMessage($uuId), $transactionId);
        }
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 1000;
    }
}
