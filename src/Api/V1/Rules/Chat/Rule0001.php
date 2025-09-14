<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Chat/Rule0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Chat;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Chat;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=Somente usuários do mesmo grupo podem alterar e/ou excluir chats.
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
    public function __construct(private RulesTranslate $rulesTranslate)
    {
    }

    public function supports(): array
    {
       /*  return [
            Chat::class => [
                'beforeUpdate',
                'beforeDelete',
                'beforePatch',
            ],
        ]; */
        return [
            Chat::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getGrupo() != $entity->getGrupo()) {
            $this->rulesTranslate->throwException('chat', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
