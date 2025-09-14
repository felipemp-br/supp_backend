<?php

declare(strict_types=1);
/**
 * /src/Repository/ModeloRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\Modelo as Entity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoModelo;

/**
 * Class ModeloRepository.
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
class ModeloRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * Busca Modelos pelo Setor.
     *
     * @param int $setorId
     *
     * @return VinculacaoModelo[]
     */
    public function findModelosBySetor(int $setorId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT vm
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoModelo vm
            INNER JOIN vm.setor s
            WHERE s.id = :setorId'
        );
        $query->setParameter('setorId', $setorId);
        $result = $query->getResult();

        return $result;
    }

    /**
     * Busca Modelos pela Unidade.
     *
     * @param int $unidadeId
     *
     * @return VinculacaoModelo[]
     */
    public function findModelosByUnidade(int $unidadeId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT vm
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoModelo vm
            LEFT JOIN vm.setor s
            WHERE ((vm.unidade =:unidadeId) OR (s.unidade =:unidadeId))'
        );
        $query->setParameter('unidadeId', $unidadeId);
        $result = $query->getResult();

        return $result;
    }

    /**
     * Busca Modelos pelo OrgaoCentral.
     *
     * @param int $orgaoCentralId
     *
     * @return VinculacaoModelo[]
     */
    public function findModelosByOrgaoCentral(int $orgaoCentralId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT vm
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoModelo vm
            LEFT JOIN vm.setor s
            LEFT JOIN vm.unidade u
            WHERE ((vm.modalidadeOrgaoCentral =:orgaoCentralId))'
        );
        $query->setParameter('orgaoCentralId', $orgaoCentralId);
        $result = $query->getResult();

        return $result;
    }

    /**
     * @param int $documentoId
     *
     * @return bool
     */
    public function findHasModeloByDocumento(int $documentoId): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT m
            FROM SuppCore\AdministrativoBackend\Entity\Modelo m
            INNER JOIN m.documento d WITH d.id = :documentoId'
        );
        $query->setParameter('documentoId', $documentoId);

        return (bool) count($query->getResult());
    }
}
