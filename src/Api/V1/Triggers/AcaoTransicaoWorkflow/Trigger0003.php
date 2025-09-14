<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\AcaoTransicaoWorkflow;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\VarExporter\LazyObjectInterface;
use function json_decode;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Compartilhamento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\CompartilhamentoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Repository\AcaoTransicaoWorkflowRepository;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger=Gera automaticamente ação para compartilhar tarefa com outro usuário.
 * @classeSwagger=Trigger0003
 */
class Trigger0003 implements TriggerInterface
{

    /**
     * Trigger0003 constructor.
     */
    public function __construct(private CompartilhamentoResource $compartilhamentoResource,
                                private UsuarioRepository $usuarioRepository,
                                private AcaoTransicaoWorkflowRepository $acaoTransicaoWorkflowRepository) {
    }

    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'afterCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param TarefaDTO|RestDtoInterface|null $tarefaDTO
     * @param EntityInterface|TarefaEntity $tarefaEntity
     * @param string $transactionId
     * @return void
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(
        TarefaDTO|RestDtoInterface|null $tarefaDTO,
        EntityInterface|TarefaEntity $tarefaEntity,
        string $transactionId
    ): void {
        if (!$tarefaDTO?->getVinculacaoWorkflow()?->getId()) {
            return;
        }

        $result = $this->acaoTransicaoWorkflowRepository
            ->getAcaoTransicaoEspecieTarefaTo(
                $tarefaDTO->getVinculacaoWorkflow()->getTarefaAtual(),
                $tarefaDTO->getEspecieTarefa(),
                $this instanceof LazyObjectInterface ? get_parent_class($this) : get_class($this)
            );

        foreach ($result as $acaoTransicaoWorkflow) {
            $contexto = json_decode($acaoTransicaoWorkflow->getContexto(), true);

            $compartilhamentoDTO = (new Compartilhamento())
                ->setUsuario($this->usuarioRepository->find($contexto['usuarioId']))
                ->setTarefa($tarefaEntity);

            $this->compartilhamentoResource->create($compartilhamentoDTO, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 3;
    }
}
