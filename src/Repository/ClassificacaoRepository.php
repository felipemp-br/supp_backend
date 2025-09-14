<?php

declare(strict_types=1);
/**
 * /src/Repository/ClassificacaoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\Classificacao as Entity;

/**
 * Class ClassificacaoRepository.
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
class ClassificacaoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param int $classificacaoId
     *
     * @return bool
     */
    public function hasProcessoAtivo(int $classificacaoId): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
                SELECT p
                FROM SuppCore\AdministrativoBackend\Entity\Processo p
                INNER JOIN p.modalidadeFase mf WITH (mf.valor = 'CORRENTE' OR mf.valor = 'INTERMEDIÁRIA')
                INNER JOIN p.classificacao c WITH c.id = :classificacaoId"
        );
        $query->setParameter('classificacaoId', $classificacaoId);
        $query->setMaxResults(1);

        return (bool) count($query->getResult());
    }

    /**
     * Não pode apagar se tiver uma classificação filho.
     * 
     * @param int $classificacaoId
     *
     * @return bool
     */
    public function hasClassificacaoFilho(int $classificacaoId): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
                SELECT c
                FROM SuppCore\AdministrativoBackend\Entity\Classificacao c
                WHERE c.parent = :classificacaoId"
        );
        $query->setParameter('classificacaoId', $classificacaoId);
        $query->setMaxResults(1);

        return (bool) count($query->getResult());
    }
}
