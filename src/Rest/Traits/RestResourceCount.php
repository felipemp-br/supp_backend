<?php

declare(strict_types=1);
/**
 * /src/Rest/Traits/RestResourceCount.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Traits;

/**
 * Trait RestResourceCount.
 *
 * @SuppressWarnings("unused")
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait RestResourceCount
{
    /**
     * Before lifecycle method for count method.
     *
     * @param mixed[] $criteria
     * @param mixed[] $search
     */
    public function beforeCount(array &$criteria, array &$search): void
    {
    }

    /**
     * Before lifecycle method for count method.
     *
     * @param mixed[] $criteria
     * @param mixed[] $search
     */
    public function afterCount(array &$criteria, array &$search, int &$count): void
    {
    }
}
