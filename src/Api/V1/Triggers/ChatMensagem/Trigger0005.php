<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ChatMensagem/Trigger0005.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ChatMensagem;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatMensagem as ChatMensagemDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatParticipanteResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ChatMensagem as ChatMensagemEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0005.
 *
 * @descSwagger=Ao enviar uma mensagem seta a data de visualização do chat.
 * @classeSwagger=Trigger0005
 */
class Trigger0005 implements TriggerInterface
{
    /**
     * Trigger0005 constructor.
     *
     * @param ChatParticipanteResource $chatParticipanteResource
     */
    public function __construct(private ChatParticipanteResource $chatParticipanteResource)
    {
    }

    public function supports(): array
    {
        return [
            ChatMensagemDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ChatMensagemDTO|null $restDto
     * @param EntityInterface|ChatMensagemEntity    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto,
                            EntityInterface $entity,
                            string $transactionId): void
    {
        $this->chatParticipanteResource->limparMensagens(
            $entity->getChat()->getId(),
            $transactionId
        );
    }

    public function getOrder(): int
    {
        return 2;
    }
}
