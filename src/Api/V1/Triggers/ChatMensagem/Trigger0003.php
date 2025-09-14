<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ChatMensagem/Trigger0003.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ChatMensagem;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatMensagem as ChatMensagemDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatMensagemResource;
use SuppCore\AdministrativoBackend\Chat\Message\ChatMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ChatMensagem as ChatMensagemEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger=Faz o push da mensagem do chat para o canal do chat.
 * @classeSwagger=Trigger0003
 */
class Trigger0003 implements TriggerInterface
{
    /**
     * Trigger0003 constructor.
     * @param TransactionManager $transactionManager
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(private TransactionManager $transactionManager,
                                private TokenStorageInterface $tokenStorage)
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

        $chatMessage = new ChatMessage(
            ['/v1/administrativo/chat/'.$restDto->getChat()->getId()],
            ChatMensagemResource::class,
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
