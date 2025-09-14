<?php

declare(strict_types=1);
/**
 * /src/Repository/VinculacaoSetorMunicipioRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\VinculacaoSetorMunicipio as Entity;

/**
 * Class VinculacaoSetorMunicipioRepository.
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
class VinculacaoSetorMunicipioRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * Retorna um objeto com a vinculação entre Setor e Municipio buscando pelo municipio e orgaoCentral.
     *
     * @param int $municipio
     * @param int $orgaoCentral
     *
     * @return Entity|false
     */
    public function findByMunicipioAndOrgaoCentral($municipio, $orgaoCentral)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT v 
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoSetorMunicipio v
            JOIN v.municipio m
            JOIN v.setor s
            JOIN s.modalidadeOrgaoCentral o            
            WHERE m.id = :municipio
            AND o.id = :orgaoCentral
            '
        );
        $query->setParameter('municipio', $municipio);
        $query->setParameter('orgaoCentral', $orgaoCentral);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }
}
