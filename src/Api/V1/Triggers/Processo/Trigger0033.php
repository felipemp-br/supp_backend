<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Enums\SiglaMomentoDisparoRegraEtiqueta;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Message\RegrasEtiquetaMessage;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0033.
 *
 * @descSwagger=Processa o momento de disparo 'CRIAÇÃO DE PROCESSO ADMINISTRATIVO' da regra de etiqueta.
 * @classeSwagger=Trigger0033
 */
class Trigger0033 implements TriggerInterface
{
    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        private readonly TransactionManager $transactionManager,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param ProcessoDTO|RestDtoInterface|null $restDto
     * @param ProcessoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $this->transactionManager->addAsyncDispatch(
            (new RegrasEtiquetaMessage())
                ->setEntityOrigemUuid($entity->getUuid())
                ->setEntityOrigemName(ProcessoEntity::class)
                ->setSiglaMomentoDisparoRegraEtiqueta(
                    SiglaMomentoDisparoRegraEtiqueta::PROCESSO_CRIACAO_PROCESSO_ADMINISTRATIVO->value
                )
                ->setUsuarioLogadoId($this->tokenStorage->getToken()?->getUser()?->getId()),
            $transactionId
        );
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
