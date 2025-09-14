<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/EspecieAtividadeResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieAtividade;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\EspecieAtividade as Entity;
use SuppCore\AdministrativoBackend\Mapper\DefaultMapper;
use SuppCore\AdministrativoBackend\Repository\EspecieAtividadeRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class EspecieAtividadeResource.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
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
class EspecieAtividadeResource extends RestResource
{
    /** @noinspection MagicMethodsValidityInspection */

    /**
     * EspecieAtividadeResource constructor.
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator,
        DefaultMapper $defaultMapper
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(EspecieAtividade::class);
    }
}
