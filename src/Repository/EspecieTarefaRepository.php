<?php

declare(strict_types=1);

/**
 * /src/Repository/EspecieTarefaRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\EspecieTarefa as Entity;

use function count;

/**
 * Class EspecieTarefaRepository.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br> *
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
class EspecieTarefaRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param $nome
     * @param $genero
     *
     * @return Entity|bool
     */
    public function findByNomeAndGenero($nome, $genero): Entity|bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT e
            FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa e
            INNER JOIN e.generoTarefa g WITH g.nome = :genero
            WHERE e.nome = :nome'
        );
        $query->setParameter('nome', $nome);
        $query->setParameter('genero', $genero);
        $query->setMaxResults(1);
        $result = $query->getResult();

        if (count($result) > 0) {
            return $result[0];
        }

        return false;
    }
}
