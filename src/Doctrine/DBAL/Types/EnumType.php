<?php

declare(strict_types=1);
/**
 * /src/Doctrine/DBAL/Types/EnumType.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Doctrine\DBAL\Types;

use function array_map;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use function implode;
use function in_array;
use InvalidArgumentException;
use function sprintf;

/**
 * Class EnumType.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class EnumType extends Type
{
    protected static string $name;

    /**
     * @var string[]
     */
    protected static array $values = [];

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @SuppressWarnings("unused")
     *
     * @param mixed[]          $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $iterator = fn (string $value): string => "'".$value."'";

        return 'ENUM('.implode(', ', array_map($iterator, static::$values)).')';
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $value = parent::convertToDatabaseValue($value, $platform);

        if (!in_array($value, static::$values, true)) {
            $message = sprintf(
                "Invalid '%s' value",
                $this->getName()
            );

            throw new InvalidArgumentException($message);
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::$name;
    }

    /**
     * If this Doctrine Type maps to an already mapped database type, reverse schema engineering can't take them apart.
     * You need to mark one of those types as commented, which will have Doctrine use an SQL comment to type hint the
     * actual Doctrine Type.
     *
     * @param AbstractPlatform $platform
     *
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        parent::requiresSQLCommentHint($platform);

        return true;
    }
}
