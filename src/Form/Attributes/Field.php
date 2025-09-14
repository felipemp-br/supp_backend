<?php

declare(strict_types=1);
/**
 * /src/Form/Attributes/Field.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Form\Attributes;

use AllowDynamicProperties;
use Attribute;

/**
 * Class Field.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AllowDynamicProperties]
#[Attribute(Attribute::TARGET_PROPERTY)]
class Field
{
    /**
     * @param string      $value
     * @param array       $options
     * @param array       $methods
     * @param string|null $name
     */
    public function __construct(
        public string $value,
        public array $options = [],
        public array $methods = [],
        public ?string $name = null,
    ) {
    }
}
