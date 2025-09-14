<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\WorkflowResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Workflow;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0017.
 *
 * @descSwagger=O workflow não faz parte dos workflows da espécie do processo da tarefa!
 * @classeSwagger=Rule0017
 */
class Rule0017 implements RuleInterface
{
    /**
     * Rule0017 constructor.
     */
    public function __construct(private RulesTranslate $rulesTranslate,
                                private WorkflowResource $workflowResource) {
    }

    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        /** @var TarefaDTO|null $restDto */
        /** @var Workflow|null $workflow */
        $workflow = $restDto->getWorkflow();
        if ($workflow) {
            $result = $this->workflowResource->find(
                [
                    'orX' =>[
                        [
                            'vinculacoesEspecieProcessoWorkflow.especieProcesso.id' =>
                                'eq:'.$restDto->getProcesso()->getEspecieProcesso()->getId()
                        ],
                        [
                            'vinculacoesTransicaoWorkflow.transicaoWorkflow.especieTarefaFrom.tarefas.dataHoraConclusaoPrazo' =>
                                'isNull',
                            'vinculacoesTransicaoWorkflow.transicaoWorkflow.especieTarefaFrom.tarefas.vinculacaoWorkflow.concluido' =>
                                'neq:true',
                            'vinculacoesTransicaoWorkflow.transicaoWorkflow.especieTarefaFrom.tarefas.processo.id' =>
                                'eq:'.$restDto->getProcesso()->getId(),
                        ]
                    ]
                ],
                null,
                1
            );

            if (!$result) {
                $this->rulesTranslate->throwException('tarefa', '0017');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 17;
    }
}
