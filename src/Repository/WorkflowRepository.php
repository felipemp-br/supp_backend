<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\Workflow as Entity;

/**
 * Class WorkflowRepository.
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
class WorkflowRepository extends BaseRepository
{
    protected static string $entityName = Entity::class;

    /**
     * @param int $workflowId
     *
     * @return bool
     */
    public function hasProcesso(int $workflowId): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
                SELECT p
                FROM SuppCore\AdministrativoBackend\Entity\Processo p
                INNER JOIN p.especieProcesso ep
                INNER JOIN ep.vinculacoesEspecieProcessoWorkflow vep
                INNER JOIN vep.workflow w WITH w.id = :workflowId"
        );
        $query->setParameter('workflowId', $workflowId);
        $query->setMaxResults(1);

        return (bool) count($query->getResult());
    }
}
