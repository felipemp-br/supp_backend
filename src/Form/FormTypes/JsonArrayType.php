<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Form\FormTypes;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class JsonType.
 */
class JsonArrayType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new JsonToArrayTransformer());
    }

    /**
     * @inheritDoc
     */
    public function getParent(): string
    {
        return TextType::class;
    }
}
