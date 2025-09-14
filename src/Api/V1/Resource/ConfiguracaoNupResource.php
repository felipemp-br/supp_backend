<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/ConfiguracaoNupResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ConfiguracaoNup;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ConfiguracaoNup as Entity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\NUP\NUPProviderManager;
use SuppCore\AdministrativoBackend\Repository\ConfiguracaoNupRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ConfiguracaoNupResource.
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
class ConfiguracaoNupResource extends RestResource
{
/** @noinspection MagicMethodsValidityInspection */
    private NUPProviderManager $nupProviderManager;

    /**
     * ConfiguracaoNupResource constructor.
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator,
        NUPProviderManager $nupProviderManager
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(ConfiguracaoNup::class);
        $this->nupProviderManager = $nupProviderManager;
    }

    public function validarNup(Processo $processo): Processo
    {
        $errorMessage = '';
        $validarNup = $this->nupProviderManager->getNupProvider(
            $processo
        )->validarNumeroUnicoProtocolo($processo, $errorMessage);

        if ($errorMessage) {
            throw new RuleException($errorMessage, 422);
        }

        $processo->setNupInvalido($validarNup);

        return $processo;
    }
}
