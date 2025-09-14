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
 * Class Mapper.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AllowDynamicProperties]
#[Attribute(Attribute::TARGET_CLASS)]
class Mapper
{
    /**
     * @param string|null $class
     * @param array       $options
     * @param array       $entityMapping
     * @param array       $excludePopulate
     */
    public function __construct(
        public ?string $class = null,
        public array $options = [],
        public array $entityMapping = [],
        public array $excludePopulate = []
    ) {
    }
}
