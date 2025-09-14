<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/ChatResource.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use Doctrine\ORM\NonUniqueResultException;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Chat as DTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatParticipante;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Chat as Entity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\ChatRepository as Repository;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ChatResource.
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
class ChatResource extends RestResource
{
    /** @noinspection MagicMethodsValidityInspection */

    /**
     * ChatResource constructor.
     *
     * @param TokenStorageInterface    $tokenStorage
     * @param UsuarioRepository        $usuarioRepository
     * @param ChatParticipanteResource $chatParticipanteResource
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator,
        private TokenStorageInterface $tokenStorage,
        private UsuarioRepository $usuarioRepository,
        private ChatParticipanteResource $chatParticipanteResource
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(DTO::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function criarOuRetorar(int $usuarioId, string $transactionId, ?bool $skipValidation = null): EntityInterface
    {
        $skipValidation ??= false;

        $usuarioTo = $this->usuarioRepository->find($usuarioId);

        $usuarioFrom = $this->tokenStorage->getToken()->getUser();
        $chat = $this->chatParticipanteResource
            ->getRepository()
            ->retornaChatIndividualEntreParticipantes($usuarioFrom, $usuarioTo);

        if ($chat) {
            return $chat;
        }

        $chatDto = new DTO();
        $chatDto
            ->setAtivo(true)
            ->setGrupo(false);

        $chat = $this->create($chatDto, $transactionId, $skipValidation);

        $chatParticipanteToDto = (new ChatParticipante())
            ->setChat($chat)
            ->setUsuario($usuarioTo)
            ->setAdministrador(false);

        $chatParticipante = $this->chatParticipanteResource->create($chatParticipanteToDto, $transactionId);
        $chat->addParticipante($chatParticipante);

        return $chat;
    }

    /**
     * @throws Exception
     */
    public function findChatList(?Usuario $usuario,
                                ?array $criteria = [],
                                ?int $limit = 100,
                                ?int $offset = 0): array
    {
        if (!$usuario) {
            $usuario = $this->tokenStorage->getToken()->getUser();
        }

        return $this->getRepository()->findChatList($usuario, $criteria, $limit, $offset);
    }
}
