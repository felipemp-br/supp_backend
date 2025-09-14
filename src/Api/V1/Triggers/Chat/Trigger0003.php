<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Chat/Trigger0003.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Chat;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Chat as ChatDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatResource;
use SuppCore\AdministrativoBackend\Chat\Message\ChatMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Chat as ChatEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger=Faz o push da criação/atualização do chat para os participantes.
 * @classeSwagger=Trigger0003
 */
class Trigger0003 implements TriggerInterface
{
    /**
     * Trigger0003 constructor.
     * @param ChatResource $chatResource
     * @param TransactionManager $transactionManager
     */
    public function __construct(private ChatResource $chatResource,
                                private TransactionManager $transactionManager)
    {
    }

    public function supports(): array
    {
        return [
            ChatDTO::class => [
                'afterCreate',
                'afterUpdate',
                'afterPatch',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ChatDTO|null $restDto
     * @param EntityInterface|ChatEntity    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto,
                            EntityInterface $entity,
                            string $transactionId): void
    {

        foreach ($entity->getParticipantes() as $participante) {

            $chatMessage = new ChatMessage(
                [
                    $participante->getUsuario()->getUsername().'/chat'
                ],
                ChatResource::class,
                $entity->getUuid(),
                [
                    'capa',
                    'ultimaMensagem'
                ],
                [
                    'chat_individual_usuario' => $participante->getUsuario()->getId(),
                    'chat_participante' => $participante->getUsuario()->getId()
                ]
            );

            $this->transactionManager->addAsyncDispatch($chatMessage, $transactionId);
        }

    }

    public function getOrder(): int
    {
        return 1000;
    }
}
