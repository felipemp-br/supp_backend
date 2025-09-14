<?php

declare(strict_types=1);
/**
 * /src/Validator/Constraints/NumeroBeneficioValidator.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class NumeroBeneficioValidator.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class NumeroBeneficioValidator extends ConstraintValidator
{

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value) {
            return;
        }

        if (!$this->isNbValid($value)) {
            /* @noinspection PhpUndefinedFieldInspection */
            $this->context->addViolation(
                $constraint->message,
                [
                    '{{ value }}' => $value,
                ]
            );
        }
    }

    /**
     * @param $value
     *
     * @return bool
     */
    private function isNbValid($value): bool
    {
        // Verificar se são apenas dígitos
        $number_validation_regex = "/^\\d+$/";
        if (!preg_match($number_validation_regex, $value)) {
            return false;
        }

        // Padding com zeros a esquerda
        $num_beneficio = str_pad($value, 11, "0", STR_PAD_LEFT);

        // Cálculo do MOD11
        $soma = [];
        $multiplicadores = [3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        foreach ($multiplicadores as $key => $multiplicador) {
            $soma[] = str_split($num_beneficio)[$key] * $multiplicador;
        }

        $resto = array_sum($soma) % 11;
        $dv = 11 - $resto;

        $digito = match ($dv) {
            10, 11 => 0,
            default => $dv,
        };

        if (substr($num_beneficio, -1) != $digito) {
            return false;
        }

        return true;
    }
}
