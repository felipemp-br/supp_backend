<?php

declare(strict_types=1);
/**
 * /src/Repository/DocumentoAvulsoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as Entity;

/**
 * Class DocumentoAvulsoRepository.
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
class DocumentoAvulsoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     *
     * @return DocumentoAvulso[]
     *
     * @throws Exception
     */
    public function findDocumentoAvulsoVencido(): array
    {
        $fim = new DateTime();
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT da 
                FROM SuppCore\AdministrativoBackend\Entity\DocumentoAvulso da
                WHERE 
                da.documentoResposta IS NULL AND
                da.documentoRemessa IS NOT NULL AND 
                da.dataHoraFinalPrazo < :fim'
        );

        $query->setParameter('fim', $fim);

        return $query->getResult();
    }

    /**
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function findDocumentoAvulsoByProcessoAndSetorDestino($processoId, $setorId): mixed
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT da
            FROM SuppCore\AdministrativoBackend\Entity\DocumentoAvulso da
            INNER JOIN da.processo p WITH p.id = :processoId 
            INNER JOIN da.setorDestino sd WITH sd.id = :setorId   
            WHERE da.dataHoraRemessa IS NOT NULL
            ORDER BY da.criadoEm ASC'
        );

        $query->setParameter('processoId', $processoId);
        $query->setParameter('setorId', $setorId);
        $result = $query->getResult();

        if (count($result)) {
            return $result[0];
        } else {
            return false;
        }
    }
}
