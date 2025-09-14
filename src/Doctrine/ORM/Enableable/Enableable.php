<?php

declare(strict_types=1);
/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable;

use Attribute;

/**
 * Class Enableable.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @Annotation
 * @NamedArgumentConstructor
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Enableable
{
    /**
     * @param string $fieldName
     * @param array  $options
     */
    public function __construct(
        public string $fieldName = 'ativo',
        public array $options = []
    ) {
    }
}
