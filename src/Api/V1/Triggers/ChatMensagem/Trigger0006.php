<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ChatMensagem/Trigger0006.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ChatMensagem;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatMensagem as ChatMensagemDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatParticipanteResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ChatMensagem as ChatMensagemEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0006.
 *
 * @descSwagger=Atualiza contador de mensagens não lidas.
 * @classeSwagger=Trigger0006
 */
class Trigger0006 implements TriggerInterface
{
    /**
     * Trigger0006 constructor.
     *
     * @param ChatParticipanteResource $chatParticipanteResource
     */
    public function __construct(private ChatParticipanteResource $chatParticipanteResource)
    {
    }

    public function supports(): array
    {
        return [
            ChatMensagemDTO::class => [
                'afterCreate',
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
        foreach ($entity->getChat()->getParticipantes() as $chatParticipante) {
            if ($chatParticipante->getUsuario()->getId() === $entity->getUsuario()->getId()) {
                $chatParticipante->setMensagensNaoLidas(0);
                $chatParticipante->setUltimaVisualizacao(new \DateTime());
            } else {
                $chatParticipante->setMensagensNaoLidas(
                    (int) $chatParticipante->getMensagensNaoLidas() + 1
                );
            }

            if ($chatParticipante->getId()) {
                $chatParticipanteDto = $this->chatParticipanteResource->getDtoForEntity(
                    $chatParticipante->getId(),
                    $this->chatParticipanteResource->getDtoClass(),
                    null,
                    $chatParticipante
                );

                $this->chatParticipanteResource->update(
                    $chatParticipante->getId(),
                    $chatParticipanteDto,
                    $transactionId
                );
            }
        }
    }

    public function getOrder(): int
    {
        return 99;
    }
}
