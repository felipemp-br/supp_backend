<?php

declare(strict_types=1);
/**
 * /src/Repository/JuntadaRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr\Join;
use SuppCore\AdministrativoBackend\Entity\Juntada as Entity;

/**
 * Class JuntadaRepository.
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
class JuntadaRepository extends BaseRepository
{
    protected static string $entityName = Entity::class;

    /**
     * @param $processoId
     *
     * @return int
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findMaxNumeracaoSequencialByProcessoId($processoId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT MAX(j.numeracaoSequencial) 
            FROM SuppCore\AdministrativoBackend\Entity\Juntada j 
            INNER JOIN j.volume v
            INNER JOIN v.processo p WITH p.id = :processoId'
        );
        $query->setParameter('processoId', $processoId);
        $maxSequencia = $query->getSingleResult();
        if ($maxSequencia[1]) {
            return (int) $maxSequencia[1];
        } else {
            return 0;
        }
    }

    /**
     * @param $processoId
     *
     * @return Entity|false
     */
    public function findLastNaoVinculadaByProcessoId($processoId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT j
            FROM SuppCore\AdministrativoBackend\Entity\Juntada j 
            INNER JOIN j.volume v
            INNER JOIN v.processo p WITH p.id = :processoId
            WHERE j.vinculada = false
            ORDER BY j.numeracaoSequencial DESC'
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
     * @param $volumeId
     *
     * @return int
     */
    public function totalJuntadaByVolumeId($volumeId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT COUNT(j)
            FROM SuppCore\AdministrativoBackend\Entity\Juntada j 
            WHERE j.volume = :volumeId
            "
        );
        $query->setParameter('volumeId', $volumeId);

        return $query->getSingleScalarResult();
    }

    /**
     * @param $processo
     * @param $fonteDados
     */
    public function findByProcessoAndFonteDados($processo, $fonteDados): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT j
            FROM SuppCore\AdministrativoBackend\Entity\Juntada j
            LEFT JOIN j.origemDados o
            LEFT JOIN j.volume v
            LEFT JOIN v.processo p            
            WHERE p.id = :processo
            AND o.fonteDados = :fonteDados'
        );
        $query->setParameter('processo', $processo);
        $query->setParameter('fonteDados', $fonteDados);

        return $query->getResult();
    }

    /**
     * Consulta juntadas pelo processo ordenadas pela numeração do sequencial.
     *
     * @author Reinaldo Pereira <reinaldo.pereira@agu.gov.br>
     *
     * @param $processo
     */
    public function findJuntadaByProcesso($processo): mixed
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT j, d, o, td, c, p, se
            FROM SuppCore\AdministrativoBackend\Entity\Juntada j
            INNER JOIN j.volume v 
            LEFT JOIN j.documento d     
            LEFT JOIN d.origemDados o
            LEFT JOIN d.tipoDocumento td
            LEFT JOIN d.componentesDigitais c
            LEFT JOIN c.processoOrigem p
            LEFT JOIN p.setorAtual se
            WHERE v.processo = :processo
            ORDER BY j.numeracaoSequencial'
        );

        $query->setParameter('processo', $processo);

        return $query->getResult();
    }

    /**
     * @param $processoId
     * @return array
     */
    public function findJuntadasByProcessoAsArray($processoId): array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT 
            PARTIAL j.{id, numeracaoSequencial, ativo},
            PARTIAL d.{id},
            PARTIAL cd.{id},
            PARTIAL vd.{id},
            PARTIAL dv.{id},
            PARTIAL cddv.{id}
            FROM SuppCore\AdministrativoBackend\Entity\Juntada j
            INNER JOIN j.volume v 
            INNER JOIN v.processo p WITH p.id = :processoId
            LEFT JOIN j.documento d
            LEFT JOIN d.componentesDigitais cd
            LEFT JOIN d.vinculacoesDocumentos vd
            LEFT JOIN vd.documentoVinculado dv
            LEFT JOIN dv.componentesDigitais cddv
            WHERE j.vinculada = false
            ORDER BY j.numeracaoSequencial DESC, cd.numeracaoSequencial, vd.id, cddv.numeracaoSequencial'
        );

        $query->setParameter('processoId', $processoId);

        return $query->getArrayResult();
    }

    /**
     * @param int        $processoId
     * @param array<int> $sequencialJuntadasIds
     *
     * @return array
     */
    public function getJuntadasProcessoSize(int $processoId, array $sequencialJuntadasIds = []): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb
            ->select('SUM(cd.tamanho) as tamanho_juntada', 'j.id', 'j.numeracaoSequencial')
            ->from('SuppCore\AdministrativoBackend\Entity\ComponenteDigital', 'cd')
            ->join('cd.documento', 'd')
            ->join('d.juntadas', 'j')
            ->join('j.volume', 'v')
            ->join('v.processo', 'p')
            ->groupBy('j.id')
            ->where(
                $qb->expr()->eq('p.id', ':processoId')
            )
            ->setParameter('processoId', $processoId);

        if (!empty($sequencialJuntadasIds)) {
            $qb->andWhere(
                $qb->expr()->in('j.numeracaoSequencial', ':sequencialJuntadasIds')
            );
            $qb->setParameter('sequencialJuntadasIds', $sequencialJuntadasIds);
        }

        return $qb->getQuery()->execute();
    }
}
