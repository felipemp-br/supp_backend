<?php

declare(strict_types=1);
/**
 * /src/Repository/FolderRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\Folder as Entity;

/**
 * Class FolderRepository.
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
class FolderRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param $usuarioId
     *
     * @return Entity[]
     */
    public function findTarefaByUsuarioId($usuarioId): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT f 
            FROM SuppCore\AdministrativoBackend\Entity\Folder f 
            INNER JOIN f.usuario u WITH u.id = :usuarioId
            INNER JOIN f.modalidadeFolder mf WITH mf.valor = :modalidadeFolder'
        );

        $query->setParameter('usuarioId', $usuarioId);
        $query->setParameter('modalidadeFolder', 'TAREFA');
        return $query->getResult();
    }
}
