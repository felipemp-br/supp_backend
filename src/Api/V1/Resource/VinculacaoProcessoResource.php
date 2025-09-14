<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/VinculacaoProcessoResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use Doctrine\Common\Collections\ArrayCollection;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso as Entity;
use SuppCore\AdministrativoBackend\Repository\VinculacaoProcessoRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class VinculacaoProcessoResource.
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
class VinculacaoProcessoResource extends RestResource
{
    /** @noinspection MagicMethodsValidityInspection */


    public function __construct(
        Repository $repository,
        ValidatorInterface $validator,
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(VinculacaoProcesso::class);
    }

    public function findAllVinculacoesByProcesso(int $processoId): array {
        $data = new ArrayCollection([]);

        $vinculacaoProcesso = $this->getRepository()->findOneBy(['processo' => $processoId]);

        if (!$vinculacaoProcesso) {
            $vinculacaoProcesso = $this->getRepository()->findOneBy(['processoVinculado' => $processoId]);
        }

        if ($vinculacaoProcesso) {
            $data = $vinculacaoProcesso->getProcesso()->getVinculacoesProcessos();
        }

        return [
            'total' => $data->count(),
            'entities' => $data
        ];
    }
}
