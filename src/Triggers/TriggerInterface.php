<?php

declare(strict_types=1);
/**
 * /src/Triggers/TriggerInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Triggers;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 * Interface TriggerInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface TriggerInterface
{
    /**
     * @return array
     */
    public function supports(): array;

    /**
     * @return int
     */
    public function getOrder(): int;
}
