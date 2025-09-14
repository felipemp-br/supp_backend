<?php

declare(strict_types=1);
/**
 * /src/Rest/RepositoryInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest;

use Doctrine\Common\Proxy\Proxy;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\QueryBuilder;
use InvalidArgumentException;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 * Interface RepositoryInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface RepositoryInterface
{
    /**
     * Getter method for entity name.
     */
    public function getEntityName(): string;

    /**
     * Gets a reference to the entity identified by the given type and identifier without actually loading it,
     * if the entity is not yet loaded.
     *
     * @throws ORMException
     */
    public function getReference(int $id): ?Proxy;

    /**
     * Gets all association mappings of the class.
     *
     * @return string[]
     */
    public function getAssociations(): array;

    /**
     * Getter method for search columns of current entity.
     *
     * @return string[]
     */
    public function getSearchColumns(): array;

    public function getEntityManager(): EntityManager;

    /**
     * Helper method to persist specified entity to database.
     *
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     * @throws ORMException
     */
    public function save(EntityInterface $entity, string $transactionId): self;

    /**
     * Helper method to remove specified entity from database.
     *
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     * @throws ORMException
     */
    public function remove(EntityInterface $entity, string $transactionId): self;

    /**
     * Generic count method to determine count of entities for specified criteria and search term(s).
     *
     * @param mixed[]|null $criteria
     * @param mixed[]|null $search
     *
     * @throws InvalidArgumentException
     * @throws NonUniqueResultException
     */
    public function countAdvanced(?array $criteria = null, ?array $search = null): int;

    /**
     * Generic replacement for basic 'findBy' method if/when you want to use generic LIKE search.
     *
     * @param mixed[]      $criteria
     * @param mixed[]|null $orderBy
     * @param mixed[]|null $search
     *
     * @return EntityInterface[]
     */
    public function findByAdvanced(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null,
        ?array $search = null
    ): array;

    /**
     * Repository method to fetch current entity id values from database and return those as an array.
     *
     * @param mixed[]|null $criteria
     * @param mixed[]|null $search
     *
     * @return string[]
     */
    public function findIds(?array $criteria = null, ?array $search = null): array;

    /**
     * Helper method to 'reset' repository entity table - in other words delete all records - so be carefully with
     * this...
     */
    public function reset(): int;

    /**
     * With this method you can attach some custom functions for generic REST API find / count queries.
     */
    public function processQueryBuilder(QueryBuilder $queryBuilder): void;

    /**
     * Adds left join to current QueryBuilder query.
     *
     * @note Requires processJoins() to be run
     *
     * @see QueryBuilder::leftJoin() for parameters
     *
     * @param mixed[] $parameters
     *
     * @throws InvalidArgumentException
     */
    public function addLeftJoin(array $parameters): self;

    /**
     * Adds inner join to current QueryBuilder query.
     *
     * @note Requires processJoins() to be run
     *
     * @see QueryBuilder::innerJoin() for parameters
     *
     * @param mixed[] $parameters
     *
     * @throws InvalidArgumentException
     */
    public function addInnerJoin(array $parameters): self;

    /**
     * Method to add callback to current query builder instance which is calling 'processQueryBuilder' method. By
     * default this method is called from following core methods:
     *  - countAdvanced
     *  - findByAdvanced
     *  - findIds.
     *
     * Note that every callback will get 'QueryBuilder' as in first parameter.
     *
     * @param mixed[]|null $args
     */
    public function addCallback(callable $callable, ?array $args = null): self;

    /**
     * Process defined joins for current QueryBuilder instance.
     */
    public function processJoins(QueryBuilder $queryBuilder): void;

    /**
     * Process defined callbacks for current QueryBuilder instance.
     */
    public function processCallbacks(QueryBuilder $queryBuilder): void;
}
