<?php

declare(strict_types=1);
/**
 * /src/Repository/EspecieProcessoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\EspecieProcesso as Entity;

/**
 * Class EspecieProcessoRepository.
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
class EspecieProcessoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param $nome
     * @param $genero
     *
     * @return bool
     */
    public function findByNomeAndGenero($nome, $genero)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT e
            FROM SuppCore\AdministrativoBackend\Entity\EspecieProcesso e
            INNER JOIN e.generoProcesso g WITH g.nome = :genero
            WHERE e.nome = :nome'
        );
        $query->setParameter('nome', $nome);
        $query->setParameter('genero', $genero);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }
}
