<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Modelo/Trigger0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Modelo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Modelo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Modelo\Message\IndexacaoMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger=Coloca o modelo na fila de indexação do elasticsearch!
 * @classeSwagger=Trigger0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
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
            Modelo::class => [
                'afterCreate',
                'afterUpdate',
                'afterPatch',
            ],
            ComponenteDigital::class => [
                'afterUpdate',
                'afterPatch',
            ],
            Setor::class => [
                'afterUpdate',
                'afterPatch',
            ],
            ModalidadeOrgaoCentral::class => [
                'afterUpdate',
                'afterPatch',
            ],
        ];
    }

    /**
     * @param Modelo|RestDtoInterface|null            $restDto
     * @param ComponenteDigitalEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if(!($restDto instanceof ComponenteDigital)){
            $this->transactionManager->addAsyncDispatch(new IndexacaoMessage($entity->getUuid(),
                explode('Entity\\', $entity::class)[1]), $transactionId);
        } elseif($entity->getDocumento()->getModelo() && $entity->getDocumento()->getModelo()->getId() ){
            $this->transactionManager->addAsyncDispatch(new IndexacaoMessage($entity->getUuid(),
                explode('Entity\\', $entity::class)[1]), $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
