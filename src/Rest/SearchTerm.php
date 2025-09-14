<?php

declare(strict_types=1);
/**
 * /src/Rest/SearchTerm.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest;

use Closure;

use function array_filter;
use function array_map;
use function array_unique;
use function array_values;
use function count;
use function explode;
use function is_array;
use function mb_strlen;
use function strpos;
use function trim;

/**
 * Class SearchTerm.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
final class SearchTerm implements SearchTermInterface
{
    /**
     * Static method to get search term criteria for specified columns and search terms with specified operand and mode.
     *
     * @param string|string[] $column  search column(s), could be a string or an array of strings
     * @param string|string[] $search  search term(s), could be a string or an array of strings
     * @param string|null     $operand Used operand with multiple search terms. See OPERAND_* constants. Defaults
     *                                 to self::OPERAND_OR
     * @param int|null        $mode    Used mode on LIKE search. See MODE_* constants. Defaults to self::MODE_FULL
     *
     * @return mixed[]|null
     */
    public static function getCriteria($column, $search, ?string $operand = null, ?int $mode = null): ?array
    {
        $operand ??= self::OPERAND_OR;
        $mode ??= self::MODE_FULL;

        $columns = self::getColumns($column);
        $searchTerms = self::getSearchTerms($search);

        // Fallback to OR operand if not supported one given
        if (self::OPERAND_AND !== $operand && self::OPERAND_OR !== $operand) {
            $operand = self::OPERAND_OR;
        }

        return self::createCriteria($columns, $searchTerms, $operand, $mode);
    }

    /**
     * Helper method to create used criteria array with given columns and search terms.
     *
     * @param string[] $columns
     * @param string[] $searchTerms
     *
     * @return mixed[]|null
     */
    private static function createCriteria(array $columns, array $searchTerms, string $operand, int $mode): ?array
    {
        $iteratorTerm = self::getTermIterator($columns, $mode);

        $criteria = array_filter(array_map($iteratorTerm, $searchTerms));

        // Initialize output
        $output = null;

        // We have some generated criteria
        if ([] !== $criteria) {
            // Create used criteria array
            $output = [
                'and' => [
                    $operand => [...array_values($criteria)],
                ],
            ];
        }

        return $output;
    }

    /**
     * Method to get term iterator closure.
     *
     * @param string[] $columns
     */
    private static function getTermIterator(array $columns, int $mode): Closure
    {
        /*
         * Lambda function to process each search term to specified search columns.
         *
         * @param string $term
         *
         * @return array
         */
        return fn (string $term): ?array => count($columns) ? array_map(
            self::getColumnIterator($term, $mode),
            $columns
        ) : null;
    }

    /**
     * Method to get column iterator closure.
     */
    private static function getColumnIterator(string $term, int $mode): Closure
    {
        /*
         * Lambda function to create actual criteria for specified column + term + mode combo.
         *
         * @param string $column
         *
         * @return string[]
         */
        return function (string $column) use ($term, $mode): array {
            if (false === strpos($column, '.')) {
                $column = 'entity.'.$column;
            }

            return [$column, 'like', self::getTerm($mode, $term)];
        };
    }

    /**
     * Method to get search term clause for 'LIKE' query for specified mode.
     */
    private static function getTerm(int $mode, string $term): string
    {
        switch ($mode) {
            case self::MODE_STARTS_WITH:
                $term .= '%';
                break;
            case self::MODE_ENDS_WITH:
                $term = '%'.$term;
                break;
            case self::MODE_FULL:
            default:
                $term = '%'.$term.'%';
                break;
        }

        return $term;
    }

    /**
     * @param string|string[] $column search column(s), could be a string or an array of strings
     *
     * @return string[]
     */
    private static function getColumns($column): array
    {
        $filter = fn (string $value): bool => mb_strlen(trim($value)) > 0;

        // Normalize column and search parameters
        return array_filter(
            array_map('trim', is_array($column) ? $column : (array) $column),
            $filter
        );
    }

    /**
     * Method to get search terms.
     *
     * @param string|string[]|null $search search term(s), could be a string or an array of strings
     *
     * @return string[]
     */
    private static function getSearchTerms($search): array
    {
        $filter = fn (string $value): bool => mb_strlen(trim($value)) > 0;

        return array_unique(
            array_filter(
                array_map('trim', is_array($search) ? $search : explode(' ', (string) $search)),
                $filter
            )
        );
    }
}
