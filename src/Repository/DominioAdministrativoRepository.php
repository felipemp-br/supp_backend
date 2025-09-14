<?php

declare(strict_types=1);
/**
 * /src/Repository/\SuppCore\AdministrativoBackend\Entity\DominioAdministrativoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\DominioAdministrativo as Entity;

/**
 * Class DominioAdministrativoRepository.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method Entity|null find(int $id, ?array $populate = null)
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entity[]    findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null)
 * @method Entity[]    findByAdvanced(array $criteria, array $orderBy = null, int $limit = null, int $offset = null, array $search = null): array
 * @method Entity[] findAll()
 */
class DominioAdministrativoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;


//    /**
//     * Busca um registro de DominioAdministrativo pelo ID do ModalidadeOrgaoCentral.
//     *
//     * @param int $modalidadeOrgaoCentralId
//     *
//     * @return Entity|null
//     */
//    public function findOneByModalidadeOrgaoCentralId(int $modalidadeOrgaoCentralId): ?Entity
//    {
//        return $this->createQueryBuilder('d')
//            ->where('d.modOrgaoCentral = :modalidadeOrgaoCentralId')
//            ->setParameter('modalidadeOrgaoCentralId', $modalidadeOrgaoCentralId)
//            ->getQuery()
//            ->getOneOrNullResult();
//    }
}
