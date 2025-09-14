<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Helpers;

use DateTime;
use Psr\Cache\CacheItemPoolInterface;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Exceptions\InvalidSchemaPropertyPathException;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Models\JsonSchemaProperty;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Models\JsonSchemaPropertyInfo;

/**
 * JsonSchemaHelper.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class JsonSchemaHelper
{
    /**
     * Constructor.
     *
     * @param CacheItemPoolInterface $inMemoryCache
     */
    public function __construct(
        private readonly CacheItemPoolInterface $inMemoryCache
    ) {
    }

    /**
     * Retorna o valor da propriedade pelo caminho.
     * ex: processo.tarefas.nome.
     *
     * @param string                 $path
     * @param array|string           $data
     * @param JsonSchemaPropertyInfo $propertySchemaInfo
     *
     * @return JsonSchemaProperty
     *
     * @throws InvalidSchemaPropertyPathException
     */
    public function getJsonSchemaPropertyByPath(
        string $path,
        array|string $data,
        JsonSchemaPropertyInfo $propertySchemaInfo
    ): JsonSchemaProperty {
        if (!$propertySchemaInfo->isValidPath()) {
            throw new InvalidSchemaPropertyPathException($path);
        }
        if (is_string($data)) {
            $data = json_decode($data, true, 512, JSON_ERROR_NONE) ?? [];
        }
        $arrPath = explode('.', $path);

        while (!empty($arrPath)) {
            $property = array_shift($arrPath);
            $data = $data[$property] ?? null;

            if (null === $data) {
                return new JsonSchemaProperty(
                    $path,
                    null,
                    $propertySchemaInfo
                );
            }
        }

        return new JsonSchemaProperty(
            $path,
            $data,
            $propertySchemaInfo
        );
    }

    /**
     * @param string          $path
     * @param string|array    $jsonSchema
     * @param string|int|null $schemaIdentifier
     *
     * @return JsonSchemaPropertyInfo
     */
    public function getSchemaPropertyInfoByPath(
        string $path,
        string|array $jsonSchema,
        string|int|null $schemaIdentifier = null
    ): JsonSchemaPropertyInfo {
        $arrPath = explode('.', $path);
        $arrExecutionPath = [];
        if ($schemaIdentifier) {
            $cacheItem = $this->inMemoryCache->getItem(
                sprintf(
                    'json_schema_%s',
                    $schemaIdentifier
                )
            );
            if (!$cacheItem->isHit()) {
                $jsonSchema = $this->getNormalizedJsonSchema($jsonSchema);
                $cacheItem->set($jsonSchema);
                $this->inMemoryCache->save($cacheItem);
            }
            $jsonSchema = $cacheItem->get();
        } else {
            $jsonSchema = $this->getNormalizedJsonSchema($jsonSchema);
        }
        $previousProperty = null;
        while (!empty($arrPath)) {
            $property = array_shift($arrPath);
            $arrExecutionPath[] = $property;
            if (is_numeric($property) && !$previousProperty) {
                $previousProperty = $property;
                continue;
            }
            $type = $jsonSchema['type'];
            $type = is_array($type) ? reset($type) : $type;
            if ($type === 'array') {
                $jsonSchema = $jsonSchema['items']['properties'][$property] ?? null;
            } else {
                $jsonSchema = $jsonSchema['properties'][$property] ?? null;
            }
            if (null === $jsonSchema) {
                return new JsonSchemaPropertyInfo(
                    implode('.', $arrExecutionPath),
                    [],
                    false
                );
            }
            $previousProperty = $property;
        }

        return new JsonSchemaPropertyInfo(
            $path,
            $jsonSchema,
            true
        );
    }

    /**
     * Retorna as propriedades do json schema em um array linear.
     *
     * @param string|array $jsonSchema
     *
     * @return JsonSchemaPropertyInfo[]
     */
    public function getLinearJsonSchemaInfoProperties(string|array $jsonSchema): array
    {
        $fields = [];
        $jsonSchema = $this->getNormalizedJsonSchema($jsonSchema);

        $this->getLinearJsonSchemaInfoPropertiesRecursive(
            $jsonSchema['properties'] ?? [],
            [],
            $fields
        );

        return $fields;
    }

    /**
     * Define o os fields com as propriedades do json schema em um array linear recursivamente.
     *
     * @param array $properties
     * @param array $arrPath
     * @param array $fields
     *
     * @return void
     */
    protected function getLinearJsonSchemaInfoPropertiesRecursive(
        array $properties,
        array $arrPath,
        array &$fields
    ): void {
        foreach ($properties as $propertyName => $property) {
            $type = is_array($property['type']) ? reset($property['type']) : $property['type'];
            if ('array' === $type) {
                $itemsType = is_array($property['items']['type'])
                    ? reset($property['items']['type']) : $property['items']['type'];
                if ('object' === $itemsType) {
                    continue;
                }
            }
            $currentArrPath = [
                ...$arrPath,
                $propertyName,
            ];
            $propertyCopy = $property;
            unset($property['properties']);

            $fields[] = new JsonSchemaPropertyInfo(
                join(
                    '.',
                    $currentArrPath
                ),
                $property,
                true
            );

            if (isset($propertyCopy['properties'])) {
                $this->getLinearJsonSchemaInfoPropertiesRecursive(
                    $propertyCopy['properties'],
                    $currentArrPath,
                    $fields
                );
            }
        }
    }

    /**
     * Retorna o JsonSchema normalizado.
     *
     * @param string|array $jsonSchema
     *
     * @return array
     */
    public function getNormalizedJsonSchema(string|array $jsonSchema): array
    {
        if (is_string($jsonSchema)) {
            $jsonSchema = json_decode($jsonSchema, true, 512, JSON_ERROR_NONE) ?? [];
        }
        return $this->normalizeJsonSchemaReferences(
            $jsonSchema,
            $this->normalizeDefinitions($jsonSchema['definitions'] ?? [])
        );
    }

    /**
     * Percorre o Json Schema e normaliza as $ref que existirem para as propriedades sem referencia.
     *
     * @param array $jsonSchema
     * @param array $definitions
     *
     * @return array
     */
    protected function normalizeJsonSchemaReferences(
        array $jsonSchema,
        array $definitions
    ): array {
        if (isset($jsonSchema['$ref'])) {
            $jsonSchema = array_merge(
                $jsonSchema,
                $definitions[str_replace('#/definitions/', '', $jsonSchema['$ref'])]
            );
            unset($jsonSchema['$ref']);
        }

        if (isset($jsonSchema['properties'])) {
            $jsonSchema['properties'] = array_map(
                fn (array $property) => $this->normalizeJsonSchemaReferences(
                    $property,
                    $definitions
                ),
                $jsonSchema['properties'],
            );
        }

        return $jsonSchema;
    }

    /**
     * Normaliza as definitions de um Json Schema.
     *
     * @param array $definitions
     *
     * @return array
     */
    public function normalizeDefinitions(array $definitions): array
    {
        return array_map(
            fn (array $definition) => $this->normalizeJsonSchemaReferences(
                $definition,
                $definitions
            ),
            $definitions
        );
    }

    /**
     * @param mixed|null  $value
     * @param string|null $valueFormat
     *
     * @return mixed
     */
    public static function parseValue(mixed $value = null, ?string $valueFormat = null): mixed
    {
        return match ($valueFormat) {
            'string' => $value ? iconv('UTF-8', 'ASCII//TRANSLIT', mb_strtolower((string) $value)) : '',
            'date', 'date-time' => $value ? new DateTime($value) : null,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            'null' => null,
            'array' => $value ?
                (is_array($value) ? $value : [is_string($value) ? mb_strtolower($value) : $value]) : null,
            default => $value,
        };
    }

    /**
     * @param mixed|null  $value
     * @param string|null $valueFormat
     * @return mixed
     */
    public static function parseToJsonValue(mixed $value = null, ?string $valueFormat = null): mixed
    {
        return match ($valueFormat) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'number' => filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE),
            'integer' => filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE),
            'null' => null,
            'date' => preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $value) === 1 ? $value : null,
            'date-time' => preg_match(
                '/^(\d{4}-\d{2}-\d{2}(T\d{2}:\d{2}:\d{2}))$/',
                (string) $value
            ) === 1 ? $value : null,
            'array' => $value ?
                (is_array($value) ? $value : [$value]) : [],
            default => $value,
        };
    }

    /**
     * Recebe um array de valores baseados, supostamente, em um json schema e devolve um array com valores formatados
     * e de acordo com o JSON Schema fornecido.
     *
     * @param array           $schemaData
     * @param string          $jsonSchema
     * @param string|int|null $jsonSchemaIdentifier
     * @param string          $previousPath
     * @param bool            $hasInvalid
     *
     * @return array
     *
     * @throws InvalidSchemaPropertyPathException
     */
    public function dataToValidJsonSchemaValueData(
        array $schemaData,
        string $jsonSchema,
        string|int|null $jsonSchemaIdentifier = null,
        string $previousPath = '',
        bool &$hasInvalid = false
    ): array {
        $validSchemaData = [];
        foreach ($schemaData as $key => $value) {
            $path = $previousPath ? $previousPath . '.' . $key : $key;
            $schemaPropertyInfo = $this->getSchemaPropertyInfoByPath($path, $jsonSchema, $jsonSchemaIdentifier);
            if ($schemaPropertyInfo->isValidPath()) {
                switch (true) {
                    case $schemaPropertyInfo->getType() === 'array' && $schemaPropertyInfo->getItemsType() === 'object':
                        $children = [];
                        foreach ($value as $itemValue) {
                            $child = $this->dataToValidJsonSchemaValueData(
                                $itemValue,
                                $jsonSchema,
                                $jsonSchemaIdentifier,
                                $path,
                                $hasInvalid
                            );
                            if (!empty($child)) {
                                $children[] = $child;
                            }
                        }
                        $validSchemaData[$key] = $children;
                        break;
                    case $schemaPropertyInfo->getType() === 'object':
                        $validSchemaData[$key] = $this->dataToValidJsonSchemaValueData(
                            $value,
                            $jsonSchema,
                            $jsonSchemaIdentifier,
                            $path,
                            $hasInvalid
                        );
                        break;
                    default:
                        $schemaProperty = $this->getJsonSchemaPropertyByPath(
                            $key,
                            [$key => $value],
                            $schemaPropertyInfo
                        );
                        if ($schemaProperty->isValidProperty()) {
                            $validSchemaData[$key] = $schemaProperty->getValue();
                        } else {
                            $hasInvalid = true;
                        }
                }
            } else {
                $hasInvalid = true;
            }
        }
        return $validSchemaData;
    }
}
