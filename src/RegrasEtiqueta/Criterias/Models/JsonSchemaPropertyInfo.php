<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Models;

/**
 * JsonSchemaProperty.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class JsonSchemaPropertyInfo
{
    /**
     * Constructor.
     *
     * @param string $path
     * @param array  $info
     * @param bool   $validPath
     */
    public function __construct(
        private readonly string $path,
        private readonly array $info,
        private readonly bool $validPath
    ) {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getInfo(): array
    {
        return $this->info;
    }

    public function isValidPath(): bool
    {
        return $this->validPath;
    }

    public function getTitle(): ?string
    {
        return $this->info['title'] ?? null;
    }

    public function getDescription(): ?string
    {
        return $this->info['description'] ?? null;
    }

    public function getType(): string
    {
        $type = $this->info['type'] ?? 'string';
        return is_array($type) ? reset($type) : $type;
    }

    public function getTypes(): array
    {
        $type = $this->info['type'] ?? 'string';
        return is_array($type) ? $type : [$type];
    }

    public function getFormat(): ?string
    {
        return $this->info['format'] ?? null;
    }

    public function getItemsType(): ?string
    {
        $type = null;
        if (isset($this->info['items']['type'])) {
            $type = $this->info['items']['type'];
        }
        return is_array($type) ? reset($type) : $type;
    }

    public function getItems(): array
    {
        return $this->info['items'] ?? [];
    }
}
