<?php

declare(strict_types=1);
/**
 * /src/Fields/FieldsManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields;

/**
 * Class FieldsManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class FieldsManager
{
    /**
     * @var FieldInterface[]
     */
    protected array $fields = [];

    /**
     * @return FieldInterface[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param string $name
     *
     * @return FieldInterface|null
     */
    public function getField(string $name): ?FieldInterface
    {
        return $this->fields[$name] ?? null;
    }

    /**
     * @param FieldInterface[] $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    /**
     * @param FieldInterface $field
     */
    public function addField(FieldInterface $field): void
    {
        $this->fields[$field->getName()] = $field;
    }
}
