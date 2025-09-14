<?php

declare(strict_types=1);
/**
 * /src/Repository/SetorRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use Doctrine\Persistence\ManagerRegistry;
use SuppCore\AdministrativoBackend\Entity\EspecieSetor;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Setor as Entity;
use SuppCore\AdministrativoBackend\QueryBuilder\ArrayQueryBuilder;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class SetorRepository.
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
class SetorRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    public function __construct(
        ManagerRegistry $managerRegistry,
        ArrayQueryBuilder $arrayQueryBuilder,
        TransactionManager $transactionManager,
        private ParameterBagInterface $parameterBag
    ) {
        parent::__construct($managerRegistry, $arrayQueryBuilder, $transactionManager);
    }

    /**
     * Busca por um arquivo no Setor pelo id da unidade.
     *
     * @param int $unidade
     *
     * @return Setor|bool
     */
    public function findArquivoInUnidade($unidade): ?Setor
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT s
            FROM SuppCore\AdministrativoBackend\Entity\Setor s
            JOIN s.unidade u
            JOIN s.especieSetor e
            WHERE e.nome = :especieSetor
            AND u.id = :unidade
        "
        );
        $query->setParameter('unidade', $unidade);
        $query->setParameter(
            'especieSetor',
            $this->parameterBag->get('constantes.entidades.especie_setor.const_2')
        );
        $query->setMaxResults(1);
        $result = $query->getResult();

        return count($result) ? $result[0] : null;
    }

    /**
     * Busca por um arquivo no Setor pela sigla da unidade.
     *
     * @param string $siglaUnidade
     *
     * @return Setor|bool
     */
    public function findArquivoInUnidadeBySigla(string $siglaUnidade): ?Setor
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT s
            FROM SuppCore\AdministrativoBackend\Entity\Setor s
            JOIN s.unidade u
            JOIN s.especieSetor e
            WHERE e.nome = :especieSetor
            AND u.sigla = :siglaUnidade
        "
        );
        $query->setParameter('siglaUnidade', $siglaUnidade);
        $query->setParameter(
            'especieSetor',
            $this->parameterBag->get('constantes.entidades.especie_setor.const_2')
        );
        $query->setMaxResults(1);
        $result = $query->getResult();

        return count($result) ? $result[0] : null;
    }

    /**
     * Busca por um arquivo no Setor pelo id da unidade.
     *
     * @param int $unidade
     *
     * @return Setor|bool
     */
    public function findProtocoloInUnidade($unidade): ?Setor
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT s
            FROM SuppCore\AdministrativoBackend\Entity\Setor s
            JOIN s.unidade u
            JOIN s.especieSetor e
            WHERE e.nome = :especieSetor
            AND u.id = :unidade
        "
        );
        $query->setParameter('unidade', $unidade);
        $query->setParameter(
            'especieSetor',
            $this->parameterBag->get('constantes.entidades.especie_setor.const_1')
        );
        $query->setMaxResults(1);
        $result = $query->getResult();

        return count($result) ? $result[0] : null;
    }

    /**
     * Realiza uma busca por setores filhos que estejam ativos.
     *
     * @param string $setor
     *
     * @return Setor[]|bool
     */
    public function findFilhos($setor): ?array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT s
            FROM SuppCore\AdministrativoBackend\Entity\Setor s
            WHERE s.parent = :setor AND s.ativo = true'
        );
        $query->setParameter('setor', $setor);
        $result = $query->getResult();

        return count($result) ? $result : null;
    }

    /**
     * @param Setor        $setor
     * @param EspecieSetor $especieSetor
     *
     * @return bool|int
     */
    public function hasChildEspecieSetor(Setor $setor, EspecieSetor $especieSetor)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
        SELECT count(s)
        FROM SuppCore\AdministrativoBackend\Entity\Setor s
        INNER JOIN s.children c
        WHERE s.id = :setor 
        AND c.especieSetor = :especieSetor'
        );
        $query->setParameter('setor', $setor->getId());
        $query->setParameter('especieSetor', $especieSetor);
        $result = $query->getResult();

        if (count($result) > 0) {
            return $result;
        }

        return false;
    }
}
