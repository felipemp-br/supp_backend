<?php

declare(strict_types=1);
/**
 * /src/Repository/SigiloRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use DateTime;
use SuppCore\AdministrativoBackend\Entity\Sigilo as Entity;

/**
 * Class SigiloRepository.
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
class SigiloRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param int $processoId
     *
     * @return int
     */
    public function findCountSigilosAtivosByProcessoId(int $processoId): int
    {
        $agora = new DateTime();
        $query = $this->getEntityManager()->createQuery(
            '
            SELECT COUNT(s) 
            FROM SuppCore\AdministrativoBackend\Entity\Sigilo s
            INNER JOIN s.processo p WITH p.id = :processoId
            WHERE s.desclassificado = false
            AND s.dataHoraValidadeSigilo > :agora'
        );
        $query->setParameter('processoId', $processoId);
        $query->setParameter('agora', $agora);
        $result = $query->getResult();

        return (int) $result[0][1];
    }

    /**
     * @param int $documentoId
     *
     * @return int
     */
    public function findCountSigilosAtivosByDocumentoId(int $documentoId): int
    {
        $agora = new DateTime();
        $query = $this->getEntityManager()->createQuery(
            '
            SELECT COUNT(s) 
            FROM SuppCore\AdministrativoBackend\Entity\Sigilo s
            INNER JOIN s.documento d WITH d.id = :documentoId
            WHERE s.desclassificado = false
            AND s.dataHoraValidadeSigilo > :agora'
        );
        $query->setParameter('documentoId', $documentoId);
        $query->setParameter('agora', $agora);
        $result = $query->getResult();

        return (int) $result[0][1];
    }
}
