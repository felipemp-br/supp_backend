<?php

declare(strict_types=1);
/**
 * /src/Validator/Constraints/PasswordValidador.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Validator\Constraints;

use function preg_match;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class PasswordValidator.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PasswordValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if ($value && (0 === preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}$/', $value))) {
            $this->context->addViolation(
                $constraint->message,
                [
                    '{{ value }}' => $value,
                ]
            );
        }
    }
}
