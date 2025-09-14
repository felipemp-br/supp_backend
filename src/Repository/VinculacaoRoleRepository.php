<?php

declare(strict_types=1);
/**
 * /src/Repository/VinculacaoRoleRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use Doctrine\ORM\NonUniqueResultException;
use SuppCore\AdministrativoBackend\Entity\VinculacaoRole;
use SuppCore\AdministrativoBackend\Entity\VinculacaoRole as Entity;

/**
 * Class VinculacaoRoleRepository.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @codingStandardsIgnoreStart
 *
 * @method Entity|null find(int $id, ?string $lockMode = null, ?string $lockVersion = null)
 * @method Entity|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method Entity[]    findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @method Entity[]    findByAdvanced(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?array $search = null): array
 * @method Entity[] findAll()
 *
 * @codingStandardsIgnoreEnd
 */
class VinculacaoRoleRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * Names of search columns.
     *
     * @var string[]
     */
    protected static array $searchColumns = ['role', 'name'];

    /**
     * @param int    $usuarioId
     * @param string $roleName
     *
     * @return VinculacaoRole|null
     *
     * @throws NonUniqueResultException
     */
    public function findByUsuarioIdAndRoleName(int $usuarioId, string $roleName): ?VinculacaoRole
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT vr 
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoRole vr 
            INNER JOIN vr.usuario u
            WHERE u.id = :usuarioId
            AND vr.role = :roleName'
        );
        $query->setParameter('usuarioId', $usuarioId);
        $query->setParameter('roleName', $roleName);

        return $query->getOneOrNullResult();
    }
}
