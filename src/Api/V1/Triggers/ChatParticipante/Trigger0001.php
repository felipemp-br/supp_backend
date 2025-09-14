<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ChatParticipante/Trigger0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ChatParticipante;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatParticipante as ChatParticipanteDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatResource;
use SuppCore\AdministrativoBackend\Chat\Message\ChatMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante as ChatParticipanteEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Faz o push de inclusão de participante no chat.
 * @classeSwagger=Trigger0001
 */
class Trigger0001 implements TriggerInterface
{
    /**
     * Trigger0001 constructor.
     * @param TransactionManager $transactionManager
     */
    public function __construct(private TransactionManager $transactionManager)
    {
    }

    public function supports(): array
    {
        return [
            ChatParticipanteDTO::class => [
                'afterCreate',
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
        // Envia uma mensagem de chat criado para o participante adicionado
        $chatMessage = new ChatMessage(
            [
                $restDto->getUsuario()->getUsername().'/chat',
            ],
            ChatResource::class,
            $entity->getChat()->getUuid(),
            [
                'capa',
                'ultimaMensagem',
            ],
            [
                'chat_individual_usuario' => $restDto->getUsuario()->getId(),
                'chat_participante' => $restDto->getUsuario()->getId(),
            ]
        );

        $this->transactionManager->addAsyncDispatch($chatMessage, $transactionId);
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
