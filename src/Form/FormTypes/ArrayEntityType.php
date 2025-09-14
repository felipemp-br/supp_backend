<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Form\FormTypes;

use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use SuppCore\AdministrativoBackend\Form\ChoiceList\ORMQueryBuilderLoader;
use Symfony\Bridge\Doctrine\Form\EventListener\MergeDoctrineCollectionListener;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ArrayEntityType.
 */
class ArrayEntityType extends EntityType
{
    /**
     * ArrayEntityType constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['multiple'] && interface_exists(Collection::class)) {
            $builder
                ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                    $data = $event->getData();

                    if (!\is_array($data)) {
                        return;
                    }

                    array_walk_recursive($data, function ($value) {
                        if (null !== $value && !is_string($value) && !is_int($value)) {
                            throw new TransformationFailedException('All choices must be NULL, strings or ints, or an array of those.');
                        }
                    });
                    $event->setData(array_map(fn ($x) => json_encode($x), $event->getData()));
                }, 1024)
                ->addEventSubscriber(new MergeDoctrineCollectionListener())
                ->addViewTransformer(new CollectionToArrayTransformer(), true)
                ->addEventSubscriber(new CustomMergeCollectionListener());
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $choiceLoader = function (Options $options) {
            // Unless the choices are given explicitly, load them on demand
            if (null === $options['choices']) {
                // If there is no QueryBuilder we can safely cache
                $vary = [$options['em'], $options['class']];

                // also if concrete Type can return important QueryBuilder parts to generate
                // hash key we go for it as well, otherwise fallback on the instance
                if ($options['query_builder']) {
                    $vary[] = $this->getQueryBuilderPartsForCachingHash($options['query_builder']) ??
                        $options['query_builder'];
                }
                $qb = $options['query_builder']
                    ?? $options['em']->getRepository($options['class'])->createQueryBuilder('e');
                return ChoiceList::loader(
                    $this,
                    new DoctrineArrayChoiceLoader(
                        $options['em'],
                        $options['class'],
                        new ORMQueryBuilderLoader($qb)
                    ),
                    $vary
                );
            }

            return null;
        };
        $resolver->setDefault('choice_loader', $choiceLoader);
    }
}
