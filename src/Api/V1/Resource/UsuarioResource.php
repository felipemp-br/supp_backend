<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/UsuarioResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use DateInterval;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use function array_filter;
use function array_values;
use DateTime;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use function in_array;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario as Entity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoRole;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use SuppCore\AdministrativoBackend\Security\RolesService;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UsuarioResource.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @codingStandardsIgnoreStart
 *
 * @method Repository  getRepository(): Repository
 * @method Entity[]    find(?array $criteria = null, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?array $search = null): array
 * @method Entity|null findOne(int $id, ?bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method Entity|null findOneBy(array $criteria, ?array $orderBy = null, ?bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method Entity      create(RestDtoInterface $dto, string $transactionId, ?bool $skipValidation = null): EntityInterface
 * @method Entity update(int $id, RestDtoInterface $dto, string $transactionId, ?bool $skipValidation = null) : EntityInterface
 * @method Entity      delete(int $id, string $transactionId): EntityInterface
 * @method Entity      save(EntityInterface $entity, string $transactionId, ?bool $skipValidation = null): EntityInterface
 *
 * @codingStandardsIgnoreEnd
 */
class UsuarioResource extends RestResource
{
/** @noinspection MagicMethodsValidityInspection */

    /**
     * UsuarioResource constructor.
     */
    public function __construct(Repository $repository,
                                ValidatorInterface $validator,
                                private RolesService $rolesService,
                                private ParameterBagInterface $parameterBag) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(Usuario::class);

    }

    /**
     * Method to fetch users for specified user group, note that this method will also check user role inheritance so
     * return value will contain all users that belong to specified user group via role inheritance.
     *
     * @return Entity[]
     */
    public function getUsersForGroup(VinculacaoRole $vinculacaoRole): array
    {
        /**
         * Filter method to see if specified user belongs to certain user group.
         *
         * @param Entity $usuario
         *
         * @return bool
         */
        $filter = fn (Entity $usuario): bool => in_array(
            $vinculacaoRole->getRole(),
            $this->rolesService->getUserRolesNames($usuario),
            true
        );

        /** @var Entity[] $usuarios */
        $usuarios = $this->find();

        return array_values(array_filter($usuarios, $filter));
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function resetaSenha(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        ?bool $skipValidation = null
    ): EntityInterface {
        $skipValidation ??= false;

        /** @var Usuario $entity */
        $entity = $this->getEntity($id);
        $strongPassword = $this->generateStrongPassword();

        /**
         * Determine used dto class and create new instance of that and load entity to that. And after that patch
         * that dto with given partial OR whole dto class.
         */
        $restDto = $this->getDtoForEntity($id, get_class($dto), $dto);
        $restDto->setPlainPassword($strongPassword);

        // Validate DTO
        $this->validateDto($restDto, $skipValidation);

        // Before callback method call
        $this->beforeResetaSenha($id, $restDto, $entity, $transactionId);

        // Create or update entity
        $this->persistEntity($entity, $restDto, $transactionId);

        // After callback method call
        $this->afterResetaSenha($id, $restDto, $entity, $transactionId);

        return $entity;
    }

    public function beforeResetaSenha(int &$id, RestDtoInterface $dto, EntityInterface $entity, string $transactionId): void
    {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertResetaSenha');
        $this->triggersManager->proccess(null, $entity, $transactionId, 'beforeResetaSenha');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeResetaSenha');
    }

    public function afterResetaSenha(int &$id, RestDtoInterface $dto, EntityInterface $entity, string $transactionId): void
    {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterResetaSenha');
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function validarUsuario(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        ?bool $skipValidation = null
    ): EntityInterface {
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
        $this->beforeValidarUsuario($id, $restDto, $entity, $transactionId);

        // Create or update entity
        $this->persistEntity($entity, $restDto, $transactionId);

        // After callback method call
        $this->afterValidarUsuario($id, $restDto, $entity, $transactionId);

        return $entity;
    }

    public function beforeValidarUsuario(int &$id, RestDtoInterface $dto, EntityInterface $entity, string $transactionId): void
    {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeValidarUsuario');
        $this->triggersManager->proccess(null, $entity, $transactionId, 'beforeValidarUsuario');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertValidarUsuario');
    }

    public function afterValidarUsuario(int &$id, RestDtoInterface $dto, EntityInterface $entity, string $transactionId): void
    {
        $this->triggersManager->proccess(null, $entity, $transactionId, 'afterValidarUsuario');
    }

    /**
     * @param $cpf
     * @param $token
     *
     * @return bool
     *
     * @throws Exception
     */
    public function validarToken($cpf, $token)
    {
        $dateTime = new DateTime();
        $hash = hash('SHA256', $cpf.''.$dateTime->format('Ymd'));

        if ($hash != $token) {
            return false;
        }

        return true;
    }

    /**
     * @param int    $length
     * @param bool   $add_dashes
     * @param string $available_sets
     *
     * @return bool|string
     */
    public function generateStrongPassword($length = 8, $add_dashes = false, $available_sets = 'lud')
    {
        $sets = [];
        if (false !== strpos($available_sets, 'l')) {
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        }
        if (false !== strpos($available_sets, 'u')) {
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        }
        if (false !== strpos($available_sets, 'd')) {
            $sets[] = '23456789';
        }
        if (false !== strpos($available_sets, 's')) {
            $sets[] = '!@#$%&*?';
        }
        $all = '';
        $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); ++$i) {
            $password .= $all[array_rand($all)];
        }
        $password = str_shuffle($password);
        if (!$add_dashes) {
            return $password;
        }
        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while (strlen($password) > $dash_len) {
            $dash_str .= substr($password, 0, $dash_len).'-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;

        return $dash_str;
    }

    /**
     * @param Entity $usuario
     * @return bool
     * @throws Exception
     */
    public function isPasswordExpired(Entity $usuario): bool
    {
        $expirationTime = $this->parameterBag->get('supp_core.administrativo_backend.password_expiration_time');

        if (!$expirationTime) {
            return false;
        }

        if (!$usuario->getPasswordAtualizadoEm()) {
            return true;
        }

        $today = new DateTime();
        $expiredDate = $usuario->getPasswordAtualizadoEm()
            ->add(DateInterval::createFromDateString($expirationTime));

        return $expiredDate < $today;
    }

    /**
     * @param int $id
     * @param RestDtoInterface $dto
     * @param string $transactionId
     * @param bool|null $skipValidation
     * @return EntityInterface
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     * @throws Exception
     */
    public function alterarSenha(int $id,
        RestDtoInterface $dto,
        string $transactionId,
        ?bool $skipValidation = null
    ): EntityInterface
    {
        $skipValidation ??= false;

        /** @var Usuario $entity */
        $entity = $this->getEntity($id);
        $restDto = $this->getDtoForEntity($id, get_class($dto), $dto);

        // Validate DTO
        $this->validateDto($restDto, $skipValidation);

        // Before callback method call
        $this->beforeAlterarSenha($id, $restDto, $entity, $transactionId);

        $this->update($id, $restDto, $transactionId);

        // After callback method call
        $this->afterAlterarSenha($id, $restDto, $entity, $transactionId);

        return $entity;
    }


    public function beforeAlterarSenha(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void
    {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertAlterarSenha');
        $this->triggersManager->proccess(null, $entity, $transactionId, 'beforeAlterarSenha');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeAlterarSenha');
    }

    public function afterAlterarSenha(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void
    {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterAlterarSenha');
    }
}
