<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Chat/Trigger0004.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Chat;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Chat as ChatDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatCounterResource;
use SuppCore\AdministrativoBackend\Counter\Message\PushMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0004.
 *
 * @descSwagger=Faz o push count das mensagens não lidas de todos os chats do usuário.
 * @classeSwagger=Trigger0004
 */
class Trigger0004 implements TriggerInterface
{
    /**
     * Trigger0004 constructor.
     *
     * @param TransactionManager $transactionManager
     */
    public function __construct(private TransactionManager $transactionManager)
    {
    }

    public function supports(): array
    {
        return [
            ChatDTO::class => [
                'afterUpdate',
                'afterPatch',
            ],
        ];
    }

    public function execute(?RestDtoInterface $restDto,
                            EntityInterface $entity,
                            string $transactionId): void
    {
        foreach ($entity->getParticipantes() as $participante) {
            $pushMessage = new PushMessage();
            $pushMessage->setChannel(
                $participante->getUsuario()->getUsername()
            );
            $pushMessage->setResource(
                ChatCounterResource::class
            );
            $pushMessage->setIdentifier('chat_mensagens_nao_lidas');
            $pushMessage->setCriteria([
                'usuarioParticipante' => $participante->getUsuario()->getId(),
            ]);
            $this->transactionManager->addAsyncDispatch($pushMessage, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
