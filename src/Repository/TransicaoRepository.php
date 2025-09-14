<?php

declare(strict_types=1);
/**
 * /src/Repository/TransicaoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Transicao;
use SuppCore\AdministrativoBackend\Entity\Transicao as Entity;

/**
 * Class TransicaoRepository.
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
class TransicaoRepository extends BaseRepository
{
    protected static string $entityName = Entity::class;

    /**
     * @return Transicao|bool
     */
    public function findUltimaCriadaByProcessoId(int $processoId): bool | Entity
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT t 
            FROM SuppCore\AdministrativoBackend\Entity\Transicao t 
            INNER JOIN t.processo p WITH p.id = :processoId
            ORDER BY t.criadoEm DESC'
        );
        $query->setParameter('processoId', $processoId);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * Busca a última transição de um processo.
     *
     * @param $processo Processo
     */
    public function findUltimaTransicaoByProcesso($processo): mixed
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT t 
            FROM SuppCore\AdministrativoBackend\Entity\Transicao t 
            LEFT JOIN t.processo p 
            LEFT JOIN t.modalidadeTransicao m
            WHERE p.id = :processo
            ORDER BY t.criadoEm DESC'
        );
        $query->setParameter('processo', $processo);
        $query->setMaxResults(1);
        $result = $query->getResult();

        if (count($result) > 0) {
            return $result[0];
        } else {
            return 0;
        }
    }
}
