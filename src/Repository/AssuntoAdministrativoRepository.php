<?php

declare(strict_types=1);
/**
 * /src/Repository/AssuntoAdministrativoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\AssuntoAdministrativo as Entity;

/**
 * Class AssuntoAdministrativoRepository.
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
class AssuntoAdministrativoRepository extends BaseRepository
{
    protected static string $entityName = Entity::class;

    /**
     * @param int $assuntoAdministrativoId
     *
     * @return bool
     */
    public function hasFilhosAtivos(int $assuntoAdministrativoId): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT a 
            FROM SuppCore\AdministrativoBackend\Entity\AssuntoAdministrativo a
            WHERE a.parent = :assuntoAdministrativoId AND a.ativo = true'
        );
        $query->setParameter('assuntoAdministrativoId', $assuntoAdministrativoId);
        $result = $query->getResult();

        return (bool) count($result);
    }
}
