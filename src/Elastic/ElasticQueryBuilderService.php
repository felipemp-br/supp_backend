<?php

declare(strict_types=1);
/**
 * /src/Elastic/ElasticQueryBuilderService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Elastic;

use Exception;
use ONGR\ElasticsearchDSL\Query\Compound\BoolQuery;
use ONGR\ElasticsearchDSL\Query\FullText\MatchQuery;
use ONGR\ElasticsearchDSL\Query\FullText\QueryStringQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\ExistsQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\RangeQuery;
use Redis;
use SuppCore\AdministrativoBackend\Elastic\Message\DenseVectorQueryMessage;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class ElasticQueryBuilderService.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ElasticQueryBuilderService
{
    private $config;
    private string $index;
    private Redis $redisClient;
    private MessageBusInterface $bus;
    private $denseVector;

    /**
     * ElasticQueryBuilderService constructor.
     *
     * @param ParameterBagInterface $parameterBag
     * @param Redis                 $redisClient
     */
    public function __construct(ParameterBagInterface $parameterBag, Redis $redisClient, MessageBusInterface $bus)
    {
        $this->config = $parameterBag->get('elasticsearch');
        $this->redisClient = $redisClient;
        $this->bus = $bus;
    }

    /**
     * @param string $index
     */
    public function init(string $index): void
    {
        $this->index = $index;
        $this->denseVector = null;
    }

    /**
     * @param       $criteria
     * @param array $boosts
     *
     * @return BoolQuery
     *
     * @throws \RedisException
     */
    public function proccessCriteria($criteria, $boosts = []): BoolQuery
    {
        $boolQuery = new CustomBoolQuery();

        foreach ($criteria as $property => $filter) {
            if ('orX' === $property) {
                $orBoolQuery = new BoolQuery();
                foreach ($filter as $orFilter) {
                    $orBoolQuery->add($this->proccessCriteria($orFilter, $boosts), BoolQuery::SHOULD);
                }
                $boolQuery->add($orBoolQuery, BoolQuery::MUST);
            } elseif ('andX' === $property) {
                foreach ($filter as $andFilter) {
                    $boolQuery->add($this->proccessCriteria($andFilter, $boosts), BoolQuery::MUST);
                }
            } else {
                $nested = explode('.', $property);
                $root = $this->config['indexes'][$this->index]['properties'];

                foreach ($nested as $nestedProperty) {
                    if (!isset($root[$nestedProperty])) {
                        throw new Exception(sprintf('Critério de pesquisa %s nao mapeado no elasticsearch', $property));
                    }
                    if (isset($root[$nestedProperty]['properties'])) {
                        $root = $root[$nestedProperty]['properties'];
                    } else {
                        $root = $root[$nestedProperty];
                    }
                }

                // query full text search
                if (isset($root['type']) && 'attachment' === $root['type']) {
                    $queryString = new QueryStringQuery(
                        $this->processaTermo(str_replace('like:', '', str_replace('%', '', $filter))),
                        ['default_field' => 'attachment.content', 'default_operator' => 'AND']
                    );
                    if (isset($boosts[$nestedProperty])) {
                        $boolQuery->addWithBoost($queryString, BoolQuery::MUST, $boosts[$nestedProperty]);
                    } else {
                        $boolQuery->add($queryString, BoolQuery::MUST);
                    }

                    continue;
                    if (isset($root['denseVector']) && $root['denseVector']) {
                        $hash = md5($filter);
                        $denseVectorMessage = new DenseVectorQueryMessage();
                        $denseVectorMessage->setId($hash);
                        $denseVectorMessage->setQuery($filter);
                        $this->bus->dispatch($denseVectorMessage);
                        $ciclo = 0;
                        while ($ciclo < 100) {
                            usleep(100000);
                            $response = $this->redisClient->get($hash);
                            if ($response) {
                                $response = json_decode($response, true);
                                $this->denseVector = $response['vector'];
                                break;
                            }
                            ++$ciclo;
                        }
                    }
                }

                if (str_starts_with($filter, 'eq:')) {
                    $term = new MatchQuery($this->processaProperty($property), str_replace('eq:', '', $filter));
                    $valueTerm = str_replace('eq:', '', $filter);
                    if (isset($boosts[$property]) && is_array(
                        $boosts[$property]
                    ) && isset($boosts[$property][$valueTerm])) {
                        $boost = $boosts[$property][$valueTerm];
                        $boolQuery->addWithBoost($term, BoolQuery::MUST, $boost);
                    } else {
                        $boolQuery->add($term, BoolQuery::MUST);
                    }
                }

                if (0 === strpos($filter, 'neq:')) {
                    $term = new MatchQuery($this->processaProperty($property), str_replace('neq:', '', $filter));
                    $boolQuery->add($term, BoolQuery::MUST_NOT);
                }

                if (str_starts_with($filter, 'like:')) {
                    $mask = '';
                    if (isset($root['asterisk']) && true === $root['asterisk']) {
                        $mask = '*';
                        $filter = preg_replace('/\s+/', '* *', $filter);
                    }
                    $queryString = new QueryStringQuery(
                        $this->processaTermo(str_replace('like:', '', str_replace('%', $mask, $filter))),
                        ['default_field' => $this->processaProperty($property), 'default_operator' => 'AND']
                    );
                    $term = $this->extracTermLike($filter);
                    if (isset($boosts[$property]) && is_array($boosts[$property]) && isset($boosts[$property][$term])) {
                        $boost = $boosts[$property][$term];
                        $boolQuery->addWithBoost($queryString, BoolQuery::MUST, $boost);
                    } else {
                        $boolQuery->add($queryString, BoolQuery::MUST);
                    }
                }

                if (str_starts_with($filter, 'notLike:')) {
                    $mask = '';
                    if (isset($root['asterisk']) && true === $root['asterisk']) {
                        $mask = '*';
                        $filter = preg_replace('/\s+/', '* *', $filter);
                    }
                    $queryString = new QueryStringQuery(
                        $this->processaTermo(str_replace('like:', '', str_replace('%', $mask, $filter))),
                        ['default_field' => $this->processaProperty($property), 'default_operator' => 'AND']
                    );
                    $boolQuery->add($queryString, BoolQuery::MUST_NOT);
                }

                if (str_starts_with($filter, 'gt:')) {
                    $range = new RangeQuery(
                        $this->processaProperty($property),
                        ['from' => str_replace('gt:', '', $filter)]
                    );
                    $boolQuery->add($range, BoolQuery::MUST);
                }

                if (str_starts_with($filter, 'gte:')) {
                    $range = new RangeQuery(
                        $this->processaProperty($property),
                        ['gte' => str_replace('gte:', '', $filter)]
                    );
                    $boolQuery->add($range, BoolQuery::MUST);
                }

                if (str_starts_with($filter, 'lt:')) {
                    $range = new RangeQuery(
                        $this->processaProperty($property),
                        ['to' => str_replace('lt:', '', $filter)]
                    );
                    $boolQuery->add($range, BoolQuery::MUST);
                }

                if (str_starts_with($filter, 'lte:')) {
                    $range = new RangeQuery(
                        $this->processaProperty($property),
                        ['to' => str_replace('lte:', '', $filter)]
                    );
                    $boolQuery->add($range, BoolQuery::MUST);
                }

                if (str_starts_with($filter, 'in:')) {
                    $boolOrQuery = new BoolQuery();
                    foreach (explode(',', str_replace('in:', '', $filter)) as $filtroId) {
                        $term = new MatchQuery($this->processaProperty($property), $filtroId);
                        $boolOrQuery->add($term, BoolQuery::SHOULD);
                    }
                    $boolQuery->add($boolOrQuery, BoolQuery::MUST);
                }

                if (str_starts_with($filter, 'notIn:')) {
                    $boolOrQuery = new BoolQuery();
                    foreach (explode(',', str_replace('in:', '', $filter)) as $filtroId) {
                        $term = new MatchQuery($this->processaProperty($property), $filtroId);
                        $boolOrQuery->add($term, BoolQuery::SHOULD);
                    }
                    $boolQuery->add($boolOrQuery, BoolQuery::MUST_NOT);
                }

                if (str_starts_with($filter, 'isNull')) {
                    $exists = new ExistsQuery($this->processaProperty($property));
                    $boolQuery->add($exists, BoolQuery::MUST_NOT);
                }

                if (str_starts_with($filter, 'isNotNull')) {
                    $exists = new ExistsQuery($this->processaProperty($property));
                    $boolQuery->add($exists, BoolQuery::MUST);
                }
            }
        }

        return $boolQuery;
    }

    /**
     * @param string $input
     *
     * @return string
     */
    public function processaProperty(string $input): string
    {
        if ('id' === $input && 'keyword' === $this->config['indexes'][$this->index]['properties'][$input]['type']) {
            return '_id';
        }

        $input = str_replace('.id', '._id', $input);

        if (isset($this->config['indexes'][$this->index]['properties'][$input]['name'])) {
            return $this->config['indexes'][$this->index]['properties'][$input]['name'];
        }

        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    /**
     * @param $termo
     *
     * @return string
     */
    private function processaTermo($termo): string
    {
        $termo = preg_replace('/"+/', '"', $termo);
        $termo = preg_replace('/\s+/', ' ', $termo);

        $qtdAspas = preg_match_all('/"/', $termo);
        if (0 != $qtdAspas % 2) {
            $termo = substr_replace($termo, '', strrpos($termo, '"'), 1);
        }

        return Sanitizer::escape($termo, ['"', '*', ' ']);
    }

    /**
     * @return mixed
     */
    public function getDenseVector(): mixed
    {
        return $this->denseVector;
    }

    public function extracTermLike($input)
    {
        $pattern = '/like:%(.+)%/';
        $matches = [];
        preg_match($pattern, $input, $matches);

        // Retorna o termo ou uma string vazia se não houver correspondência
        return isset($matches[1]) ? $matches[1] : '';
    }

    public function extracTermEq($input)
    {
        $pattern = '/eq:%(.+)%/';
        $matches = [];
        preg_match($pattern, $input, $matches);

        // Retorna o termo ou uma string vazia se não houver correspondência
        return isset($matches[1]) ? $matches[1] : '';
    }
}
