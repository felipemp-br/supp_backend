<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/AfastamentoResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Afastamento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Afastamento as Entity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\AfastamentoRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AfastamentoResource.
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
class AfastamentoResource extends RestResource
{
    /** @noinspection MagicMethodsValidityInspection */

    /**
     * AfastamentoResource constructor.
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(Afastamento::class);
    }

    /**
     * Método que verifica e remove da lista os usuários que estão afastados.
     *
     * @param Usuario[] $usuarios
     *
     * @return Usuario[]
     */
    public function limpaListaUsuariosAfastados(array $usuarios, ?DateTime $finalPrazo): array
    {
        foreach ($usuarios as $i => $usuario) {
            $temAfastamento = $this->getRepository()
                ->findAfastamento($usuario->getColaborador()->getId(), $finalPrazo);

            if ($temAfastamento) {
                unset($usuarios[$i]);
            }
        }

        return $usuarios;
    }
}
