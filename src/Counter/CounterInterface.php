<?php

declare(strict_types=1);
/**
 * /src/Counter/CounterInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Counter;

use SuppCore\AdministrativoBackend\Counter\Message\PushMessage;

/**
 * Interface CounterInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface CounterInterface
{
    /**
     * @return PushMessage[]
     */
    public function getMessages(): array;

    /**
     * @return int
     */
    public function getOrder(): int;
}
