<?php

declare(strict_types=1);
/**
 * /src/Triggers/TriggerReadInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Triggers;

use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 * Interface TriggerReadInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface TriggerReadOneInterface extends TriggerInterface
{
    /**
     * @param int $id
     * @param array $populate
     * @param array $orderBy
     * @param array $context
     * @param EntityInterface|null $entity
     *
     * @return void
     */
    public function execute(
        int &$id,
        array &$populate,
        array &$orderBy,
        array &$context,
        ?EntityInterface &$entity
    ): void;

    /**
     * @return int
     */
    public function getOrder(): int;
}
