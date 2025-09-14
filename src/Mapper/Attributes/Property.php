<?php

declare(strict_types=1);
/**
 * /src/Mapper/Attributes/Mapper.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Mapper\Attributes;

use AllowDynamicProperties;
use Attribute;

/**
 * Class Property.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AllowDynamicProperties]
#[Attribute(Attribute::TARGET_PROPERTY)]
class Property
{
    /**
     * @param string|null $name
     * @param string|null $dtoClass
     * @param string|null $dtoGetter
     * @param string|null $dtoSetter
     * @param string|null $entityGetter
     * @param string|null $entitySetter
     * @param bool        $collection
     * @param array       $options
     */
    public function __construct(
        public ?string $name = null,
        public ?string $dtoClass = null,
        public ?string $dtoGetter = null,
        public ?string $dtoSetter = null,
        public ?string $entityGetter = null,
        public ?string $entitySetter = null,
        public bool $collection = false,
        public array $options = []
    ) {
    }
}
