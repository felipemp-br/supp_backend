<?php

declare(strict_types=1);
/**
 * /src/Validator/Constraints/Password.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * Class Password.
 *
 * @Annotation
 * @Target({"PROPERTY"})
 * @NamedArgumentConstructor
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Password extends Constraint
{
    /**
     * @param string     $message
     * @param array|null $groups
     * @param mixed|null $payload
     */
    public function __construct(
        public string $message = 'A senha deve conter entre 8 e 16 caracteres com pelo menos 1 letra maiúscula, 1 letra minúscula e 1 número!',
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([], $groups, $payload);
    }
}
