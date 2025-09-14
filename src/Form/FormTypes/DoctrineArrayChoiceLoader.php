<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Form\FormTypes;

use Doctrine\Persistence\ObjectManager;
use Exception;
use SuppCore\AdministrativoBackend\Utils\Reflex;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Component\Form\ChoiceList\Loader\AbstractChoiceLoader;

/**
 * Class DoctrineArrayChoiceLoader.
 */
class DoctrineArrayChoiceLoader extends AbstractChoiceLoader
{
    private ObjectManager $manager;

    private EntityLoaderInterface $objectLoader;

    private string $class;

    /**
     * DoctrineArrayChoiceLoader constructor.
     *
     * @param ObjectManager         $manager
     * @param string                $class
     * @param EntityLoaderInterface $objectLoader
     */
    public function __construct(ObjectManager $manager, string $class, EntityLoaderInterface $objectLoader)
    {
        $classMetadata = $manager->getClassMetadata($class);
        $this->manager = $manager;
        $this->objectLoader = $objectLoader;
        $this->class = $classMetadata->getName();
    }

    /**
     * @return iterable
     */
    protected function loadChoices(): iterable
    {
        return $this->manager->getRepository($this->class)->findAll();
    }

    /**
     * @param array         $choices
     * @param callable|null $value
     *
     * @return array
     *
     * @throws Exception
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function loadValuesForChoices(array $choices, callable $value = null): array
    {
        if (!$choices) {
            return [];
        }

        return $this->doLoadValuesForChoices($choices);
    }

    /**
     * [objetos] => [ids].
     *
     * @param array $choices
     *
     * @return array
     *
     * @throws Exception
     * @noinspection PhpMissingParentCallCommonInspection
     */
    protected function doLoadValuesForChoices(array $choices): array
    {
        array_walk_recursive(
            $choices,
            function (&$v) {
                $v = Reflex::getProperty($v, 'id');
            }
        );

        return $choices;
    }

    /**
     * [ids (int)] => [objetos].
     *
     * @param array         $values
     * @param callable|null $value
     *
     * @return array
     *
     * @throws Exception
     * @noinspection PhpMissingParentCallCommonInspection
     */
    protected function doLoadChoicesForValues(array $values, ?callable $value): array
    {
        // cada elemento do array vem uma string json
        // entrada: [ '{ 10 }', '{ 5 }', '{ [ 1, 2, 3 ]}' ]
        // saida: [ 10, 5, [1, 2, 3]] (mappedValues)
        $mappedValues = array_map(fn ($x) => json_decode($x), $values);

        // flattenning
        // entrada: [1, [2, 3], [4, 5]]
        // saida: [1, 2, 3, 4, 5] (flatValues)
        $flatValues = [];
        array_walk_recursive(
            $mappedValues,
            function ($a) use (&$flatValues) {
                $flatValues[] = $a;
            }
        );

        // array de objetos [ id => objeto, ...]
        $objectsById = [];
        foreach ($this->objectLoader->getEntitiesByIds('id', $flatValues) as $object) {
            $objectsById[Reflex::getProperty($object, 'id')] = $object;
        }

        $idsNotFound = array_diff($flatValues, array_keys($objectsById));
        if (count($idsNotFound)) {
            throw new Exception("Alguns ids da classe $this->class não foram encontrados!");
        }

        // faz o mapeamento de id para objeto
        array_walk_recursive(
            $mappedValues,
            function (&$v) use ($objectsById) {
                $v = $objectsById[$v];
            }
        );

        // retorna o array de array de objetos
        return $mappedValues;
    }
}
