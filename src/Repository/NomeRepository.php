<?php

declare(strict_types=1);
/**
 * /src/Repository/NomeRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\Nome;
use SuppCore\AdministrativoBackend\Entity\Nome as Entity;

/**
 * Class NomeRepository.
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
class NomeRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * Realiza uma consulta na entidade Nome buscando por pessoa e fonteDados.
     *
     * @param $pessoa
     * @param $fonteDados
     * @return Nome[]|false
     */
    public function findByPessoaAndFonteDados($pessoa, $fonteDados): array|bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT n
            FROM SuppCore\AdministrativoBackend\Entity\Nome n
            INNER JOIN n.pessoa p WITH p.id = :pessoa
            INNER JOIN n.origemDados o WITH o.fonteDados = :fonteDados'
        );
        $query->setParameter('pessoa', $pessoa);
        $query->setParameter('fonteDados', $fonteDados);
        return $query->getResult();
    }

    /**
     * Realiza uma consulta na entidade Nome buscando por pessoa e valor.
     *
     * @param string $valor
     * @param int $pessoa
     * @return Entity|false
     */
    public function findByValor($valor, $pessoa): bool|Entity
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT n
            FROM SuppCore\AdministrativoBackend\Entity\Nome n
            INNER JOIN n.pessoa p WITH p.id = :pessoa
            WHERE n.valor = :valor'
        );
        $query->setParameter('valor', $valor);
        $query->setParameter('pessoa', $pessoa);
        $query->setMaxResults(1);

        $result = $query->getResult();
        if (count($result) == 0) {
            return false;
        } else {
            return $result[0];
        }
    }
}
