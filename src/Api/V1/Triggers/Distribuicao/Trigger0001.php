<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Distribuicao;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Distribuicao as DistribuicaoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Distribuicao as DistribuicaoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Enums\SiglaMomentoDisparoRegraEtiqueta;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Message\RegrasEtiquetaMessage;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Processa o momento de disparo 'DISTRIBUIÇÃO DE TAREFA' da regra de etiqueta.
 * @classeSwagger=Trigger0001
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    /**
     * Trigger0001 constructor.
     *
     * @param TransactionManager    $transactionManager
     * @param TokenStorageInterface $tokenStorage
     *
     */
    public function __construct(
        private readonly TransactionManager $transactionManager,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    public function supports(): array
    {
        return [
            DistribuicaoDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param DistribuicaoDTO|RestDtoInterface|null $restDto
     * @param DistribuicaoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $this->transactionManager->addAsyncDispatch(
            (new RegrasEtiquetaMessage())
                ->setEntityOrigemUuid($entity->getTarefa()->getUuid())
                ->setEntityOrigemName(Tarefa::class)
                ->setSiglaMomentoDisparoRegraEtiqueta(SiglaMomentoDisparoRegraEtiqueta::TAREFA_DISTRIBUICAO->value)
                ->setUsuarioLogadoId($entity->getUsuarioPosterior()->getId()),
            $transactionId
        );
    }

    public function getOrder(): int
    {
        return 1;
    }
}
