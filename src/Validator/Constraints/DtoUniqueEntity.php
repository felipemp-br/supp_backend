<?php

/** @noinspection PhpMissingParentCallCommonInspection */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @NamedArgumentConstructor
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class DtoUniqueEntity extends Constraint
{
    public const NOT_UNIQUE_ERROR = 'e777db8d-3af0-41f6-8a73-55255375cdca';

    protected const ERROR_NAMES = [
        self::NOT_UNIQUE_ERROR => 'NOT_UNIQUE_ERROR',
    ];

    /**
     * @param array       $fieldMapping
     * @param string|null $entityClass
     * @param bool        $ignoreNull
     * @param string      $message
     * @param string|null $errorPath
     * @param string      $repositoryMethod
     * @param string|null $em
     * @param array|null  $groups
     * @param mixed|null  $payload
     */
    #[HasNamedArguments]
    public function __construct(
        public array $fieldMapping = [],
        public ?string $entityClass = null,
        public bool $ignoreNull = true,
        public string $message = 'Este campo já está em utilização.',
        public ?string $errorPath = null,
        public string $repositoryMethod = 'findBy',
        public ?string $em = null,
        ?array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([], $groups, $payload);
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return DtoUniqueEntityValidator::class;
    }
}
