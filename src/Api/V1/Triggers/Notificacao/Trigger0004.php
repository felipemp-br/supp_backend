<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Notificacao/Trigger0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Notificacao;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Notificacao as NotificacaoEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0004.
 *
 * @descSwagger=Faz o push da notificação ao usuário!
 * @classeSwagger=Trigger0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0004 implements TriggerInterface
{
    private NotificacaoResource $notificacaoResource;

    /**
     * Trigger0004 constructor.
     */
    public function __construct(
        NotificacaoResource $notificacaoResource
    ) {
        $this->notificacaoResource = $notificacaoResource;
    }

    public function supports(): array
    {
        return [
            Notificacao::class => [
                'afterCreate',
                'afterToggleLida',
            ],
        ];
    }

    /**
     * @param Notificacao|RestDtoInterface|null $restDto
     * @param NotificacaoEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $this->notificacaoResource->push(
            $entity,
            $entity->getDestinatario()->getUsername(),
            $transactionId,
            ['criadoPor', 'atualizadoPor', 'remetente', 'destinatario', 'modalidadeNotificacao', 'tipoNotificacao']
        );
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
