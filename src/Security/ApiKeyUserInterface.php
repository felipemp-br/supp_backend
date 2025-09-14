<?php

declare(strict_types=1);
/**
 * /src/Security/ApiKeyUser.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security;

use SuppCore\AdministrativoBackend\Entity\ApiKey;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface ApiKeyUserInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface ApiKeyUserInterface extends UserInterface
{
    /**
     * ApiKeyUser constructor.
     *
     * @param ApiKey       $apiKey
     * @param RolesService $rolesService
     */
    public function __construct(ApiKey $apiKey, RolesService $rolesService);

    /**
     * Getter method for ApiKey entity.
     *
     * @return ApiKey
     */
    public function getApiKey(): ApiKey;

    /**
     * Returns the roles granted to the api user.
     *
     * @return string[] The user roles
     */
    public function getRoles(): array;
}
