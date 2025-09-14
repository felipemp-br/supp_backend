<?php

declare(strict_types=1);
/**
 * /src/Repository/AssuntoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\Assunto;
use SuppCore\AdministrativoBackend\Entity\Assunto as Entity;

/**
 * Class AssuntoRepository.
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
class AssuntoRepository extends BaseRepository
{
    protected static string $entityName = Entity::class;

    /**
     * @param int $processoId
     *
     * @return int
     */
    public function findCountPrincipalByProcessoId(int $processoId): int
    {
        $query = $this->getEntityManager()->createQuery(
            '
            SELECT COUNT(a) 
            FROM SuppCore\AdministrativoBackend\Entity\Assunto a
            INNER JOIN a.processo p WITH p.id = :processoId
            WHERE a.principal = true'
        );
        $query->setParameter('processoId', $processoId);
        $result = $query->getResult();

        return (int) $result[0][1];
    }

    /**
     * @param int $assuntoAdministrativoId
     * @param int $processoId
     *
     * @return int
     */
    public function findCountByAssuntoAdministrativoIdAndByProcessoId(
        int $assuntoAdministrativoId,
        int $processoId
    ): int {
        $query = $this->getEntityManager()->createQuery(
            '
            SELECT COUNT(a) 
            FROM SuppCore\AdministrativoBackend\Entity\Assunto a
            INNER JOIN a.processo p WITH p.id = :processoId
            INNER JOIN a.assuntoAdministrativo aa WITH aa.id = :assuntoAdministrativoId'
        );
        $query->setParameter('processoId', $processoId);
        $query->setParameter('assuntoAdministrativoId', $assuntoAdministrativoId);
        $result = $query->getResult();

        return (int) $result[0][1];
    }

    /**
     * @param $processoId
     *
     * @return bool|Assunto
     */
    public function findPrincipal($processoId)
    {
        $query = $this->getEntityManager()->createQuery(
            '
            SELECT a
            FROM SuppCore\AdministrativoBackend\Entity\Assunto a
            JOIN a.processo p WITH p.id = :processoId
            WHERE a.principal = true'
        );
        $query->setParameter('processoId', $processoId);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (0 == count($result)) {
            return false;
        } else {
            return $result[0];
        }
    }
}
