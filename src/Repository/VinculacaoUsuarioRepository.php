<?php

declare(strict_types=1);
/**
 * /src/Repository/VinculacaoUsuarioRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\VinculacaoUsuario as Entity;

/**
 * Class VinculacaoUsuarioRepository.
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
class VinculacaoUsuarioRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * Realiza uma busca por usuario e usuario que tenham vinculo.
     *
     * @param int $usuario
     * @param int $usuarioVinculado
     *
     * @return SuppCore\AdministrativoBackend\Entity\VinculacaoUsuarioPessoa|false
     */
    public function findByUsuarioAndUsuarioVinculado($usuario, $usuarioVinculado)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT v 
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoUsuario v
            JOIN v.usuario u
            JOIN v.usuarioVinculado uv
            WHERE u.id = :usuario
            AND uv.id = :usuarioVinculado
            '
        );
        $query->setParameter('usuario', $usuario);
        $query->setParameter('usuarioVinculado', $usuarioVinculado);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }
}
