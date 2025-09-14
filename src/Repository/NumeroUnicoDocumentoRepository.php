<?php

declare(strict_types=1);
/**
 * /src/Repository/NumeroUnicoDocumentoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\NumeroUnicoDocumento as Entity;

/**
 * Class NumeroUnicoDocumentoRepository.
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
class NumeroUnicoDocumentoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param $ano
     * @param $setor
     * @param $tipoDocumento
     *
     * @return int
     */
    public function findMaxSequencia($ano, $setor, $tipoDocumento)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT MAX(n.sequencia) 
            FROM SuppCore\AdministrativoBackend\Entity\NumeroUnicoDocumento n 
            INNER JOIN n.setor s WITH s.id = :setor
            INNER JOIN n.tipoDocumento e WITH e.id = :tipoDocumento
            WHERE n.ano = :ano'
        );
        $query->setParameter('ano', $ano);
        $query->setParameter('setor', $setor);
        $query->setParameter('tipoDocumento', $tipoDocumento);
        $result = $query->getArrayResult();
        if (count($result) > 0) {
            return (int) $result[0][1];
        } else {
            return 0;
        }
    }
}
