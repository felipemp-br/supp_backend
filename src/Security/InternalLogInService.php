<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Security;

use Gedmo\Blameable\BlameableListener;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class InternalLogInService.
 */
class InternalLogInService
{

    /**
     * InternalLogInService constructor.
     *
     * @param BlameableListener $blameableListener
     * @param RolesService $roleService
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        private readonly BlameableListener $blameableListener,
        private readonly RolesService $roleService,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    /**
     * @param Usuario $usuario
     */
    public function logUserIn(Usuario $usuario)
    {
        $roles = $this->roleService->getContextualRoles($usuario);

        $token = new UsernamePasswordToken(
            $usuario,
            'main',
            $roles
        );

        $token->setAttribute('trusted', 'internal');
        $this->blameableListener->setUserValue($usuario);

        $this->tokenStorage->setToken($token);
    }
}
