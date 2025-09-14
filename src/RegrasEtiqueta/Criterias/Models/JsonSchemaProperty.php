<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Models;

use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Helpers\JsonSchemaHelper;

/**
 * JsonSchemaProperty.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class JsonSchemaProperty
{
    /**
     * Constructor.
     *
     * @param string                 $path
     * @param mixed                  $value
     * @param JsonSchemaPropertyInfo $jsonSchemaPropertyInfo
     */
    public function __construct(
        private readonly string $path,
        private readonly mixed $value,
        private readonly JsonSchemaPropertyInfo $jsonSchemaPropertyInfo
    ) {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getValue(): mixed
    {
        return JsonSchemaHelper::parseToJsonValue(
            $this->value,
            $this->jsonSchemaPropertyInfo->getFormat() ?? $this->jsonSchemaPropertyInfo->getType()
        );
    }

    public function getParsedValue(): mixed
    {
        return JsonSchemaHelper::parseValue(
            $this->getValue(),
            $this->jsonSchemaPropertyInfo->getFormat() ?? $this->jsonSchemaPropertyInfo->getType()
        );
    }

    public function isValidProperty(): bool
    {
        if (!$this->getJsonSchemaPropertyInfo()->isValidPath()) {
            return false;
        }
        $types = $this->getJsonSchemaPropertyInfo()->getTypes();
        if (empty($this->getValue()) && !in_array('null', $types)) {
            return false;
        }
        return true;
    }

    public function getJsonSchemaPropertyInfo(): JsonSchemaPropertyInfo
    {
        return $this->jsonSchemaPropertyInfo;
    }
}
