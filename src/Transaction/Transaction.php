<?php

declare(strict_types=1);
/**
 * /src/Transaction/Transaction.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Transaction;

use Ramsey\Uuid\Uuid;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 * Class Transaction.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Transaction
{
    protected string $uuid;

    /**
     * @var EntityInterface[]
     */
    protected array $toPersistEntities = [];

    /**
     * @var EntityInterface[]
     */
    protected array $toRemoveEntities = [];

    protected ?bool $success = null;

    /** @var Context[] */
    protected array $context = [];

    /**
     * Transaction constructor.
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4()->toString();
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return EntityInterface[]
     */
    public function getToPersistEntities(): array
    {
        return $this->toPersistEntities;
    }

    /**
     * @param EntityInterface $toPersistEntity
     *
     * @return Transaction
     */
    public function addToPersistEntities(EntityInterface $toPersistEntity): self
    {
        $this->toPersistEntities[] = $toPersistEntity;

        return $this;
    }

    /**
     * @return EntityInterface[]
     */
    public function getToRemoveEntities(): array
    {
        return $this->toRemoveEntities;
    }

    /**
     * @param EntityInterface $toRemoveEntity
     *
     * @return Transaction
     */
    public function addToRemoveEntities(EntityInterface $toRemoveEntity): self
    {
        $this->toRemoveEntities[] = $toRemoveEntity;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isSuccess(): ?bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     *
     * @return Transaction
     */
    public function setSuccess(?bool $success): self
    {
        $this->success = $success;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return Context|null
     */
    public function getContext(string $name): ?Context
    {
        return isset($this->context[$name]) ? $this->context[$name] : null;
    }

    /**
     * @param Context $context
     */
    public function addContext(Context $context): void
    {
        $this->context[$context->getName()] = $context;
    }

    /**
     * @param string $name
     */
    public function removeContext(string $name): void
    {
        if (isset($this->context[$name])) {
            unset($this->context[$name]);
        }
    }

    /**
     * Reset the transaction.
     */
    public function reset(): void
    {
        $this->toPersistEntities = [];
        $this->toRemoveEntities = [];
        $this->context = [];
        $this->setSuccess(null);
    }
}
