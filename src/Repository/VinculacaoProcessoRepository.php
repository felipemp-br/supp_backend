<?php

declare(strict_types=1);
/**
 * /src/Repository/VinculacaoProcessoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso as Entity;
use SuppCore\AdministrativoBackend\QueryBuilder\ArrayQueryBuilder;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class VinculacaoProcessoRepository.
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
class VinculacaoProcessoRepository extends BaseRepository
{
    /**
     * @var string
     */
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
     * @param $processoId
     *
     * @return bool
     */
    public function estaApensada($processoId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT v, vp, vpc, mvp
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso v
            LEFT JOIN v.processo vp 
            LEFT JOIN v.processoVinculado vpc
            LEFT JOIN v.modalidadeVinculacaoProcesso mvp
            WHERE vpc.id = :processoId
            AND (mvp.valor = :modalidadeVinculacaoProcessoApensamento
            OR mvp.valor = :modalidadeVinculacaoProcessoAnexacao)"
        );
        $query->setParameter('processoId', $processoId);
        $query->setParameter(
            'modalidadeVinculacaoProcessoAnexacao',
            $this->parameterBag->get('constantes.entidades.modalidade_vinculacao_processo.const_2')
        );
        $query->setParameter(
            'modalidadeVinculacaoProcessoApensamento',
            $this->parameterBag->get('constantes.entidades.modalidade_vinculacao_processo.const_3')
        );
        $query->setMaxResults(1);
        $result = $query->getArrayResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $processoId
     *
     * @return bool
     */
    public function estaEstritamenteApensada($processoId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT v, vp, vpc, mvp
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso v
            LEFT JOIN v.processo vp 
            LEFT JOIN v.processoVinculado vpc
            LEFT JOIN v.modalidadeVinculacaoProcesso mvp
            WHERE vpc.id = :processoId
            AND mvp.valor = :modalidadeVinculacaoProcessoApensamento"
        );
        $query->setParameter('processoId', $processoId);
        $query->setParameter(
            'modalidadeVinculacaoProcessoApensamento',
            $this->parameterBag->get('constantes.entidades.modalidade_vinculacao_processo.const_3')
        );
        $query->setMaxResults(1);
        $result = $query->getArrayResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $processo
     *
     * @return bool
     */
    public function findByProcessoVinculado($processo)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT v, vp, vpc, mvp
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso v
            LEFT JOIN v.processo vp 
            LEFT JOIN v.processoVinculado vpc
            LEFT JOIN v.modalidadeVinculacaoProcesso mvp
            WHERE vpc.id = :processo"
        );
        $query->setParameter('processo', $processo);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $processo
     *
     * @return bool
     */
    public function findByProcessoPrincipal($processo)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT v, vp, vpc, mvp
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso v
            LEFT JOIN v.processo vp 
            LEFT JOIN v.processoVinculado vpc
            LEFT JOIN v.modalidadeVinculacaoProcesso mvp
            WHERE vp.id = :processo"
        );
        $query->setParameter('processo', $processo);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $processoId
     *
     * @return bool
     */
    public function possuiApensadas($processoId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
        SELECT v, vp, vpc, mvp
        FROM SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso v
        LEFT JOIN v.processo vp 
        LEFT JOIN v.processoVinculado vpc
        LEFT JOIN v.modalidadeVinculacaoProcesso mvp
        WHERE vp.id = :processoId
        AND (mvp.valor = :modalidadeVinculacaoProcessoApensamento
        OR mvp.valor = :modalidadeVinculacaoProcessoAnexacao)"
        );
        $query->setParameter('processoId', $processoId);
        $query->setParameter(
            'modalidadeVinculacaoProcessoAnexacao',
            $this->parameterBag->get('constantes.entidades.modalidade_vinculacao_processo.const_2')
        );
        $query->setParameter(
            'modalidadeVinculacaoProcessoApensamento',
            $this->parameterBag->get('constantes.entidades.modalidade_vinculacao_processo.const_3')
        );
        $query->setMaxResults(1);
        $result = $query->getArrayResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $processoId
     *
     * @return bool
     */
    public function possuiEstritamenteApensadas($processoId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
        SELECT v, vp, vpc, mvp
        FROM SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso v
        LEFT JOIN v.processo vp 
        LEFT JOIN v.processoVinculado vpc
        LEFT JOIN v.modalidadeVinculacaoProcesso mvp
        WHERE vp.id = :processoId
        AND mvp.valor = :modalidadeVinculacaoProcessoApensamento"
        );
        $query->setParameter('processoId', $processoId);
        $query->setParameter(
            'modalidadeVinculacaoProcessoApensamento',
            $this->parameterBag->get('constantes.entidades.modalidade_vinculacao_processo.const_3')
        );
        $query->setMaxResults(1);
        $result = $query->getArrayResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * Retorna todas as vinculações informando o id do processo ou do processo vinculado
     * @param $processoId
     * @return Paginator
     */
    public function findAllVinculacoesByProcesso($processoId): Paginator
    {
        $qb = $this->createQueryBuilder('vp');
        $subQb = $this->createQueryBuilder('s1_vp');

        $subQb->select('s1_p.id')
            ->distinct()
            ->join('s1_vp.processoVinculado', 's1_pv')
            ->join('s1_vp.processo', 's1_p')
            ->where(
                $subQb->expr()->eq('s1_pv.id', ':processoVinculadoId')
            );

        $qb->select('vp')
            ->distinct()
            ->join('vp.processo', 'p')
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->eq('vp.processo', ':processoId'),
                    $qb->expr()->in('p.id', $subQb->getQuery()->getDQL()),
                )
            )
            ->setParameter('processoId', $processoId)
            ->setParameter('processoVinculadoId', $processoId);

        return new Paginator($qb->getQuery());
    }

    public function findAllVinculacoesToFix() {
        $qb = $this->createQueryBuilder('vpr');
        $qb->select([
            'vpr.id as vpr_id',
            'vpr_p.id as vpr_processo_id',
            'vpr_pv.id as vpr_processo_vinculado_id',

            'vpp.id as vpp_id',
            'vpp_p.id as vpp_processo_id',
            'vpp_pv.id as vpp_processo_vinculado_id'
        ]);
        $qb->join('vpr.processo', 'vpr_p')
            ->join('vpr.processoVinculado', 'vpr_pv')
            ->join('SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso', 'vpp', 'WITH', 'vpr.processo = vpp.processoVinculado')
            ->join('vpp.processo', 'vpp_p')
            ->join('vpp.processoVinculado', 'vpp_pv');

        return $qb->getQuery()->getArrayResult();
    }

    public function updateVinculacoesToFix($processoId, $vinculacoesIds) {
        $qb = $this->createQueryBuilder('vp')
        ->update()
        ->set('vp.processo',':processoId')
        ->setParameter('processoId', $processoId);

        $qb->where(
            $qb->expr()->in('vp.id', ':vinculacoesIds')
        )
        ->setParameter('vinculacoesIds', $vinculacoesIds);

        return $qb->getQuery()->execute();
    }
}
