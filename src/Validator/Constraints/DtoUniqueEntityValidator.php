<?php

/** @noinspection NullPointerExceptionInspection */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Validator\Constraints;

use function class_exists;
use function count;
use Countable;
use DateTimeInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use function get_class;
use function is_array;
use function is_object;
use function is_string;
use Iterator;
use IteratorAggregate;
use ReflectionClass;
use ReflectionException;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Traversable;

/**
 * Class DtoUniqueEntityValidator.
 */
class DtoUniqueEntityValidator extends ConstraintValidator
{
    private Constraint $constraint;

    /**
     * @var EntityManager
     */
    private $em;

    private ClassMetadata $entityMeta;

    private ManagerRegistry $registry;

    private ?ObjectRepository $repository = null;

    private RestDtoInterface $validationObject;

    /**
     * DtoUniqueEntityValidator constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param mixed      $object
     * @param Constraint $constraint
     *
     * @throws ReflectionException
     */
    public function validate($object, Constraint $constraint): void
    {
        $this->validationObject = $object;
        $this->constraint = $constraint;
        $this->checkTypes();

        $this->entityMeta = $this->getEntityManager()->getClassMetadata($this->constraint->entityClass);
        $criteria = $this->getCriteria();

        if (empty($criteria)) {
            return;
        }

        $result = $this->checkConstraint($criteria);

        if (!$result || (1 === count($result) && current($result)->getId() === $object->getId())) {
            return;
        }

        $objectFields = array_keys($this->constraint->fieldMapping);
        $errorPath = $this->constraint->errorPath ?? $objectFields[0];

        $invalidValue = $criteria[$this->constraint->fieldMapping[$errorPath]] ??
            $criteria[reset($this->constraint->fieldMapping)];

        $this->context->buildViolation($this->constraint->message)
            ->atPath($errorPath)
            ->setParameter('{{ value }}', $this->formatWithIdentifiers($invalidValue))
            ->setInvalidValue($invalidValue)
            ->setCode(DtoUniqueEntity::NOT_UNIQUE_ERROR)
            ->setCause($result)
            ->addViolation();
    }

    private function checkTypes(): void
    {
        if (!$this->validationObject instanceof RestDtoInterface) {
            throw new UnexpectedTypeException($this->validationObject, RestDtoInterface::class);
        }

        if (!$this->constraint instanceof DtoUniqueEntity) {
            throw new UnexpectedTypeException($this->constraint, DtoUniqueEntity::class);
        }

        if (null === $this->constraint->entityClass || !class_exists($this->constraint->entityClass)) {
            throw new UnexpectedTypeException($this->constraint->entityClass, Entity::class);
        }

        if (!is_array($this->constraint->fieldMapping) || 0 === count($this->constraint->fieldMapping)) {
            throw new UnexpectedTypeException($this->constraint->fieldMapping, '[objectProperty => entityProperty]');
        }

        if ((null !== $this->constraint->errorPath) && !is_string($this->constraint->errorPath)) {
            throw new UnexpectedTypeException($this->constraint->errorPath, 'string or null');
        }
    }

    /**
     * @return ObjectManager|EntityManager|null
     */
    private function getEntityManager()
    {
        if (null !== $this->em) {
            return $this->em;
        }

        if ($this->constraint->em) {
            $this->em = $this->registry->getManager($this->constraint->em);

            if (!$this->em) {
                throw new ConstraintDefinitionException(sprintf('Object manager "%s" does not exist.', $this->constraint->em));
            }
        } else {
            $this->em = $this->registry->getManagerForClass($this->constraint->entityClass);

            if (!$this->em) {
                throw new ConstraintDefinitionException(sprintf('Unable to find the object manager associated with an entity of class "%s".', $this->constraint->entityClass));
            }
        }

        return $this->em;
    }

    /**
     * @return array
     *
     * @throws ReflectionException
     */
    private function getCriteria(): array
    {
        $validationClass = new ReflectionClass($this->validationObject);
        $criteria = [];
        foreach ($this->constraint->fieldMapping as $objectField => $entityField) {
            if (!$validationClass->hasProperty($objectField)) {
                throw new ConstraintDefinitionException(sprintf('Property for fieldMapping key "%s" does not exist on this Object.', $objectField));
            }
            if (!property_exists($this->constraint->entityClass, $entityField)) {
                throw new ConstraintDefinitionException(sprintf('Property for fieldMapping key "%s" does not exist in given EntityClass.', $objectField));
            }
            if (!$this->entityMeta->hasField($entityField) && !$this->entityMeta->hasAssociation($entityField)) {
                throw new ConstraintDefinitionException(sprintf('The field "%s" is not mapped by Doctrine, so it cannot be validated for uniqueness.', $entityField));
            }
            $property = $validationClass->getProperty($objectField);
            $property->setAccessible(true);
            $fieldValue = $property->getValue($this->validationObject);

            if ($fieldValue) {
                $criteria[$entityField] = $fieldValue;
            } elseif (!$this->constraint->ignoreNull) {
                $criteria[$entityField] = $fieldValue;
            }

            if (isset($criteria[$entityField]) && null !== $criteria[$entityField] && $this->entityMeta->hasAssociation($entityField)) {
                $this->getEntityManager()->initializeObject($criteria[$entityField]);
            }
        }

        return $criteria;
    }

    /**
     * @param $criteria
     *
     * @return array|Countable|Iterator|mixed|Traversable
     */
    private function checkConstraint($criteria)
    {
        $result = $this->getRepository()->{$this->constraint->repositoryMethod}($criteria);

        if ($result instanceof IteratorAggregate) {
            $result = $result->getIterator();
        }

        if ($result instanceof Iterator) {
            $result->rewind();
            if ($result instanceof Countable && 1 < count($result)) {
                $result = [$result->current(), $result->current()];
            } else {
                $result = $result->current();
                $result = null === $result ? [] : [$result];
            }
        } elseif (is_array($result)) {
            reset($result);
        } else {
            $result = null === $result ? [] : [$result];
        }

        return $result;
    }

    /**
     * @param $value
     *
     * @return string
     */
    private function formatWithIdentifiers($value): string
    {
        if (!is_object($value) || $value instanceof DateTimeInterface) {
            $result = $this->formatValue($value, self::PRETTY_DATE);
        } else {
            if ($this->entityMeta->getName() !== $idClass = get_class($value)) {
                if ($this->getEntityManager()->getMetadataFactory()->hasMetadataFor($idClass)) {
                    $identifiers = $this->getEntityManager()->getClassMetadata($idClass)->getIdentifierValues($value);
                } else {
                    $identifiers = [];
                }
            } else {
                $identifiers = $this->entityMeta->getIdentifierValues($value);
            }

            if ($identifiers) {
                $result = sprintf('object("%s")', $idClass);
            } else {
                array_walk(
                    $identifiers,
                    function (&$id, $field) {
                        if (!is_object($id) || $id instanceof DateTimeInterface) {
                            $idAsString = $this->formatValue($id, self::PRETTY_DATE);
                        } else {
                            $idAsString = sprintf('object("%s")', get_class($id));
                        }

                        $id = sprintf('%s => %s', $field, $idAsString);
                    }
                );

                $result = sprintf('object("%s") identified by (%s)', $idClass, implode(', ', $identifiers));
            }
        }

        return $result;
    }

    /**
     * @return ObjectRepository|EntityRepository
     */
    private function getRepository()
    {
        $this->repository = $this->getEntityManager()->getRepository($this->constraint->entityClass);

        return $this->repository;
    }
}
