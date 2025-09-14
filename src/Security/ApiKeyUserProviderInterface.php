<?php

declare(strict_types=1);
/**
 * /src/Security/ApiKeyUserProviderInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security;

use SuppCore\AdministrativoBackend\Entity\ApiKey;
use SuppCore\AdministrativoBackend\Repository\ApiKeyRepository;

/**
 * Interface ApiKeyUserProviderInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface ApiKeyUserProviderInterface
{
    /**
     * ApiKeyUserProvider constructor.
     *
     * @param ApiKeyRepository $apiKeyRepository
     * @param RolesService     $rolesService
     */
    public function __construct(ApiKeyRepository $apiKeyRepository, RolesService $rolesService);

    /**
     * Method to fetch ApiKey entity for specified token.
     *
     * @param string $token
     *
     * @return ApiKey|null
     */
    public function getApiKeyForToken(string $token): ?ApiKey;
}
