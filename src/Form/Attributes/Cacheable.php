<?php

declare(strict_types=1);
/**
 * /src/Form/Attributes/Cacheable.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Form\Attributes;

use AllowDynamicProperties;
use Attribute;

/**
 * Class Cacheable.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AllowDynamicProperties]
#[Attribute(Attribute::TARGET_CLASS)]
class Cacheable
{
    /**
     * @param int $expire
     */
    public function __construct(
        public int $expire,
    ) {
    }
}
