<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/VinculacaoPessoaBarramentoResource.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoPessoaBarramento;
use SuppCore\AdministrativoBackend\Entity\VinculacaoPessoaBarramento as Entity;
use SuppCore\AdministrativoBackend\Repository\VinculacaoPessoaBarramentoRepository as Repository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class VinculacaoPessoaBarramentoResource.
 *
 *
 *
 * @codingStandardsIgnoreStart
 *
 * @method Repository  getRepository(): Repository
 * @method Entity[]    find(array $criteria = null, array $orderBy = null, int $limit = null, int $offset = null, array $search = null, array $populate = null): array
 * @method Entity|null findOne(int $id, bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null, bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method Entity      create(RestDtoInterface $dto, string $transactionId, bool $skipValidation = null): EntityInterface
 * @method Entity      update(int $id, RestDtoInterface $dto, string $transactionId, bool $skipValidation = null): EntityInterface
 * @method Entity      delete(int $id, string $transactionId): EntityInterface
 * @method Entity      save(EntityInterface $entity, string $transactionId, bool $skipValidation = null): EntityInterface
 *
 * @codingStandardsIgnoreEnd
 */
class VinculacaoPessoaBarramentoResource extends RestResource
{
/** @noinspection MagicMethodsValidityInspection */

    /**
     * VinculacaoPessoaBarramentoResource constructor.
     *
     * @param Repository         $repository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(VinculacaoPessoaBarramento::class);
    }
}
