<?php

declare(strict_types=1);
/**
 * /src/Security/ApiKeyUser.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security;

use function array_unique;
use JMS\Serializer\Annotation\Groups;
use SuppCore\AdministrativoBackend\Entity\ApiKey;
use SuppCore\AdministrativoBackend\Entity\VinculacaoRole;

/**
 * Class ApiKeyUser.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ApiKeyUser implements ApiKeyUserInterface
{
    /**
     * @Groups({
     *      "ApiKeyUser",
     *      "ApiKeyUser.apiKey",
     *  })
     */
    private string $username;

    /**
     * @Groups({
     *      "ApiKeyUser.apiKey",
     *  })
     */
    private ApiKey $apiKey;

    /**
     * @var string[]
     *
     * @Groups({
     *      "ApiKeyUser",
     *      "ApiKeyUser.roles",
     *  })
     */
    private array $roles;

    /**
     * ApiKeyUser constructor.
     *
     * @param ApiKey       $apiKey
     * @param RolesService $rolesService
     */
    public function __construct(ApiKey $apiKey, RolesService $rolesService)
    {
        $this->apiKey = $apiKey;

        $this->username = $this->apiKey->getToken();

        // Attach base 'ROLE_API' for API user
        $roles = [RolesService::ROLE_API];

        // Iterate API key user groups and attach those roles for API user
        $this->apiKey->getVinculacoesRoles()->map(function (VinculacaoRole $vinculacaoRole) use (&$roles): void {
            $roles[] = $vinculacaoRole->getRole();
        });

        $this->roles = array_unique($rolesService->getInheritedRoles($roles));
    }

    /**
     * Getter method for ApiKey entity.
     *
     * @return ApiKey
     */
    public function getApiKey(): ApiKey
    {
        return $this->apiKey;
    }

    /**
     * Returns the roles granted to the api user.
     *
     * @return string[] The user roles
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getPassword(): string
    {
        return '';
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @codeCoverageIgnore
     */
    public function getSalt(): void
    {
    }

    /**
     * Returns the identifier for this user (e.g. its username or email address).
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @codeCoverageIgnore
     */
    public function eraseCredentials(): void
    {
    }
}
