<?php

declare(strict_types=1);
/**
 * /src/Repository/LotacaoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\Lotacao;
use SuppCore\AdministrativoBackend\Entity\Lotacao as Entity;

/**
 * Class LotacaoRepository.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method Entity|null find(int $id, ?array $populate = null)
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entity[]    findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null)
 * @method Entity[]    findByAdvanced(array $criteria, array $orderBy = null, int $limit = null, int $offset = null, array $search = null): array
 * @method Entity[] findAll()
 */
class LotacaoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param int $colaboradorId
     *
     * @return int
     */
    public function findCountPrincipalByColaboradorId(int $colaboradorId): int
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT COUNT(l) 
            FROM SuppCore\AdministrativoBackend\Entity\Lotacao l
            INNER JOIN l.colaborador c WITH c.id = :colaboradorId
            WHERE l.principal = true'
        );
        $query->setParameter('colaboradorId', $colaboradorId);
        $result = $query->getResult();

        return (int) $result[0][1];
    }

    /**
     * @param $colaboradorId
     *
     * @return bool|Lotacao
     */
    public function findLotacaoPrincipal($colaboradorId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT l 
            FROM SuppCore\AdministrativoBackend\Entity\Lotacao l 
            INNER JOIN l.colaborador c WITH c.id = :colaboradorId
            WHERE l.principal = true'
        );
        $query->setParameter('colaboradorId', $colaboradorId);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $colaboradorId
     * @param $setorId
     *
     * @return bool
     */
    public function findIsDistribuidor($colaboradorId, $setorId): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT l 
            FROM SuppCore\AdministrativoBackend\Entity\Lotacao l            
            INNER JOIN l.colaborador c WITH c.id = :colaboradorId
            INNER JOIN l.setor s WITH s.id = :setorId
            WHERE l.distribuidor = true'
        );
        $query->setParameter('colaboradorId', $colaboradorId);
        $query->setParameter('setorId', $setorId);
        $result = $query->getResult();

        return (bool) count($result);
    }

    /**
     * @param $setor
     * @param $colaborador
     *
     * @return Entity|bool
     */
    public function findLotacaoBySetorAndColaborador($setor, $colaborador)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT l, c, s, u 
            FROM SuppCore\AdministrativoBackend\Entity\Lotacao l 
            INNER JOIN l.colaborador c WITH c.id = :colaborador
            INNER JOIN l.setor s WITH s.id = :setor
            LEFT JOIN s.unidade u'
        );
        $query->setParameter('colaborador', $colaborador);
        $query->setParameter('setor', $setor);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }


    /**
     * @param $setorId
     */
    public function findLotacaoDistribuidor($setorId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT l 
            FROM SuppCore\AdministrativoBackend\Entity\Lotacao l            
            INNER JOIN l.setor s WITH s.id = :setorId
            WHERE s.apenasDistribuidor = true AND l.distribuidor = true'
        );
        $query->setParameter('setorId', $setorId);
        $result = $query->getResult();

        return $result;
    }

    /**
     * @param $setorId
     * @param $usuarioId
     *
     * @return bool
     */
    public function findIsLotadoSetor($setorId, $usuarioId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT l 
            FROM SuppCore\AdministrativoBackend\Entity\Lotacao l            
            INNER JOIN l.colaborador c
            INNER JOIN c.usuario u WITH u.id = :usuarioId
            INNER JOIN l.setor s WITH s.id = :setorId'
        );
        $query->setParameter('usuarioId', $usuarioId);
        $query->setParameter('setorId', $setorId);
        $result = $query->getResult();

        return (bool) count($result);
    }

    /**
     * @param $unidadeId
     * @param $usuarioId
     *
     * @return bool
     */
    public function findIsLotadoUnidade($unidadeId, $usuarioId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT l 
            FROM SuppCore\AdministrativoBackend\Entity\Lotacao l            
            INNER JOIN l.colaborador c
            INNER JOIN c.usuario u WITH u.id = :usuarioId
            INNER JOIN l.setor s 
            INNER JOIN s.unidade un WITH un.id = :unidadeId'
        );
        $query->setParameter('usuarioId', $usuarioId);
        $query->setParameter('unidadeId', $unidadeId);
        $result = $query->getResult();

        return (bool) count($result);
    }

    /**
     * @param $modOrgaoCentralId
     * @param $usuarioId
     *
     * @return bool
     */
    public function findIsLotadoOrgaoCentral($modOrgaoCentralId, $usuarioId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT l 
            FROM SuppCore\AdministrativoBackend\Entity\Lotacao l            
            INNER JOIN l.colaborador c
            INNER JOIN c.usuario u WITH u.id = :usuarioId
            INNER JOIN l.setor s 
            INNER JOIN s.modalidadeOrgaoCentral oc WITH oc.id = :modOrgaoCentralId'
        );
        $query->setParameter('usuarioId', $usuarioId);
        $query->setParameter('modOrgaoCentralId', $modOrgaoCentralId);
        $result = $query->getResult();

        return (bool) count($result);
    }
}
