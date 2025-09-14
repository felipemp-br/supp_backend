<?php

declare(strict_types=1);
/**
 * /src/Form/Attributes/Method.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Form\Attributes;

use AllowDynamicProperties;
use Attribute;

/**
 * Class Method.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AllowDynamicProperties]
#[Attribute(Attribute::TARGET_PROPERTY)]
class Method
{
    /**
     * @param string $value
     * @param array  $roles
     */
    public function __construct(
        public string $value,
        public array $roles = [],
    ) {
    }
}
