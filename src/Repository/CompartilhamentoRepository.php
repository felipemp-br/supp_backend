<?php

declare(strict_types=1);
/**
 * /src/Repository/CompartilhamentoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\Compartilhamento as Entity;

/**
 * Class CompartilhamentoRepository.
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
class CompartilhamentoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param $tarefa
     * @param $usuario
     *
     * @return bool
     */
    public function findByTarefaAndUsuario($tarefa, $usuario)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT a 
            FROM SuppCore\AdministrativoBackend\Entity\Compartilhamento a 
            INNER JOIN a.tarefa t WITH t.id = :tarefa
            INNER JOIN a.usuario u WITH u.id = :usuario'
        );
        $query->setParameter('tarefa', $tarefa);
        $query->setParameter('usuario', $usuario);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (0 == count($result)) {
            return false;
        } else {
            return $result[0];
        }
    }

    /**
     * @param $processo
     * @param $usuario
     * @return false|mixed
     */
    public function findByProcessoAndUsuario($processo, $usuario)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT a 
            FROM SuppCore\AdministrativoBackend\Entity\Compartilhamento a 
            INNER JOIN a.processo t WITH t.id = :processo
            INNER JOIN a.usuario u WITH u.id = :usuario
            '
        );
        $query->setParameter('processo', $processo);
        $query->setParameter('usuario', $usuario);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (0 == count($result)) {
            return false;
        } else {
            return $result[0];
        }
    }

    /**
     * @param $processo
     *
     * @return int|mixed|string
     */
    public function findUsuarioByProcesso($processo)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT a 
            FROM SuppCore\AdministrativoBackend\Entity\Compartilhamento a 
            INNER JOIN a.processo t WITH t.id = :processo
            '
        );
        $query->setParameter('processo', $processo);
        return $result = $query->getResult();
    }
}
