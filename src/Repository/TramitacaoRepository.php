<?php

declare(strict_types=1);
/**
 * /src/Repository/TramitacaoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\Tramitacao as Entity;

/**
 * Class TramitacaoRepository.
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
class TramitacaoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param $processo
     *
     * @return bool
     */
    public function findPendenteExternaProcesso($processo)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT t 
            FROM SuppCore\AdministrativoBackend\Entity\Tramitacao t 
            JOIN t.processo p WITH p.id = :processo
            WHERE t.pessoaDestino IS NOT NULL 
            AND t.dataHoraRecebimento IS NULL"
        );
        $query->setParameter('processo', $processo);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (0 == count($result)) {
            return false;
        } else {
            return $result[0];
        }
    }

    /**
     * @param int $processo
     *
     * @return bool|Entity
     */
    public function findTramitacaoPorProcesso(int $processo)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT t, sd
            FROM SuppCore\AdministrativoBackend\Entity\Tramitacao t
            INNER JOIN t.processo p
            LEFT JOIN t.setorDestino sd
            WHERE p.id = :processoId            
            ORDER BY t.criadoEm ASC"
        );
        $query->setParameter('processoId', $processo);
        $result = $query->getResult();
        if (0 == count($result)) {
            return false;
        } else {
            return $result;
        }
    }

    /**
     * @param int $processo
     *
     * @return bool|Entity
     */
    public function findTramitacaoPendentePorProcesso(int $processoId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT t
            FROM SuppCore\AdministrativoBackend\Entity\Tramitacao t
            INNER JOIN t.processo p
            WHERE p.id = :processoId
            AND t.dataHoraRecebimento IS NULL      
            ORDER BY t.criadoEm ASC"
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
