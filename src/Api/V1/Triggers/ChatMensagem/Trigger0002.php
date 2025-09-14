<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ChatMensagem/Trigger0002.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ChatMensagem;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Chat as ChatDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatMensagem as ChatMensagemDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Após salvar a mensagem atualiza o chat com a mesma.
 * @classeSwagger=Trigger0002
 */
class Trigger0002 implements TriggerInterface
{
    /**
     * Trigger0002 constructor.
     *
     * @param ChatResource $chatResource
     */
    public function __construct(
        private ChatResource $chatResource,
    ) {
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
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function execute(?RestDtoInterface $restDto,
                            EntityInterface $entity,
                            string $transactionId): void
    {
        $restDto->getChat()->setUltimaMensagem($entity);
        if ($restDto->getChat()->getId()) {
            // Se o chat ainda não existir a mensagem é enviada junto na referência
            // do ponteiro do Obj ChatMensagem e persistem juntos.
            $restDto->getChat()->setUltimaMensagem($entity);
            $chatDTO = $this->chatResource->getDtoForEntity(
                $restDto->getChat()->getId(),
                ChatDTO::class,
                null,
                $restDto->getChat()
            );

            $this->chatResource->update($restDto->getChat()->getId(), $chatDTO, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
