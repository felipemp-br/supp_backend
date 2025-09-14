<?php

declare(strict_types=1);
/**
 * /src/Repository/AcaoTransicaoWorkflowRepository.php.
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\AcaoTransicaoWorkflow as Entity;
use SuppCore\AdministrativoBackend\Entity\EspecieTarefa;
use SuppCore\AdministrativoBackend\Entity\Tarefa;

/**
 * Class AcaoTransicaoWorkflowRepository.
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
class AcaoTransicaoWorkflowRepository extends BaseRepository
{
    protected static string $entityName = Entity::class;


    /**
     * Retorna a AcaoTransicaoWorkflow para a criação da tarefa do alvo da transição
     * @return Entity[]
     */
    public function getAcaoTransicaoEspecieTarefaTo(
        Tarefa $tarefaAtual,
        EspecieTarefa $especieTarefaTo,
        string $triggerName
    ): array
    {
        $qb = $this->createQueryBuilder('aatw');

        $qb->select('aatw')
            ->join(
                'aatw.tipoAcaoWorkflow',
                'maw'
            )
            ->join(
                'aatw.transicaoWorkflow',
                'atw'
            )
            ->andWhere(
                $qb->expr()->eq('atw.especieTarefaFrom', ':especieTarefaFrom')
            )
            ->andWhere(
                $qb->expr()->eq('atw.especieTarefaTo', ':especieTarefaTo')
            )
            ->andWhere(
                $qb->expr()->eq('maw.trigger', ':triggerName')
            )
            ->andWhere(
                $qb->expr()->eq('atw.workflow', ':workflow')
            )
            ->setParameter(
                'especieTarefaFrom',
                $tarefaAtual->getEspecieTarefa()
            )
            ->setParameter('especieTarefaTo', $especieTarefaTo)
            ->setParameter('triggerName', $triggerName)
            ->setParameter('workflow', $tarefaAtual->getVinculacaoWorkflow()->getWorkflow());

        return $qb->getQuery()->getResult();
    }
}
