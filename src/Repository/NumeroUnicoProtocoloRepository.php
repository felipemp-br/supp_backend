<?php

declare(strict_types=1);
/**
 * /src/Repository/NumeroUnicoProtocoloRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Entity\NumeroUnicoProtocolo as Entity;

/**
 * Class NumeroUnicoProtocoloRepository.
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
class NumeroUnicoProtocoloRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param string $prefixoNUP
     *
     * @return int
     *
     * @throws Exception
     */
    public function findMaxSequencia(string $prefixoNUP): ?int
    {
        $hoje = new DateTime();
        $ano = $hoje->format('Y');

        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT MAX(n.sequencia) 
            FROM SuppCore\AdministrativoBackend\Entity\NumeroUnicoProtocolo n 
            WHERE n.prefixoNUP = :prefixoNUP AND 
            n.ano = :ano'
        );
        $query->setParameter('prefixoNUP', $prefixoNUP);
        $query->setParameter('ano', $ano);
        $result = $query->getArrayResult();
        if (count($result) > 0) {
            return (int) $result[0][1];
        } else {
            return 0;
        }
    }
}
