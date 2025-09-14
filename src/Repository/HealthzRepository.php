<?php

declare(strict_types=1);
/**
 * /src/Repository/HealthzRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use DateInterval;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use SuppCore\AdministrativoBackend\Entity\Healthz as Entity;

/**
 * Class HealthzRepository.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @codingStandardsIgnoreStart
 *
 * @method Entity|null find(int $id, ?string $lockMode = null, ?string $lockVersion = null)
 * @method Entity|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method Entity[]    findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @method Entity[]    findByAdvanced(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?array $search = null): array
 * @method Entity[] findAll()
 *
 * @codingStandardsIgnoreEnd
 */
class HealthzRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * Method to read value from database.
     *
     * @return Entity|null
     *
     * @throws NonUniqueResultException
     */
    public function read(): ?Entity
    {
        $query = $this
            ->createQueryBuilder('h')
            ->select('h')
            ->orderBy('h.timestamp', 'DESC')
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * Method to write new value to database.
     *
     * @param string $transactionId
     * @return Entity
     *
     */
    public function create(string $transactionId): Entity
    {
        // Create new entity
        $entity = new Entity();

        // Store entity to database
        $this->save($entity, $transactionId);

        return $entity;
    }

    /**
     * Method to cleanup 'healthz' table.
     *
     * @return int
     *
     * @throws Exception
     */
    public function cleanup(): int
    {
        // Determine date
        $date = new DateTime('NOW', new DateTimeZone('UTC'));
        $date->sub(new DateInterval('P7D'));

        // Create query builder
        $queryBuilder = $this
            ->createQueryBuilder('h')
            ->delete()
            ->where('h.timestamp < :timestamp')
            ->setParameter('timestamp', $date);

        // Return deleted row count
        return (int) $queryBuilder->getQuery()->execute();
    }
}
