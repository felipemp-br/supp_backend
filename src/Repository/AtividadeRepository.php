<?php

declare(strict_types=1);
/**
 * /src/Repository/AtividadeRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\Atividade as Entity;

/**
 * Class AtividadeRepository.
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
class AtividadeRepository extends BaseRepository
{
    protected static string $entityName = Entity::class;

    /**
     * @return bool|Entity
     */
    public function findByEncerraTarefaByTarefaId(
        int $tarefaId
    ): bool | Entity {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT a
            FROM SuppCore\AdministrativoBackend\Entity\Atividade a
            INNER JOIN a.tarefa t WITH t.id = :tarefaId
            WHERE a.encerraTarefa = true
            ORDER BY a.id ASC
            '
        );
        $query->setParameter('tarefaId', $tarefaId);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }
}
