<?php

declare(strict_types=1);
/**
 * /src/Repository/DocumentoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Entity\Documento as Entity;

/**
 * Class DocumentoRepository.
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
class DocumentoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param int $documentoId
     *
     * @return bool
     */
    public function isAssinado(int $documentoId): bool
    {
        $query = $this->getEntityManager()->createQuery(
            '
            SELECT a.id 
            FROM SuppCore\AdministrativoBackend\Entity\Assinatura a
            INNER JOIN a.componenteDigital cd
            INNER JOIN cd.documento d WITH d.id = :documentoId'
        );
        $query->setParameter('documentoId', $documentoId);
        $query->setMaxResults(1);
        return (bool) count($query->getArrayResult());
    }

    /**
     * @param int $documentoId
     *
     * @return bool
     */
    public function isAssinadoUser(int $documentoId, int $userId): bool
    {
        $query = $this->getEntityManager()->createQuery(
            '
            SELECT a.id 
            FROM SuppCore\AdministrativoBackend\Entity\Assinatura a
            INNER JOIN a.componenteDigital cd
            INNER JOIN cd.documento d WITH d.id = :documentoId
            INNER JOIN a.criadoPor u WITH u.id = :userId'
        );
        $query->setParameter('documentoId', $documentoId);
        $query->setParameter('userId', $userId);
        $query->setMaxResults(1);
        return (bool) count($query->getArrayResult());
    }

    /**
     * @param int $documentoId
     *
     * @return array
     */
    public function findAssinaturas(int $documentoId): array
    {
        $query = $this->getEntityManager()->createQuery(
            '
            SELECT 
                a.id AS assinaturaId,
                u.id AS criadoPorId
            FROM SuppCore\AdministrativoBackend\Entity\Assinatura a
            INNER JOIN a.componenteDigital cd
            INNER JOIN cd.documento d WITH d.id = :documentoId
            INNER JOIN a.criadoPor u'
        );
        $query->setParameter('documentoId', $documentoId);
        return $query->getArrayResult();
    }

    /**
     * @param int $documentoId
     *
     * @return bool
     */
    public function isMinuta(int $documentoId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
                SELECT d
                FROM SuppCore\AdministrativoBackend\Entity\Documento d
                WHERE d.juntadaAtual IS NULL AND d.id = :documentoId'
        );
        $query->setParameter('documentoId', $documentoId);
        $query->setMaxResults(1);

        return (bool) count($query->getResult());
    }

    /**
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function findDocumentoValidadeVencida()
    {
        $fim = new DateTime();
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT d 
                FROM SuppCore\AdministrativoBackend\Entity\Documento d
                WHERE 
                d.dataHoraValidade IS NOT NULL AND
                d.dataHoraValidade < :fim'
        );

        $query->setParameter('fim', $fim);

        return $query->getResult();
    }

    /**
     * @return Entity[]
     */
    public function findByProcessoOrderByNumeracaoSequencial(int|string $processo, bool $integracao = null): array
    {
        return $this
            ->getEntityManager()
            ->createQuery(
                '
                SELECT d, td, j, t, et
                FROM SuppCore\AdministrativoBackend\Entity\Documento d
                INNER JOIN d.tipoDocumento td
                INNER JOIN d.juntadas j
                LEFT JOIN j.tarefa t
                LEFT JOIN t.especieTarefa et
                INNER JOIN j.volume v
                INNER JOIN v.processo p
                WHERE j.documento IS NOT NULL'.
                match (is_string($processo)) {
                    true => ' AND p.uuid = :processo ',
                    false => ' AND p.id = :processo ',
                }.
                match ($integracao) {
                    true => ' AND j.origemDados IS NOT NULL',
                    false => ' AND j.origemDados IS NULL',
                    null => ''
                }.
                ' ORDER BY j.numeracaoSequencial'
            )
            ->setParameter('processo', $processo)
            ->getResult();
    }
}
