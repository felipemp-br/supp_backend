<?php

declare(strict_types=1);
/**
 * /src/Rest/RestResourceInterfaces.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest;

use BadMethodCallException;
use Doctrine\Common\Proxy\Proxy;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use InvalidArgumentException;
use LogicException;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\BaseRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use UnexpectedValueException;

/**
 * Interface ResourceInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface RestResourceInterface
{
    /**
     * Getter method for entity repository.
     */
    public function getRepository(): BaseRepositoryInterface;

    /**
     * Setter method for repository.
     */
    public function setRepository(BaseRepositoryInterface $repository): self;

    public function push(
        EntityInterface $entity,
        string $username,
        string $transactionId,
        array $populate = []
    ): void;

    /**
     * Getter for used validator.
     */
    public function getValidator(): ValidatorInterface;

    /**
     * Setter for used validator.
     */
    public function setValidator(ValidatorInterface $validator): self;

    /**
     * Getter method for used DTO class for this REST service.
     *
     * @throws UnexpectedValueException
     */
    public function getDtoClass(): ?string;

    /**
     * Setter for used DTO class.
     */
    public function setDtoClass(string $dtoClass): self;

    /**
     * Getter method for used default FormType class for this REST resource.
     */
    public function getFormTypeClass(): string;

    /**
     * Setter method for used default FormType class for this REST resource.
     */
    public function setFormTypeClass(string $formTypeClass): self;

    /**
     * Getter method for current entity name.
     */
    public function getEntityName(): string;

    /**
     * @noinspection GenericObjectTypeUsageInspection
     */

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
    public function getReference(int $id);

    /**
     * Getter method for all associations that current entity contains.
     *
     * @return string[]|array<int, string>
     */
    public function getAssociations(array $populate = []): array;

    /**
     * Getter method DTO class with loaded entity data.
     *
     * @throws NotFoundHttpException
     */
    public function getDtoForEntity(int $id, string $dtoClass): RestDtoInterface;

    /**
     * Generic find method to return an array of items from database. Return value is an array of specified repository
     * entities.
     *
     * @param mixed[]|null $criteria
     * @param mixed[]|null $orderBy
     * @param mixed[]|null $populate
     *
     * @return EntityInterface[]
     */
    public function find(
        ?array $criteria = null,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null,
        ?array $context = null,
        ?array $populate = null
    ): array;

    public function findOne(int $id, ?array $populate = null, ?array $context = null): ?EntityInterface;

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
    ): ?EntityInterface;

    /**
     * Generic count method to return entity count for specified criteria and search terms.
     *
     * @param mixed[]|null $criteria
     * @param mixed[]|null $search
     *
     * @throws InvalidArgumentException
     * @throws NonUniqueResultException
     */
    public function count(?array $criteria = null, ?array $search = null): int;

    /**
     * Generic method to create new item (entity) to specified database repository. Return value is created entity for
     * specified repository.
     *
     * @throws ValidatorException
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     * @throws ORMException
     */
    public function create(RestDtoInterface $dto, string $transactionId, ?bool $skipValidation = null): EntityInterface;

    /**
     * Generic method to update specified entity with new data.
     *
     * @throws LogicException
     * @throws BadMethodCallException
     * @throws NotFoundHttpException
     * @throws ValidatorException
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     * @throws ORMException
     */
    public function update(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        ?bool $skipValidation = null
    ): EntityInterface;

    /**
     * Generic method to delete specified entity from database.
     *
     * @throws NotFoundHttpException
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     * @throws ORMException
     */
    public function delete(int $id, string $transactionId): EntityInterface;

    /**
     * Generic ids method to return an array of id values from database. Return value is an array of specified
     * repository entity id values.
     *
     * @param mixed[]|null $criteria
     * @param mixed[]|null $search
     *
     * @return string[]
     *
     * @throws InvalidArgumentException
     */
    public function getIds(?array $criteria = null, ?array $search = null): array;

    /**
     * Generic method to save given entity to specified repository. Return value is created entity.
     *
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     * @throws ORMException
     * @throws ValidatorException
     */
    public function save(EntityInterface $entity, string $transactionId, ?bool $skipValidation = null): EntityInterface;
}
