<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/AcaoTransicaoWorkflow/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\AcaoTransicaoWorkflow;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\VarExporter\LazyObjectInterface;
use function json_decode;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Repository\AcaoTransicaoWorkflowRepository;
use SuppCore\AdministrativoBackend\Repository\ModeloRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Gera automaticamente uma minuta na tarefa de acordo com o modelo pré-selecionado!
 * @classeSwagger=Trigger0001
 */
class Trigger0001 implements TriggerInterface
{
    private ComponenteDigitalResource $componenteDigitalResource;
    private ModeloRepository $modeloRepository;
    private AcaoTransicaoWorkflowRepository $acaoTransicaoWorkflowRepository;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        ComponenteDigitalResource $componenteDigitalResource,
        ModeloRepository $modeloRepository,
        AcaoTransicaoWorkflowRepository $acaoTransicaoWorkflowRepository
    ) {
        $this->componenteDigitalResource = $componenteDigitalResource;
        $this->modeloRepository = $modeloRepository;
        $this->acaoTransicaoWorkflowRepository = $acaoTransicaoWorkflowRepository;
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
     * @param TarefaDTO|RestDtoInterface|null $restDto
     * @param TarefaEntity|EntityInterface $tarefaEntity
     * @param string $transactionId
     * @return void
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(
        TarefaDTO|RestDtoInterface|null $restDto,
        TarefaEntity|EntityInterface $tarefaEntity,
        string $transactionId
    ): void {
        if (!$restDto?->getVinculacaoWorkflow()?->getId()) {
            return;
        }

        $result = $this->acaoTransicaoWorkflowRepository
            ->getAcaoTransicaoEspecieTarefaTo(
                $restDto->getVinculacaoWorkflow()->getTarefaAtual(),
                $restDto->getEspecieTarefa(),
                $this instanceof LazyObjectInterface ? get_parent_class($this) : get_class($this)
            );

        foreach ($result as $acaoTransicaoWorkflow) {
            $contexto = json_decode($acaoTransicaoWorkflow->getContexto(), true);

            if ($contexto['modeloId']) {
                $modelo = $this->modeloRepository->find($contexto['modeloId']);
                $componenteDigitalDTO = new ComponenteDigitalDTO();
                $componenteDigitalDTO->setTarefaOrigem($tarefaEntity);
                $componenteDigitalDTO->setModelo($modelo);
                $componenteDigitalDTO->setFileName($modelo->getNome().'.html');
                $this->componenteDigitalResource->create($componenteDigitalDTO, $transactionId);
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
