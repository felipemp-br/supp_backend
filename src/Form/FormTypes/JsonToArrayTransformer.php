<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Form\FormTypes;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class JsonType.
 */
class JsonToArrayTransformer implements DataTransformerInterface
{
    /**
     * @inheritDoc
     */
    public function reverseTransform($value): mixed
    {
        if (empty($value)) {
            return [];
        }

        return json_decode($value, true);
    }

    /**
     * @ihneritdoc
     */
    public function transform($value): mixed
    {
        if (empty($value)) {
            return json_encode([]);
        }

        return json_encode($value);
    }
}
