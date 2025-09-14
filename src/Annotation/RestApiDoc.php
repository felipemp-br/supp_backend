<?php

declare(strict_types=1);
/**
 * /src/Annotation/RestApiDoc.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Annotation;

/**
 * Class RestApiDoc.
 *
 * @Annotation
 * @NamedArgumentConstructor
 * @Annotation\Target("METHOD")
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class RestApiDoc
{
    /**
     * @param bool $disabled
     */
    public function __construct(
        public bool $disabled = false,
    ) {
    }
}
