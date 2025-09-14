<?php

declare(strict_types=1);
/**
 * /src/Security/Roles.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security;

use SuppCore\AdministrativoBackend\Entity\ApiKey;
use SuppCore\AdministrativoBackend\Entity\UserInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

/**
 * Class Roles.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RolesService implements RolesServiceInterface
{
    /**
     * Roles hierarchy.
     *
     * @var mixed[]
     */
    private array $rolesHierarchy;

    /**
     * @var mixed[]
     */
    private static array $roleNames = [
        self::ROLE_LOGGED => 'Logged in users',
        self::ROLE_USER => 'External users',
        self::ROLE_COLABORADOR => 'Internal users',
        self::ROLE_COORDENADOR_SETOR => 'Coordenador Setor users',
        self::ROLE_COORDENADOR_UNIDADE => 'Coordenador Unidade users',
        self::ROLE_COORDENADOR_ORGAO_CENTRAL => 'Coordenador Orgao Central users',
        self::ROLE_ADMIN => 'Admin users',
        self::ROLE_ROOT => 'Root users',
        self::ROLE_API => 'API users',
    ];

    /**
     * @var array
     */
    private array $roles = [];

    private TokenStorageInterface $tokenStorage;

    /**
     * RolesHelper constructor.
     *
     * @param array                 $rolesHierarchy This is a 'security.role_hierarchy.roles' parameter value
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(array $rolesHierarchy, TokenStorageInterface $tokenStorage)
    {
        $this->rolesHierarchy = $rolesHierarchy;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param RoleInterface $role
     */
    public function addRole(RoleInterface $role): void
    {
        $this->roles[] = $role;
    }

    /**
     * Getter for role hierarchy.
     *
     * @return array
     */
    public function getHierarchy(): array
    {
        return $this->rolesHierarchy;
    }

    /**
     * Getter method to return all contextual roles.
     *
     * @param Usuario $usuario
     *
     * @return string[]
     */
    public function getContextualRoles(Usuario $usuario): array
    {
        $contextualRoles = $usuario->getRoles();

        if ($this->tokenStorage->getToken()?->hasAttribute('apiKeyId')) {
            $apiKeyId = $this->tokenStorage->getToken()->getAttribute('apiKeyId');
            $apiKeyRoles = $usuario->getApiKeys()->filter(function (ApiKey $apiKey) use ($apiKeyId) {
                return $apiKey->getId() === $apiKeyId;
            })->first()->getRoles();

            if (null !== $apiKeyRoles) {
                $contextualRoles = array_merge($contextualRoles, $apiKeyRoles);
            }
        }

        foreach ($this->roles as $role) {
            $roleName = $role->getRole($usuario);
            if ($roleName) {
                if (is_array($roleName)) {
                    $contextualRoles = array_merge($contextualRoles, $roleName);
                } else {
                    $contextualRoles[] = $roleName;
                }
            }
        }

        return \array_map('\strval', \array_unique($this->getInheritedRoles($contextualRoles)));
    }

    /**
     * Getter method to return all roles in single dimensional array.
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        return [
            self::ROLE_LOGGED,
            self::ROLE_USER,
            self::ROLE_COLABORADOR,
            self::ROLE_COORDENADOR_SETOR,
            self::ROLE_COORDENADOR_UNIDADE,
            self::ROLE_COORDENADOR_ORGAO_CENTRAL,
            self::ROLE_ADMIN,
            self::ROLE_ROOT,
            self::ROLE_API,
        ];
    }

    /**
     * Getter method for role label.
     *
     * @param string $role
     *
     * @return string
     */
    public function getRoleLabel(string $role): string
    {
        $output = 'Unknown - '.$role;

        if (\array_key_exists($role, self::$roleNames)) {
            $output = self::$roleNames[$role];
        }

        return $output;
    }

    /**
     * Getter method for short role.
     *
     * @param string $role
     *
     * @return string
     */
    public function getShort(string $role): string
    {
        $offset = \mb_strpos($role, '_');
        $offset = false !== $offset ? $offset + 1 : 0;

        return \mb_strtolower(\mb_substr($role, $offset));
    }

    /**
     * Helper method to get inherited roles for given roles.
     *
     * @param string[] $roles
     *
     * @return string[]
     */
    public function getInheritedRoles(array $roles): array
    {
        $hierarchy = new RoleHierarchy($this->rolesHierarchy);

        return \array_unique(
            \array_map(
                fn (string $role): string => $role,
                $hierarchy->getReachableRoleNames(
                    \array_map(
                        fn (string $role): string => $role,
                        $roles
                    )
                )
            )
        );
    }

    /**
     * @param UserInterface $usuario
     *
     * @return array
     */
    public function getUserRolesNames(UserInterface $usuario): array
    {
        return \array_unique($this->getInheritedRoles($usuario->getRoles()));
    }
}
