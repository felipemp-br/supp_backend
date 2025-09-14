<?php
/**
 * @noinspection LongLine
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use SuppCore\AdministrativoBackend\Entity\Dossie;
use SuppCore\AdministrativoBackend\Entity\Dossie as DossieEntity;
use SuppCore\AdministrativoBackend\Entity\Pessoa as PessoaEntity;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\TipoDossie as TipoDossieEntity;
use function count;

/**
 * Class DossieRepository.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 * @codingStandardsIgnoreStart
 *
 * @method DossieEntity|null find(int $id, string $lockMode = null, string $lockVersion = null)
 * @method DossieEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method DossieEntity[]    findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null)
 * @method DossieEntity[]    findByAdvanced(array $criteria, array $orderBy = null, int $limit = null, int $offset = null, array $search = null): array
 * @method DossieEntity[] findAll()
 *
 * @codingStandardsIgnoreEnd
 */
class DossieRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = DossieEntity::class;

    /**
     * @param $documentoPrincipal
     *
     * @return DossieEntity|false
     * @noinspection PhpUnused
     */
    public function findDossieByDocumentoPrincipal($documentoPrincipal): DossieEntity | false
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c 
            FROM SuppCore\AdministrativoBackend\Entity\Dossie dp 
            INNER JOIN dp.pessoa p WITH p.numero_doc_principal = :documentoPrincipal'
        );

        $query->setParameter('documentoPrincipal', $documentoPrincipal);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        }

        return false;
    }

    /**
     * @param TipoDossieEntity $tipoDossie
     * @param PessoaEntity $pessoa
     * @param ProcessoEntity $processo
     * @return Dossie|null
     */
    public function findMostRecent(TipoDossieEntity $tipoDossie, PessoaEntity $pessoa, ProcessoEntity $processo): ?Dossie
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT dp
            FROM   SuppCore\AdministrativoBackend\Entity\Dossie dp 
            WHERE  dp.tipoDossie = :tipoDossie AND dp.pessoa = :pessoa AND dp.processo = :processo AND 
                   dp.dataConsulta IS NOT NULL AND dp.conteudo IS NOT NULL AND dp.documento IS NOT NULL 
            ORDER BY dp.dataConsulta DESC '
        );
        $query->setParameter('processo',    $processo->getId());
        $query->setParameter('tipoDossie',  $tipoDossie->getId());
        $query->setParameter('pessoa',      $pessoa->getId());
        $query->setMaxResults(1);

        /** @noinspection PhpAssignmentInConditionInspection */
        return ($dossies = $query->getResult()) ? $dossies[0] : null;
    }

    /**
     * @param TipoDossieEntity $tipoDossie
     * @param PessoaEntity $pessoa
     * @return Dossie|null
     */
    public function findSimilarMostRecent(TipoDossieEntity $tipoDossie, PessoaEntity $pessoa): ?Dossie
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT dp
            FROM   SuppCore\AdministrativoBackend\Entity\Dossie dp 
            WHERE  dp.tipoDossie = :tipoDossie AND dp.pessoa = :pessoa AND 
                   dp.dataConsulta IS NOT NULL and dp.conteudo IS NOT NULL AND dp.documento IS NOT NULL 
            ORDER BY dp.dataConsulta DESC '
        );

        $query->setParameter('tipoDossie',  $tipoDossie->getId());
        $query->setParameter('pessoa',      $pessoa->getId());
        $query->setMaxResults(1);

        /** @noinspection PhpAssignmentInConditionInspection */
        return ($dossies = $query->getResult()) ? $dossies[0] : null;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findLastIdByTipoDossie(string $tipoDossie): int|string|null
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT MAX(d.id) 
                FROM SuppCore\AdministrativoBackend\Entity\Dossie d 
                INNER JOIN d.tipoDossie t 
                WHERE t.nome = :tipoDossie
                AND d.conteudo IS NULL'
            )
            ->setParameter('tipoDossie', $tipoDossie)
            ->getSingleScalarResult();
    }
}
