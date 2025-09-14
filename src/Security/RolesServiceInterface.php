<?php

declare(strict_types=1);
/**
 * /src/Security/RolesInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security;

use SuppCore\AdministrativoBackend\Entity\UserInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Interface RolesInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface RolesServiceInterface
{
    // Used role constants
    public const ROLE_LOGGED = 'ROLE_LOGGED';
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_COLABORADOR = 'ROLE_COLABORADOR';
    public const ROLE_COORDENADOR_SETOR = 'ROLE_COORDENADOR_SETOR';
    public const ROLE_COORDENADOR_UNIDADE = 'ROLE_COORDENADOR_UNIDADE';
    public const ROLE_COORDENADOR_ORGAO_CENTRAL = 'ROLE_COORDENADOR_ORGAO_CENTRAL';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_ROOT = 'ROLE_ROOT';
    public const ROLE_API = 'ROLE_API';

    /**
     * RolesHelper constructor.
     *
     * @param mixed[]               $rolesHierarchy This is a 'security.role_hierarchy.roles' parameter value
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(array $rolesHierarchy, TokenStorageInterface $tokenStorage);

    /**
     * Getter for role hierarchy.
     *
     * @return mixed[]
     */
    public function getHierarchy(): array;

    /**
     * Getter method to return all roles in single dimensional array.
     *
     * @return string[]
     */
    public function getRoles(): array;

    /**
     * Getter method to return all contextual roles.
     *
     * @param Usuario $usuario
     *
     * @return string[]
     */
    public function getContextualRoles(Usuario $usuario): array;

    /**
     * Getter method for role label.
     *
     * @param string $role
     *
     * @return string
     */
    public function getRoleLabel(string $role): string;

    /**
     * Getter method for short role.
     *
     * @param string $role
     *
     * @return string
     */
    public function getShort(string $role): string;

    /**
     * @param string[] $roles
     *
     * @return string[]
     */
    public function getInheritedRoles(array $roles): array;

    /**
     * @param UserInterface $usuario
     *
     * @return array
     */
    public function getUserRolesNames(UserInterface $usuario): array;
}
