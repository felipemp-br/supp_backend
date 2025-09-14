<?php

declare(strict_types=1);
/**
 * /src/Rest/RestResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest;

use BadMethodCallException;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Proxy\Proxy;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Exception;
use InvalidArgumentException;
use Redis;
use ReflectionException;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\MapperManager;
use SuppCore\AdministrativoBackend\Repository\BaseRepositoryInterface;
use SuppCore\AdministrativoBackend\Rest\Message\PushMessage;
use SuppCore\AdministrativoBackend\Rest\Traits\RestResourceLifeCycles;
use SuppCore\AdministrativoBackend\Rules\RulesManager;
use SuppCore\AdministrativoBackend\Triggers\TriggersManager;
use SuppCore\AdministrativoBackend\Utils\JSON;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\VarExporter\LazyObjectInterface;
use Symfony\Contracts\Service\Attribute\Required;
use UnexpectedValueException;

use function get_class;
use function sprintf;

/**
 * Class RestResource.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class RestResource implements RestResourceInterface
{
    // Attach generic life cycle traits
    use RestResourceLifeCycles;
    public RulesManager $rulesManager;
    public TriggersManager $triggersManager;
    protected Redis $redisClient;
    private BaseRepositoryInterface $repository;
    private ValidatorInterface $validator;
    private ?string $dtoClass = null;
    private MapperManager $dtoMapperManager;
    private string $formTypeClass;

    #[Required]
    public function setDependencies(
        RulesManager $rulesManager,
        TriggersManager $triggersManager,
        MapperManager $dtoMapperManager,
        Redis $redisClient,
    ): void {
        $this->rulesManager = $rulesManager;
        $this->triggersManager = $triggersManager;
        $this->dtoMapperManager = $dtoMapperManager;
        $this->redisClient = $redisClient;
    }



    /**
     * Getter method for entity repository.
     */
    public function getRepository(): BaseRepositoryInterface
    {
        return $this->repository;
    }

    /**
     * Getter method for redis.
     */
    public function getRedisClient(): Redis
    {
        return $this->redisClient;
    }

    /**
     * Setter method for repository.
     */
    public function setRepository(BaseRepositoryInterface $repository): RestResourceInterface
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * Getter for used validator.
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * Setter for used validator.
     */
    public function setValidator(ValidatorInterface $validator): RestResourceInterface
    {
        $this->validator = $validator;

        return $this;
    }

    public function processoDto(RestDtoInterface $dto): void
    {
        // classes filhas podem sobrescrever esta operacao
    }

    /**
     * Getter method for used DTO class for this REST service.
     *
     * @throws UnexpectedValueException
     */
    public function getDtoClass(): ?string
    {
        if ('' === $this->dtoClass) {
            $message = sprintf(
                'DTO class not specified for \'%s\' resource',
                static::class
            );

            throw new UnexpectedValueException($message);
        }

        return $this->dtoClass;
    }

    /**
     * Setter for used DTO class.
     */
    public function setDtoClass(string $dtoClass): RestResourceInterface
    {
        $this->dtoClass = $dtoClass;

        return $this;
    }

    /**
     * Getter method for used default FormType class for this REST resource.
     *
     * @throws UnexpectedValueException
     */
    public function getFormTypeClass(): string
    {
        if ('' === $this->formTypeClass) {
            $message = sprintf(
                'FormType class not specified for \'%s\' resource',
                static::class
            );

            throw new UnexpectedValueException($message);
        }

        return $this->formTypeClass;
    }

    /**
     * Setter method for used default FormType class for this REST resource.
     */
    public function setFormTypeClass(string $formTypeClass): RestResourceInterface
    {
        $this->formTypeClass = $formTypeClass;

        return $this;
    }

    /**
     * Getter method for current entity name.
     */
    public function getEntityName(): string
    {
        return $this->getRepository()->getEntityName();
    }

    /**
     * Gets a reference to the entity identified by the given type and identifier without actually loading it,
     * if the entity is not yet loaded.
     *
     * @param int $id the entity identifier
     *
     * @return Proxy|object|null
     *
     * @throws ORMException
     */
    public function getReference(int $id)
    {
        return $this->getRepository()->getReference($id);
    }

    /**
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function getAssociations(array $populate = [], ?string $dtoClass = null): array
    {
        $associations = [];
        $mapperMetadata = $this->dtoMapperManager->getMapperMetadata($dtoClass ?? $this->dtoClass);
        $entityMapping = [];
        if (null !== $mapperMetadata->getMapper()) {
            $entityMapping = $mapperMetadata->getMapper()->entityMapping;
        }

        foreach ($mapperMetadata->getProperties() as $property) {
            if (null === $property->dtoClass || !in_array($property->name, $populate, true)) {
                continue;
            }
            $associations[] = array_key_exists(
                $property->name,
                $entityMapping
            ) ? $entityMapping[$property->name] : $property->name;

            // nested?
            $subPopulate = $this->getSubPopulate($populate, $property->name);

            if ([] !== $subPopulate) {
                $subAssociations = $this->getAssociations($subPopulate, $property->dtoClass);
                foreach ($subAssociations as $subAssociation) {
                    $prefix = array_key_exists(
                        $property->name,
                        $entityMapping
                    ) ? $entityMapping[$property->name] : $property->name;
                    $associations[] = $prefix.'.'.$subAssociation;
                }
            }
        }

        return array_values($associations);
    }

    /**
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function getAllAssociations(): array
    {
        $associations = [];
        $mapperMetadata = $this->dtoMapperManager->getMapperMetadata($this->dtoClass);
        $excludePopulate = [];
        if (null !== $mapperMetadata->getMapper()) {
            $excludePopulate = $mapperMetadata->getMapper()->excludePopulate;
        }

        foreach ($mapperMetadata->getProperties() as $property) {
            if ((null !== $property->dtoClass) &&
                !in_array($property->name, $excludePopulate, true) &&
                (true !== $property->collection)) {
                $associations[] = $property->name;
            }
        }

        return array_values($associations);
    }

    /**
     * @return array
     */
    public function getSubPopulate(array $populate, string $property)
    {
        $subPopulate = [];
        foreach ($populate as $p) {
            if (0 === strpos((string) $p, $property.'.')) {
                $subPopulate[] = str_replace($property.'.', '', (string) $p);
            }
        }

        return $subPopulate;
    }

    /**
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function getDtoForEntity(
        int $id,
        string $dtoClassName,
        ?RestDtoInterface $dto = null,
        ?EntityInterface $entity = null
    ): RestDtoInterface {
        // Fetch entity
        if (null === $entity) {
            $entity = $this->getEntity($id);
        }

        $dtoMapper = $this->dtoMapperManager->getMapper($dtoClassName);

        // Create new instance of DTO and load entity to that.
        $restDto = $dtoMapper->createDTOFromEntity($dtoClassName, $entity);

        if (null !== $dto) {
            $restDto = $dtoMapper->patch($dto, $restDto);
        }

        return $restDto;
    }

    /**
     * Generic find method to return an array of items from database. Return value is an array of specified repository
     * entities.
     *
     * @param mixed[]|null $criteria
     * @param mixed[]|null $orderBy
     * @param mixed[]|null $search
     * @param mixed[]|null $populate
     *
     * @return EntityInterface[]
     *
     * @throws InvalidArgumentException
     */
    public function find(
        ?array $criteria = null,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null,
        ?array $search = null,
        ?array $populate = null
    ): array {
        $criteria ??= [];
        $orderBy ??= [];
        $limit ??= 0;
        $offset ??= 0;
        $search ??= [];
        $populate ??= [];
        $result = [];

        $className = $this->getRepository()->getEntityName();

        // Before callback method call
        $this->beforeFind($className, $criteria, $orderBy, $limit, $offset, $populate, $result);

        // Fetch data
        $result = $this->getRepository()->findByAdvanced($criteria, $orderBy, $limit, $offset, $search, $populate);

        // After callback method call
        $this->afterFind($className, $criteria, $orderBy, $limit, $offset, $populate, $result);

        return $result;
    }

    /**
     * Generic findOne method to return single item from database. Return value is single entity from specified
     * repository.
     */
    public function findOne(
        int $id,
        ?array $populate = null,
        ?array $context = null,
        ?array $orderBy = null
    ): ?EntityInterface {
        $className = $this->getRepository()->getEntityName();
        $entity = null;

        // Before callback method call
        $this->beforeFindOne($className, $id, $populate, $orderBy, $context, $entity);

        /** @var EntityInterface|null $entity */
        $entity = $this->getRepository()->find($id, $populate, $orderBy);

        if (!$entity) {
            throw new NotFoundHttpException('Não encontrado');
        }

        // After callback method call
        $this->afterFindOne($className, $id, $populate, $orderBy, $context, $entity);

        return $entity;
    }

    /**
     * Generic findOneBy method to return single item from database by given criteria. Return value is single entity
     * from specified repository or null if entity was not found.
     *
     * @param mixed[]      $criteria
     * @param mixed[]|null $orderBy
     *
     * @throws NotFoundHttpException
     */
    public function findOneBy(
        array $criteria,
        ?array $orderBy = null,
        ?bool $throwExceptionIfNotFound = null
    ): ?EntityInterface {
        $orderBy ??= [];
        $throwExceptionIfNotFound ??= false;

        // Before callback method call
        $this->beforeFindOneBy($criteria, $orderBy);

        $entity = $this->getRepository()->findOneBy($criteria, $orderBy);

        // Entity not found
        if ($throwExceptionIfNotFound && !$entity instanceof \SuppCore\AdministrativoBackend\Entity\EntityInterface) {
            throw new NotFoundHttpException('Not found');
        }

        // After callback method call
        $this->afterFindOneBy($criteria, $orderBy, $entity);

        return $entity;
    }

    /**
     * Generic count method to return entity count for specified criteria and search terms.
     *
     * @param mixed[]|null $criteria
     * @param mixed[]|null $search
     *
     * @throws InvalidArgumentException
     * @throws NonUniqueResultException
     */
    public function count(?array $criteria = null, ?array $search = null): int
    {
        $criteria ??= [];
        $search ??= [];

        // Before callback method call
        $this->beforeCount($criteria, $search);

        $count = $this->getRepository()->countAdvanced($criteria, $search);

        // After callback method call
        $this->afterCount($criteria, $search, $count);

        return $count;
    }

    /**
     * Generic method to create new item (entity) to specified database repository. Return value is created entity for
     * specified repository.
     *
     * @throws Exception
     * @throws ValidatorException
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     * @throws ORMException
     */
    public function create(RestDtoInterface $dto, string $transactionId, ?bool $skipValidation = null): EntityInterface
    {
        $skipValidation ??= false;

        // Create new entity
        $entity = $this->createEntity();

        $this->processoDto($dto);

        // Before callback method call
        $this->beforeCreate($dto, $entity, $transactionId);

        // Validate DTO
        $this->validateDto($dto, $skipValidation);

        // Create or update entity
        $this->persistEntity($entity, $dto, $transactionId, $skipValidation);

        // After callback method call
        $this->afterCreate($dto, $entity, $transactionId);

        $this->redisClient->del($this->getDtoClass());

        return $entity;
    }

    /**
     * Generic method to update specified entity with new data.
     *
     * @throws BadMethodCallException
     * @throws Exception
     * @throws ValidatorException
     * @throws NotFoundHttpException
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     * @throws ORMException
     */
    public function update(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        ?bool $skipValidation = null
    ): EntityInterface {
        $skipValidation ??= false;

        // Fetch entity
        $entity = $this->getEntity($id);

        /**
         * Determine used dto class and create new instance of that and load entity to that. And after that patch
         * that dto with given partial OR whole dto class.
         */
        $restDto = $this->getDtoForEntity($id, get_class($dto), $dto, $entity);
        $this->processoDto($restDto);

        // Before callback method call
        $this->beforeUpdate($id, $restDto, $entity, $transactionId);

        // Validate DTO
        $this->validateDto($restDto, $skipValidation);

        // Create or update entity
        $this->persistEntity($entity, $restDto, $transactionId);

        // After callback method call
        $this->afterUpdate($id, $restDto, $entity, $transactionId);

        $this->redisClient->del($this->getDtoClass());

        return $entity;
    }

    /**
     * Generic method to delete specified entity from database.
     *
     * @throws NotFoundHttpException
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     * @throws ORMException
     */
    public function delete(int $id, string $transactionId): EntityInterface
    {
        // Fetch entity
        $entity = $this->getEntity($id);

        $restDto = $this->getDtoForEntity($id, $this->getDtoClass(), null, $entity);
        $this->processoDto($restDto);

        $restDto = $this->getDtoForEntity($id, $this->getDtoClass(), null, $entity);
        $this->processoDto($restDto);

        // Before callback method call
        $this->beforeDelete($id, $restDto, $entity, $transactionId);

        // And remove entity from repo
        $this->getRepository()->remove($entity, $transactionId);

        // After callback method call
        $this->afterDelete($id, $restDto, $entity, $transactionId);

        $this->redisClient->del($this->getDtoClass());

        return $entity;
    }

    /**
     * Generic method to undelete specified entity from database.
     *
     * @throws NotFoundHttpException
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     * @throws ORMException
     */
    public function undelete(int $id, string $transactionId): EntityInterface
    {
        // Fetch entity
        $entity = $this->getRepository()->findDeleted($id);

        $restDto = $this->getDtoForEntity($id, $this->getDtoClass(), null, $entity);
        $this->processoDto($restDto);

        // Before callback method call
        $this->beforeUndelete($id, $restDto, $entity, $transactionId);

        // And remove entity from repo
        $this->getRepository()->unremove($entity, $transactionId);

        // After callback method call
        $this->afterUndelete($id, $restDto, $entity, $transactionId);

        $this->redisClient->del($this->getDtoClass());

        return $entity;
    }

    /**
     * Generic ids method to return an array of id values from database. Return value is an array of specified
     * repository entity id values.
     *
     * @param mixed[]|null $criteria
     * @param mixed[]|null $search
     *
     * @return string[]|array<mixed, mixed>
     *
     * @throws InvalidArgumentException
     */
    public function getIds(?array $criteria = null, ?array $search = null): array
    {
        $criteria ??= [];
        $search ??= [];

        // Before callback method call
        $this->beforeIds($criteria, $search);

        // Fetch data
        $ids = $this->getRepository()->findIds($criteria, $search);

        // After callback method call
        $this->afterIds($ids, $criteria, $search);

        return $ids;
    }

    /**
     * Generic method to save given entity to specified repository. Return value is created entity.
     *
     * @throws Exception
     * @throws ValidatorException
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     * @throws ORMException
     */
    public function save(EntityInterface $entity, string $transactionId, ?bool $skipValidation = null): EntityInterface
    {
        $skipValidation ??= false;

        // Before callback method call
        $this->beforeSave($entity, $transactionId);

        // Validate current entity
        $this->validateEntity($entity, $skipValidation);

        // Persist on database
        $this->getRepository()->save($entity, $transactionId);

        // After callback method call
        $this->afterSave($entity, $transactionId);

        return $entity;
    }

    public function push(
        EntityInterface $entity,
        string $channel,
        string $transactionId,
        array $populate = []
    ): void {
        $pushMessage = new PushMessage();
        $pushMessage->setUuid($entity->getUuid());
        $pushMessage->setResource(
            $this instanceof LazyObjectInterface ? get_parent_class($this) : static::class
        );
        $pushMessage->setChannel($channel);
        $pushMessage->setPopulate($populate);

        $this->getRepository()->getTransactionManager()->addAsyncDispatch($pushMessage, $transactionId);
    }

    public function getDtoMapperManager(): MapperManager
    {
        return $this->dtoMapperManager;
    }

    /**
     * Helper method to set data to specified entity and store it to database.
     *
     * @throws Exception
     * @throws ValidatorException
     * @throws ORMException
     * @throws ORMInvalidArgumentException
     * @throws OptimisticLockException
     */
    protected function persistEntity(
        EntityInterface $entity,
        RestDtoInterface $dto,
        string $transactionId,
        ?bool $skipValidation = null
    ): void {
        $skipValidation ??= false;

        // Update entity according to DTO current state
        $dtoMapper = $this->dtoMapperManager->getMapper(get_class($dto));
        $dtoMapper->update($entity, $dto);

        // And save current entity
        $this->save($entity, $transactionId, $skipValidation);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function getEntity(int $id): ?EntityInterface
    {
        $entity = $this->getRepository()->find($id);

        // Entity not found
        if (!$entity instanceof \SuppCore\AdministrativoBackend\Entity\EntityInterface) {
            throw new NotFoundHttpException('Not found');
        }

        return $entity;
    }

    /**
     * Helper method to validate given DTO class.
     *
     * @throws Exception
     * @throws ValidatorException
     */
    protected function validateDto(RestDtoInterface $dto, bool $skipValidation): void
    {
        /** @var ConstraintViolationListInterface|null $errors */
        $errors = $skipValidation ? null : $this->getValidator()->validate($dto, null, ['Default', 'Resource']);

        // Oh noes, we have some errors
        if (null !== $errors && $errors->count() > 0) {
            $this->createValidatorException($errors);
        }
    }

    /**
     * @psalm-suppress MoreSpecificReturnType
     */
    protected function createEntity(): EntityInterface
    {
        $entityClass = $this->getRepository()->getEntityName();

        return new $entityClass();
    }

    /**
     * Method to validate specified entity.
     *
     * @throws Exception
     * @throws ValidatorException
     */
    private function validateEntity(EntityInterface $entity, bool $skipValidation): void
    {
        /** @var ConstraintViolationListInterface|null $errors */
        $errors = $skipValidation ? null : $this->getValidator()->validate($entity);

        // Oh noes, we have some errors
        if (null !== $errors && $errors->count() > 0) {
            $this->createValidatorException($errors);
        }
    }

    /**
     * @throws Exception
     * @throws ValidatorException
     */
    private function createValidatorException(ConstraintViolationListInterface $errors): void
    {
        $output = [];

        /**
         * @var ConstraintViolationInterface $error
         */
        foreach ($errors as $error) {
            $output[] = [
                'message' => $error->getMessage(),
                'propertyPath' => $error->getPropertyPath(),
                'code' => $error->getCode(),
            ];
        }

        throw new ValidatorException(JSON::encode($output));
    }
}
