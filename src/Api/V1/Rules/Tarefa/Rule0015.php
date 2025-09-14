<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0015.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0015.
 *
 * @descSwagger=A tarefa já está concluída e não pode ser excluída!
 * @classeSwagger=Rule0015
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0015 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0015 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate)
    {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param \SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa|RestDtoInterface|null $restDto
     * @param Tarefa|EntityInterface                                                  $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($entity->getDataHoraConclusaoPrazo()) {
            $this->rulesTranslate->throwException('tarefa', '0015');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 15;
    }
}
