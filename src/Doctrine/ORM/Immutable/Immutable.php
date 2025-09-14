<?php

declare(strict_types=1);
/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Doctrine\ORM\Immutable;

use Attribute;

/**
 * Class Immutable.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @Annotation
 * @Annotation\Target({"CLASS"})
 * @NamedArgumentConstructor
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Immutable
{
    public const EXPRESSION_EQUALS = 'equals';
    public const EXPRESSION_NOT_EQUALS = 'not_equals';
    public const EXPRESSION_IN = 'in';
    public const EXPRESSION_NOT_IN = 'not_in';
    public const EXPRESSION_IS_NULL = 'is_null';
    public const EXPRESSION_IS_NOT_NULL = 'is_not_null';

    /**
     * @param string|null $fieldName
     * @param mixed|null  $expressionValues
     * @param string      $expression
     * @param bool        $lockAll
     */
    public function __construct(
        public ?string $fieldName = null,
        public mixed $expressionValues = null,
        public string $expression = self::EXPRESSION_EQUALS,
        public bool $lockAll = false,
    ) {
    }
}
