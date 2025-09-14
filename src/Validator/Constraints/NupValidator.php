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
class NupValidator extends ConstraintValidator
{

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value) {
            return;
        }

        if (!$this->isNupValid($value)) {
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
    private function isNupValid($value): bool
    {
        $nup = preg_replace('/\D/', '', $value);

        if (strlen($nup) !== 17) {
            return false;
        }

        $prefixo = substr($nup, 0, 5);
        $sequencial = substr($nup, 5, 6);
        $ano = substr($nup, 11, 4);
        $dv = substr($nup, 15, 2);

        if (substr($ano, 0, 2) === '19') {
            $ano = substr($ano, 2, 2);
        }

        $digitos = $prefixo . $sequencial . $ano;
        $tamanho = strlen($digitos);
        $dv1 = 0;
        $dv2 = 0;

        for ($i = $tamanho - 1, $peso = 2; $i >= 0; $i--, $peso++) {
            $dv1 += intval($digitos[$i]) * $peso;
        }

        if (($dv1 = 11 - ($dv1 % 11)) >= 10) {
            $dv1 -= 10;
        }

        $digitos .= $dv1;

        for ($i = $tamanho, $peso = 2; $i >= 0; $i--, $peso++) {
            $dv2 += intval($digitos[$i]) * $peso;
        }

        if (($dv2 = 11 - ($dv2 % 11)) >= 10) {
            $dv2 -= 10;
        }

        return $dv === $dv1 . $dv2;
    }
}
