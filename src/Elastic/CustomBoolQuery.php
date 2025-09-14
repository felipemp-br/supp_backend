<?php

namespace SuppCore\AdministrativoBackend\Elastic;

use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\Query\Compound\BoolQuery as BaseBoolQuery;


class CustomBoolQuery extends BaseBoolQuery
{
    public function addWithBoost(BuilderInterface $query, $type = self::MUST, $boost = null)
    {
        if (null !== $boost) {
            $query->addParameter('boost', $boost);
        }

        // Chama o método add da classe base.
        return $this->add($query, $type);
    }
}
