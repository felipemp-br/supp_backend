<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/VinculacaoWorkflow/Pipe0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\VinculacaoWorkflow;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoWorkflow as VinculacaoWorkflowDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoWorkflow as VinculacaoWorkflowEntity;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Repository\TransicaoWorkflowRepository;

/**
 * Class Pipe0001.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0001 implements PipeInterface
{
    /**
     * Pipe0001 constructor.
     */
    public function __construct(private TransicaoWorkflowRepository $transicaoWorkflowRepository) {
    }

    public function supports(): array
    {
        return [
            VinculacaoWorkflowDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param VinculacaoWorkflowDTO|RestDtoInterface|null $restDto
     * @param VinculacaoWorkflowEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(
        VinculacaoWorkflowDTO|RestDtoInterface|null &$restDto,
        VinculacaoWorkflowEntity|EntityInterface $entity
    ): void
    {
        $transicaoFinalWorkflow = $this->transicaoWorkflowRepository
            ->findOneBy([
                'especieTarefaFrom' => $entity->getTarefaAtual()->getEspecieTarefa(),
                'workflow' => $entity->getWorkflow()
            ]);

        $restDto->setTransicaoFinalWorkflow(!$transicaoFinalWorkflow);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
