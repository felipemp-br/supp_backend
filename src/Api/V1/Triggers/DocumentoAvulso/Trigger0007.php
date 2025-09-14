<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/DocumentoAvulso/Trigger0007.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\DocumentoAvulso;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as DocumentoAvulsoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0007.
 *
 * @descSwagger=Se tarefa originária ainda estiver aberta e fora da caixa de entrada, recoloca ela lá!
 * @classeSwagger=Trigger0007
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0007 implements TriggerInterface
{
    private TarefaResource $tarefaResource;

    /**
     * Trigger0007 constructor.
     */
    public function __construct(
        TarefaResource $tarefaResource,
        private TransactionManager $transactionManager
    ) {
        $this->tarefaResource = $tarefaResource;
    }

    public function supports(): array
    {
        return [
            DocumentoAvulso::class => [
                'beforeResponder',
            ],
        ];
    }

    /**
     * @param DocumentoAvulso|RestDtoInterface|null $restDto
     * @param DocumentoAvulsoEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $tarefaOrigem = $this->tarefaResource->getRepository()->findTarefaByDocumentoAvulso(
            $restDto->getId());
        if ($tarefaOrigem &&
            !$tarefaOrigem->getDataHoraConclusaoPrazo() &&
            $tarefaOrigem->getFolder()) {
            /** @var Tarefa $tarefaDTO */
            $tarefaDTO = $this->tarefaResource->getDtoForEntity(
                $restDto->getTarefaOrigem()->getId(),
                Tarefa::class
            );
            $this->transactionManager->addContext(
                new Context(
                    'respostaDocumentoAvulso',
                    true
                ),
                $transactionId
            );
            $tarefaDTO->setFolder(null);
            $this->tarefaResource->update($restDto->getTarefaOrigem()->getId(), $tarefaDTO, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
