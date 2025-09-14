<?php

declare(strict_types=1);
/**
 * /src/Form/Attributes/Form.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Form\Attributes;

use AllowDynamicProperties;
use Attribute;

/**
 * Class Form.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AllowDynamicProperties]
#[Attribute(Attribute::TARGET_CLASS)]
class Form
{
    /**
     * @param string|null $value
     * @param array       $validationGroups
     */
    public function __construct(
        public ?string $value = null,
        public array $validationGroups = [],
    ) {
    }
}
