<?php

declare(strict_types=1);
/**
 * /src/Repository/VinculacaoPessoaBarramentoRepository.php.
 *
 *
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\VinculacaoPessoaBarramento as Entity;

/**
 * Class VinculacaoPessoaBarramentoRepository.
 *
 *
 * @codingStandardsIgnoreStart
 *
 * @method Entity|null find(int $id, string $lockMode = null, string $lockVersion = null)
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entity[]    findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null)
 * @method Entity[]    findByAdvanced(array $criteria, array $orderBy = null, int $limit = null, int $offset = null, array $search = null): array
 * @method Entity[] findAll()
 *
 * @codingStandardsIgnoreEnd
 */
class VinculacaoPessoaBarramentoRepository extends BaseRepository
{
    protected static string $entityName = Entity::class;

    /**
     * @param int $pessoaId
     * @return false|Entity
     */
    public function findByPessoaId(int $pessoaId): Entity|bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoPessoaBarramento v
            INNER JOIN v.pessoa p WITH p.id = :pessoaId'
        );
        $query->setParameter('pessoa', $pessoaId);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }
}
