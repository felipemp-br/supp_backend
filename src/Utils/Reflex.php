<?php /** @noinspection PhpUnused */

declare(strict_types=1);
/**
 * /src/Helper/Utils/Utils.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

use Exception;

/**
 * Class Utils.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
class Reflex
{
    /**
     * @param object $object
     *
     * @return array
     */
    public static function getters(object $object): array
    {
        return array_filter(get_class_methods($object), fn ($m) => preg_match('/^(get|is|has)/', $m));
    }

    /**
     * @param object $object
     *
     * @return array
     */
    public static function setters(object $object): array
    {
        return array_filter(get_class_methods($object), fn ($m) => preg_match('/^set/', $m));
    }

    /**
     * @param object $object
     *
     * @return array
     */
    public static function properties(object $object): array
    {
        return array_keys(get_object_vars($object));
    }

    /**
     * @param object $object
     *
     * @return array
     */
    public static function settablesProperties(object $object): array
    {
        return array_unique(
            [
                ...array_map(fn ($s) => self::propertyName($s), self::setters($object)),
                ...self::properties($object),
            ]
        );
    }

    /**
     * @param object $object
     * @param string $settable
     *
     * @return bool
     */
    public static function hasSettable(object $object, string $settable): bool
    {
        return in_array($settable, self::settablesProperties($object));
    }

    /**
     * @param object $object
     * @param string $gettable
     *
     * @return bool
     * @noinspection PhpUnused
     */
    public static function hasGettable(object $object, string $gettable): bool
    {
        return in_array($gettable, self::gettablesProperties($object));
    }

    /**
     * @param object $object
     *
     * @return array
     */
    public static function gettablesProperties(object $object): array
    {
        return array_unique(
            [
                ...array_map(fn ($s) => self::propertyName($s), self::getters($object)),
                ...self::properties($object),
            ]
        );
    }

    /**
     * @param string $getterSetter
     *
     * @return string
     */
    public static function propertyName(string $getterSetter): string
    {
        return lcfirst(preg_replace('/^(get|is|has|set)/', '', $getterSetter));
    }

    /**
     * @param string $property
     *
     * @return string
     */
    public static function setterName(string $property): string
    {
        return 'set'.ucfirst($property);
    }

    /**
     * @param string $property
     *
     * @return string
     */
    public static function getterName(string $property): string
    {
        return 'get'.ucfirst($property);
    }

    /**
     * @param string $property
     *
     * @return array
     */
    public static function getterNames(string $property): array
    {
        return ['get'.ucfirst($property), 'is'.ucfirst($property), 'has'.ucfirst($property)];
    }

    /**
     * @param object $object
     * @param string $settable
     * @param mixed  $value
     * @param bool   $throwsIfNotExist
     *
     * @throws Exception
     * @noinspection PhpParameterByRefIsNotUsedAsReferenceInspection
     */
    public static function setProperty(
        object &$object,
        string $settable,
        mixed $value,
        bool $throwsIfNotExist = true
    ): void {
        $setter = self::setterName($settable);
        if (method_exists($object, $setter)) {
            $object->$setter($value);
        } elseif (property_exists($object, $settable)) {
            /* @noinspection PhpVariableVariableInspection */
            $object->$settable = $value;
        } elseif ($throwsIfNotExist) {
            throw new Exception("settable property $settable not found");
        }
    }

    private const OP_REGULAR = '.';
    private const OP_ELVIS = '?.';

    /**
     * @throws Exception
     */
    public static function getPropertyPath(object &$object, string $path)
    {
        // recupera operador + propriedade
        preg_match_all('/(\??\.)/', $path = self::OP_REGULAR . $path, $matches, PREG_OFFSET_CAPTURE);
        foreach ($matches[1] as $index => $match) {
            [$op, $pos] = $match;
            $b = $pos + strlen($op);
            $e = $index < count($matches[1])-1 ? $matches[1][$index+1][1] : strlen($path);
            $opGettables[] = [$op, substr($path, $b, $e-$b)];
        }

        /** @noinspection PhpUndefinedVariableInspection */
        foreach ($opGettables as $opGettable) {
            [$op, $gettable] = $opGettable;
            if ($object === null) {
                return $op === self::OP_ELVIS ? null : throw new Exception("");
            }
            $object = self::getProperty($object, $gettable);
        }

        return $object;
    }

    /**
     * @param object $object
     * @param string $gettable
     *
     * @return mixed
     *
     * @throws Exception
     * @noinspection PhpParameterByRefIsNotUsedAsReferenceInspection
     */
    public static function getProperty(object &$object, string $gettable): mixed
    {
        foreach (self::getterNames($gettable) as $getter) {
            if (method_exists($object, $getter)) {
                return $object->$getter();
            }
        }

        if (property_exists($object, $gettable)) {
            /* @noinspection PhpVariableVariableInspection */
            return $object->$gettable;
        }

        throw new Exception("gettable property $gettable not found");
    }

    /**
     * @param object|null $fromObject
     * @param object|null $toObject
     * @param array       $properties
     *
     * @throws Exception
     */
    public static function copyProperties(?object $fromObject, ?object &$toObject, array $properties = []): void
    {
        if (!$fromObject || !$toObject || !count($properties)) {
            return;
        }

        // copyProperties($boleto, $memoria, ["nome", "id" => "boletoId", etc.])
        foreach ($properties as $fromProperty => $toProperty) {
            $fromProperty = is_string($fromProperty) ? $fromProperty : $toProperty;
            self::setProperty($toObject, $toProperty, self::getProperty($fromObject, $fromProperty));
        }
    }

    /**
     * @param object|null $fromObject
     * @param object|null $toObject
     * @param array       $mapping
     * @param array $excludeFrom
     *
     * @throws Exception
     */
    public static function copyAll(
        ?object $fromObject,
        ?object &$toObject,
        array $mapping = [],
        array $excludeFrom = []
    ): void {
        if (!$fromObject || !$toObject) {
            return;
        }

        // copyProperties($boleto, $memoria, ["nome", "id" => "boletoId", etc.])
        foreach (self::gettablesProperties($fromObject) as $gettable) {
            if (in_array($gettable, $excludeFrom)) {
                continue;
            }
            $settable = $mapping[$gettable] ?? $gettable;
            if (self::hasSettable($toObject, $settable)) {
                self::setProperty($toObject, $settable, self::getProperty($fromObject, $gettable));
            }
        }
    }

    /**
     * @param object $fromObject
     * @param object $toObject
     *
     * @return array
     *
     * @throws Exception
     * @noinspection DuplicatedCode
     */
    public static function diff(object $fromObject, object $toObject): array
    {
        if (get_class($fromObject) !== get_class($toObject)) {
            throw new Exception('impossible to get diff of objects of distinct classes');
        }

        $diffProperties = [];
        $fromGettables = self::gettablesProperties($fromObject);
        foreach ($fromGettables as $gettable) {
            $fromValue = self::getProperty($fromObject, $gettable);
            $toValue = self::getProperty($toObject, $gettable);
            if ($fromValue !== $toValue) {
                $diffProperties[$gettable] = true;
            }
        }

        return array_keys($diffProperties);
    }

    public static function newObject(array &$array, bool $convertSnakeCaseToCamelCase = false): object
    {
        return $convertSnakeCaseToCamelCase ?
            (object) self::snakeCaseToCamelCase($array) :
            (object) $array;
    }


    public static function snakeCaseToCamelCase(array &$array): array
    {
        foreach (array_keys($array) as $key) {
            $value = &$array[$key];
            unset($array[$key]);

            $newKey = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
            if (is_array($value)) {
                self::snakeCaseToCamelCase($value);
            }
            $array[$newKey] = $value;
            unset($value);
        }

        return $array;
    }
}
