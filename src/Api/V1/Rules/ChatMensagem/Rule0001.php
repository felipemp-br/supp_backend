<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ChatMensagem/Rule0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ChatMensagem;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatMensagem;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=Não é possível enviar mensagem ou iniciar uma conversa consigo mesmo.
 * @classeSwagger=Rule0001
 */
class Rule0001 implements RuleInterface
{
    /**
     * Rule0001 constructor.
     *
     * @param RulesTranslate        $rulesTranslate
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(private RulesTranslate $rulesTranslate,
                                private TokenStorageInterface $tokenStorage)
    {
    }

    public function supports(): array
    {
        return [
            ChatMensagem::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getUsuarioTo()
            && $restDto->getUsuarioTo()->getId() == $this->tokenStorage->getToken()->getUser()->getId()) {
            $this->rulesTranslate->throwException('chatMensagem', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
