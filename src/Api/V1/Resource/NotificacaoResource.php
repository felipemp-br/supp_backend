<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/NotificacaoResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use DateTime;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use function get_class;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Notificacao as Entity;
use SuppCore\AdministrativoBackend\Repository\NotificacaoRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class NotificacaoResource.
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
class NotificacaoResource extends RestResource
{
    private TokenStorageInterface $tokenStorage;

    /** @noinspection MagicMethodsValidityInspection */

    /**
     * NotificacaoResource constructor.
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator,
        TokenStorageInterface $tokenStorage,
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(Notificacao::class);
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function toggleLida(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        ?bool $skipValidation = null
    ): EntityInterface {
        $skipValidation ??= false;

        // Fetch entity
        $entity = $this->getEntity($id);

        if ($entity->getDataHoraLeitura()) {
            $dto->setDataHoraLeitura(null);
        } else {
            $dto->setDataHoraLeitura(new DateTime());
        }

        /**
         * Determine used dto class and create new instance of that and load entity to that. And after that patch
         * that dto with given partial OR whole dto class.
         */
        $restDto = $this->getDtoForEntity($id, get_class($dto), $dto);

        // Validate DTO
        $this->validateDto($restDto, $skipValidation);

        // Before callback method call
        $this->beforeToggleLida($id, $restDto, $entity, $transactionId);

        // Create or update entity
        $this->persistEntity($entity, $restDto, $transactionId);

        // After callback method call
        $this->afterToggleLida($id, $restDto, $entity, $transactionId);

        return $entity;
    }

    public function beforeToggleLida(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertToggleLida');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeToggleLida');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeToggleLida');
    }

    public function afterToggleLida(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterToggleLida');
    }

    /**
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function marcarTodasComoLida(
        string $transactionId,
        ?bool $skipValidation = null
    ): array {
        $skipValidation ??= false;

        $respository = $this->getRepository();
        $userId = $this->tokenStorage->getToken()->getUser()->getId();
        $notificacoes = $respository->getByDestinatarioId($userId);

        /**
         * @var Notificacao $notificacao
         */
        foreach ($notificacoes as $notificacao) {
            $id = $notificacao->getId();
            $notificacao->setDataHoraLeitura(new \DateTime());

            $restDto = $this->getDtoForEntity($id, Notificacao::class, null, $notificacao);

            // BEFORE
            $this->validateDto($restDto, $skipValidation);
            $this->beforeToggleLida($id, $restDto, $notificacao, $transactionId);

            // PERSIST
            $this->persistEntity($notificacao, $restDto, $transactionId);

            // AFTER
            $this->afterToggleLida($id, $restDto, $notificacao, $transactionId);
        }

        return $notificacoes;
    }

    /**
     * @param string $transactionId
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function excluirTodas(
        string $transactionId
    ): void {
        $respository = $this->getRepository();
        $userId = $this->tokenStorage->getToken()->getUser()->getId();
        $notificacoes = $respository->getByDestinatarioId($userId, false);

        /**
         * @var Notificacao $notificacao
         */
        foreach ($notificacoes as $notificacao) {
            $id = $notificacao->getId();

            $restDto = $this->getDtoForEntity($id, $this->getDtoClass(), null, $notificacao);
            // BEFORE
            $this->beforeDelete($id, $restDto, $notificacao, $transactionId);

            // DELETE
            $this->delete($id, $transactionId);

            // AFTER
            $this->afterDelete($id, $restDto, $notificacao, $transactionId);
        }
    }
}
