<?php

declare(strict_types=1);
/**
 * /src/Security/ApiKeyUserProvider.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security;

use Exception;
use SuppCore\AdministrativoBackend\Entity\ApiKey;
use SuppCore\AdministrativoBackend\Repository\ApiKeyRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class ApiKeyUserProvider.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ApiKeyUserProvider implements ApiKeyUserProviderInterface, UserProviderInterface
{
    private ApiKeyRepository $apiKeyRepository;

    private RolesService $rolesService;

    /**
     * ApiKeyUserProvider constructor.
     *
     * @param ApiKeyRepository $apiKeyRepository
     * @param RolesService     $rolesService
     */
    public function __construct(ApiKeyRepository $apiKeyRepository, RolesService $rolesService)
    {
        $this->apiKeyRepository = $apiKeyRepository;
        $this->rolesService = $rolesService;
    }

    /**
     * Method to fetch ApiKey entity for specified token.
     *
     * @param string $token
     *
     * @return ApiKey|null
     */
    public function getApiKeyForToken(string $token): ?ApiKey
    {
        return $this->apiKeyRepository->findOneBy(['token' => $token]);
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UserNotFoundException if the user is not found. If user (API key) is found method
     * will create ApiKeyUser object for specified ApiKey entity.
     *
     * @param string $identifier
     *
     * @return ApiKeyUserInterface
     *
     * @throws UserNotFoundException
     */
    public function loadUserByIdentifier(string $identifier): ApiKeyUserInterface
    {
        /** @var ApiKey|null $apiKey */
        $apiKey = $this->apiKeyRepository->findOneBy(['token' => $identifier]);

        if (null === $apiKey) {
            throw new UserNotFoundException('API key is not valid');
        }

        return new ApiKeyUser($apiKey, $this->rolesService);
    }

    /**
     * Refreshes the user.
     *
     * It is up to the implementation to decide if the user data should be totally reloaded (e.g. from the database),
     * or if the UserInterface object can just be merged into some internal array of users / identity map.
     *
     * @SuppressWarnings("unused")
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws Exception
     * @throws UnsupportedUserException
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        throw new UnsupportedUserException('API key cannot refresh user');
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass(string $class): bool
    {
        return ApiKeyUser::class === $class;
    }
}
