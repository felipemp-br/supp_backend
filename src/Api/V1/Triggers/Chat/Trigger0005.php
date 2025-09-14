<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Chat/Trigger0005.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Chat;

use JMS\Serializer\SerializerInterface;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatCounterResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatResource;
use SuppCore\AdministrativoBackend\Counter\Message\PushMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Chat;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

/**
 * Class Trigger0005.
 *
 * @descSwagger=Faz o push de exclusão de Chat.
 * @classeSwagger=Trigger0005
 */
class Trigger0005 implements TriggerInterface
{
    /**
     * Trigger0005 constructor.
     * @param ChatResource $chatResource
     * @param HubInterface $hub
     * @param SerializerInterface $serializer
     * @param TransactionManager $transactionManager
     */
    public function __construct(private ChatResource $chatResource,
                                private HubInterface $hub,
                                private SerializerInterface $serializer,
                                private TransactionManager $transactionManager)
    {
    }

    public function supports(): array
    {
        return [
            Chat::class => [
                'afterDelete',
            ],
        ];
    }

    public function execute(?RestDtoInterface $restDto,
                            EntityInterface $entity,
                            string $transactionId): void
    {
        $dtoMapper = $this->chatResource->getDtoMapperManager()->getMapper(
            $this->chatResource->getDtoClass()
        );

        $dto = $dtoMapper->convertEntityToDto(
            $entity,
            $this->chatResource->getDtoClass(),
            []
        );

        $serializedDto = json_decode(
            $this->serializer->serialize(
                $dto,
                'json'
            ),
            true
        );

        foreach ($entity->getParticipantes() as $participante) {
            $pushMessage = new PushMessage();
            $pushMessage->setChannel($participante->getUsuario()->getUsername().'/chat');
            $pushMessage->setResource(ChatCounterResource::class);
            $pushMessage->setIdentifier('chat_mensagens_nao_lidas');
            $pushMessage->setCriteria([
                'usuarioParticipante' => $participante->getUsuario()->getId(),
            ]);

            $this->transactionManager->addAsyncDispatch($pushMessage, $transactionId);

            $update = new Update(
                [$participante->getUsuario()->getUsername().'/chat'],
                json_encode(
                    [
                        'deleteData' => $serializedDto,
                    ]
                )
            );

            $this->hub->publish($update);
        }
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
