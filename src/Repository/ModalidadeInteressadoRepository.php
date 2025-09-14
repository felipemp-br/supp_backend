<?php

declare(strict_types=1);
/**
 * /src/Repository/ModalidadeInteressadoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use SuppCore\AdministrativoBackend\Entity\ModalidadeInteressado as Entity;

/**
 * Class ModalidadeInteressadoRepository.
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
class ModalidadeInteressadoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param $valor
     *
     * @return mixed
     *
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findByValor($valor): mixed
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('m')
            ->from('SuppCore\AdministrativoBackend\Entity\ModalidadeInteressado', 'm')
            ->where('m.valor = ?1')
            ->setParameter(1, $valor);

        return $qb->getQuery()->getSingleResult();
    }
}
