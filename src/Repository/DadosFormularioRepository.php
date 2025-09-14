<?php

declare(strict_types=1);
/**
 * /src/Repository/DadosFormularioRepository.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\DadosFormulario as Entity;
use SuppCore\AdministrativoBackend\Entity\Documento;

/**
 * Class DadosFormularioRepository.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br> *
 * @codingStandardsIgnoreStart
 *
 * @method Entity|null find(int $id, string $lockMode = null, string $lockVersion = null)
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entity[]    findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null)
 * @method Entity[]    findByAdvanced(array $criteria, array $orderBy = null, int $limit = null, int $offset = null, array $search = null) : array
 * @method Entity[]    findAll()
 *
 * @codingStandardsIgnoreEnd
 */
class DadosFormularioRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param string $sigla
     * @param Documento $documento
     *
     * @return Entity|null
     */
    public function findOneBySiglaFormularioDocumento(string $sigla, Documento $documento): ?Entity
    {
        $qb = $this->createQueryBuilder('df');

        $qb->select('df')
            ->join('df.formulario', 'f')
            ->where($qb->expr()->eq('f.sigla', ':sigla'))
            ->andWhere($qb->expr()->eq('df.documento', ':documento'))
            ->setParameter('sigla', $sigla)
            ->setParameter('documento', $documento);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param int $formulario
     * @param int $documento
     *
     * @return int|null
     */
    public function findAnswersByFormularioDocumento(int $formulario, int $documento): ?int
    {
        $qb = $this->createQueryBuilder('df');

        $qb->select('COUNT(df)')
            ->where($qb->expr()->eq('df.formulario', ':id'))
            ->andWhere($qb->expr()->eq('df.documento', ':documento'))
            ->setParameter('id', $formulario)
            ->setParameter('documento', $documento);

        return (int) $qb->getQuery()->getSingleScalarResult() ?? 0;
    }
}
