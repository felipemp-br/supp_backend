<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/TeseResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tese as DTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tese as TeseDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoMetadados as VinculacaoMetadadosDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoOrgaoCentralMetadados as VinculacaoOrgaoCentralMetadadosDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoTese as VinculacaoTeseDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tese as Entity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoOrgaoCentralMetadados;
use SuppCore\AdministrativoBackend\Entity\VinculacaoTese;
use SuppCore\AdministrativoBackend\Repository\TeseRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class TeseResource.
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
class TeseResource extends RestResource
{
    /** @noinspection MagicMethodsValidityInspection */

    /**
     * TeseResource constructor.
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator,
        private VinculacaoMetadadosResource $vinculacaoMetadadosResource,
        private VinculacaoOrgaoCentralMetadadosResource $vinculacaoOrgaoCentralMetadadosResource,
        private VinculacaoTeseResource $vinculacaoTeseResource
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(DTO::class);
    }

    /**
     * @param int                      $teseOrigemId
     * @param int                      $teseDestinoId
     * @param TeseDTO|RestDtoInterface $teseOrigemDTO
     * @param TeseDTO|RestDtoInterface $teseDestinoDTO
     * @param string                   $transactionId
     *
     * @return Entity
     */
    public function mergeTeses(
        int $teseOrigemId,
        int $teseDestinoId,
        TeseDTO|RestDtoInterface $teseOrigemDTO,
        TeseDTO|RestDtoInterface $teseDestinoDTO,
        string $transactionId
    ): Entity {
        $teseOrigemEntity = $this->findOne($teseOrigemId);
        $teseDestinoEntity = $this->findOne($teseDestinoId);

        $this->beforeMergeTeses($teseOrigemId, $teseOrigemDTO, $teseOrigemEntity, $transactionId);

        $teseDestinoDTO->setEnunciado($teseDestinoEntity->getEnunciado().' '.$teseOrigemEntity->getEnunciado());
        $teseDestinoDTO->setEmenta($teseDestinoEntity->getEmenta().' '.$teseOrigemEntity->getEmenta());
        $teseDestinoDTO->setKeywords($teseDestinoEntity->getKeywords().' '.$teseOrigemEntity->getKeywords());

        foreach ($teseOrigemEntity->getVinculacoesMetadados()->toArray() as $vinculacaoMetadado) {
            $vincMetadadoDTO = $this->vinculacaoMetadadosResource->getDtoForEntity(
                $vinculacaoMetadado->getId(),
                VinculacaoMetadadosDTO::class
            );
            $vincMetadadoDTO->setTese($teseDestinoEntity);
            $this->vinculacaoMetadadosResource->update($vinculacaoMetadado->getId(), $vincMetadadoDTO, $transactionId);
        }

        foreach ($teseOrigemEntity->getVinculacoesOrgaoCentralMetadados()->toArray() as $vOrgaoCentralMetadados) {
            $vincOrgDTO = $this->vinculacaoOrgaoCentralMetadadosResource->getDtoForEntity(
                $vOrgaoCentralMetadados->getId(),
                VinculacaoOrgaoCentralMetadadosDTO::class
            );
            $vincOrgDTO->setTese($teseDestinoEntity);
            $this->vinculacaoOrgaoCentralMetadadosResource->update($vOrgaoCentralMetadados->getId(), $vincOrgDTO, $transactionId);
        }

        foreach ($teseOrigemEntity->getVinculacoesTeses()->toArray() as $vincTese) {
            $vincTeseDTO = $this->vinculacaoTeseResource->getDtoForEntity(
                $vincTese->getId(),
                VinculacaoTeseDTO::class
            );
            $vincTeseDTO->setTese($teseDestinoEntity);
            $this->vinculacaoTeseResource->update($vincTese->getId(), $vincTeseDTO, $transactionId);
        }

        $this->update($teseDestinoEntity->getId(), $teseDestinoDTO, $transactionId);
        $teseOrigemDTO->setAtivo(false);
        $this->update($teseOrigemEntity->getId(), $teseOrigemDTO, $transactionId);

        $this->afterMergeTeses($teseOrigemId, $teseOrigemDTO, $teseOrigemEntity, $transactionId);

        return $teseOrigemEntity;
    }

    public function beforeMergeTeses(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->rulesManager->proccess(
            $dto,
            $entity,
            $transactionId,
            'assertMergeTeses'
        );
        $this->triggersManager->proccess(
            $dto,
            $entity,
            $transactionId,
            'beforeMergeTeses'
        );
        $this->rulesManager->proccess(
            $dto,
            $entity,
            $transactionId,
            'beforeMergeTeses'
        );
    }

    public function afterMergeTeses(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->triggersManager->proccess(
            $dto,
            $entity,
            $transactionId,
            'afterMergeTeses'
        );
    }
}
