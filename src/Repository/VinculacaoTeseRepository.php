<?php

declare(strict_types=1);
/**
 * /src/Repository/VinculacaoTeseRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use Doctrine\ORM\NonUniqueResultException;
use SuppCore\AdministrativoBackend\Entity\VinculacaoTese as Entity;

/**
 * Class VinculacaoTeseRepository.
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
class VinculacaoTeseRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param int $teseId
     * @param int $processoId
     * @return Entity|null
     * @throws NonUniqueResultException
     */
    public function findByTeseIdAndProcessoId(int $teseId, int $processoId): ?Entity
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT vt 
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoTese vt 
            INNER JOIN vt.tese t WITH t.id = :teseId
            INNER JOIN vt.processo p WITH p.id = :processoId
            ');
        $query->setParameter('teseId', $teseId);
        $query->setParameter('processoId', $processoId);

        return $query->getOneOrNullResult();
    }
}
