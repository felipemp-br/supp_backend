<?php

declare(strict_types=1);
/**
 * /src/Repository/VinculacaoEtiquetaRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta as Entity;

/**
 * Class VinculacaoEtiquetaRepository.
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
class VinculacaoEtiquetaRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param int    $tarefaId
     * @param string $nomeEtiqueta
     *
     * @return bool
     */
    public function findTarefaHasEtiquetaSistemaByNome(int $tarefaId, string $nomeEtiqueta): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta v 
            JOIN v.etiqueta e WITH e.nome = :nomeEtiqueta AND e.sistema = true
            JOIN v.tarefa t WITH t.id = :tarefaId');
        $query->setParameter('tarefaId', $tarefaId);
        $query->setParameter('nomeEtiqueta', $nomeEtiqueta);
        $query->setMaxResults(1);

        return (bool) count($query->getArrayResult());
    }

    /**
     * @param int    $tarefaId
     * @param string $nomeEtiqueta
     *
     * @return VinculacaoEtiqueta[]
     */
    public function findByTarefaIdAndEtiquetaSistemaByNome(int $tarefaId, string $nomeEtiqueta): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta v 
            JOIN v.etiqueta e WITH e.nome = :nomeEtiqueta AND e.sistema = true
            JOIN v.tarefa t WITH t.id = :tarefaId');
        $query->setParameter('tarefaId', $tarefaId);
        $query->setParameter('nomeEtiqueta', $nomeEtiqueta);
        $query->setMaxResults(1);

        return $query->getResult();
    }

    /**
     * @param $etiqueta
     * @param $usuario
     *
     * @return bool
     */
    public function findEtiquetaByUsuarioModalidade($etiqueta, $usuario)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta v
            INNER JOIN v.etiqueta m WITH m.nome = :nomeEtiqueta
            INNER JOIN v.usuario us WITH us.id = :usuario
            INNER JOIN m.modalidadeEtiqueta me WITH me.id = :modalidadeEtiqueta"
        );
        $query->setParameter('modalidadeEtiqueta', $etiqueta->getModalidadeEtiqueta()->getId());
        $query->setParameter('usuario', $usuario);
        $query->setParameter('nomeEtiqueta', $etiqueta->getNome());
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $etiqueta
     * @param $usuario
     *
     * @return bool
     */
    public function findEtiquetaBySetorModalidade($etiqueta, $setor)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta v
            INNER JOIN v.etiqueta m WITH m.nome = :nomeEtiqueta
            INNER JOIN v.setor se WITH se.id = :setor
            INNER JOIN m.modalidadeEtiqueta me WITH me.id = :modalidadeEtiqueta"
        );
        $query->setParameter('modalidadeEtiqueta', $etiqueta->getModalidadeEtiqueta()->getId());
        $query->setParameter('setor', $setor);
        $query->setParameter('nomeEtiqueta', $etiqueta->getNome());
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $etiqueta
     * @param $usuario
     *
     * @return bool
     */
    public function findEtiquetaByUnidadeModalidade($etiqueta, $unidade)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta v
            INNER JOIN v.etiqueta m WITH m.nome = :nomeEtiqueta
            INNER JOIN v.unidade un WITH un.id = :unidade
            INNER JOIN m.modalidadeEtiqueta me WITH me.id = :modalidadeEtiqueta"
        );
        $query->setParameter('modalidadeEtiqueta', $etiqueta->getModalidadeEtiqueta()->getId());
        $query->setParameter('unidade', $unidade);
        $query->setParameter('nomeEtiqueta', $etiqueta->getNome());
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $etiqueta
     * @param $usuario
     *
     * @return bool
     */
    public function findEtiquetaByOrgaoCentralModalidade($etiqueta, $orgaoCentral)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta v
            INNER JOIN v.etiqueta m WITH m.nome = :nomeEtiqueta
            INNER JOIN m.modalidadeEtiqueta me WITH me.id = :modalidadeEtiqueta
            INNER JOIN v.modalidadeOrgaoCentral un WITH un.id = :orgaoCentral"
        );
        $query->setParameter('modalidadeEtiqueta', $etiqueta->getModalidadeEtiqueta()->getId());
        $query->setParameter('orgaoCentral', $orgaoCentral);
        $query->setParameter('nomeEtiqueta', $etiqueta->getNome());
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param array $tarefasId
     *
     * @return array
     */
    public function findByUuidByTarefaId(array $tarefasId): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT v.uuid AS vinculacaoEtiquetaUuid, t.id AS tarefaId
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta v 
            INNER JOIN v.tarefa t WITH t.id IN (:tarefasId)');
        $query->setParameter('tarefasId', $tarefasId);
        return $query->getArrayResult();
    }

    /**
     * @param $etiquetaId
     *
     * @return VinculacaoEtiqueta
     */
    public function findVinculacaoEtiqueta($etiquetaId): VinculacaoEtiqueta
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta v
            INNER JOIN v.etiqueta e WITH e.id = :etiquetaId
            WHERE v.documento is NULL 
            AND v.processo is NULL
            AND v.relatorio is NULL
            AND v.documentoAvulso is NULL
            AND v.tarefa is NULL
            ORDER BY e.id ASC"
        );
        $query->setParameter('etiquetaId', $etiquetaId);
        $query->setMaxResults(1);
        return $query->getResult()[0];
    }

    /**
     * @param $etiquetasIds
     * @return void
     */
    public function deleteInVinculacoesEtiqueta($etiquetasIds)
    {
        $queryBuilder = $this->createQueryBuilder('v');
        $queryBuilder->delete()
            ->where($queryBuilder->expr()->in('v.id', ':ids'))
            ->setParameter('ids', $etiquetasIds)
            ->getQuery()
            ->execute();
    }
}
