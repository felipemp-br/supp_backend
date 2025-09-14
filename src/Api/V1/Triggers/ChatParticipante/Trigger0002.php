<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ChatParticipante/Trigger0002.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ChatParticipante;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatParticipante as ChatParticipanteDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatCounterResource;
use SuppCore\AdministrativoBackend\Counter\Message\PushMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante as ChatParticipanteEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Faz o push para atualização de contegem de mensagens não lidas.
 * @classeSwagger=Trigger0002
 */
class Trigger0002 implements TriggerInterface
{
    /**
     * Trigger0002 constructor.
     *
     * @param TransactionManager $transactionManager
     */
    public function __construct(private TransactionManager $transactionManager)
    {
    }

    public function supports(): array
    {
        return [
            ChatParticipanteDTO::class => [
                'afterPatch',
                'afterUpdate',
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
        $pushMessage = new PushMessage();
        $pushMessage->setChannel(
            $entity->getUsuario()->getUsername().'/chat'
        );
        $pushMessage->setResource(
            ChatCounterResource::class
        );
        $pushMessage->setIdentifier('chat_mensagens_nao_lidas');
        $pushMessage->setCriteria([
            'usuarioParticipante' => $entity->getUsuario()->getId(),
        ]);

        $this->transactionManager->addAsyncDispatch($pushMessage, $transactionId);
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
