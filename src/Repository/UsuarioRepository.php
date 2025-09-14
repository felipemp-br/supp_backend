<?php

declare(strict_types=1);
/**
 * /src/Repository/UsuarioRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use Doctrine\ORM\NonUniqueResultException;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\Usuario as Entity;
use SuppCore\AdministrativoBackend\Repository\Traits\FindUserByUsernameOrEmailTrait;

/**
 * Class UsuarioRepository.
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
class UsuarioRepository extends BaseRepository
{
    // Traits
    use FindUserByUsernameOrEmailTrait;

    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * Names of search columns.
     *
     * @var string[]
     */
    protected static array $searchColumns = ['username', 'nome', 'assinaturaHTML', 'email'];

    /**
     * Method to check if specified username is available or not.
     *
     * @param string   $username
     * @param int|null $id
     *
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function isUsernameAvailable(string $username, ?int $id = null): bool
    {
        return $this->isUnique('username', $username, $id);
    }

    /**
     * Method to check if specified email is available or not.
     *
     * @param string   $email Email to check
     * @param int|null $id    User id to ignore
     *
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function isEmailAvailable(string $email, ?int $id = null): bool
    {
        return $this->isUnique('email', $email, $id);
    }

    /**
     * @param string   $column Column to check
     * @param string   $value  Value of specified column
     * @param int|null $id     User id to ignore
     *
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    private function isUnique(string $column, string $value, ?int $id = null): bool
    {
        // Build query
        $query = $this
            ->createQueryBuilder('u')
            ->select('u')
            ->where('u.'.$column.' = :value')
            ->setParameter('value', $value);

        if (null !== $id) {
            $query
                ->andWhere('u.id <> :id')
                ->setParameter('id', $id);
        }

        return null === $query->getQuery()->getOneOrNullResult();
    }

    /**
     * Realiza uma busca por usuarios de um setor que não estejam afastados.
     *
     * @param int $setor
     *
     * @return Usuario[]
     */
    public function findUsuariosDisponiveisSetor($setor): array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT u 
            FROM SuppCore\AdministrativoBackend\Entity\Usuario u
            INNER JOIN u.colaborador c 
            INNER JOIN c.lotacoes l
            INNER JOIN l.setor s 
            WHERE s.id = :setor AND
            u.enabled = true AND
            l.peso > 0"
        );
        $query->setParameter('setor', $setor);

        return $query->getResult();
    }

    /**
     * Realiza uma busca por usuarios de um setor que não estejam afastados.
     *
     * @param int $setor
     *
     * @return array []
     */
    public function findUsuariosDistribuidoresDisponiveisSetor($setor): array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT u 
            FROM SuppCore\AdministrativoBackend\Entity\Usuario u
            INNER JOIN u.colaborador c 
            INNER JOIN c.lotacoes l
            INNER JOIN l.setor s 
            WHERE s.id = :setor AND
            u.enabled = true AND
            l.peso > 0 AND
            l.distribuidor = true"
        );
        $query->setParameter('setor', $setor);

        return $query->getResult();
    }

    /**
     * Realiza uma busca por usuarios de um setor que não estejam afastados.
     *
     * @param int $setor
     *
     * @return Usuario[]
     */
    public function findUsuariosNaoDistribuidoresDisponiveisSetor($setor): array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT u 
            FROM SuppCore\AdministrativoBackend\Entity\Usuario u
            INNER JOIN u.colaborador c 
            INNER JOIN c.lotacoes l
            INNER JOIN l.setor s 
            WHERE s.id = :setor AND
            u.enabled = true AND
            l.peso > 0 AND
            l.distribuidor = false"
        );
        $query->setParameter('setor', $setor);

        return $query->getResult();
    }

    /**
     * Realiza uma busca por usuarios que sejam coordenadores de um setor que não estejam afastados.
     *
     * @param int $setor
     *
     * @return array []
     */
    public function findUsuariosCoordenadoresDisponiveisSetor(int $setor): array
    {
        $qb = $this->createQueryBuilder('u');

        $qb
            ->join('u.colaborador', 'c')
            ->join('u.coordenadores', 'coord')
            ->join('coord.setor', 's')
            ->andWhere(
                $qb->expr()->eq(
                    's.id',
                    ':setor'
                )
            )
            ->setParameter('setor', $setor);

        return $qb->getQuery()->getResult();
    }

    /**
     * Realiza uma busca por usuarios de um setor que não estejam afastados.
     *
     * @param int $setor
     *
     * @return Usuario[]
     */
    public function findUsuariosBySetor($setor): array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT u 
            FROM SuppCore\AdministrativoBackend\Entity\Usuario u
            INNER JOIN u.colaborador c 
            INNER JOIN c.lotacoes l
            INNER JOIN l.setor s 
            WHERE s.id = :setor"
        );
        $query->setParameter('setor', $setor);

        return $query->getResult();
    }

    /**
     * Realiza uma busca por usuarios de uma unidade que não estejam afastados.
     *
     * @param int $unidade
     *
     * @return Usuario[]
     */
    public function findUsuariosByUnidade(int $unidade): array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT u 
            FROM SuppCore\AdministrativoBackend\Entity\Usuario u
            INNER JOIN u.colaborador c 
            INNER JOIN c.lotacoes l
            INNER JOIN l.setor s 
            INNER JOIN s.unidade u 
            WHERE u.id = :unidade"
        );
        $query->setParameter('unidade', $unidade);

        return $query->getResult();
    }
}
