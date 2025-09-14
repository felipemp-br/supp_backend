<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Chat\MessageHandler;

use JMS\Serializer\SerializerInterface;
use SuppCore\AdministrativoBackend\Chat\Message\ChatMessage;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Class ChatMessageHandler
 * @package SuppCore\AdministrativoBackend\Chat\MessageHandler
 */
#[AsMessageHandler]
class ChatMessageHandler
{

    /**
     * ChatMessageHandler constructor.
     * @param HubInterface $hub
     * @param ContainerInterface $container
     * @param SerializerInterface $serializer
     * @param TransactionManager $transactionManager
     */
    public function __construct(private HubInterface $hub,
                                private ContainerInterface $container,
                                private SerializerInterface $serializer,
                                private TransactionManager $transactionManager)
    {
    }

    /**
     * @param ChatMessage $message
     */
    public function __invoke(ChatMessage $message)
    {
        /** @var RestResource $resource */
        $resource = $this->container->get($message->getResource());
        $dtoMapper = $resource->getDtoMapperManager()->getMapper($resource->getDtoClass());

        $entity = $resource->findOneBy(['uuid' => $message->getUuid()]);

        if ($entity) {

            $transactionId = $this->transactionManager->begin();
            foreach ($message->getContexts() as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $dto = $dtoMapper->convertEntityToDto(
                $entity,
                $resource->getDtoClass(),
                $message->getPopulate(),
            );

            $serializedDto = json_decode(
                $this->serializer->serialize(
                    $dto,
                    'json'
                ),
                true
            );

            try {
                $update = new Update(
                    $message->getChanels(),
                    json_encode(
                        [
                            'addData' => $serializedDto,
                        ]
                    )
                );

                $this->transactionManager->commit($transactionId);
                $this->hub->publish($update);
            }catch (Throwable $e) {
            }
        }
    }
}
