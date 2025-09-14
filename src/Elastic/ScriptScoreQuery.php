<?php

declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SuppCore\AdministrativoBackend\Elastic;

use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\ParametersTrait;

/**
 * Represents Elasticsearch "script_score" query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-script-score-query.html
 */
class ScriptScoreQuery implements BuilderInterface
{
    use ParametersTrait;

    /**
     * @var BuilderInterface
     */
    private $query;

    /**
     * @var array[]
     */
    private $script;

    /**
     * @param BuilderInterface $query
     * @param array            $script
     */
    public function __construct(BuilderInterface $query, array $script = [])
    {
        $this->query = $query;
        $this->script = $script;
    }

    /**
     * Returns the query instance.
     *
     * @return BuilderInterface object
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $query = [
            'query' => $this->query->toArray(),
            'script' => $this->script,
        ];

        $output = $this->processArray($query);

        return [$this->getType() => $output];
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'script_score';
    }
}
