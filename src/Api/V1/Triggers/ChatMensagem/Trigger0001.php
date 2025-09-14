<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ChatMensagem/Trigger0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ChatMensagem;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Chat;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatMensagem as ChatMensagemDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatParticipante;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatParticipanteResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ChatMensagem as ChatMensagemEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\ChatParticipanteRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Cria o chat e adiciona os participantes quando o mesmo não é informado.
 * @classeSwagger=Trigger0001
 */
class Trigger0001 implements TriggerInterface
{
    /**
     * Trigger0001 constructor.
     *
     * @param ChatParticipanteRepository $chatParticipanteRepository
     * @param TokenStorageInterface      $tokenStorage
     * @param ChatResource               $chatResource
     * @param ChatParticipanteResource   $chatParticipanteResource
     */
    public function __construct(
        private ChatParticipanteRepository $chatParticipanteRepository,
        private TokenStorageInterface $tokenStorage,
        private ChatResource $chatResource,
        private ChatParticipanteResource $chatParticipanteResource
    ) {
    }

    public function supports(): array
    {
        return [
            ChatMensagemDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ChatMensagemDTO|null $restDto
     * @param EntityInterface|ChatMensagemEntity    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto,
                            EntityInterface $entity,
                            string $transactionId): void
    {
        if (!$restDto->getChat() && $restDto->getUsuarioTo()) {
            $restDto->setUsuario($this->tokenStorage->getToken()->getUser());
            $chatEntity = $this->chatParticipanteRepository->retornaChatIndividualEntreParticipantes(
                $restDto->getUsuario(),
                $restDto->getUsuarioTo()
            );

            if (!$chatEntity) {
                $chat = (new Chat())
                    ->setGrupo(false)
                    ->setAtivo(true);

                $chatEntity = $this->chatResource->create($chat, $transactionId);

                $restDto->setChat($chatEntity);

                $chatParticipanteTo = (new ChatParticipante())
                    ->setChat($chatEntity)
                    ->setUsuario($restDto->getUsuarioTo())
                    ->setAdministrador(false);

                $chatParticipanteEntity = $this->chatParticipanteResource->create($chatParticipanteTo, $transactionId);
                $chatEntity->addParticipante($chatParticipanteEntity);
            }

            $restDto->setChat($chatEntity);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
