<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ChatParticipante/Trigger0005.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ChatParticipante;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatParticipante as ChatParticipanteDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatParticipanteResource;
use SuppCore\AdministrativoBackend\Chat\Message\ChatMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante as ChatParticipanteEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0005.
 *
 * @descSwagger=Faz o push de atualização de participante.
 * @classeSwagger=Trigger0005
 */
class Trigger0005 implements TriggerInterface
{
    /**
     * Trigger0005 constructor.
     * @param TransactionManager $transactionManager
     */
    public function __construct(private TransactionManager $transactionManager)
    {
    }

    public function supports(): array
    {
        return [
            ChatParticipanteDTO::class => [
                'afterUpdate',
                'afterPatch',
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
        $chatMessage = new ChatMessage(
            [
                '/v1/administrativo/chat/'.$restDto->getChat()->getId()
            ],
            ChatParticipanteResource::class,
            $entity->getUuid(),
            [
                'usuario',
                'usuario.imgPerfil',
            ]
        );

        $this->transactionManager->addAsyncDispatch($chatMessage, $transactionId);
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
