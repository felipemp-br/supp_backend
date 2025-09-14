<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/ChatParticipanteResource.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatParticipante as DTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante as Entity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\ChatParticipanteRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ChatParticipanteResource.
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
class ChatParticipanteResource extends RestResource
{
    /** @noinspection MagicMethodsValidityInspection */

    /**
     * ChatParticipanteResource constructor.
     * @param Repository $repository
     * @param ValidatorInterface $validator
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(Repository $repository,
                                ValidatorInterface $validator,
                                private TokenStorageInterface $tokenStorage)
    {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(DTO::class);
    }


    /**
     * @param int $id
     * @param $transactionId
     * @return EntityInterface
     */
    public function limparMensagens(int $id, $transactionId): EntityInterface
    {
        $chatParticipante = $this->findOneBy([
            'chat' => $id,
            'usuario' => $this->tokenStorage->getToken()->getUser()->getId()
        ]);

        if (!$chatParticipante) {
            throw new NotFoundHttpException('Not found');
        }

        /** @var DTO $chatParticipanteDto */
        $chatParticipanteDto = $this->getDtoForEntity(
            $chatParticipante->getId(),
            $this->getDtoClass(),
            null,
            $chatParticipante
        );

        $chatParticipanteDto
            ->setMensagensNaoLidas(0)
            ->setUltimaVisualizacao(new DateTime());

        return $this->update(
            $chatParticipante->getId(),
            $chatParticipanteDto,
            $transactionId,
            false
        );
    }
}
