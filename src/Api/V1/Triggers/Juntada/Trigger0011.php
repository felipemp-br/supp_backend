<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Juntada;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Juntada as JuntadaEntity;
use SuppCore\AdministrativoBackend\Mercure\Message\PushMessage;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0011.
 *
 * @descSwagger=Faz o push do contador de juntadas
 * @classeSwagger=Trigger0011
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0011 implements TriggerInterface
{

    /**
     * Trigger0011 constructor.
     */
    public function __construct(private TransactionManager $transactionManager)
    {
    }

    public function supports(): array
    {
        return [
            JuntadaDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param JuntadaDTO|RestDtoInterface|null $restDto
     * @param JuntadaEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $this->transactionManager->addAsyncDispatch(
            new PushMessage(
                'juntadas_'.$restDto->getVolume()->getProcesso()->getId(),
                [
                    'nova_juntada' => [],
                ]
            ),
            $transactionId
        );
    }

    public function getOrder(): int
    {
        return 5;
    }
}
