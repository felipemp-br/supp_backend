<?php

declare(strict_types=1);
/**
 * /src/Repository/RelevanciaRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\Relevancia as Entity;

/**
 * Class RelevanciaRepository.
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
class RelevanciaRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param $processo
     * @param $especie
     *
     * @return bool
     */
    public function findByProcessoAndEspecie($processo, $especie)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT r
            FROM SuppCore\AdministrativoBackend\Entity\Relevancia r
            INNER JOIN r.processo p
            INNER JOIN r.especieRelevancia er
            WHERE p.id = :processo
            AND er.id = :especie'
        );
        $query->setParameter('processo', $processo);
        $query->setParameter('especie', $especie);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }
}
