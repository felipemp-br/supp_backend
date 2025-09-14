<?php

declare(strict_types=1);
/**
 * /src/Repository/EnderecoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\Endereco as Entity;

/**
 * Class EnderecoRepository.
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
class EnderecoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param $pessoa
     *
     * @return int|Entity
     */
    public function findPrincipalByPessoa($pessoa)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT e
            FROM SuppCore\AdministrativoBackend\Entity\Endereco e
            INNER JOIN e.pessoa p WITH p.id = :pessoa
            WHERE e.principal = true'
        );
        $query->setParameter('pessoa', $pessoa);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return 0;
        }
    }

    /**
     * @param $pessoa
     * @param string $fonteDados
     * @return mixed
     */
    public function findByPessoaAndFonteDados($pessoa, $fonteDados): mixed
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT e
            FROM SuppCore\AdministrativoBackend\Entity\Endereco e
            INNER JOIN e.pessoa p WITH p.id = :pessoa
            INNER JOIN e.origemDados o WITH o.fonteDados = :fonteDados'
        );
        $query->setParameter('pessoa', $pessoa);
        $query->setParameter('fonteDados', $fonteDados);
        return $query->getResult();
    }
}
