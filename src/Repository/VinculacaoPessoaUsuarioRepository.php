<?php

declare(strict_types=1);
/**
 * /src/Repository/VinculacaoPessoaUsuarioRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\VinculacaoPessoaUsuario as Entity;

/**
 * Class VinculacaoPessoaUsuarioRepository.
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
class VinculacaoPessoaUsuarioRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = \SuppCore\AdministrativoBackend\Entity\VinculacaoPessoaUsuario::class;

    /**
     * Realiza uma busca por pessoa e usuario que tenham vinculo.
     *
     * @param int $pessoa
     * @param int $usuarioVinculado
     *
     * @return SuppCore\AdministrativoBackend\Entity\VinculacaoUsuarioPessoa|false
     */
    public function findByPessoaAndUsuarioVinculado($pessoa, $usuarioVinculado)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT v 
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoPessoaUsuario v
            JOIN v.pessoa u
            JOIN v.usuarioVinculado uv
            WHERE u.id = :pessoa
            AND uv.id = :usuarioVinculado
            '
        );
        $query->setParameter('pessoa', $pessoa);
        $query->setParameter('usuarioVinculado', $usuarioVinculado);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * Realiza uma busca por usuario vinculado.
     *
     * @param int $usuarioVinculado
     *
     * @return SuppCore\AdministrativoBackend\Entity\VinculacaoUsuarioPessoa|false
     */
    public function findByUsuarioVinculado($usuarioVinculado)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT v 
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoPessoaUsuario v            
            JOIN v.usuarioVinculado uv            
            WHERE uv.id = :usuarioVinculado
            '
        );
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
