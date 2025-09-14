<?php
/**
 * @noinspection LongLine
 *
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);
/**
 * /src/Repository/ComponenteDigitalRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as Entity;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento;

/**
 * Class ComponenteDigitalRepository.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br> *
 *
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
class ComponenteDigitalRepository extends BaseRepository
{
    protected static string $entityName = Entity::class;

    /**
     * @param int $componenteDigitalId
     *
     * @return bool
     */
    public function isAssinado(int $componenteDigitalId): bool
    {
        $query = $this->getEntityManager()->createQuery(
            '
            SELECT a.id 
            FROM SuppCore\AdministrativoBackend\Entity\Assinatura a
            INNER JOIN a.componenteDigital cd WITH cd.id = :componenteDigitalId'
        );
        $query->setParameter('componenteDigitalId', $componenteDigitalId);
        $query->setMaxResults(1);

        return (bool) count($query->getArrayResult());
    }

    /**
     * @param int $componenteDigitalId
     * @param int $userId
     *
     * @return bool
     */
    public function isAssinadoUser(int $componenteDigitalId, int $userId): bool
    {
        $query = $this->getEntityManager()->createQuery(
            '
            SELECT a.id 
            FROM SuppCore\AdministrativoBackend\Entity\Assinatura a
            INNER JOIN a.componenteDigital cd WITH cd.id = :componenteDigitalId
            INNER JOIN a.criadoPor u WITH u.id = :userId'
        );
        $query->setParameter('componenteDigitalId', $componenteDigitalId);
        $query->setParameter('userId', $userId);
        $query->setMaxResults(1);

        return (bool) count($query->getArrayResult());
    }

    /**
     * @param int $componenteDigitalId
     *
     * @return array
     */
    public function findAssinaturas(int $componenteDigitalId): array
    {
        $query = $this->getEntityManager()->createQuery(
            '
            SELECT 
                a.id AS assinaturaId,
                u.id AS criadoPorId
            FROM SuppCore\AdministrativoBackend\Entity\Assinatura a
            INNER JOIN a.componenteDigital cd WITH cd.id = :componenteDigitalId
            INNER JOIN a.criadoPor u'
        );
        $query->setParameter('componenteDigitalId', $componenteDigitalId);

        return $query->getArrayResult();
    }

    /**
     * @param string $hash
     *
     * @return int
     */
    public function findCountByHash(string $hash): int
    {
        $query = $this->getEntityManager()->createQuery(
            '
            SELECT COUNT(c) 
            FROM SuppCore\AdministrativoBackend\Entity\ComponenteDigital c 
            WHERE c.hash = :hash'
        );
        $query->setParameter('hash', $hash);
        $result = $query->getResult();

        return (int) $result[0][1];
    }

    /**
     * @param $documento
     *
     * @return int
     */
    public function findCountByDocumento($documento)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT COUNT(c) 
            FROM SuppCore\AdministrativoBackend\Entity\ComponenteDigital c 
            INNER JOIN c.documento d
            WHERE d.id = :documento'
        );
        $query->setParameter('documento', $documento);
        $result = $query->getResult();

        return (int) $result[0][1];
    }

    /**
     * @param $documento
     *
     * @return array|bool
     */
    public function findByDocumentoAsArray($documento)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT c
            FROM SuppCore\AdministrativoBackend\Entity\ComponenteDigital c
            INNER JOIN c.documento d
            WHERE d.id = :documento"
        );
        $query->setParameter('documento', $documento);
        $result = $query->getArrayResult();
        if (0 == count($result)) {
            return false;
        } else {
            return $result;
        }
    }

    public function findByDocumento($documentoId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT c
            FROM SuppCore\AdministrativoBackend\Entity\ComponenteDigital c
            INNER JOIN c.documento d
            WHERE d.id = :documentoId"
        );
        $query->setParameter('documentoId', $documentoId);

        return $query->getResult();
    }

    /**
     * @param $ids
     *
     * @return float|int|mixed|string
     */
    public function findByIds($ids)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array|bool
     */
    public function findVersaoComponjenteDigital(DateTime $seisMesesAtras, int $batch)
    {
        // TODO Verificar o campo versoesEliminadas pois não existe mais.
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT c
                    FROM SuppCore\AdministrativoBackend\Entity\ComponenteDigital c
                    INNER JOIN c.documento d
                    WHERE c.versoesEliminadas = false 
                    AND c.editavel = true
                    AND c.criadoEm < :seisMeses
                    AND d.juntadaAtual IS NULL"
        );

        $query->setParameter('seisMeses', $seisMesesAtras);
        $query->setMaxResults($batch);

        $result = $query->getArrayResult();

        if (0 == count($result)) {
            return false;
        } else {
            return $result;
        }
    }

    /**
     * @param $documentoId
     *
     * @return int
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findMaxNumeracaoSequencialByDocumentoId($documentoId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT MAX(cd.numeracaoSequencial) 
            FROM SuppCore\AdministrativoBackend\Entity\ComponenteDigital cd
            INNER JOIN cd.documento d WITH d.id = :documentoId');
        $query->setParameter('documentoId', $documentoId);
        $maxSequencia = $query->getSingleResult();
        if ($maxSequencia[1]) {
            return $maxSequencia[1];
        } else {
            return 0;
        }
    }

    /**
     * @param $juntadaId
     *
     * @return Entity|false
     */
    public function findFirstByJuntadaIdAndProcessoId($juntadaId): Entity|false
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT cd 
            FROM SuppCore\AdministrativoBackend\Entity\ComponenteDigital cd
            INNER JOIN cd.documento d
            INNER JOIN d.juntadaAtual ja WITH ja.id = :juntadaId
            ORDER BY cd.numeracaoSequencial ASC'
        );
        $query->setParameter('juntadaId', $juntadaId);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        }

        return false;
    }

    /**
     * Busca componente digital pelo hash e pelo documento.
     *
     * @param string $hash      - Hash do componente digital
     * @param int    $documento - Id do documento
     *
     * @return array|bool
     */
    public function findByHashAndDocumento($hash, $documento): bool|array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT c, o 
            FROM SuppCore\AdministrativoBackend\Entity\ComponenteDigital c
             LEFT JOIN c.origemDados o
            WHERE c.hash = :hash
            AND c.documento = :documento"
        );
        $query->setParameter('hash', $hash);
        $query->setParameter('documento', $documento);
        $result = $query->getResult();
        if (0 == count($result)) {
            return false;
        } else {
            return $result;
        }
    }

    public function findVinculadosByDocumento(Documento $documento): bool|array
    {
        /** @var VinculacaoDocumento[] $vincDocumento */
        $vincDocumentos = $this
            ->getEntityManager()
            ->createQuery(
                "
                SELECT v, docPrincipal, docVinculado, compPrincipal, compVinculado 
                FROM SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento v
                INNER JOIN v.documento docPrincipal
                INNER JOIN v.documentoVinculado docVinculado 
                INNER JOIN docPrincipal.componentesDigitais compPrincipal
                INNER JOIN docVinculado.componentesDigitais compVinculado
                WHERE v.documento = :documento OR v.documentoVinculado = :documento"
            )
            ->setParameter('documento', $documento)
            ->getResult();

        /** @var ComponenteDigital[] $componentesDigitais */
        if (empty($vincDocumentos)) {
            $componentesDigitais = $documento->getComponentesDigitais()->toArray();
        } else {
            $componentesDigitais = array_merge(
                ...array_map(
                    fn (VinculacaoDocumento $v) => [
                        ...$v->getDocumento()->getComponentesDigitais()->toArray(),
                        ...$v->getDocumentoVinculado()->getComponentesDigitais()->toArray(),
                    ],
                    $vincDocumentos
                )
            );
        }
        $unique = [];
        foreach ($componentesDigitais as $componentesDigital) {
            $unique[$componentesDigital->getId()] = $componentesDigital;
        }

        return $unique;
    }


    /**
     * @param string $hash
     * @param ProcessoEntity $processo
     *
     * @return Entity
     * @throws NonUniqueResultException
     */
    public function findByHashAndProcesso(string $hash, ProcessoEntity $processo): Entity|null
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT c
            FROM SuppCore\AdministrativoBackend\Entity\ComponenteDigital c
            INNER JOIN c.documento d
            INNER JOIN d.juntadaAtual ja
            INNER JOIN ja.volume v
            INNER JOIN v.processo p
            WHERE c.hash = :hash
            AND p.uuid = :processoUuid"
        );
        $query->setParameter('hash', $hash);
        $query->setParameter('processoUuid', $processo->getUuid());

        return $query->getOneOrNullResult();
    }

    /**
     * @param string $hash
     * @param ProcessoEntity $processo
     *
     * @return bool
     */
    public function findByHashAndProcessoIndex(string $hash, ProcessoEntity $processo): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT c, o 
            FROM SuppCore\AdministrativoBackend\Entity\ComponenteDigital c
            INNER JOIN c.documento d
            INNER JOIN d.juntadaAtual ja
            INNER JOIN ja.volume v
            INNER JOIN v.processo p
            WHERE c.hash = :hash"
        );
        $query->setParameter('hash', $hash);

        $processos = array_map(
            fn(Entity $c) => $c->getDocumento()->getJuntadaAtual()->getVolume()->getProcesso(),
            $query->getArrayResult()
        );

        return count(array_filter(
                $processos,
                fn($p) => $p->getUuid() === $processo->getUuid()
            )) > 0;
    }
}
