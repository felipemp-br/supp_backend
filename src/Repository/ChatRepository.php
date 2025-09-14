<?php

declare(strict_types=1);
/**
 * /src/Repository/ChatRepository.php.
 */

namespace SuppCore\AdministrativoBackend\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use SuppCore\AdministrativoBackend\Entity\Chat as Entity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use Throwable;

/**
 * Class ChatRepository.
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
class ChatRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param Usuario $usuario
     * @param array|null $criteria
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     * @throws Exception
     */
    public function findChatList(Usuario $usuario,
                                ?array $criteria = [],
                                ?int $limit = null,
                                ?int $offset = null): array
    {
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.participantes', 'participante')
            ->leftJoin('c.participantes', 'outrosParticipantes')
            ->leftJoin('c.ultimaMensagem', 'ultimaMensagem')
            ->leftJoin('participante.usuario', 'usuarioParticipante')
            ->leftJoin('outrosParticipantes.usuario', 'usuarioOutroParticipante')
            ->leftJoin('usuarioParticipante.imgPerfil', 'imgPerfilParticipante');

        $qb
            ->andWhere(
                $qb->expr()->eq('participante.usuario', ':usuario')
            )
            ->setParameter('usuario', $usuario)
            ->orderBy('c.atualizadoEm', 'DESC')
            ->addOrderBy('c.criadoEm', 'DESC');

        if (isset($criteria['keyword']) && $criteria['keyword']) {
            $keywords = array_filter(explode(' ', $criteria['keyword']), fn($word) => strlen($word) > 2);
            $expr = $qb->expr()->orX();
            foreach ($keywords as $word) {
                $expr->add(
                    $qb->expr()->like('usuarioOutroParticipante.nome', "'%$word%'")
                );
                $expr->add(
                    $qb->expr()->like('c.nome', "'%$word%'")
                );
            }
            $qb->andWhere($expr)
                ->andWhere(
                    $qb->expr()->neq('usuarioOutroParticipante.id', 'usuarioParticipante.id')
                );
        }

        if ($limit > 100) {
            $limit = 100;
        }

        $qb->setMaxResults($limit ?? 10)
            ->setFirstResult($offset ?? 0);

        $paginator = new Paginator($qb, true);

        $entities = $paginator->getIterator()->getArrayCopy();
        $count = $paginator->count();

        return [
            'entities' => $entities,
            'total' => $count,
        ];
    }

    /**
     * Retorna a contagem do total de mensagens não lidas dos chats do usuário
     * $criteria | ['usuarioParticipante' => (int) usuario_id]
     * @param array $criteria
     * @return int
     */
    public function countMensagensNaoLidas(array $criteria): int
    {

        $qb = $this->createQueryBuilder('chat');
        $qb
            ->distinct()
            ->select($qb->expr()->count('chat.id'))
            ->innerJoin('chat.mensagens', 'mensagens')
            ->innerJoin('mensagens.usuario', 'usuarioMensagem')
            ->innerJoin('chat.participantes', 'participantes')
            ->innerJoin('participantes.usuario', 'usuarioParticipante')
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->gt('mensagens.criadoEm', 'participantes.ultimaVisualizacao'),
                    $qb->expr()->isNull('participantes.ultimaVisualizacao')
                )
            )
            ->andWhere(
                $qb->expr()->eq('usuarioParticipante.id', ':usuarioParticipante')
            )
            ->andWhere(
                $qb->expr()->neq('usuarioMensagem.id', 'usuarioParticipante.id')
            )
            ->setParameters($criteria);

        $qb->setMaxResults(1);

        try {
            $result = $qb->getQuery()->getSingleScalarResult();
        } catch (Throwable $e) {
            $result = 0;
        }

        return (int) $result;

    }
}
