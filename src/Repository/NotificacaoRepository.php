<?php

declare(strict_types=1);
/**
 * /src/Repository/NotificacaoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use Doctrine\ORM\NonUniqueResultException;
use SuppCore\AdministrativoBackend\Entity\Notificacao as Entity;

/**
 * Class NotificacaoRepository.
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
class NotificacaoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param $destinatarioId
     *
     * @return mixed
     *
     * @throws NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function countNaoLidasByDestinatarioId($destinatarioId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT COUNT(n)
            FROM SuppCore\AdministrativoBackend\Entity\Notificacao n
            INNER JOIN n.destinatario d WITH d.id = :destinatarioId
            WHERE n.dataHoraLeitura IS NULL'
        );
        $query->setParameter('destinatarioId', $destinatarioId);

        return $query->getSingleScalarResult();
    }

    /**
     * @param $destinatarioUsername
     *
     * @return mixed
     *
     * @throws NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function countNaoLidasByDestinatarioUsername($destinatarioUsername)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT COUNT(n)
            FROM SuppCore\AdministrativoBackend\Entity\Notificacao n
            INNER JOIN n.destinatario d WITH d.username = :destinatarioUsername
            WHERE n.dataHoraLeitura IS NULL'
        );
        $query->setParameter('destinatarioUsername', $destinatarioUsername);

        return $query->getSingleScalarResult();
    }

    /**
     * @param $destinatarioId
     * @param boolean $filtrarNaoLidas
     * @return array
     */
    public function getByDestinatarioId($destinatarioId, $filtrarNaoLidas = true): array
    {
        $qb = $this->createQueryBuilder('n');
        $qb->where(
            $qb->expr()->eq('n.destinatario', ':destinatario_id')
        );

        if ($filtrarNaoLidas) {
            $qb->andWhere(
                $qb->expr()->eq('n.destinatario', ':destinatario_id')
            );
        }

        $qb->setParameter('destinatario_id', $destinatarioId);

        return $qb->getQuery()->getResult();
    }
}
