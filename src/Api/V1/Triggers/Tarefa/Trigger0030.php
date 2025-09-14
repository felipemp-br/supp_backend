<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0030.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Enums\SiglaMomentoDisparoRegraEtiqueta;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Message\RegrasEtiquetaMessage;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0030.
 *
 * @descSwagger=Processa o momento de disparo 'APÓS CRIAÇÃO DA PRIMEIRA TAREFA DO PROCESSO' da regra de etiqueta.
 * @classeSwagger=Trigger0030
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0030 implements TriggerInterface
{

    /**
     * Constructor.
     *
     * @param TransactionManager    $transactionManager
     * @param TokenStorageInterface $tokenStorage
     * @param TarefaResource        $tarefaResource
     *
     */
    public function __construct(
        private readonly TransactionManager $transactionManager,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly TarefaResource $tarefaResource
    ) {
    }

    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|TarefaDTO|null $restDto
     * @param EntityInterface|TarefaEntity    $entity
     * @param string                          $transactionId
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $isPrimeiraTarefaProcesso = $this->tarefaResource
                ->count(['processo.id' => sprintf('eq:%s', $restDto->getProcesso()->getId())]) === 0;

        if ($isPrimeiraTarefaProcesso) {
            $this->transactionManager->addAsyncDispatch(
                (new RegrasEtiquetaMessage())
                    ->setEntityOrigemUuid($entity->getUuid())
                    ->setEntityOrigemName(TarefaEntity::class)
                    ->setSiglaMomentoDisparoRegraEtiqueta(
                        SiglaMomentoDisparoRegraEtiqueta::PROCESSO_PRIMEIRA_TAREFA->value
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
