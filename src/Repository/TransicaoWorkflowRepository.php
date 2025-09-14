<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\EspecieAtividade;
use SuppCore\AdministrativoBackend\Entity\EspecieTarefa;
use SuppCore\AdministrativoBackend\Entity\TransicaoWorkflow as Entity;
use SuppCore\AdministrativoBackend\Entity\Workflow;

/**
 * Class TransicaoWorkflowRepository.
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
class TransicaoWorkflowRepository extends BaseRepository
{
    protected static string $entityName = Entity::class;

    /**
     * @param int $workflowId
     * @param int $especieTarefaFromId
     * @return Entity[]
     */
    public function findTransicaoByWorkflowIdAndEspecieTarefaFromId(
        int $workflowId,
        int $especieTarefaFromId
    ): array {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT tw, ett 
            FROM SuppCore\AdministrativoBackend\Entity\TransicaoWorkflow tw
            INNER JOIN tw.workflow p WITH p.id = :workflowId
            INNER JOIN tw.especieTarefaFrom etf WITH etf.id = :especieTarefaFromId
            LEFT JOIN tw.especieTarefaTo ett'
        );
        $query->setParameter('workflowId', $workflowId);
        $query->setParameter('especieTarefaFromId', $especieTarefaFromId);

        return $query->getResult();
    }

    /**
     * @param Workflow $workflow
     * @param EspecieTarefa $especieTarefaFrom
     * @param EspecieAtividade $especieAtividade
     * @return Entity[]
     */
    public function findProximasTransicoesWorkflow(
        Workflow $workflow,
        EspecieTarefa $especieTarefaFrom,
        EspecieAtividade $especieAtividade
    ): array
    {
        $qb = $this->createQueryBuilder('transicaoTo');

        $qb->select('transicaoTo, aetf')
            ->join(
                'transicaoTo.workflow',
                'aw'
            )
            ->join(
                'transicaoTo.especieTarefaFrom',
                'aetf'
            )
            ->join(
                'aw.transicoesWorkflow',
                'transicaoFrom'
            )
            ->andWhere(
                $qb->expr()->eq('transicaoTo.workflow', ':workflow')
            )
            ->andWhere(
                $qb->expr()->eq('transicaoFrom.especieAtividade', ':especieAtividade')
            )
            ->andWhere(
                $qb->expr()->eq('transicaoFrom.especieTarefaFrom', ':especieTarefaFrom')
            )
            ->andWhere(
                $qb->expr()->eq('transicaoTo.especieTarefaFrom', 'transicaoFrom.especieTarefaTo')
            )
            ->setParameter('workflow', $workflow)
            ->setParameter('especieAtividade', $especieAtividade)
            ->setParameter('especieTarefaFrom', $especieTarefaFrom);

        return $qb->getQuery()->getResult();
    }
}
