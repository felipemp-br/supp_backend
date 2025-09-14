<?php

declare(strict_types=1);
/**
 * /src/Repository/InteressadoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\Interessado as Entity;

/**
 * Class InteressadoRepository.
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
class InteressadoRepository extends BaseRepository
{
    protected static string $entityName = Entity::class;

    /**
     * @param $pasta
     * @param $fonteDados
     * @return mixed
     */
    public function findByProcessoAndFonteDados($pasta, $fonteDados): mixed
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT i
            FROM SuppCore\AdministrativoBackend\Entity\Interessado i
            LEFT JOIN i.pasta p
            LEFT JOIN i.pessoa pe
            LEFT JOIN i.modalidadeInteressado m
            LEFT JOIN i.origemDados o
            WHERE p.id = :pasta
            AND o.fonteDados = :fonteDados'
        );
        $query->setParameter('pasta', $pasta);
        $query->setParameter('fonteDados', $fonteDados);

        return $query->getResult();
    }
}
