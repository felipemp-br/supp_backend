<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Chat/Trigger0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Chat;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Chat as ChatDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatParticipante;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatParticipanteResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Chat as ChatEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
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
     * @param TokenStorageInterface    $tokenStorage
     * @param ChatParticipanteResource $chatParticipanteResource
     */
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private ChatParticipanteResource $chatParticipanteResource
    ) {
    }

    public function supports(): array
    {
        return [
            ChatDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ChatDTO|null $restDto
     * @param EntityInterface|ChatEntity    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto,
                            EntityInterface $entity,
                            string $transactionId): void
    {
        $usuarioLogado = $this->tokenStorage->getToken()->getUser();
        $participanteCriador = null;

        foreach ($restDto->getParticipantes() as $participante) {
            if ($participante->getUsuario()->getId() == $usuarioLogado->getId()) {
                $participanteCriador = $participante;
                break;
            }
        }

        if (!$participanteCriador) {
            $participanteCriadorDto = (new ChatParticipante())
                ->setChat($entity)
                ->setUsuario($usuarioLogado)
                ->setAdministrador($restDto->getGrupo());

            $participanteCriador = $this->chatParticipanteResource->create($participanteCriadorDto, $transactionId);

            $entity->addParticipante($participanteCriador);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
