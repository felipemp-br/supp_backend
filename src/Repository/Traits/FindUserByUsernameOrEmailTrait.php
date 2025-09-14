<?php

declare(strict_types=1);
/**
 * /src/Repository/Traits/FindUserByUsernameOrEmailTrait.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository\Traits;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use SuppCore\AdministrativoBackend\Entity\UserInterface;

/**
 * Trait FindUserByUsernameOrEmailTrait.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method QueryBuilder createQueryBuilder(string $alias = null, string $indexBy = null): QueryBuilder
 */
trait FindUserByUsernameOrEmailTrait
{
    /**
     * @param string $usernameOrEmail
     *
     * @return UserInterface|null
     *
     * @throws NonUniqueResultException
     */
    public function findUserByUsernameOrEmail(string $usernameOrEmail): ?UserInterface
    {
        // Build query
        $query = $this
            ->createQueryBuilder('u')
            ->select('u, g')
            ->leftJoin('u.vinculacoesRoles', 'g')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $usernameOrEmail)
            ->setParameter('email', $usernameOrEmail)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    public function findUserByUsername(string $username): ?UserInterface
    {
        // Build query
        $query = $this
            ->createQueryBuilder('u')
            ->select('u, g')
            ->leftJoin('u.vinculacoesRoles', 'g')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
