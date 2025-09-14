<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/VinculacaoEtiquetaResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\OptimisticLockException;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta as Entity;
use SuppCore\AdministrativoBackend\Repository\VinculacaoEtiquetaRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class VinculacaoEtiquetaResource.
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
class VinculacaoEtiquetaResource extends RestResource
{
    /** @noinspection MagicMethodsValidityInspection */

    /**
     * VinculacaoEtiquetaResource constructor.
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(VinculacaoEtiqueta::class);
    }

    /**
     * @param int $id
     * @param RestDtoInterface $dto
     * @param string $transactionId
     * @param bool|null $skipValidation
     * @return EntityInterface|null
     * @throws AnnotationException
     * @throws OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     * @throws ReflectionException
     */
    public function aprovarSugestao(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        ?bool $skipValidation = null ): ?EntityInterface
    {
        $skipValidation ??= false;

        // Fetch entity
        $entity = $this->getEntity($id);

        /**
         * Determine used dto class and create new instance of that and load entity to that. And after that patch
         * that dto with given partial OR whole dto class.
         */
        $restDto = $this->getDtoForEntity($id, get_class($dto), $dto);

        // Validate DTO
        $this->validateDto($restDto, $skipValidation);

        // Before callback method call
        $this->beforeAprovarSugestao($restDto, $entity, $transactionId);

        // update entity
        $this->update($id, $restDto, $transactionId, $skipValidation);

        // After callback method call
        $this->afterAprovarSugestao($restDto, $entity, $transactionId);

        return $entity;
    }

    /**
     * @param RestDtoInterface $dto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return void
     */
    protected function beforeAprovarSugestao(
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertAprovarSugestao');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeAprovarSugestao');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeAprovarSugestao');
    }

    /**
     * @param RestDtoInterface $dto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return void
     */
    public function afterAprovarSugestao(
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void
    {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterAprovarSugestao');
    }
}
