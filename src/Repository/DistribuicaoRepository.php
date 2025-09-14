<?php

declare(strict_types=1);
/**
 * /src/Repository/DistribuicaoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\Distribuicao as Entity;

/**
 * Class DistribuicaoRepository.
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
class DistribuicaoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param $tarefaId
     *
     * @return mixed
     */
    public function findUltimaDistribuicaoByTarefaId($tarefaId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT d 
            FROM SuppCore\AdministrativoBackend\Entity\Distribuicao d
            INNER JOIN d.tarefa t WITH t.id = :tarefaId
            ORDER BY d.dataHoraDistribuicao DESC'
        );
        $query->setParameter('tarefaId', $tarefaId);
        $query->setMaxResults(1);

        return $query->getResult();
    }
}
