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
class CnjValidator extends ConstraintValidator
{

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value) {
            return;
        }

        if (!$this->isCnjValid($value)) {
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
    private function isCnjValid($value): bool
    {
        $numProcessoJudicial = preg_replace('/\D/', '', $value);

        if (20 !== mb_strlen($numProcessoJudicial, 'UTF-8') && 22 !== mb_strlen($numProcessoJudicial, 'UTF-8')
        ) {
            return false;
        }

        $sequencial = mb_substr($numProcessoJudicial, 0, 7, 'UTF-8');
        $dv = mb_substr($numProcessoJudicial, 7, 2, 'UTF-8');
        $ano = mb_substr($numProcessoJudicial, 9, 4, 'UTF-8');
        $justica = mb_substr($numProcessoJudicial, 13, 1, 'UTF-8');
        $tribunal = mb_substr($numProcessoJudicial, 14, 2, 'UTF-8');
        $unidade = mb_substr($numProcessoJudicial, 16, 4, 'UTF-8');

        $r1 = intval($sequencial) % 97;
        $r2 = intval($r1 . $ano . $justica . $tribunal) % 97;
        $r3 = intval($r2 . $unidade . '00') % 97;

        $dvCalculado = strval(98 - $r3);

        if (strlen($dvCalculado) === 1) {
            $dvCalculado = '0' . $dvCalculado;
        }

        return $dv === $dvCalculado;
    }
}
