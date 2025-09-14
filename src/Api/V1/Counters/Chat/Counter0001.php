<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Counters/Chat/Counter0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Counters\Chat;

use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatCounterResource;
use SuppCore\AdministrativoBackend\Counter\CounterInterface;
use SuppCore\AdministrativoBackend\Counter\Message\PushMessage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Counter0001.
 */
class Counter0001 implements CounterInterface
{
    /**
     * Counter0001 constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(private TokenStorageInterface $tokenStorage)
    {
    }

    /**
     * @return PushMessage[]
     */
    public function getMessages(): array
    {
        $pushMessage = new PushMessage();
        $pushMessage->setChannel(
            $this->tokenStorage->getToken()->getUser()->getUserIdentifier()
        );
        $pushMessage->setResource(
            ChatCounterResource::class
        );
        $pushMessage->setIdentifier('chat_mensagens_nao_lidas');
        $pushMessage->setCriteria([
            'usuarioParticipante' => $this->tokenStorage->getToken()->getUser()->getId(),
        ]);

        return [$pushMessage];
    }

    public function getOrder(): int
    {
        return 1;
    }
}
