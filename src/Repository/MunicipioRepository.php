<?php

declare(strict_types=1);
/**
 * /src/Repository/MunicipioRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\Municipio as Entity;

/**
 * Class MunicipioRepository.
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
class MunicipioRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param string $nome
     * @param string $uf
     *
     * @return bool | Entity
     */
    public function findByNomeAndUf(string $nome, string $uf)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT m, e
            FROM SuppCore\AdministrativoBackend\Entity\Municipio m
            JOIN m.estado e 
            WHERE m.nome = :nome
            AND e.uf = :uf'
        );
        $query->setParameter('nome', $nome);
        $query->setParameter('uf', $uf);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }
}
