<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\Coordenador as Entity;

/**
 * Class CoordenadorRepository.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
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
class CoordenadorRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param $usuarioId
     * @param $setorId
     *
     * @return bool|Entity
     */
    public function findCoordenadorByUsuarioAndSetor($usuarioId, $setorId): Entity|bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c 
            FROM SuppCore\AdministrativoBackend\Entity\Coordenador c 
            INNER JOIN c.usuario u WITH u.id = :usuarioId
            INNER JOIN c.setor s WITH s.id = :setorId'
        );

        $query->setParameter('usuarioId', $usuarioId);
        $query->setParameter('setorId', $setorId);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $usuarioId
     * @param $unidadeId
     *
     * @return bool|Entity
     */
    public function findCoordenadorByUsuarioAndUnidade($usuarioId, $unidadeId): Entity|bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c 
            FROM SuppCore\AdministrativoBackend\Entity\Coordenador c 
            INNER JOIN c.usuario u WITH u.id = :usuarioId
            INNER JOIN c.unidade s WITH s.id = :unidadeId'
        );

        $query->setParameter('usuarioId', $usuarioId);
        $query->setParameter('unidadeId', $unidadeId);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $usuarioId
     * @param $orgaoCentralId
     *
     * @return bool|Entity
     */
    public function findCoordenadorByUsuarioAndOrgaoCentral($usuarioId, $orgaoCentralId): Entity|bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c 
            FROM SuppCore\AdministrativoBackend\Entity\Coordenador c 
            INNER JOIN c.usuario u WITH u.id = :usuarioId
            INNER JOIN c.orgaoCentral s WITH s.id = :orgaoCentralId'
        );

        $query->setParameter('usuarioId', $usuarioId);
        $query->setParameter('orgaoCentralId', $orgaoCentralId);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }
}
