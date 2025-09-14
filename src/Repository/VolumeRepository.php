<?php

declare(strict_types=1);
/**
 * /src/Repository/VolumeRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use SuppCore\AdministrativoBackend\Entity\Volume as Entity;

/**
 * Class VolumeRepository.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br> *
 * @codingStandardsIgnoreStart
 *
 * @method Entity|null find(int $id, ?array $populate = null)
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entity[]    findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null)
 * @method Entity[]    findByAdvanced(array $criteria, array $orderBy = null, int $limit = null, int $offset = null, array $search = null): array
 * @method Entity[] findAll()
 *
 * @codingStandardsIgnoreEnd
 */
class VolumeRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param $processoId
     *
     * @return Entity|null
     *
     * @throws NonUniqueResultException
     */
    public function findVolumeAbertoByProcessoId($processoId): ?Entity
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT v 
            FROM SuppCore\AdministrativoBackend\Entity\Volume v 
            INNER JOIN v.processo p WITH p.id = :processoId
            AND v.encerrado = false"
        );
        $query->setParameter('processoId', $processoId);

        return $query->getOneOrNullResult();
    }

    /**
     * @param $volumeId
     * @return bool
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function hasComponenteDigital($volumeId): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT COUNT(cd.id) 
            FROM SuppCore\AdministrativoBackend\Entity\Volume v 
            INNER JOIN v.juntadas j
            INNER JOIN j.documento d
            INNER JOIN d.componentesDigitais cd
            WHERE j.ativo = true"
        );
        $query->setParameter('volumeId', $volumeId);

        return !!$query->getSingleScalarResult();
    }
}
