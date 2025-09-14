<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Models;

use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Enums\AggregationEnum;

/**
 * CriteriaAggregation.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CriteriaAggregation
{
    protected ?CriteriaAggregation $parent = null;
    protected array $criterias = [];

    /**
     * Constructor.
     *
     * @param AggregationEnum $aggregation
     */
    public function __construct(
        protected AggregationEnum $aggregation = AggregationEnum::AND
    ) {
    }

    /**
     * @return array
     */
    public function getCriterias(): array
    {
        return $this->criterias;
    }

    /**
     * @param array|CriteriaAggregation $criteria
     *
     * @return $this
     */
    public function addCriteria(array|CriteriaAggregation $criteria): self
    {
        $this->criterias[] = $criteria;

        return $this;
    }

    /**
     * @param array $criterias
     * @return $this
     */
    public function setCriterias(array $criterias): self
    {
        $this->criterias = $criterias;

        return $this;
    }

    /**
     * @param array|CriteriaAggregation $criteria
     * @return $this
     */
    public function removeCriteria(array|CriteriaAggregation $criteria): self
    {
        $index = array_search($criteria, $this->criterias, true);
        if (!is_bool($index)) {
            unset($this->criterias[$index]);
        }

        return $this;
    }

    /**
     * @return self
     */
    public function cleanCriterias(): self
    {
        $this->criterias = [];

        return $this;
    }

    /**
     * @return $this
     */
    public function cleanAggregations(): self
    {
        $this->criateriaAggregations = [];

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->criterias);
    }

    /**
     * @return AggregationEnum
     */
    public function getAggregation(): AggregationEnum
    {
        return $this->aggregation;
    }

    /**
     * @return CriteriaAggregation|null
     */
    public function getParent(): ?CriteriaAggregation
    {
        return $this->parent;
    }

    /**
     * @param CriteriaAggregation|null $parent
     * @return self
     */
    public function setParent(?CriteriaAggregation $parent): self
    {
        $this->parent = $parent;

        return $this;
    }
}
