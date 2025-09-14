<?php

declare(strict_types=1);
/**
 * /src/Repository/VinculacaoModeloRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\VinculacaoModelo as Entity;

/**
 * Class VinculacaoModeloRepository.
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
class VinculacaoModeloRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param $modelo
     * @param $especieSetor
     *
     * @return bool
     */
    public function findByModeloEspecieSetor($modelo, $especieSetor)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoModelo v
            INNER JOIN v.modelo m WITH m.id = :modelo
            INNER JOIN v.especieSetor es WITH es.id = :especieSetor"
        );
        $query->setParameter('modelo', $modelo);
        $query->setParameter('especieSetor', $especieSetor);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $modelo
     * @param $usuario
     *
     * @return bool
     */
    public function findModeloByUsuarioTemplate($modelo, $usuario)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoModelo v
            INNER JOIN v.modelo m WITH m.nome = :nomeModelo
            INNER JOIN m.template t WITH t.id = :template
            INNER JOIN v.usuario us WITH us.id = :usuario"
        );
        $query->setParameter('template', $modelo->getTemplate()->getId());
        $query->setParameter('usuario', $usuario);
        $query->setParameter('nomeModelo', $modelo->getNome());
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $modelo
     * @param $usuario
     *
     * @return bool
     */
    public function findModeloBySetorTemplate($modelo, $setor)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoModelo v
            INNER JOIN v.modelo m WITH m.nome = :nomeModelo
            INNER JOIN m.template t WITH t.id = :template
            INNER JOIN v.setor se WITH se.id = :setor"
        );
        $query->setParameter('template', $modelo->getTemplate()->getId());
        $query->setParameter('setor', $setor);
        $query->setParameter('nomeModelo', $modelo->getNome());
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $modelo
     * @param $usuario
     *
     * @return bool
     */
    public function findModeloByUnidadeTemplate($modelo, $unidade)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoModelo v
            INNER JOIN v.modelo m WITH m.nome = :nomeModelo
            INNER JOIN m.template t WITH t.id = :template
            INNER JOIN v.unidade un WITH un.id = :unidade"
        );
        $query->setParameter('template', $modelo->getTemplate()->getId());
        $query->setParameter('unidade', $unidade);
        $query->setParameter('nomeModelo', $modelo->getNome());
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param int $modeloId
     * @param int $especieSetorId
     * @param int $unidadeId
     * @return mixed
     */
    public function findByModeloIdEspecieSetorIdUnidadeId(int $modeloId, int $especieSetorId, int $unidadeId): mixed
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoModelo v
            INNER JOIN v.modelo m WITH m.id = :modeloId
            INNER JOIN v.especieSetor es WITH es.id = :especieSetorId
            INNER JOIN v.unidade un WITH un.id = :unidadeId"
        );
        $query->setParameter('modeloId', $modeloId);
        $query->setParameter('unidadeId', $unidadeId);
        $query->setParameter('especieSetorId', $especieSetorId);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param int $modeloId
     * @param int $especieSetorId
     * @param int $modalidadeOrgaoCentralId
     * @return mixed
     */
    public function findByModeloIdEspecieSetorIdModalidadeOrgaoCentralId(
        int $modeloId,
        int $especieSetorId,
        int $modalidadeOrgaoCentralId
    ): mixed {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoModelo v
            INNER JOIN v.modelo m WITH m.id = :modeloId
            INNER JOIN v.especieSetor es WITH es.id = :especieSetorId
            INNER JOIN v.modalidadeOrgaoCentral un WITH un.id = :modalidadeOrgaoCentralId"
        );
        $query->setParameter('modeloId', $modeloId);
        $query->setParameter('modalidadeOrgaoCentralId', $modalidadeOrgaoCentralId);
        $query->setParameter('especieSetorId', $especieSetorId);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $modelo
     * @param $usuario
     *
     * @return bool
     */
    public function findModeloByOrgaoCentralTemplate($modelo, $orgaoCentral)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoModelo v
            INNER JOIN v.modelo m WITH m.nome = :nomeModelo
            INNER JOIN m.template t WITH t.id = :template
            INNER JOIN v.modalidadeOrgaoCentral un WITH un.id = :orgaoCentral"
        );
        $query->setParameter('template', $modelo->getTemplate()->getId());
        $query->setParameter('orgaoCentral', $orgaoCentral);
        $query->setParameter('nomeModelo', $modelo->getNome());
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }
}
