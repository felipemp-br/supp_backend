<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Form\ChoiceList;


use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\ChoiceList\ORMQueryBuilderLoader as SymfonyORMQueryBuilderLoader;

/**
 * ORMQueryBuilderLoader.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ORMQueryBuilderLoader extends SymfonyORMQueryBuilderLoader
{
    private const int IN_CHUNK_SIZE = 500;

    /**
     * Constructor.
     *
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(
        protected readonly QueryBuilder $queryBuilder,
    ) {
        parent::__construct($this->queryBuilder);
    }

    public function getEntitiesByIds(string $identifier, array $values): array
    {
        if (!$values) {
            return [];
        }
        $qb = clone $this->queryBuilder;
        $alias = current($qb->getRootAliases());
        $chunks = array_chunk(
            $values,
            self::IN_CHUNK_SIZE
        );
        foreach ($chunks as $chunk) {
            $qb->orWhere(
                $qb->expr()->in($alias.'.'.$identifier, $chunk)
            );
        }

        return $qb->getQuery()
            ->getResult();
    }
}
