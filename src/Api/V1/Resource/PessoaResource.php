<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/PessoaResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Pessoa as Entity;
use SuppCore\AdministrativoBackend\Entity\Pessoa as PessoaEntity;
use SuppCore\AdministrativoBackend\Repository\PessoaRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use SuppCore\AdministrativoBackend\Transaction\Context;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class PessoaResource.
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
class PessoaResource extends RestResource
{
    /** @noinspection MagicMethodsValidityInspection */

    /** PessoaResource constructor. */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(Pessoa::class);
    }

    /**
     * Procura a pessoa em diferentes contextos.
     *
     * @param string $numeroDocumentoPrincipal
     * @param string $transactionId
     *
     * @return PessoaEntity|null
     */
    public function findPessoaAdvanced(string $numeroDocumentoPrincipal, string $transactionId): ?Entity
    {
        $pessoaEntity = $this->getRepository()->findOneBy([
            'numeroDocumentoPrincipal' => $numeroDocumentoPrincipal,
        ]);

        if ($pessoaEntity) {
            return $pessoaEntity;
        }

        $persistEntities = $this->getRepository()
            ->getTransactionManager()
            ->getToPersistEntities(
                $transactionId
            );
        $pessoaPersistida = current(
            array_filter(
                $persistEntities,
                fn ($p) => $p instanceof PessoaEntity
                    && $p->getNumeroDocumentoPrincipal() === $numeroDocumentoPrincipal
            )
        );

        if ($pessoaPersistida) {
            return $pessoaPersistida;
        }

        $this->getRepository()
            ->getTransactionManager()
            ->addContext(
                new Context(
                    'cpf',
                    $numeroDocumentoPrincipal
                ),
                $transactionId
            );
        $pessoaContexto = current(
            $this->find(
                criteria: [
                    'numeroDocumentoPrincipal' => sprintf(
                        'eq:%s',
                        $numeroDocumentoPrincipal
                    ),
                ],
                limit: 1,
                offset: 0
            )['entities']
        );
        $this->getRepository()
            ->getTransactionManager()
            ->removeContext(
                'cpf',
                $transactionId
            );

        if ($pessoaContexto) {
            return $pessoaContexto;
        }

        return null;
    }
}
