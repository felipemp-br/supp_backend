<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\AcaoTransicaoWorkflow;

use Doctrine\ORM\NonUniqueResultException;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use Symfony\Component\VarExporter\LazyObjectInterface;
use function json_decode;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\AcaoTransicaoWorkflowRepository;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Distribuir automaticamente as tarefas automaticas ou por responsável.
 * @classeSwagger=Trigger0002
 */
class Trigger0002 implements TriggerInterface
{

    /**
     * Trigger0002 constructor.
     */
    public function __construct(private SetorRepository $setorRepository,
                                private UsuarioRepository $usuarioRepository,
                                private AcaoTransicaoWorkflowRepository $acaoTransicaoWorkflowRepository) {
    }

    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'beforeCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|TarefaDTO|null $tarefaDTO
     * @param EntityInterface|Tarefa $tarefaEntity
     * @param string $transactionId
     * @return void
     * @throws NonUniqueResultException
     */
    public function execute(
        RestDtoInterface|TarefaDTO|null $tarefaDTO,
        EntityInterface|Tarefa $tarefaEntity,
        string $transactionId
    ): void {
        if (!$tarefaDTO?->getVinculacaoWorkflow()?->getId()) {
            return;
        }

        $result = $this->acaoTransicaoWorkflowRepository->getAcaoTransicaoEspecieTarefaTo(
            $tarefaDTO->getVinculacaoWorkflow()->getTarefaAtual(),
            $tarefaDTO->getEspecieTarefa(),
            $this instanceof LazyObjectInterface ? get_parent_class($this) : get_class($this)
        );

        foreach ($result as $acaoTransicaoWorkflow) {
            $contexto = json_decode($acaoTransicaoWorkflow->getContexto(), true);

            $tarefaDTO->setSetorResponsavel($this->setorRepository->find($contexto['setorResponsavelId']));

            if (isset($contexto['usuarioId'])) {
                $tarefaDTO->setUsuarioResponsavel($this->usuarioRepository->find($contexto['usuarioId']));
            }
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
