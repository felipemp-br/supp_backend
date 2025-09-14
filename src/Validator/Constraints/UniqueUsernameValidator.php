<?php

declare(strict_types=1);
/**
 * /src/Validator/Constraints/UniqueUsernameValidator.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Validator\Constraints;

use Doctrine\ORM\NonUniqueResultException;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class UniqueUsernameValidator.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class UniqueUsernameValidator extends ConstraintValidator
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
     * @param mixed      $value
     * @param Constraint $constraint
     *
     * @throws NonUniqueResultException
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$this->repository->isUsernameAvailable($value->getUsername(), $value->getId())) {
            $this->context
                ->buildViolation(UniqueUsername::MESSAGE)
                ->setCode(UniqueUsername::IS_UNIQUE_USERNAME_ERROR)
                ->addViolation();
        }
    }
}
