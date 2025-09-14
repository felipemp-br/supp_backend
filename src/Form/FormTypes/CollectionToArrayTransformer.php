<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Form\FormTypes;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use function is_array;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class CollectionToArrayTransformer.
 */
class CollectionToArrayTransformer implements DataTransformerInterface
{
    /**
     * Transforms a collection into an array.
     *
     * @param $value
     *
     * @return mixed An array of entities
     */
    public function transform($value): mixed
    {
        if (is_array($value)) {
            return $value;
        }

        if (null === $value) {
            return [];
        }

        if (!$value instanceof Collection) {
            return $value;
        }

        return array_map(
            fn ($i) => $this->transform($i),
            $value->toArray()
        );
    }

    /**
     * Transforms choice keys into entities.
     *
     * @param mixed $value An array of entities
     *
     * @return Collection A collection of entities
     */
    public function reverseTransform($value): mixed
    {
        $value = ('' === $value || null === $value) ? [] : (array) $value;

        return new ArrayCollection(
            array_map(
                fn ($i) => is_array($i) ? new ArrayCollection($i) : $i,
                $value
            )
        );
    }
}
