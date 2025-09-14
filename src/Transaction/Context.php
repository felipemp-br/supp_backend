<?php

declare(strict_types=1);
/**
 * /src/Transaction/Context.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Transaction;

use Ramsey\Uuid\Uuid;

/**
 * Class Context.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Context
{
    protected string $uuid;

    protected string $name;

    protected mixed $value;

    /**
     * Context constructor.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __construct(string $name, mixed $value)
    {
        $this->uuid = Uuid::uuid4()->toString();
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }
}
