<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Usuario;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0002.
 *
 * @descSwagger=Verifica se é o próprio usuário para alterar a senha e valida se está correta a senha atual.
 * @classeSwagger=Rule0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    private TokenStorageInterface $tokenStorage;

    private RulesTranslate $rulesTranslate;

    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Rule0002 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TokenStorageInterface $tokenStorage,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->tokenStorage = $tokenStorage;
        $this->passwordHasher = $passwordHasher;
    }

    public function supports(): array
    {
        return [
            UsuarioDTO::class => [
                'beforeUpdate',
                'beforePatch',
                // 'skipeWhenCommand',
            ],
        ];
    }

    /**
     * @param UsuarioDTO|RestDtoInterface|null $restDto
     * @param UsuarioEntity|EntityInterface    $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (null == $restDto->getPlainPassword()) { // validação somente para alteração de senha
            return true;
        }

        if ($this->tokenStorage->getToken()->getUser()->getId() !== $entity->getId()) {
            $this->rulesTranslate->throwException('usuario', '0002a');
        }

        if (!$restDto->getCurrentPlainPassword()) {
            $this->rulesTranslate->throwException('usuario', '0002b');
        }

        if (!$this->passwordHasher->isPasswordValid(
            $entity,
            $restDto->getCurrentPlainPassword()
        )) {
            $this->rulesTranslate->throwException('usuario', '0002b');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
