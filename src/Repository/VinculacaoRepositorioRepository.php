<?php

declare(strict_types=1);
/**
 * /src/Repository/VinculacaoRepositorioRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\VinculacaoRepositorio as Entity;

/**
 * Class VinculacaoRepositorioRepository.
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
class VinculacaoRepositorioRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;


    /**
     * @param $repositorio
     * @param $especieSetor
     *
     * @return bool
     */
    public function findByRepositorioEspecieSetor($repositorio, $especieSetor)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoRepositorio v
            INNER JOIN v.repositorio m WITH m.id = :repositorio
            INNER JOIN v.especieSetor es WITH es.id = :especieSetor"
        );
        $query->setParameter('repositorio', $repositorio);
        $query->setParameter('especieSetor', $especieSetor);
        $query->setMaxResults(1);
        $result = $query->getArrayResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $repositorio
     * @param $usuario
     *
     * @return bool
     */
    public function findRepositorioByUsuarioModalidade($repositorio, $usuario)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoRepositorio v
            INNER JOIN v.repositorio m WITH m.nome = :nomeRepositorio
            INNER JOIN m.modalidadeRepositorio mr WITH mr.id = :modalidadeRepositorio
            INNER JOIN v.usuario us WITH us.id = :usuario"
        );
        $query->setParameter('modalidadeRepositorio', $repositorio->getModalidadeRepositorio()->getId());
        $query->setParameter('usuario', $usuario);
        $query->setParameter('nomeRepositorio', $repositorio->getNome());
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $repositorio
     * @param $usuario
     *
     * @return bool
     */
    public function findRepositorioBySetorModalidade($repositorio, $setor)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoRepositorio v
            INNER JOIN v.repositorio m WITH m.nome = :nomeRepositorio
            INNER JOIN m.modalidadeRepositorio mr WITH mr.id = :modalidadeRepositorio
            INNER JOIN v.setor se WITH se.id = :setor"
        );
        $query->setParameter('modalidadeRepositorio', $repositorio->getModalidadeRepositorio()->getId());
        $query->setParameter('setor', $setor);
        $query->setParameter('nomeRepositorio', $repositorio->getNome());
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $repositorio
     * @param $usuario
     *
     * @return bool
     */
    public function findRepositorioByUnidadeModalidade($repositorio, $unidade)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoRepositorio v
            INNER JOIN v.repositorio m WITH m.nome = :nomeRepositorio
            INNER JOIN m.modalidadeRepositorio mr WITH mr.id = :modalidadeRepositorio
            INNER JOIN v.unidade un WITH un.id = :unidade"
        );
        $query->setParameter('modalidadeRepositorio', $repositorio->getModalidadeRepositorio()->getId());
        $query->setParameter('unidade', $unidade);
        $query->setParameter('nomeRepositorio', $repositorio->getNome());
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $repositorio
     * @param $usuario
     *
     * @return bool
     */
    public function findRepositorioByOrgaoCentralModalidade($repositorio, $orgaoCentral)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoRepositorio v
            INNER JOIN v.repositorio m WITH m.nome = :nomeRepositorio
            INNER JOIN m.modalidadeRepositorio mr WITH mr.id = :modalidadeRepositorio
            INNER JOIN v.modalidadeOrgaoCentral un WITH un.id = :orgaoCentral"
        );
        $query->setParameter('modalidadeRepositorio', $repositorio->getModalidadeRepositorio()->getId());
        $query->setParameter('orgaoCentral', $orgaoCentral);
        $query->setParameter('nomeRepositorio', $repositorio->getNome());
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param $repositorio
     * @param $usuario
     *
     * @return bool
     */
    public function findIsRepositorioOrgaoCentral($repositorioId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
            SELECT v
            FROM SuppCore\AdministrativoBackend\Entity\VinculacaoRepositorio v
            INNER JOIN v.repositorio r WITH r.id = :idRepositorio
            WHERE v.modalidadeOrgaoCentral IS NOT NULL"
        );
        $query->setParameter('idRepositorio', $repositorioId);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }
}
