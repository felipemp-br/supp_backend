<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Atividade;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade as AtividadeDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoWorkflow;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Atividade as AtividadeEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\TransicaoWorkflow;
use SuppCore\AdministrativoBackend\Repository\TransicaoWorkflowRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0003.
 *
 * @descSwagger=Atividades que encerram tarefas ou da espécie workflow somente podem ser criadas se a transição do workflow for permitida.
 * @classeSwagger=Rule0003
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Rule0003 implements RuleInterface
{

    /**
     * Rule0003 constructor.
     */
    public function __construct(private RulesTranslate $rulesTranslate,
                                private TransicaoWorkflowRepository $transicaoWorkflowRepository) {
    }

    public function supports(): array
    {
        return [
            AtividadeDTO::class => [
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
        // se não for uma especie de atividade de workflow não entra na regra
        if (!$restDto->getTarefa()->getVinculacaoWorkflow() || !$restDto->getEncerraTarefa()) {
            return true;
        }

        $proximaTransicaoWorkflow = $this->transicaoWorkflowRepository
            ->findOneBy(
                [
                    'workflow' => $restDto->getTarefa()->getVinculacaoWorkflow()->getWorkflow(),
                    'especieTarefaFrom' => $restDto->getTarefa()->getEspecieTarefa()
                ]
            );

        if ($proximaTransicaoWorkflow) {
            $transicaoPermitida = $this->transicaoWorkflowRepository
                ->findBy([
                    'workflow' => $restDto->getTarefa()->getVinculacaoWorkflow()->getWorkflow(),
                    'especieTarefaFrom' => $restDto
                        ->getTarefa()
                        ->getVinculacaoWorkflow()
                        ->getTarefaAtual()
                        ->getEspecieTarefa(),
                    'especieAtividade' => $restDto->getEspecieAtividade()

                ]);

            if (!$transicaoPermitida) {
                $this->rulesTranslate->throwException('atividade', '0003');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 3;
    }
}
