<?php

declare(strict_types=1);
/**
 * /src/Repository/BaseRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use function array_map;
use function array_unshift;
use Doctrine\Common\Proxy\Proxy;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use function implode;
use function in_array;
use InvalidArgumentException;
use function serialize;
use function sha1;
use function spl_object_hash;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\QueryBuilder\ArrayQueryBuilder;
use SuppCore\AdministrativoBackend\Rest\RepositoryHelper;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Throwable;
use UnexpectedValueException;

/**
 * Class BaseRepository.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    private const INNER_JOIN = 'innerJoin';
    private const LEFT_JOIN = 'leftJoin';

    /**
     * Names of search columns.
     *
     * @var string[]
     */
    protected static array $searchColumns = [];

    protected static string $entityName;

    protected static EntityManager $entityManager;

    protected ArrayQueryBuilder $arrayQueryBuilder;

    protected TransactionManager $transactionManager;

    protected ManagerRegistry $managerRegistry;

    /**
     * Joins that need to attach to queries, this is needed for to prevent duplicate joins on those.
     *
     * @var mixed[]
     */
    private static array $joins = [
        self::INNER_JOIN => [],
        self::LEFT_JOIN => [],
    ];

    /**
     * @var mixed[]
     */
    private static array $processedJoins = [
        self::INNER_JOIN => [],
        self::LEFT_JOIN => [],
    ];

    /**
     * @var mixed[]
     */
    private static array $callbacks = [];

    /**
     * @var mixed[]
     */
    private static array $processedCallbacks = [];

    /**
     * BaseRepository constructor.
     *
     * @param ManagerRegistry    $managerRegistry
     * @param ArrayQueryBuilder  $arrayQueryBuilder
     * @param TransactionManager $transactionManager
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        ArrayQueryBuilder $arrayQueryBuilder,
        TransactionManager $transactionManager
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->arrayQueryBuilder = $arrayQueryBuilder;
        $this->transactionManager = $transactionManager;
    }

    /**
     * @return TransactionManager
     */
    public function getTransactionManager(): TransactionManager
    {
        return $this->transactionManager;
    }

    /**
     * Getter method for entity name.
     *
     * @return string
     */
    public function getEntityName(): string
    {
        return static::$entityName;
    }

    /**
     * Getter method for search columns of current entity.
     *
     * @return string[]
     */
    public function getSearchColumns(): array
    {
        return static::$searchColumns;
    }

    /**
     * With this method you can attach some custom functions for generic REST API find / count queries.
     *
     * @param QueryBuilder $queryBuilder
     */
    public function processQueryBuilder(QueryBuilder $queryBuilder): void
    {
        // Reset processed joins and callbacks
        self::$processedJoins = [self::INNER_JOIN => [], self::LEFT_JOIN => []];
        self::$processedCallbacks = [];

        $this->processJoins($queryBuilder);
        $this->processCallbacks($queryBuilder);
    }

    /**
     * Adds left join to current QueryBuilder query.
     *
     * @note Requires processJoins() to be run
     *
     * @param mixed[] $parameters
     *
     * @return BaseRepositoryInterface
     *
     * @throws InvalidArgumentException
     *
     * @see QueryBuilder::leftJoin() for parameters
     */
    public function addLeftJoin(array $parameters): BaseRepositoryInterface
    {
        if (!empty($parameters)) {
            $this->addJoinToQuery(self::LEFT_JOIN, $parameters);
        }

        return $this;
    }

    /**
     * Adds inner join to current QueryBuilder query.
     *
     * @note Requires processJoins() to be run
     *
     * @param mixed[] $parameters
     *
     * @return BaseRepositoryInterface
     *
     * @throws InvalidArgumentException
     *
     * @see QueryBuilder::innerJoin() for parameters
     */
    public function addInnerJoin(array $parameters): BaseRepositoryInterface
    {
        if (!empty($parameters)) {
            $this->addJoinToQuery(self::INNER_JOIN, $parameters);
        }

        return $this;
    }

    /**
     * Method to add callback to current query builder instance which is calling 'processQueryBuilder' method. By
     * default this method is called from following core methods:
     *  - countAdvanced
     *  - findByAdvanced
     *  - findIds.
     *
     * Note that every callback will get 'QueryBuilder' as in first parameter.
     *
     * @param callable     $callable
     * @param mixed[]|null $args
     *
     * @return BaseRepositoryInterface
     */
    public function addCallback(callable $callable, ?array $args = null): BaseRepositoryInterface
    {
        $args ??= [];
        $hash = sha1(serialize([...[spl_object_hash((object) $callable)], ...$args]));

        if (!in_array($hash, self::$processedCallbacks, true)) {
            self::$callbacks[$hash] = [$callable, $args];
            self::$processedCallbacks[] = $hash;
        }

        return $this;
    }

    /**
     * Process defined joins for current QueryBuilder instance.
     *
     * @param QueryBuilder $queryBuilder
     */
    protected function processJoins(QueryBuilder $queryBuilder): void
    {
        /**
         * @var string
         * @var mixed[] $joins
         */
        foreach (self::$joins as $joinType => $joins) {
            foreach ($joins as $joinParameters) {
                $queryBuilder->{$joinType}(...$joinParameters);
            }

            self::$joins[$joinType] = [];
        }
    }

    /**
     * Process defined callbacks for current QueryBuilder instance.
     *
     * @param QueryBuilder $queryBuilder
     */
    protected function processCallbacks(QueryBuilder $queryBuilder): void
    {
        /**
         * @var callable
         * @var mixed[]  $args
         */
        foreach (self::$callbacks as [$callback, $args]) {
            array_unshift($args, $queryBuilder);

            $callback(...$args);
        }

        self::$callbacks = [];
    }

    /**
     * Method to add defined join(s) to current QueryBuilder query. This will keep track of attached join(s) so any of
     * those are not added multiple times to QueryBuilder.
     *
     * @note processJoins() method must be called for joins to actually be added to QueryBuilder. processQueryBuilder()
     *       method calls this method automatically.
     *
     * @param string  $type       Join type; leftJoin, innerJoin or join
     * @param mixed[] $parameters Query builder join parameters
     *
     * @see QueryBuilder::leftJoin()
     * @see QueryBuilder::innerJoin()
     */
    private function addJoinToQuery(string $type, array $parameters): void
    {
        $comparision = implode('|', $parameters);

        if (!in_array($comparision, self::$processedJoins[$type], true)) {
            self::$joins[$type][] = $parameters;

            self::$processedJoins[$type][] = $comparision;
        }
    }

    /**
     * @param int        $id
     * @param array|null $populate
     * @param array|null $orderBy
     *
     * @return int|mixed|string
     *
     * @throws NonUniqueResultException
     */
    public function find(int $id, ?array $populate = null, ?array $orderBy = null)
    {
        $criteria = ['id' => 'eq:'.$id];

        // Get query builder
        $queryBuilder = $this->getQueryBuilder($criteria, [], $orderBy, 1, 0, $populate);

        try {
            $result = $queryBuilder->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * @param int $id
     * @param array|null $populate
     * @return false|int|mixed|string
     * @throws NonUniqueResultException
     */
    public function findDeleted(int $id, ?array $populate = null)
    {
        if (array_key_exists(
            'softdeleteable',
            $this->managerRegistry->getManager()->getFilters()->getEnabledFilters()
        )) {
            $this->managerRegistry->getManager()->getFilters()->disable('softdeleteable');
        }
        $result = $this->find($id, $populate);
        if (!array_key_exists(
            'softdeleteable',
            $this->managerRegistry->getManager()->getFilters()->getEnabledFilters()
        )) {
            $this->managerRegistry->getManager()->getFilters()->enable('softdeleteable');
        }

        return $result;
    }

    /**
     * Wrapper for default Doctrine repository findOneBy method.
     *
     * @param mixed[]      $criteria
     * @param mixed[]|null $orderBy
     *
     * @return EntityInterface|mixed|null
     */
    public function findOneBy(array $criteria, ?array $orderBy = null)
    {
        $repository = $this->getEntityManager()->getRepository($this->getEntityName());

        return $repository instanceof EntityRepository ? $repository->findOneBy($criteria, $orderBy) : null;
    }

    /**
     * Wrapper for default Doctrine repository findBy method.
     *
     * @param mixed[]       $criteria
     * @param string[]|null $orderBy
     * @param int|null      $limit
     * @param int|null      $offset
     *
     * @return array<EntityInterface>|EntityInterface[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return array_values(
            $this
                ->getEntityManager()
                ->getRepository($this->getEntityName())
                ->findBy($criteria, $orderBy, $limit, $offset)
        );
    }

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     * @param array|null $search
     * @param array|null $populate
     *
     * @return array
     *
     * @throws Exception
     */
    public function findByAdvanced(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null,
        ?array $search = null,
        ?array $populate = null
    ): array {
        // Get query builder
        $queryBuilder = $this->getQueryBuilder($criteria, $search, $orderBy, $limit, $offset, $populate);

        if ($this->arrayQueryBuilder->hashLike() &&
            'oracle' === $this->getEntityManager()->getConnection()->getDatabasePlatform()->getName()) {
            $this->getEntityManager()->getConnection()->executeStatement(
                "ALTER SESSION SET NLS_COMP = 'LINGUISTIC' NLS_SORT = 'BINARY_AI'"
            );
        }

        // Process custom QueryBuilder actions
        $this->processQueryBuilder($queryBuilder);

        RepositoryHelper::resetParameterCount();

        $paginator = new Paginator($queryBuilder, $this->arrayQueryBuilder->hashToMany());

        $entities = $paginator->getIterator()->getArrayCopy();
        $count = $paginator->count();

        if ($this->arrayQueryBuilder->hashLike() &&
            'oracle' === $this->getEntityManager()->getConnection()->getDatabasePlatform()->getName()) {
            $this->getEntityManager()->getConnection()->executeStatement(
                "ALTER SESSION SET NLS_COMP = 'BINARY' NLS_SORT = 'BINARY'"
            );
        }

        /* @psalm-suppress UndefinedMethod */
        return [
            'entities' => $entities,
            'total' => $count,
        ];
    }

    /**
     * Wrapper for default Doctrine repository findBy method.
     *
     * @return array<EntityInterface>|EntityInterface[]
     */
    public function findAll(): array
    {
        return array_values(
            $this->getEntityManager()
                ->getRepository($this->getEntityName())
                ->findAll()
        );
    }

    /**
     * @param array|null $criteria
     * @param array|null $search
     *
     * @return array
     */
    public function findIds(?array $criteria = null, ?array $search = null): array
    {
        // Get query builder
        $queryBuilder = $this->getQueryBuilder($criteria, $search);

        // Build query
        $queryBuilder
            ->select('e.id')
            ->distinct();

        // Process custom QueryBuilder actions
        $this->processQueryBuilder($queryBuilder);

        RepositoryHelper::resetParameterCount();

        return array_values(array_map('\strval', array_map('\current', $queryBuilder->getQuery()->getArrayResult())));
    }

    /**
     * @param array|null $criteria
     * @param array|null $search
     *
     * @return int
     */
    public function countAdvanced(?array $criteria = null, ?array $search = null): int
    {
        // Get query builder
        $queryBuilder = $this->getQueryBuilder($criteria, $search);

        // Build query
        $queryBuilder
            ->select('COUNT(a0.id)')
            ->distinct();

        // Process custom QueryBuilder actions
        $this->processQueryBuilder($queryBuilder);

        RepositoryHelper::resetParameterCount();

        try {
            $result = $queryBuilder->getQuery()->getSingleScalarResult();
        } catch (Throwable $e) {
            $result = 0;
        }

        return (int) $result;
    }

    /**
     * Helper method to 'reset' repository entity table - in other words delete all records - so be carefully with
     * this...
     *
     * @return int
     */
    public function reset(): int
    {
        // Create query builder
        $queryBuilder = $this->createQueryBuilder();

        // Define delete query
        $queryBuilder->delete();

        // Return deleted row count
        return (int) $queryBuilder->getQuery()->execute();
    }

    /**
     * Helper method to persist specified entity to database.
     *
     * @param EntityInterface $entity
     * @param string          $transactionId
     *
     * @return BaseRepositoryInterface
     */
    public function save(EntityInterface $entity, string $transactionId): BaseRepositoryInterface
    {
        $this->transactionManager->addToPersistEntities($entity, $transactionId);

        return $this;
    }

    /**
     * Helper method to remove specified entity from database.
     *
     * @param EntityInterface $entity
     * @param string          $transactionId
     *
     * @return BaseRepositoryInterface
     *
     * @throws ORMInvalidArgumentException
     */
    public function remove(EntityInterface $entity, string $transactionId): BaseRepositoryInterface
    {
        $this->transactionManager->addToRemoveEntities($entity, $transactionId);

        return $this;
    }

    /**
     * Helper method to unremove specified entity from database.
     *
     * @param EntityInterface $entity
     * @param string          $transactionId
     *
     * @return BaseRepositoryInterface
     *
     * @throws ORMInvalidArgumentException
     */
    public function unremove(EntityInterface $entity, string $transactionId): BaseRepositoryInterface
    {
        $entity->setApagadoEm(null);
        $entity->setApagadoPor(null);
        $this->transactionManager->addToPersistEntities($entity, $transactionId);

        return $this;
    }

    /**
     * @param array|null $criteria
     * @param array|null $search
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     * @param array|null $populate
     *
     * @return QueryBuilder
     */
    private function getQueryBuilder(
        ?array $criteria = null,
        ?array $search = null,
        ?array $orderBy = [],
        ?int $limit = null,
        ?int $offset = null,
        ?array $populate = []
    ): QueryBuilder {
        // Normalize inputs
        $limit ??= 1;
        $offset ??= 0;

        if ($limit > 100) {
            $limit = 100;
        }

        $params = [];
        $params['object'] = $this->getEntityName();
        $params['limit'] = $limit;
        $params['offset'] = $offset;
        $params['fetch'] = $populate;
        $params['sort'] = $orderBy;
        $params['filter'] = $criteria;

        // Create new QueryBuilder for this instance
        return $this->arrayQueryBuilder->buildQueryBuilder($params);
    }

    /**
     * @param array $criteria
     *
     * @return array
     */
    private function convertFilter(array $criteria): array
    {
        $filterConverted = [];
        foreach ($criteria as $property => $value) {
            $filterConverted[] = [
                'property' => $property,
                'value' => $value,
            ];
        }

        return $filterConverted;
    }

    /**
     * @param array $criteria
     *
     * @return array
     */
    private function convertOrFilter(array $criteria): array
    {
        $orFilterConverted = [];
        foreach ($criteria as $index => $orFilter) {
            if (!isset($orFilter[0])) {
                $orFilterConverted[] = $this->convertFilter($orFilter)[0];
            } else {
                $orFilterConverted[] = $this->convertOrFilter($orFilter);
            }
        }

        return [$orFilterConverted];
    }

    /** @noinspection GenericObjectTypeUsageInspection */

    /**
     * @param int $id
     *
     * @return bool|Proxy|object|null
     *
     * @throws ORMException
     */
    public function getReference(int $id)
    {
        return $this->getEntityManager()->getReference($this->getEntityName(), $id);
    }

    /**
     * Gets all association mappings of the class.
     *
     * @return string[]|array<int, string>
     */
    public function getAssociations(): array
    {
        return $this->getClassMetaData()->getAssociationMappings();
    }

    /**
     * @return ClassMetadataInfo
     */
    public function getClassMetaData(): ClassMetadataInfo
    {
        return $this->getEntityManager()->getClassMetadata($this->getEntityName());
    }

    /**
     * Getter method for EntityManager for current entity.
     *
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        $manager = $this->managerRegistry->getManagerForClass($this->getEntityName());

        if (!($manager instanceof EntityManager)) {
            throw new UnexpectedValueException('Cannot get entity manager for entity \''.$this->getEntityName().'\'');
        }

        return $manager;
    }

    /**
     * Method to create new query builder for current entity.
     *
     * @param string|null $alias
     * @param string|null $indexBy
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(?string $alias = null, ?string $indexBy = null): QueryBuilder
    {
        $alias ??= 'entity';

        // Create new query builder
        return $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select($alias)
            ->from($this->getEntityName(), $alias, $indexBy);
    }
}
