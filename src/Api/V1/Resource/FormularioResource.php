<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/FormularioResource.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Formulario;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Formulario as Entity;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Helpers\JsonSchemaHelper;
use SuppCore\AdministrativoBackend\Repository\FormularioRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class FormularioResource.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @codingStandardsIgnoreStart
 *
 * @method Repository  getRepository()                                                                            : Repository
 * @method Entity[]    find(array $criteria = null, array $orderBy = null, int $limit = null, int $offset = null, array $search = null, array $populate = null) : array
 * @method Entity|null findOne(int $id, ?array $populate = null)                                                  : ?EntityInterface
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null, bool $throwExceptionIfNotFound = null)   : ?EntityInterface
 * @method Entity      create(RestDtoInterface $dto, string $transactionId, bool $skipValidation = null)          : EntityInterface
 * @method Entity      update(int $id, RestDtoInterface $dto, string $transactionId, bool $skipValidation = null) : EntityInterface
 * @method Entity      delete(int $id, string $transactionId)                                                     : EntityInterface
 * @method Entity      save(EntityInterface $entity, string $transactionId, bool $skipValidation = null)          : EntityInterface
 *
 * @codingStandardsIgnoreEnd
 */
class FormularioResource extends RestResource
{
    /**
     * Constructor.
     *
     * @param Repository         $repository
     * @param ValidatorInterface $validator
     * @param JsonSchemaHelper   $jsonSchemaHelper
     *
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator,
        private readonly JsonSchemaHelper $jsonSchemaHelper,
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(Formulario::class);
    }

    /**
     * Retorna os campos do formulário para ia.
     *
     * @param int $id
     *
     * @return array
     */
    public function getFormularioJsonSchemaFields(int $id): array
    {
        $result = [];
        $className = $this->getRepository()->getEntityName();
        $criteria = ['id' => sprintf('eq:%s', $id)];

        // Before callback method call
        $this->beforeGetFormularioJsonSchemaFields($className, $criteria, $result);
        /** @var Entity $formulario */
        $formulario = $this->getEntity($id);

        if (!$formulario) {
            throw new NotFoundHttpException('Formulário não encontrado.');
        }
        $result = $this->jsonSchemaHelper->getLinearJsonSchemaInfoProperties($formulario->getDataSchema());

        // After callback method call
        $this->afterGetFormularioJsonSchemaFields($className, $criteria, $result);

        return $result;
    }

    /**
     * Before lifecycle method for getFormularioJsonSchemaFields method.
     *
     * @param string $className
     * @param array  $criteria
     * @param array  $result
     */
    public function beforeGetFormularioJsonSchemaFields(
        string $className,
        array $criteria,
        array $result
    ): void {
        $orderBy = [];
        $populate = [];
        $limit = 999;
        $offset = 0;
        $this->triggersManager->proccessRead(
            $className,
            $criteria,
            $orderBy,
            $limit,
            $offset,
            $populate,
            $result,
            'beforeGetFormularioJsonSchemaFields'
        );
    }

    /**
     * After lifecycle method for afterGetFormularioJsonSchemaFields method.
     *
     * @param string $className
     * @param array  $criteria
     * @param array  $result
     */
    public function afterGetFormularioJsonSchemaFields(
        string $className,
        array $criteria,
        array $result
    ): void {
        $orderBy = [];
        $populate = [];
        $limit = 999;
        $offset = 0;
        $this->triggersManager->proccessRead(
            $className,
            $criteria,
            $orderBy,
            $limit,
            $offset,
            $populate,
            $result,
            'afterGetFormularioJsonSchemaFields'
        );
    }
}
