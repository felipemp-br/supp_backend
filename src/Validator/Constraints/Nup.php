<?php

declare(strict_types=1);
/**
 * /src/Validator/Constraints/Nup.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

/**
 * Class Nup.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Attribute]
class Nup extends Constraint
{
    /**
     * @param string     $message
     * @param array|null $groups
     * @param mixed|null $payload
     */
    #[HasNamedArguments]
    public function __construct(
        public string $message = 'Número do processo administrativo inválido!',
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([], $groups, $payload);
    }
}
