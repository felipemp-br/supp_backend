<?php

declare(strict_types=1);
/**
 * /src/Repository/ModalidadeOrgaoCentralRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Pagination\Paginator;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral as Entity;
use function Doctrine\ORM\QueryBuilder;

/**
 * Class ModalidadeOrgaoCentralRepository.
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
class ModalidadeOrgaoCentralRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * Retorna a quantidade de filhos ativos
     * @param int $idModalidadeOrgaoCentral
     * @return int
     */
    public function countFilhosAtivos(int $idModalidadeOrgaoCentral):int
    {
        $qb = $this->createQueryBuilder('moc');
        $qb->join(
                'SuppCore\AdministrativoBackend\Entity\Setor',
                's',
                Join::WITH,
                $qb->expr()->eq('moc.id', 's.modalidadeOrgaoCentral')
            )
            ->where($qb->expr()->eq('s.ativo', ':ativo'))
            ->andWhere($qb->expr()->eq('moc.id', ':id'))
            ->setParameter('ativo', true)
            ->setParameter('id', $idModalidadeOrgaoCentral);

        $paginator = new Paginator($qb->getQuery());

        return $paginator->count();
    }

}
