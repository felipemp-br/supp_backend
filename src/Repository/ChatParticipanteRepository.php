<?php

declare(strict_types=1);
/**
 * /src/Repository/ChatParticipanteRepository.php.
 */

namespace SuppCore\AdministrativoBackend\Repository;

use Doctrine\ORM\NonUniqueResultException;
use SuppCore\AdministrativoBackend\Entity\Chat;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante as Entity;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class ChatParticipanteRepository.
 *
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
class ChatParticipanteRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param Usuario $usuarioFrom
     * @param Usuario $usuarioTo
     * @return Chat|null
     * @throws NonUniqueResultException
     */
    public function retornaChatIndividualEntreParticipantes(Usuario $usuarioFrom, Usuario $usuarioTo) : Chat|null
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('c')
            ->from('SuppCore\AdministrativoBackend\Entity\Chat', 'c')
            ->join('c.participantes', 'p')
            ->andWhere($qb->expr()->eq('c.grupo', ':grupo'))
            ->andWhere($qb->expr()->in('p.usuario', ':usuarios'))
            ->setParameter('grupo', false)
            ->setParameter('usuarios', [$usuarioFrom->getId(), $usuarioTo->getId()])
            ->having($qb->expr()->eq('COUNT(p.usuario)', ':qtdParticipantes'))
            ->setParameter('qtdParticipantes', 2)
        ->groupBy('c');

        return $qb->getQuery()->getOneOrNullResult();
    }
}
