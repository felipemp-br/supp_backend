<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0032.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

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
 * Class Trigger0032.
 *
 * @descSwagger=Processa o momento de disparo 'DISTRIBUIÇÃO DE PROCESSO' da regra de etiqueta.
 * @classeSwagger=Trigger0032
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0032 implements TriggerInterface
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
                'beforeCreate',
                'beforeUpdate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ProcessoDTO|null $restDto
     * @param EntityInterface|ProcessoEntity $entity
     * @param string $transactionId
     * @return void
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$entity->getId() || $restDto->getSetorAtual()->getId() !== $entity->getSetorAtual()->getId()) {
            $this->transactionManager->addAsyncDispatch(
                (new RegrasEtiquetaMessage())
                    ->setEntityOrigemUuid($entity->getUuid())
                    ->setEntityOrigemName(ProcessoEntity::class)
                    ->setSiglaMomentoDisparoRegraEtiqueta(
                        SiglaMomentoDisparoRegraEtiqueta::PROCESSO_DISTRIBUICAO->value
                    )
                    ->setUsuarioLogadoId($this->tokenStorage->getToken()?->getUser()?->getId()),
                $transactionId
            );
        }
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
