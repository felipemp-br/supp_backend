<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/StatusBarramentoResource.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use Doctrine\Common\Annotations\AnnotationException;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\StatusBarramento;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoClient;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\StatusBarramento as Entity;
use SuppCore\AdministrativoBackend\Repository\StatusBarramentoRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class StatusBarramentoResource.
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
class StatusBarramentoResource extends RestResource
{
    private ProcessoResource $processoResource;

    /**
     * @param Repository         $repository
     * @param ValidatorInterface $validator
     * @param ProcessoResource   $processoResource
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator,
        ProcessoResource $processoResource,
        private BarramentoClient $barramentoClient
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(StatusBarramento::class);
        $this->processoResource = $processoResource;
    }

    /**
     * @param int    $id
     * @param string $transactionId
     *
     * @return EntityInterface|null
     *
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function sincronizaBarramento(int $id, string $transactionId): ?EntityInterface
    {
        $entity = $this->processoResource->findOne($id);
        $dto = $this->getDtoForEntity(
            $id,
            Processo::class,
            null,
            $entity
        );

        $this->beforeSincronizaBarramento($dto, $entity, $transactionId);
        $this->afterSincronizaBarramento($dto, $entity, $transactionId);

        return $entity;
    }

    /**
     * Before lifecycle method for SincronizaBarramento method.
     */
    public function beforeSincronizaBarramento(
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertSincronizaBarramento');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeSincronizaBarramento');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeSincronizaBarramento');
    }

    /**
     * After lifecycle method for SincronizaBarramento method.
     */
    public function afterSincronizaBarramento(
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterSincronizaBarramento');
    }

    /**
     * @param int    $id
     * @param string $transactionId
     *
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function consultaTramitesBarramento(int $id, string $transactionId)
    {
        $processoEntity = $this->processoResource->findOne($id);
        $processoDto = $this->getDtoForEntity(
            $id,
            Processo::class,
            null,
            $processoEntity
        );

        $nupBarramento = $processoEntity->getOutroNumero() ?: $processoEntity->getNUPFormatado();

        $responseConsultaTramites = $this->barramentoClient->consultarTramites(null, $nupBarramento);
        if (is_array($responseConsultaTramites->tramitesEncontrados->tramite)) {
            $responseConsultaTramites->tramitesEncontrados->tramite =
                array_reverse($responseConsultaTramites->tramitesEncontrados->tramite);
        }

        return $responseConsultaTramites->tramitesEncontrados;
    }
}
