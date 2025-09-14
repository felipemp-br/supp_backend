<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ChatMensagem/Trigger0004.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ChatMensagem;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatMensagem as ChatMensagemDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ChatMensagem as ChatMensagemEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0004.
 *
 * @descSwagger=Seta o usuário da mensagem de acordo com o usuário logado.
 * @classeSwagger=Trigger0004
 */
class Trigger0004 implements TriggerInterface
{
    /**
     * Trigger0004 constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(private TokenStorageInterface $tokenStorage)
    {
    }

    public function supports(): array
    {
        return [
            ChatMensagemDTO::class => [
                'beforeCreate',
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
        $restDto->setUsuario($this->tokenStorage->getToken()->getUser());
    }

    public function getOrder(): int
    {
        return 1;
    }
}
