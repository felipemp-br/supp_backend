<?php

declare(strict_types=1);
/**
 * /src/Validator/Constraints/CpfCnpj.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * Class CpfCnpj.
 *
 * @Annotation
 * @Target({"PROPERTY"})
 * @NamedArgumentConstructor
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class CpfCnpj extends Constraint
{
    /**
     * @param string     $message
     * @param array|null $groups
     * @param mixed|null $payload
     */
    public function __construct(
        public string $message = 'CPF/CNPJ inválido!',
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([], $groups, $payload);
    }
}
