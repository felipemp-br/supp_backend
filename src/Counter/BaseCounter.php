<?php

declare(strict_types=1);
/**
 * /src/Counter/BaseCounter.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Counter;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Counter0001.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class BaseCounter
{

    /**
     * BaseCounter constructor.
     */
    public function __construct(
        protected readonly TokenStorageInterface $tokenStorage,
        protected readonly AuthorizationCheckerInterface $authorizationChecker
    ) {
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }
}
