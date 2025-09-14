<?php

declare(strict_types=1);
/**
 * /src/Security/UserProvider.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use function get_class;
use function is_subclass_of;
use function sprintf;
use SuppCore\AdministrativoBackend\Entity\Usuario as Entity;
use SuppCore\AdministrativoBackend\Repository\Traits\FindUserByUsernameOrEmailTrait;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserProvider.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class UserProvider extends EntityRepository implements UserProviderInterface, UserLoaderInterface
{
    // Traits
    use FindUserByUsernameOrEmailTrait;

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be totally reloaded (e.g. from the database),
     * or if the UserInterface object can just be merged into some internal array of users / identity map.
     *
     * @param UserInterface $user
     *
     * @return Entity
     *
     * @throws NonUniqueResultException|UserNotFoundException|UnsupportedUserException
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        $class = get_class($user);

        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instance of "%s" is not supported.', $class));
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    /**
     * @param string $identifier
     *
     * @return UserInterface
     *
     * @throws NonUniqueResultException|UserNotFoundException
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->findUserByUsernameOrEmail($identifier);

        if (null === $user || false === $user->getEnabled()) {
            throw new UserNotFoundException();
        }

        return $user;
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
        return $class === $this->getEntityName() || is_subclass_of($class, $this->getEntityName());
    }
}
