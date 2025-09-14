<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Workflow;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Workflow as WorkflowEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\WorkflowRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001.
 *
 * @descSwagger=Apenas um workflow sem uso pode ser apagado!
 * @classeSwagger=Rule0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;
    private WorkflowRepository $workflowRepository;

    /**
     * Rule0001 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        WorkflowRepository $workflowRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->workflowRepository = $workflowRepository;
    }

    public function supports(): array
    {
        return [
            WorkflowEntity::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->workflowRepository->hasProcesso($entity->getId())) {
            $this->rulesTranslate->throwException('workflow', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
