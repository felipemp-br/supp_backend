<?php

declare(strict_types=1);
/**
 * /src/Repository/VinculacaoDocumentoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento as Entity;

/**
 * Class VinculacaoDocumentoRepository.
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
class VinculacaoDocumentoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param $id
     *
     * @return bool|Entity
     */
    public function findByDocumento($id)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT v, d, dp, mvd 
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento v 
            JOIN v.documentoVinculado d 
            JOIN v.documento dp
            JOIN v.modalidadeVinculacaoDocumento mvd
            WHERE dp.id = :id');
        $query->setParameter('id', $id);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (0 === count($result)) {
            return false;
        } else {
            return $result[0];
        }
    }

    /**
     * @param $id
     *
     * @return bool|Entity
     */
    public function findByDocumentoVinculado($id)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT v, d, dp, mvd 
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento v 
            JOIN v.documentoVinculado d 
            JOIN v.documento dp
            JOIN v.modalidadeVinculacaoDocumento mvd
            WHERE d.id = :id');
        $query->setParameter('id', $id);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (0 === count($result)) {
            return false;
        } else {
            return $result[0];
        }
    }
}
