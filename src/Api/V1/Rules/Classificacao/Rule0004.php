<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Classificacao;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Classificacao;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Classificacao as ClassificacaoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0004.
 *
 * @descSwagger=Não é possível criar ou alterar classificação cuja modalidade de destinação seja 'TRANSFERÊNCIA'.
 * @classeSwagger=Rule0004
 *
 * @author       Willian Santos <willian.santos@datainfo.inf.br>
 */
class Rule0004 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0004 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
    ) {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            Classificacao::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Classificacao|RestDtoInterface|null $restDto
     * @param ClassificacaoEntity|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getModalidadeDestinacao()->getValor() === 'TRANSFERÊNCIA') {
            $this->rulesTranslate->throwException('classificacao', '0004');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 4;
    }
}
