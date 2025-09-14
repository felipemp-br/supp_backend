<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ChatParticipante/Trigger0004.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ChatParticipante;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatParticipante as ChatParticipanteDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatParticipanteResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante as ChatParticipanteEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0004.
 *
 * @descSwagger=Excluir o chat no caso do último usuário sair do grupo.
 * @classeSwagger=Trigger0004
 */
class Trigger0004 implements TriggerInterface
{
    /**
     * Trigger0004 constructor.
     * @param ChatParticipanteResource $chatParticipanteResource
     * @param ChatResource $chatResource
     */
    public function __construct(private ChatParticipanteResource $chatParticipanteResource,
                                private ChatResource $chatResource)
    {
    }

    public function supports(): array
    {
        return [
            ChatParticipanteEntity::class => [
                'afterDelete',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ChatParticipanteDTO|null $restDto
     * @param EntityInterface|ChatParticipanteEntity    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto,
                            EntityInterface $entity,
                            string $transactionId): void
    {
        if ($entity->getChat()->getParticipantes()->count() <= 1) {
            $this->chatResource->delete($entity->getChat()->getId(), $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 9;
    }
}
