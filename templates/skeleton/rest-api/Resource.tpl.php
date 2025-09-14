<?= "<?php\n" ?>
declare(strict_types = 1);
/**
 * /src/Resource/<?= $resourceName ?>.php
 *
 * @author  <?= $author . "\n" ?>
 */
namespace SuppCore\AdministrativoBackend\Resource;

use SuppCore\AdministrativoBackend\DTO\<?= $entityName ?>;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\<?= $entityName ?> as Entity;
use SuppCore\AdministrativoBackend\Repository\<?= $repositoryName ?> as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @noinspection PhpHierarchyChecksInspection */
/** @noinspection PhpMissingParentCallCommonInspection */

/**
 * Class <?= $resourceName . "\n" ?>
 *
 * @package SuppCore\AdministrativoBackend\Resource
 * @author  <?= $author . "\n" ?>
 *
 * @codingStandardsIgnoreStart
 *
 * @method Repository  getRepository(): Repository
 * @method Entity[]    find(array $criteria = null, array $orderBy = null, int $limit = null, int $offset = null, array $search = null): array
 * @method Entity|null findOne(int $id, bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null, bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method Entity      create(RestDtoInterface $dto, bool $skipValidation = null): EntityInterface
 * @method Entity      update(int $id, RestDtoInterface $dto, bool $skipValidation = null): EntityInterface
 * @method Entity      delete(int $id): EntityInterface
 * @method Entity      save(EntityInterface $entity, bool $skipValidation = null): EntityInterface
 *
 * @codingStandardsIgnoreEnd
 */
class <?= $resourceName ?> extends RestResource
{
    /** @noinspection PhpMissingParentConstructorInspection */
    /** @noinspection MagicMethodsValidityInspection */

    /**
     * <?= $resourceName ?> constructor.
     *
     * @param Repository $repository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(<?= $entityName ?>::class);
    }
}
