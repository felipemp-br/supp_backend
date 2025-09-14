<?php

declare(strict_types=1);
/**
 * /src/Transaction/TransactionManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Transaction;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Psr\Log\LoggerInterface;
use RuntimeException;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class TransactionManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TransactionManager
{
    /**
     * @var Transaction[]
     */
    protected array $transactions = [];
    protected array $asyncDispatch = [];
    protected array $afterFlushFunctions = [];
    private ?string $lastCommitedTransactionId = null;
    private ?string $currentTransactionId = null;

    /**
     * TransactionManager constructor.
     */
    public function __construct(
        protected readonly ManagerRegistry $managerRegistry,
        protected readonly MessageBusInterface $bus,
        protected readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @return EntityInterface[]
     *
     * @noinspection PhpUnused
     */
    public function getToPersistEntities(string $transactionId): array
    {
        return $this->transactions[$transactionId]->getToPersistEntities();
    }

    /**
     * @return TransactionManager
     */
    public function addToPersistEntities(EntityInterface $toPersistEntity, string $transactionId): self
    {
        if ($this->getCurrentTransactionId() !== $transactionId) {
            throw new RuntimeException('O transactionId informado não corresponde à transação aberta!');
        }

        $this->transactions[$transactionId]->addToPersistEntities($toPersistEntity);

        return $this;
    }

    /**
     * @return EntityInterface[]
     *
     * @noinspection PhpUnused
     */
    public function getToRemoveEntities(string $transactionId): array
    {
        return $this->transactions[$transactionId]->getToRemoveEntities();
    }

    /**
     * @return TransactionManager
     */
    public function addToRemoveEntities(EntityInterface $toRemoveEntity, string $transactionId): self
    {
        if ($this->getCurrentTransactionId() !== $transactionId) {
            throw new RuntimeException('O transactionId informado não corresponde à transação aberta!');
        }

        $this->transactions[$transactionId]->addToRemoveEntities($toRemoveEntity);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function begin(): string
    {
        if ($this->getCurrentTransactionId()) {
            return $this->getCurrentTransactionId();
        }

        $transaction = new Transaction();
        $transactionId = $transaction->getUuid();
        $this->transactions[$transactionId] = $transaction;
        $this->asyncDispatch[$transactionId] = [];

        $this->setCurrentTransactionId($transactionId);

        return $transactionId;
    }

    /**
     * @throws Exception
     */
    public function beginNewTransaction(): string
    {
        $transaction = new Transaction();
        $transactionId = $transaction->getUuid();
        $this->transactions[$transactionId] = $transaction;
        $this->asyncDispatch[$transactionId] = [];

        $this->setCurrentTransactionId($transactionId);

        return $transactionId;
    }

    public function commit(?string $transactionId = null): void
    {
        $entityManager = $this->managerRegistry->getManager();

        // como default pega a ultima transaction iniciada
        if (is_null($transactionId)) {
            end($this->transactions);
            $transactionId = key($this->transactions);
        }

        $transaction = $this->transactions[$transactionId];

        foreach ($transaction->getToPersistEntities() as $toPersistEntity) {
            $entityManager->persist($toPersistEntity);
        }

        foreach ($transaction->getToRemoveEntities() as $toRemoveEntity) {
            $entityManager->remove($toRemoveEntity);
        }

        $entityManager->flush();

        if (isset($this->getAsyncDispatchs()[$transactionId])) {
            foreach ($this->getAsyncDispatchs()[$transactionId] as $message) {
                $this->bus->dispatch($message);
            }
        }

        if (isset($this->getAfterFlushFunctions()[$transactionId])) {
            foreach ($this->afterFlushFunctions[$transactionId] as $function) {
                try {
                    $function();
                } catch (\Throwable $e) {
                    $this->logger->critical(
                        'Falha ao executar after flush funcion.',
                        [
                            'message' => $e->getMessage(),
                            'code' => $e->getCode(),
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                            'trace' => $e->getTraceAsString(),
                        ]
                    );
                }
            }
        }

        $this->setCurrentTransactionId(null);
        $this->setLastCommitedTransactionId($transactionId);

        unset($this->asyncDispatch[$transactionId]);
    }

    /**
     * @noinspection PhpUnused
     */
    public function getTransaction(string $transactionId): Transaction
    {
        return $this->transactions[$transactionId];
    }

    /**
     * Clear the entity manager.
     *
     * @noinspection PhpUnused
     */
    public function clearManager(): void
    {
        $this->transactions = [];
        $this->asyncDispatch = [];
        $this->afterFlushFunctions = [];
        $this->lastCommitedTransactionId = null;
        $this->currentTransactionId = null;
        $this->managerRegistry->getManager()->clear();
    }

    /**
     * @noinspection PhpUnused
     */
    public function resetTransaction(?string $transactionId = null): void
    {
        // como default pega a ultima transaction iniciada
        if (is_null($transactionId)) {
            end($this->transactions);
            $transactionId = key($this->transactions);
        }

        if (isset($this->transactions[$transactionId])) {
            $this->transactions[$transactionId]->reset();
        }
        $this->setCurrentTransactionId(null);
    }

    public function setLastCommitedTransactionId(string $transactionId)
    {
        $this->lastCommitedTransactionId = $transactionId;
    }

    public function getLastCommitedTransactionId(): ?string
    {
        return $this->lastCommitedTransactionId;
    }

    public function getAsyncDispatchs(): array
    {
        return $this->asyncDispatch;
    }

    public function addAsyncDispatch(mixed $message, string $transactionId): self
    {
        $this->asyncDispatch[$transactionId][] = $message;

        return $this;
    }

    public function getAfterFlushFunctions(): array
    {
        return $this->afterFlushFunctions;
    }

    public function addAfterFlushFunctions(callable $function, string $transactionId): self
    {
        $this->afterFlushFunctions[$transactionId][] = $function;

        return $this;
    }

    public function getScheduledEntities(string $instanceName, string $transactionId): array
    {
        $entities = [];

        $transaction = $this->getTransaction($transactionId);

        foreach ($transaction->getToPersistEntities() as $entity) {
            if (is_a($entity, $instanceName)) {
                $entities[] = $entity;
            }
        }

        return $entities;
    }

    public function getCurrentTransactionId(): ?string
    {
        return $this->currentTransactionId;
    }

    public function setCurrentTransactionId(?string $currentTransactionId): void
    {
        $this->currentTransactionId = $currentTransactionId;
    }

    public function addContext(Context $context, string $transactionId): void
    {
        $transaction = $this->getTransaction($transactionId);
        $transaction->addContext($context);
    }

    public function getContext(string $name, string $transactionId): ?Context
    {
        $transaction = $this->getTransaction($transactionId);

        return $transaction->getContext($name);
    }

    public function removeContext(string $name, string $transactionId): void
    {
        $transaction = $this->getTransaction($transactionId);
        $transaction->removeContext($name);
    }
}
