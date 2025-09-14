<?php

declare(strict_types=1);
/**
 * /src/Repository/AreaTrabalhoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use Doctrine\ORM\NonUniqueResultException;
use SuppCore\AdministrativoBackend\Entity\AreaTrabalho as Entity;

/**
 * Class AreaTrabalhoRepository.
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
class AreaTrabalhoRepository extends BaseRepository
{
    protected static string $entityName = Entity::class;

    /**
     * @param $documento
     *
     * @return mixed
     *
     * @throws NonUniqueResultException
     */
    public function findAreaTrabalhoDono($documento)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT a, u
            FROM SuppCore\AdministrativoBackend\Entity\AreaTrabalho a 
            LEFT JOIN a.usuario u
            LEFT JOIN a.documento d 
            WHERE a.dono = true
            AND d.id = :documento'
        );
        $query->setParameter('documento', $documento);

        return $query->getOneOrNullResult();
    }

    /**
     * @param $documento
     * @param $usuario
     *
     * @return int
     */
    public function findAreaTrabalhoByDocumentoAndUsuario($documento, $usuario)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT a, d, u
            FROM SuppCore\AdministrativoBackend\Entity\AreaTrabalho a 
            LEFT JOIN a.usuario u
            LEFT JOIN a.documento d 
            WHERE u.id = :usuario
            AND d.id = :documento'
        );
        $query->setParameter('documento', $documento);
        $query->setParameter('usuario', $usuario);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return 0;
        }
    }
}
