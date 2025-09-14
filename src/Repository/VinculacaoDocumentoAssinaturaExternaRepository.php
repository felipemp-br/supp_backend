<?php

declare(strict_types=1);
/**
 * /src/Repository/VinculacaoDocumentoAssinaturaExternaRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumentoAssinaturaExterna as Entity;

/**
 * Class VinculacaoDocumentoAssinaturaExternaRepository.
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
class VinculacaoDocumentoAssinaturaExternaRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param $id
     *
     * @return bool|Entity
     */
    public function findByDocumentoNumeroDocumentoPrincipal($documentoId, $numeroDocumentoPrincipal)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT v, d, u
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoDocumentoAssinaturaExterna v 
            JOIN v.documento d WITH d.id = :documentoId
            LEFT JOIN v.usuario u
            WHERE v.numeroDocumentoPrincipal = :numeroDocumentoPrincipal OR u.username = :numeroDocumentoPrincipal');
        $query->setParameter('documentoId', $documentoId);
        $query->setParameter('numeroDocumentoPrincipal', $numeroDocumentoPrincipal);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (0 === count($result)) {
            return false;
        } else {
            return $result[0];
        }
    }
}
