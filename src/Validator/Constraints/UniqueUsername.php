<?php

declare(strict_types=1);
/**
 * /src/Validator/Constraints/UniqueUsername.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueUsername.
 *
 * @Annotation
 * @Target({"CLASS"})
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class UniqueUsername extends Constraint
{
    /**
     * Unique constant for validator constrain.
     *
     * @var string
     */
    public const IS_UNIQUE_USERNAME_ERROR = 'ea62740a-4d9b-4a25-9a56-46fb4c3d5fea';

    /**
     * Message for validation error.
     *
     * @var string
     */
    public const MESSAGE = 'Este username já está em utilização.';

    /**
     * Error names configuration.
     *
     * @var mixed[]
     */
    protected const ERROR_NAMES = [
        self::IS_UNIQUE_USERNAME_ERROR => 'IS_UNIQUE_USERNAME_ERROR',
    ];

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * Returns whether the constraint can be put onto classes, properties or both.
     *
     * This method should return one or more of the constants
     * Constraint::CLASS_CONSTRAINT and Constraint::PROPERTY_CONSTRAINT.
     *
     * @return string One or more constant values
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
