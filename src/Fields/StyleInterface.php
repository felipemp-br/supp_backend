<?php

declare(strict_types=1);
/**
 * /src/Fields/StyleInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields;

use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 * Interface StyleInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface StyleInterface
{
    /**
     * @param ComponenteDigital $componenteDigital
     * @return string|null
     */
    public function support(EntityInterface|ComponenteDigital $componenteDigital): ?array;

    public function getOrder(): int;
}
