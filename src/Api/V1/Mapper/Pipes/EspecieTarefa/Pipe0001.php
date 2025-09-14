<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/EspecieTarefa/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\EspecieTarefa;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieTarefa as EspecieTarefaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\EspecieTarefa as EspecieTarefaEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEspecieProcessoWorkflow;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Repository\ProcessoRepository;
use Symfony\Component\HttpFoundation\RequestStack;

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
    public function __construct(private RequestStack $requestStack,
                                private ProcessoRepository $processoRepository) {
    }

    public function supports(): array
    {
        return [
            EspecieTarefaDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param EspecieTarefaDTO|RestDtoInterface|null $restDto
     * @param EspecieTarefaEntity|EntityInterface    $entity
     *
     * @throws Exception
     * @noinspection PhpParameterByRefIsNotUsedAsReferenceInspection
     */
    public function execute(
        EspecieTarefaDTO|RestDtoInterface|null &$restDto,
        EspecieTarefaEntity|EntityInterface $entity
    ): void {
        if (!$this->requestStack->getCurrentRequest()) {
            return;
        }

        //Pipe valida as especie de tarefas validas para o processo
        if ($this->requestStack->getCurrentRequest()->get('context')) {
            $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));
            if (isset($context->processoId) && ($context->processoId)) {
                $processo = $this->processoRepository->find($context->processoId);

                if ($processo && $processo->getEspecieProcesso()->getVinculacoesEspecieProcessoWorkflow()) {

                    $restDto->setValida(false);
                    /** @var VinculacaoEspecieProcessoWorkflow $vinculacaoEspecieProcessoWorkflow */
                    foreach ($processo->getEspecieProcesso()
                                 ->getVinculacoesEspecieProcessoWorkflow() as $vinculacaoEspecieProcessoWorkflow) {
                        if ($vinculacaoEspecieProcessoWorkflow->getWorkflow()->getEspecieTarefaInicial()->getId()
                            === $restDto->getId()) {
                            $restDto->setValida(true);
                        }
                    }
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
