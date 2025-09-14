<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\TipoAcaoWorkflow;

use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoAcaoWorkflow as DTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\TipoAcaoWorkflow as Entity;
use SuppCore\AdministrativoBackend\Repository\TipoAcaoWorkflowRepository as Repository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001.
 *
 * @descSwagger=Verifica unicidade de nome
 * @classeSwagger=Rule0001
 */
class Rule0001 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;
    private Repository $repository;

    /**
     * Rule0001 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        Repository $repository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->repository = $repository;
    }

    public function supports(): array
    {
        return [
            DTO::class => [
                'beforeCreate',
                'beforeUpdate',
            ],
        ];
    }

    /**
     * @param DTO|RestDtoInterface|null $restDto
     * @param Entity|EntityInterface    $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $result = $this->repository->findOneBy(
            [
                'valor' => $restDto->getValor(),
            ]
        );

        if ($result && $result->getId() != $restDto->getId()) {
            $this->rulesTranslate->throwException('tipoAcaoWorkflow', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
