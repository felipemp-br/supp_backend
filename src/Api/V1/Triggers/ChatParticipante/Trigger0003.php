<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ChatParticipante/Trigger0003.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ChatParticipante;

use Exception;
use JMS\Serializer\SerializerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatParticipante as ChatParticipanteDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatParticipanteResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante as ChatParticipanteEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

/**
 * Class Trigger0003.
 *
 * @descSwagger  =Faz o push da exclusão/saída de participante do chat.
 * @classeSwagger=Trigger0003
 */
class Trigger0003 implements TriggerInterface
{
    /**
     * Trigger0003 constructor.
     *
     * @param ChatParticipanteResource $chatParticipanteResource
     * @param HubInterface             $hub
     * @param SerializerInterface      $serializer
     */
    public function __construct(
        private ChatParticipanteResource $chatParticipanteResource,
        private HubInterface $hub,
        private SerializerInterface $serializer
    ) {
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
    public function execute(
        ?RestDtoInterface $restDto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $dtoMapper = $this->chatParticipanteResource->getDtoMapperManager()->getMapper(
            $this->chatParticipanteResource->getDtoClass()
        );

        $dto = $dtoMapper->convertEntityToDto(
            $entity,
            $this->chatParticipanteResource->getDtoClass(),
            [
                'chat',
                'usuario',
            ]
        );

        $serializedDto = json_decode(
            $this->serializer->serialize(
                $dto,
                'json'
            ),
            true
        );

        $update = new Update(
            [
                '/v1/administrativo/chat/'.$entity->getChat()->getId(),
                $entity->getUsuario()->getUsername().'/chat',
            ],
            json_encode(
                [
                    'deleteData' => $serializedDto,
                ]
            )
        );

        $this->hub->publish($update);
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
