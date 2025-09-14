<?php

declare(strict_types=1);
/**
 * /src/Repository/VinculacaoEspecieProcessoWorkflowRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use Doctrine\Persistence\ManagerRegistry;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEspecieProcessoWorkflow as Entity;
use SuppCore\AdministrativoBackend\QueryBuilder\ArrayQueryBuilder;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;

/**
 * Class VinculacaoEspecieProcessoWorkflowRepository.
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
class VinculacaoEspecieProcessoWorkflowRepository extends BaseRepository
{

    protected static string $entityName = Entity::class;

    public function __construct(ManagerRegistry $managerRegistry,
                                ArrayQueryBuilder $arrayQueryBuilder,
                                TransactionManager $transactionManager)
    {
        parent::__construct($managerRegistry, $arrayQueryBuilder, $transactionManager);
    }
}
