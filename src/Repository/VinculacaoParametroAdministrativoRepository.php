<?php

declare(strict_types=1);
/**
 * /src/Repository/\SuppCore\AdministrativoBackend\Entity\VinculacaoParametroAdministrativoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\VinculacaoParametroAdministrativo as Entity;

/**
 * Class VinculacaoParametroAdministrativoRepository.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method Entity|null find(int $id, ?array $populate = null)
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entity[]    findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null)
 * @method Entity[]    findByAdvanced(array $criteria, array $orderBy = null, int $limit = null, int $offset = null, array $search = null): array
 * @method Entity[]    findAll()
 */
class VinculacaoParametroAdministrativoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;


    public function findByProcessoAndParametroAdministrativo($processo, $parametroAdministrativo)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT v 
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoParametroAdministrativo v
            JOIN v.processo u
            JOIN v.parametroAdministrativo uv
            WHERE u.id = :processo
            AND uv.id = :parametroAdministrativo
            '
        );
        $query->setParameter('processo', $processo);
        $query->setParameter('parametroAdministrativo', $parametroAdministrativo);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }
}
