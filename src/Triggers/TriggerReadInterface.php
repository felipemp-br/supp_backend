<?php

declare(strict_types=1);
/**
 * /src/Triggers/TriggerReadInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Triggers;

/**
 * Interface TriggerReadInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface TriggerReadInterface extends TriggerInterface
{
    /**
     * @param array $criteria
     * @param array $orderBy
     * @param int   $limit
     * @param int   $offset
     * @param array $populate
     * @param array $result
     */
    public function execute(
        array &$criteria,
        array &$orderBy,
        int &$limit,
        int &$offset,
        array &$populate,
        array &$result
    ): void;

    /**
     * @return int
     */
    public function getOrder(): int;
}
