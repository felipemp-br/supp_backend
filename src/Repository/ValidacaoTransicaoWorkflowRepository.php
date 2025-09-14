<?php

declare(strict_types=1);
/**
 * /src/Repository/ValidacaoTransicaoWorkflowRepository.php.
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\EspecieAtividade;
use SuppCore\AdministrativoBackend\Entity\EspecieProcesso;
use SuppCore\AdministrativoBackend\Entity\EspecieTarefa;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\ValidacaoTransicaoWorkflow as Entity;

/**
 * Class ValidacaoTransicaoWorkflowRepository.
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
class ValidacaoTransicaoWorkflowRepository extends BaseRepository
{
    protected static string $entityName = Entity::class;


    /**
     * Retorna a ValidacaoTransicaoWorkflow para a tarefa destino pela especie de tarefa
     * @param Tarefa $tarefa
     * @param EspecieTarefa $especieTarefaTo
     * @return Entity[]
     */
    public function getValidacaoTransicaoEspecieTarefaTo(Tarefa $tarefa, EspecieTarefa $especieTarefaTo): array
    {
        $qb = $this->createQueryBuilder('avtw');

        $qb->select('avtw')
            ->join(
                'avtw.transicaoWorkflow',
                'atw'
            )
            ->andWhere(
                $qb->expr()->eq('atw.especieTarefaFrom', ':especieTarefaFrom')
            )
            ->andWhere(
                $qb->expr()->eq('atw.especieTarefaTo', ':especieTarefaTo')
            )
            ->andWhere(
                $qb->expr()->eq('atw.workflow', ':workflow')
            )
            ->setParameter(
                'especieTarefaFrom',
                $tarefa->getEspecieTarefa()
            )
            ->setParameter('especieTarefaTo', $especieTarefaTo)
            ->setParameter('workflow', $tarefa->getVinculacaoWorkflow()->getWorkflow());

        return $qb->getQuery()->getResult();
    }

    /**
     * Retorna a ValidacaoTransicaoWorkflow para a tarefa destino pela especie de tarefa
     * @param EspecieAtividade $especieAtividade
     * @param EspecieTarefa $especieTarefaFrom
     * @param EspecieProcesso $especieProcesso
     * @return Entity[]
     */
    public function getValidacaoTransicaoAtividadeTarefaFrom(
        EspecieAtividade $especieAtividade,
        EspecieTarefa $especieTarefaFrom,
        EspecieProcesso $especieProcesso
    ): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('avtw')
            ->from(
                'SuppCore\AdministrativoBackend\Entity\ValidacaoTransicaoWorkflow',
                'avtw'
            )
            ->join(
                'avtw.transicaoWorkflow',
                'atw'
            )
            ->join(
                'atw.especieAtividade',
                'aea'
            )
            ->join(
                'atw.especieTarefaFrom',
                'aetf'
            )
            ->join(
                'atw.workflow',
                'aw'
            )
            ->join(
                'aw.vinculacoesEspecieProcessoWorkflow',
                'avepw'
            )
            ->join(
                'avepw.especieProcesso',
                'aep'
            )
            ->andWhere(
                $qb->expr()->eq('aea.id', ':especieAtividade')
            )
            ->andWhere(
                $qb->expr()->eq('aetf.id', ':especieTarefa')
            )
            ->andWhere(
                $qb->expr()->eq('aep.id', ':especieProcesso')
            )
            ->setParameter('especieAtividade', $especieAtividade->getId())
            ->setParameter('especieTarefa', $especieTarefaFrom->getId())
            ->setParameter('especieProcesso', $especieProcesso->getId());

        return $qb->getQuery()->getResult();
    }

}
