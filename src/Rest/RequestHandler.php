<?php

declare(strict_types=1);
/**
 * /src/Rest/RequestHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest;

use LogicException;
use SuppCore\AdministrativoBackend\Utils\JSON;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

use function abs;
use function array_filter;
use function array_key_exists;
use function array_merge;
use function array_unique;
use function array_values;
use function array_walk;
use function explode;
use function in_array;
use function is_array;

/**
 * Class RequestHandler.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
final class RequestHandler
{
    /**
     * Method to get used criteria array for 'find' and 'count' methods. Some examples below.
     *
     * Basic usage:
     *  ?where={"foo": "bar"}                       => WHERE entity.foo = 'bar'
     *  ?where={"bar.foo": "foobar"}                => WHERE bar.foo = 'foobar'
     *  ?where={"id":1,2,3}                      => WHERE entity.id IN (1,2,3)
     *  ?where={"bar.foo":1,2,3}                 => WHERE bar.foo IN (1,2,3)
     *
     * Advanced usage:
     *  By default you cannot make anything else that described above, but you can easily manage special cases within
     *  your controller 'processCriteria' method, where you can modify this generated 'criteria' array as you like.
     *
     *  Note that with advanced usage you can easily use everything that SuppCore\AdministrativoBackend\Repository\Base::getExpression method
     *  supports - and that is basically 99% that you need on advanced search criteria.
     *
     * @return mixed[]
     *
     * @throws HttpException
     */
    public static function getCriteria(HttpFoundationRequest $request): array
    {
        try {
            $where = array_filter(
                JSON::decode($request->get('where', '{}'), true),
                fn ($value): bool => null !== $value
            );
        } catch (LogicException $error) {
            throw new HttpException(HttpFoundationResponse::HTTP_BAD_REQUEST, 'Current \'where\' parameter is not valid JSON.', $error);
        }

        return $where;
    }

    public static function getBoosts(HttpFoundationRequest $request): array
    {
        try {
            $boosts = array_filter(
                JSON::decode($request->get('boosts', '{}'), true),
                fn ($value): bool => null !== $value
            );
        } catch (LogicException $error) {
            throw new HttpException(HttpFoundationResponse::HTTP_BAD_REQUEST, 'Current \'boosts\' parameter is not valid JSON.', $error);
        }

        return $boosts;
    }

    public static function validateCriteria(array $criteria): void
    {
        foreach ($criteria as $filter) {
            if (str_starts_with('like:', (string) $filter)) {
                $test = str_replace('like:', '', (string) $filter);
                if (!$test || ('%' === $test)) {
                    throw new HttpException(HttpFoundationResponse::HTTP_BAD_REQUEST, 'Cláusula where inválida!');
                }
            }
        }
    }

    /**
     * Getter method for used order by option within 'find' method. Some examples below.
     *
     * Basic usage:
     *  ?order=column1                                  => ORDER BY entity.column1 ASC
     *  ?order=-column1                                 => ORDER BY entity.column2 DESC
     *  ?order=foo.column1                              => ORDER BY foo.column1 ASC
     *  ?order=-foo.column1                             => ORDER BY foo.column2 DESC
     *
     * Array parameter usage:
     *  ?order[column1]=ASC                             => ORDER BY entity.column1 ASC
     *  ?order[column1]=DESC                            => ORDER BY entity.column1 DESC
     *  ?order[column1]=foobar                          => ORDER BY entity.column1 ASC
     *  ?order[column1]=DESC&order[column2]=DESC        => ORDER BY entity.column1 DESC, entity.column2 DESC
     *  ?order[foo.column1]=ASC                         => ORDER BY foo.column1 ASC
     *  ?order[foo.column1]=DESC                        => ORDER BY foo.column1 DESC
     *  ?order[foo.column1]=foobar                      => ORDER BY foo.column1 ASC
     *  ?order[foo.column1]=DESC&order[column2]=DESC    => ORDER BY foo.column1 DESC, entity.column2 DESC
     *
     * @return mixed[]
     */
    public static function getOrderBy(HttpFoundationRequest $request): array
    {
        try {
            $order = array_filter(
                JSON::decode($request->get('order', '{}'), true),
                fn ($value): bool => null !== $value
            );
        } catch (LogicException $error) {
            throw new HttpException(HttpFoundationResponse::HTTP_BAD_REQUEST, 'Current \'order\' parameter is not valid JSON.', $error);
        }

        return $order;
    }

    /**
     * @return mixed[]
     */
    public static function getArrayParam(HttpFoundationRequest $request, string $paramName): array
    {
        try {
            $populate = array_filter(
                JSON::decode($request->get($paramName, '[]'), true),
                fn ($value): bool => null !== $value
            );
        } catch (LogicException $error) {
            throw new HttpException(HttpFoundationResponse::HTTP_BAD_REQUEST, 'Current '.$paramName.' parameter is not valid JSON.', $error);
        }

        return $populate;
    }

    /**
     * Getter method for used populate option within 'find' method. Some examples below.
     *
     * Usage:
     *  ?populate[]=association
     *  ?populate[]=association.association
     *
     * @return mixed[]
     */
    public static function getPopulate(HttpFoundationRequest $request, RestResourceInterface $resource): array
    {
        try {
            $populate = array_filter(
                JSON::decode($request->get('populate', '[]'), true),
                fn ($value): bool => null !== $value
            );
        } catch (LogicException $error) {
            throw new HttpException(HttpFoundationResponse::HTTP_BAD_REQUEST, 'Current \'populate\' parameter is not valid JSON.', $error);
        }

        if (in_array('populateAll', $populate, true)) {
            $populate = array_diff($populate, ['populateAll']);
            $associations = array_merge($populate, $resource->getAllAssociations());
            $populate = $resource->getAssociations($associations);
        } else {
            $populate = $resource->getAssociations($populate);
        }

        return array_unique($populate);
    }

    /**
     * @return mixed[]
     */
    public static function getContext(HttpFoundationRequest $request): array
    {
        try {
            $context = array_filter(
                JSON::decode($request->get('context', '{}'), true),
                fn ($value): bool => null !== $value
            );
        } catch (LogicException $error) {
            throw new HttpException(HttpFoundationResponse::HTTP_BAD_REQUEST, 'Current \'context\' parameter is not valid JSON.', $error);
        }

        return $context;
    }

    /**
     * Getter method for used limit option within 'find' method.
     *
     * Usage:
     *  ?limit=10
     */
    public static function getLimit(HttpFoundationRequest $request): ?int
    {
        $limit = $request->get('limit');

        return null === $limit ? null : abs((int) $limit);
    }

    /**
     * Getter method for used offset option within 'find' method.
     *
     * Usage:
     *  ?offset=10
     */
    public static function getOffset(HttpFoundationRequest $request): ?int
    {
        $offset = $request->get('offset');

        return null === $offset ? null : abs((int) $offset);
    }

    /**
     * Getter method for used search terms within 'find' and 'count' methods. Note that these will affect to columns /
     * properties that you have specified to your resource service repository class.
     *
     * Usage examples:
     *  ?search=term
     *  ?search=term1+term2
     *  ?search={"and": ["term1", "term2"]}
     *  ?search={"or": ["term1", "term2"]}
     *  ?search={"and": ["term1", "term2"], "or": ["term3", "term4"]}
     *
     * @return mixed[]
     *
     * @throws HttpException
     */
    public static function getSearchTerms(HttpFoundationRequest $request): array
    {
        $search = $request->get('search');

        return null !== $search ? self::getSearchTermCriteria($search) : [];
    }

    /**
     * Method to return search term criteria as an array that repositories can easily use.
     *
     * @return mixed[]
     *
     * @throws HttpException
     */
    private static function getSearchTermCriteria(string $search): array
    {
        $searchTerms = self::determineSearchTerms($search);

        // By default we want to use 'OR' operand with given search words.
        $output = [
            'or' => array_unique(array_values(array_filter(explode(' ', $search)))),
        ];

        if (null !== $searchTerms) {
            $output = self::normalizeSearchTerms($searchTerms);
        }

        return $output;
    }

    /**
     * Method to determine used search terms. Note that this will first try to JSON decode given search term. This is
     * for cases that 'search' request parameter contains 'and' or 'or' terms.
     *
     * @return mixed[]|null
     *
     * @throws HttpException
     */
    private static function determineSearchTerms(string $search): ?array
    {
        try {
            $searchTerms = JSON::decode($search, true);

            self::checkSearchTerms($searchTerms);
        } /* @noinspection BadExceptionsProcessingInspection */ catch (LogicException $error) {
            // Parameter was not JSON so just use parameter values as search strings
            $searchTerms = null;
        }

        return $searchTerms;
    }

    /**
     * @param mixed $searchTerms
     *
     * @throws LogicException
     * @throws HttpException
     */
    private static function checkSearchTerms($searchTerms): void
    {
        if (!is_array($searchTerms)) {
            throw new LogicException('Search term is not an array, fallback to string handling');
        }

        if (!array_key_exists('and', $searchTerms) && !array_key_exists('or', $searchTerms)) {
            throw new HttpException(HttpFoundationResponse::HTTP_BAD_REQUEST, 'Given search parameter is not valid, within JSON provide \'and\' and/or \'or\' property.');
        }
    }

    /**
     * Method to normalize specified search terms. Within this we will just filter out any "empty" values and return
     * unique terms after that.
     *
     * @param string[] $searchTerms
     *
     * @return string[]|array<mixed, mixed>
     */
    private static function normalizeSearchTerms(array $searchTerms): array
    {
        /**
         * Lambda function to normalize JSON search terms.
         *
         * @param string|array $terms
         */
        $iterator = static function (&$terms): void {
            $terms = array_unique(array_values(array_filter($terms)));
        };

        // Normalize user input, note that this support array and string formats on value
        array_walk($searchTerms, $iterator);

        return $searchTerms;
    }
}
