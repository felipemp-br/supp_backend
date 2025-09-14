<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Models;

use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Enums\AggregationEnum;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Parsers\FieldParserInterface;

/**
 * CriteriaAggregationTree.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CriteriaAggregationTree
{
    /**
     * @var array<int, CriteriaAggregation[]>
     */
    private array $plainTree;
    private ?bool $lastParsedValue = null;

    /**
     * Constructor.
     *
     * @param CriteriaAggregation $root
     */
    public function __construct(
        private readonly CriteriaAggregation $root,
    ) {
        $this->plainTree = [];
        $this->convertCriteriaAggregationToPlainTree($this->root);
    }

    /**
     * Constroi o criteria aggregation tree.
     *
     * @param string|array $json
     *
     * @return self
     */
    public static function build(string|array $json): self
    {
        $jsonData = json_decode($json, true, 512, JSON_ERROR_NONE);
        $jsonData ??= [];
        $aggregation = new CriteriaAggregation();
        self::convertArrayToCriteria($aggregation, $jsonData);

        return new self($aggregation);
    }

    /**
     * Converte os campos passíveis de conversão com base na entity,
     * removendo os blocos com base no resultado e agregação.
     *
     * @param Processo|null          $processo
     * @param FieldParserInterface[] $parsers
     *
     * @return self
     */
    public function parse(?Processo $processo = null, array $parsers = []): self
    {
        $level = count($this->plainTree) - 1;
        while ($level >= 0 && isset($this->plainTree[$level])) {
            $aggregations = $this->plainTree[$level];
            foreach ($aggregations as $aggregation) {
                foreach ($aggregation->getCriterias() as $criteria) {
                    if ($criteria instanceof CriteriaAggregation) {
                        continue;
                    }
                    foreach ($criteria as $field => $value) {
                        $this->lastParsedValue = $this->getParser($field, $parsers)
                            ?->parse($field, $value, $processo);
                        if (!is_null($this->lastParsedValue)) {
                            $aggregation->removeCriteria($criteria);
                            $aggregationRemoved = $this->resolveParsedValueToAggregation(
                                $aggregation,
                                $level,
                                $this->lastParsedValue
                            );
                            if ($aggregationRemoved) {
                                continue 3;
                            }
                        }
                    }
                }
            }
            --$level;
        }

        return $this->reindexTree();
    }

    /**
     * Simplifica as aggregações tentando eliminar os blocos desnecessários.
     *
     * @return self
     */
    public function simplify(): self
    {
        $reverseTree = array_reverse($this->plainTree, true);

        foreach ($reverseTree as $level => $aggregations) {
            foreach ($aggregations as $aggregation) {
                if (
                    $aggregation->getParent()
                    && (
                        $aggregation->getParent()->getAggregation() === $aggregation->getAggregation()
                        || 1 === count($aggregation->getCriterias())
                    )
                ) {
                    $aggregation->getParent()->removeCriteria($aggregation);
                    $aggregation->getParent()->setCriterias(
                        [
                            ...$aggregation->getParent()->getCriterias(),
                            ...array_map(
                                fn ($criteria) => $criteria instanceof CriteriaAggregation
                                    ? $criteria->setParent($aggregation->getParent()) : $criteria,
                                $aggregation->getCriterias()
                            ),
                        ]
                    );
                    unset($this->plainTree[$level][array_search($aggregation, $this->plainTree[$level])]);
                }
                if (empty($this->plainTree[$level])) {
                    unset($this->plainTree[$level]);
                }
            }
        }

        return $this->reindexTree();
    }

    /**
     * Converte a arvore em array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->aggretationToArray($this->root);
    }

    /**
     * Converte a arvore para json.
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Verifica se os criterios estão vazios.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->root->isEmpty();
    }

    /**
     * Retorna o criteria.
     *
     * @return CriteriaAggregation
     */
    public function getCriteriaAggregationRoot(): CriteriaAggregation
    {
        return $this->root;
    }

    /**
     * Retorna o último valor parseado, caso existe.
     *
     * @return bool|null
     */
    public function getLastParsedValue(): ?bool
    {
        return $this->lastParsedValue;
    }

    /**
     * Convert array to criteria of criteria aggregation.
     *
     * @param array               $criteriaData
     * @param CriteriaAggregation $criteriaAggregation
     *
     * @return void
     */
    protected static function convertArrayToCriteria(
        CriteriaAggregation $criteriaAggregation,
        array $criteriaData
    ): void {
        foreach ($criteriaData as $fieldOrAggregation => $valueOrCriterias) {
            $criteria = null;
            if (!in_array($fieldOrAggregation, [AggregationEnum::AND->value, AggregationEnum::OR->value])) {
                $criteria = [$fieldOrAggregation => $valueOrCriterias];
            } else {
                $criteria = new CriteriaAggregation(AggregationEnum::from($fieldOrAggregation));
                $criteria->setParent($criteriaAggregation);
                foreach ($valueOrCriterias as $criterias) {
                    self::convertArrayToCriteria($criteria, $criterias);
                }
            }
            $criteriaAggregation->addCriteria($criteria);
        }
    }

    /**
     * Converte a agregação em uma arvore linear de agregadores indexada por nível de profundidade.
     *
     * @param CriteriaAggregation $aggregation
     * @param int                 $level
     *
     * @return void
     */
    protected function convertCriteriaAggregationToPlainTree(
        CriteriaAggregation $aggregation,
        int $level = 0
    ): void {
        $this->plainTree[$level][] = $aggregation;
        foreach ($aggregation->getCriterias() as $criteria) {
            if ($criteria instanceof CriteriaAggregation) {
                $this->convertCriteriaAggregationToPlainTree($criteria, $level + 1);
            }
        }
    }

    /**
     * Converte uma aggregação em um array.
     *
     * @param CriteriaAggregation $aggregation
     *
     * @return array
     */
    private function aggretationToArray(CriteriaAggregation $aggregation): array
    {
        if ($aggregation->isEmpty()) {
            return [];
        }

        $aggregationKey = $aggregation->getAggregation()->value;
        $result[$aggregationKey] = [];

        foreach ($aggregation->getCriterias() as $criteria) {
            if ($criteria instanceof CriteriaAggregation) {
                $criteria = $this->aggretationToArray($criteria);
            }
            $result[$aggregationKey][] = $criteria;
        }

        return $result;
    }

    /**
     * Remove os aggregations do plain tree a depender do valor do parsed value.
     *
     * @param CriteriaAggregation $aggregation
     * @param int                 $level
     * @param bool                $parsedValue
     *
     * @return bool
     */
    private function resolveParsedValueToAggregation(
        CriteriaAggregation $aggregation,
        int $level,
        bool $parsedValue
    ): bool {
        if (
            (false === $parsedValue && AggregationEnum::AND === $aggregation->getAggregation())
            || (true === $parsedValue && AggregationEnum::OR === $aggregation->getAggregation())
            || $aggregation->isEmpty()
        ) {
            unset($this->plainTree[$level][array_search($aggregation, $this->plainTree[$level])]);
            if (empty($this->plainTree[$level])) {
                unset($this->plainTree[$level]);
            }
            $aggregation->cleanCriterias();
            if ($aggregation->getParent()) {
                $aggregation->getParent()->removeCriteria($aggregation);
                $this->resolveParsedValueToAggregation(
                    $aggregation->getParent(),
                    $level - 1,
                    $parsedValue
                );
            }

            return true;
        }

        return false;
    }

    /**
     * Reindexa a arvore.
     *
     * @return $this
     */
    private function reindexTree(): self
    {
        $this->plainTree = array_values($this->plainTree);

        return $this;
    }

    /**
     * Retorna o parser.
     *
     * @param string                 $field
     * @param FieldParserInterface[] $parsers
     *
     * @return FieldParserInterface|null
     */
    private function getParser(string $field, array $parsers): ?FieldParserInterface
    {
        foreach ($parsers as $parser) {
            if ($parser->support($field)) {
                return $parser;
            }
        }
        return null;
    }
}
