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
 * Class Rule0001.
 *
 * @descSwagger=Uma árvore de classificação só pode ter no máximo 10 filhos.
 * @classeSwagger=Rule0001
 *
 * @author       Willian Santos <willian.santos@datainfo.inf.br>
 */
class Rule0001 implements RuleInterface
{
    /**
     * Rule0001 constructor.
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
    ) {
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
        if ($restDto->getParent() && $restDto->getParent()->getLvl() > 9) {
            $this->rulesTranslate->throwException('classificacao', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
