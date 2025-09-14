<?php

declare(strict_types=1);
/**
 * /src/Repository/ProcessoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Processo as Entity;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso;
use SuppCore\AdministrativoBackend\QueryBuilder\ArrayQueryBuilder;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class ProcessoRepository.
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
class ProcessoRepository extends BaseRepository
{
    protected static string $entityName = Entity::class;

    public function __construct(
        ManagerRegistry $managerRegistry,
        ArrayQueryBuilder $arrayQueryBuilder,
        TransactionManager $transactionManager,
        private ParameterBagInterface $parameterBag
    ) {
        parent::__construct($managerRegistry, $arrayQueryBuilder, $transactionManager);
    }

    /**
     * @param $setorId
     *
     * @return Entity|bool
     */
    public function findImpedeInativacao($setorId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT p 
            FROM SuppCore\AdministrativoBackend\Entity\Processo p
            INNER JOIN p.setorAtual s
            WHERE s.id = :setorId'
        );
        $query->setParameter('setorId', $setorId);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param int   $processoId
     * @param array $sequenciasId
     *
     * @return float|int|mixed|string
     *
     * @throws NonUniqueResultException|NoResultException
     */
    public function findSizeBytesBySequenciais(int $processoId, array $sequenciasId): mixed
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT SUM(cd.tamanho) 
            FROM SuppCore\AdministrativoBackend\Entity\Processo p
            LEFT JOIN p.volumes v
            INNER JOIN v.juntadas j WITH j.numeracaoSequencial IN (:sequenciasId)
            LEFT JOIN j.documento d
            LEFT JOIN d.componentesDigitais cd
            WHERE p.id = :processoId'
        );
        $query->setParameter('processoId', $processoId);
        $query->setParameter('sequenciasId', $sequenciasId);
        return $query->getSingleScalarResult();
    }

    /**
     * @param int $processoId
     *
     * @return float|int|mixed|string
     *
     * @throws NonUniqueResultException|NoResultException
     */
    public function findSizeBytes(int $processoId): mixed
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT SUM(cd.tamanho) 
            FROM SuppCore\AdministrativoBackend\Entity\Processo p
            LEFT JOIN p.volumes v
            LEFT JOIN v.juntadas j
            LEFT JOIN j.documento d
            LEFT JOIN d.componentesDigitais cd
            WHERE p.id = :processoId'
        );
        $query->setParameter('processoId', $processoId);

        return $query->getSingleScalarResult();
    }

    /**
     * @param $processoId
     *
     * @return Entity|bool
     */
    public function findProcessoEmTramitacao($processoId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT p 
            FROM SuppCore\AdministrativoBackend\Entity\Processo p
            INNER JOIN p.tramitacoes t 
            WHERE t.dataHoraRecebimento IS NULL
            AND p.id = :processoId'
        );
        $query->setParameter('processoId', $processoId);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return 0;
        }
    }

    /**
     * @param $processoId
     */
    public function findProcessoEmTramitacaoExterna($processoId): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT p 
            FROM SuppCore\AdministrativoBackend\Entity\Processo p
            INNER JOIN p.tramitacoes t WITH t.dataHoraRecebimento IS NULL AND t.pessoaDestino IS NOT NULL
            WHERE p.id = :processoId'
        );
        $query->setParameter('processoId', $processoId);
        $query->setMaxResults(1);
        $result = $query->getResult();

        return (bool) count($result);
    }

    /**
     * @return Paginator|int
     */
    public function findProcessosConsultivo(int $batch = null, int $offset = null)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT p
            FROM SuppCore\AdministrativoBackend\Entity\Processo p   
            INNER JOIN p.especiePasta ep
            INNER JOIN ep.generoPasta gp
            WHERE gp.nome = 'CONSULTIVO'
            ORDER BY p.id ASC"
        );

        if ($batch) {
            $query->setMaxResults($batch);
        }
        if ($offset) {
            $query->setFirstResult($offset);
        }

        $paginator = new Paginator($query, true);

        if (count($paginator) > 0) {
            return $paginator;
        } else {
            return 0;
        }
    }

    /**
     * @param $processoId
     *
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function hasComponenteDigital($processoId): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT COUNT(cd.id) 
            FROM SuppCore\AdministrativoBackend\Entity\Processo p
            INNER JOIN p.volumes v
            INNER JOIN v.juntadas j
            INNER JOIN j.documento d
            INNER JOIN d.componentesDigitais cd
            WHERE j.ativo = true
              AND p.id = :processoId"
        );
        $query->setParameter('processoId', $processoId);

        return (bool) $query->getSingleScalarResult();
    }

    /**
     * Retorna os processos disponíveis para desarquivamento conforme data de agendamento.
     *
     * @return array|ArrayCollection<Processo>|null
     */
    public function findProcessosDesarquivamento(?\DateTime $dataAgendamento = null)
    {
        if (!$dataAgendamento) {
            $dataAgendamento = new \DateTime();
        }
        $modalidadeFases = [
            'INTERMEDIÁRIA',
            'DEFINITIVA',
        ];

        $qb = $this->createQueryBuilder('p');
        $qb->join('p.modalidadeFase', 'mf')
            ->andWhere(
                $qb->expr()->in('mf.valor', ':modalidadeFases')
            )
            ->andWhere(
                $qb->expr()->isNotNull('p.dataHoraDesarquivamento')
            )
            ->andWhere(
                $qb->expr()->lte('p.dataHoraDesarquivamento', ':dataAgendamento')
            )
            ->setParameter('modalidadeFases', $modalidadeFases)
            ->setParameter('dataAgendamento', $dataAgendamento);

        return $qb->getQuery()->execute();
    }

    /**
     * Retorna os processos que estao sem tarefas aberta dentro da quantidade de dias especificadas por default sao 10 dias.
     */
    public function findProcessosAbertosLimbo(int $data = 10): array
    {
        $data = new \DateTime("-$data days");

        $sqb = $this->getEntityManager()->createQueryBuilder();
        $sqb->select('p1.id')
            ->from(Tarefa::class, 't1')
            ->join('t1.processo', 'p1')
            ->andWhere($sqb->expr()->isNull('t1.dataHoraConclusaoPrazo'))
            ->andWhere($sqb->expr()->eq('p1.id', 'p.id'));

        $sqb1 = $this->getEntityManager()->createQueryBuilder();
        $sqb1->select('vp')
            ->from(VinculacaoProcesso::class, 'vp')
            ->join('vp.processoVinculado', 'pv')
            ->join('vp.modalidadeVinculacaoProcesso', 'mvp')
            ->andWhere($sqb1->expr()->in(
                'mvp.valor',
                [
                    $this->parameterBag->get('constantes.entidades.modalidade_vinculacao_processo.const_3'),
                    $this->parameterBag->get('constantes.entidades.modalidade_vinculacao_processo.const_2'),
                ]
            ))
            ->andWhere($sqb1->expr()->eq('pv.id', 'p.id'));

        $sqb2 = $this->getEntityManager()->createQueryBuilder();
        $sqb2->select('p3.id')
            ->from(Tarefa::class, 't2')
            ->leftJoin('t2.processo', 'p3')
            ->andWhere($sqb2->expr()->eq('p3.id', 'p.id'))
            ->andWhere($sqb2->expr()->lt('t2.dataHoraConclusaoPrazo', ':data'));

        $qb = $this->createQueryBuilder('p');
        $qb->join('p.especieProcesso', 'ep')
             ->join('p.setorAtual', 'sa')
             ->join('sa.especieSetor', 'es')
            ->leftJoin('p.tramitacoes', 'tr')
            ->andWhere($qb->expr()->isNull('tr.id'))
            ->andWhere($qb->expr()->isNull('p.dataHoraEncerramento'))
            ->andWhere($qb->expr()->notIn(
                'es.nome',
                [
                    $this->parameterBag->get('constantes.entidades.especie_setor.const_2'),
                    $this->parameterBag->get('constantes.entidades.especie_setor.const_3'),
                ]
            ))
            ->andWhere($qb->expr()->not($qb->expr()->exists($sqb->getDQL())))
            ->andWhere($qb->expr()->not($qb->expr()->exists($sqb1->getDQL())))
            ->andWhere($qb->expr()->exists($sqb2->getDQL()))
            ->setParameter('data', $data)
            ->orderBy('p.id', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Entity[]
     */
    public function findByModalidadeFaseValorAndClassificacaoId(
        string $modalidadeFaseValor,
        int $classificacaoId
    ): array {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
                SELECT p
                FROM SuppCore\AdministrativoBackend\Entity\Processo p
                INNER JOIN p.modalidadeFase mf WITH mf.valor = :modalidadeFaseValor
                INNER JOIN p.classificacao c WITH c.id = :classificacaoId"
        );
        $query->setParameter('modalidadeFaseValor', $modalidadeFaseValor);
        $query->setParameter('classificacaoId', $classificacaoId);
        $query->setMaxResults(1);

        return $query->getResult();
    }
}
