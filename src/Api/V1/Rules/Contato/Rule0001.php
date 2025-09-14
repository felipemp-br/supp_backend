<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Contato;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Contato as DTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Contato as Entity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001.
 *
 * @descSwagger=Valida se foi passado algum contato
 * @classeSwagger=Rule0001
 */
class Rule0001 implements RuleInterface
{
    /**
     * Rule0001 constructor.
     *
     * @param RulesTranslate $rulesTranslate
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
    ) {
    }

    public function supports(): array
    {
        return [
            DTO::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param DTO|RestDtoInterface|null $restDto
     * @param Entity|EntityInterface    $entity
     * @param string                    $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(
        RestDtoInterface|DTO|null $restDto,
        EntityInterface|Entity $entity,
        string $transactionId
    ): bool {
        if (!$restDto->getUnidade() && !$restDto->getSetor() && !$restDto->getUsuario()) {
            $this->rulesTranslate->throwException('contato', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
