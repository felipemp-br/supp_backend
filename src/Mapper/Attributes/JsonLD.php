<?php

declare(strict_types=1);
/**
 * /src/JsonLD/Attributes/JsonLD.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Mapper\Attributes;

use AllowDynamicProperties;
use Attribute;

/**
 * Class JsonLD.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AllowDynamicProperties]
#[Attribute(Attribute::TARGET_CLASS)]
class JsonLD
{
    /**
     * @param string $jsonLDId
     * @param string $jsonLDType
     * @param string $jsonLDContext
     * @param array  $options
     */
    public function __construct(
        public string $jsonLDId,
        public string $jsonLDType,
        public string $jsonLDContext,
        public array $options = [],
    ) {
    }
}
