<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/EspecieAtividade/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\EspecieAtividade;

use Doctrine\ORM\NonUniqueResultException;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieAtividade as EspecieAtividadeDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\EspecieAtividade as EspecieAtividadeEntity;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Repository\TarefaRepository;
use SuppCore\AdministrativoBackend\Repository\TransicaoWorkflowRepository;
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
                                private TarefaRepository $tarefaRepository,
                                private TransicaoWorkflowRepository $transicaoWorkflowRepository) {
    }

    public function supports(): array
    {
        return [
            EspecieAtividadeDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     *
     * @throws NonUniqueResultException
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if (!$this->requestStack->getCurrentRequest()) {
            return;
        }

        //Pipe valida as especie de tarefas validas para o processo
        if ($this->requestStack->getCurrentRequest()->get('context')) {
            $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));
            if (isset($context->tarefaId) && ($context->tarefaId)) {
                $tarefa = $this->tarefaRepository->find($context->tarefaId);

                if ($tarefa && $tarefa->getVinculacaoWorkflow()) {

                    $transicoesWorkflow = $this->transicaoWorkflowRepository->
                    findTransicaoByWorkflowIdAndEspecieTarefaFromId(
                        $tarefa->getVinculacaoWorkflow()->getWorkflow()->getId(),
                        $tarefa->getEspecieTarefa()->getId()
                    );

                    // caso seja processo de workflow e não tenha tarefa atual worflow ainda esta na fase inicial
                    $restDto->setValida(!$transicoesWorkflow);

                    foreach ($transicoesWorkflow as $transicaoWorkflow) {
                        if ($transicaoWorkflow->getEspecieAtividade()->getId() === $entity->getId()) {
                            $restDto->setValida(true);

                            return;
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
