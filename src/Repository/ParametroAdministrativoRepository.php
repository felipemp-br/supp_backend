<?php

declare(strict_types=1);
/**
 * /src/Repository/\SuppCore\AdministrativoBackend\Entity\ParametroAdministrativoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\ParametroAdministrativo as Entity;

/**
 * Class ParametroAdministrativoRepository.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method Entity|null find(int $id, ?array $populate = null)
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entity[]    findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null)
 * @method Entity[]    findByAdvanced(array $criteria, array $orderBy = null, int $limit = null, int $offset = null, array $search = null): array
 * @method Entity[]    findAll()
 */
class ParametroAdministrativoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param int $parametroAdministrativoId
     *
     * @return bool
     */
    public function hasFilhosAtivos(int $parametroAdministrativoId): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT a 
            FROM SuppCore\AdministrativoBackend\Entity\ParametroAdministrativo a
            WHERE a.parent = :parametroAdministrativoId AND a.ativo = true'
        );
        $query->setParameter('parametroAdministrativoId', $parametroAdministrativoId);
        $result = $query->getResult();

        return (bool) count($result);
    }

}
