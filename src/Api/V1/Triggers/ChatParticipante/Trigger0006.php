<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ChatParticipante/Trigger0006.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ChatParticipante;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatParticipante as ChatParticipanteDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante as ChatParticipanteEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0006.
 *
 * @descSwagger=Seta a data de ultima visualização e mensagens não lidas na criação do participante.
 * @classeSwagger=Trigger0006
 */
class Trigger0006 implements TriggerInterface
{
    /**
     * Trigger0006 constructor.
     * @param TransactionManager $transactionManager
     */
    public function __construct(private TransactionManager $transactionManager)
    {
    }

    public function supports(): array
    {
        return [
            ChatParticipanteDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ChatParticipanteDTO|null $restDto
     * @param EntityInterface|ChatParticipanteEntity    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto,
                            EntityInterface $entity,
                            string $transactionId): void
    {
        $restDto->setUltimaVisualizacao(new \DateTime());
        $restDto->setMensagensNaoLidas(0);
    }

    public function getOrder(): int
    {
        return 2;
    }
}
