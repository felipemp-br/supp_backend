<?php

declare(strict_types=1);
/**
 * /src/Repository/EspecieSetorRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\EspecieSetor as Entity;

/**
 * Class EspecieSetorRepository.
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
class EspecieSetorRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param string $nome
     *
     * @return int
     */
    public function findByNome(string $nome)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT e 
            FROM SuppCore\AdministrativoBackend\Entity\EspecieSetor e
            WHERE e.nome = :nome'
        );
        $query->setParameter('nome', $nome);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return 0;
        }
    }
}
