<?php

declare(strict_types=1);
/**
 * /src/App/Validator/Constraints/UniqueEmailValidator.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Validator\Constraints;

use Doctrine\ORM\NonUniqueResultException;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class UniqueEmailValidator.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class UniqueEmailValidator extends ConstraintValidator
{
    private UsuarioRepository $repository;

    /**
     * UniqueUsernameValidator constructor.
     *
     * @param UsuarioRepository $repository
     */
    public function __construct(UsuarioRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @throws NonUniqueResultException
     *
     * @param UserInterface|mixed    $value      The value that should be validated
     * @param Constraint|UniqueEmail $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$this->repository->isEmailAvailable($value->getEmail(), $value->getId())) {
            $this->context
                ->buildViolation(UniqueEmail::MESSAGE)
                ->setCode(UniqueEmail::IS_UNIQUE_EMAIL_ERROR)
                ->addViolation();
        }
    }
}
