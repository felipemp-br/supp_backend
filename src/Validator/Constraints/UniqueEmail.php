<?php

declare(strict_types=1);
/**
 * /src/Validator/Constraints/UniqueEmail.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueEmail.
 *
 * @Annotation
 * @Target({"CLASS"})
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class UniqueEmail extends Constraint
{
    /**
     * Unique constant for validator constrain.
     *
     * @var string;
     */
    public const IS_UNIQUE_EMAIL_ERROR = 'd487278d-8b13-4da0-b4cc-f862e6e99af6';

    /**
     * Message for validation error.
     *
     * @var string
     */
    public const MESSAGE = 'Este email já está em utilização.';

    /**
     * Error names configuration.
     *
     * @var mixed[]
     */
    protected const ERROR_NAMES = [
        self::IS_UNIQUE_EMAIL_ERROR => 'IS_UNIQUE_EMAIL_ERROR',
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
