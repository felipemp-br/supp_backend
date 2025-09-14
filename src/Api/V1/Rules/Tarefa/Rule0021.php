<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0021.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0021.
 *
 * @descSwagger=Não é possível dar ciência com minutas na tarefa!
 * @classeSwagger=Rule0021
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0021 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0021 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate)
    {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'beforeCiencia',
            ],
        ];
    }

    /**
     * @param TarefaDTO|RestDtoInterface|null $restDto
     * @param Tarefa|EntityInterface                                                  $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($entity->getMinutas()->filter(fn ($doc) => !$doc->getJuntadaAtual())->count() > 0) {
            $this->rulesTranslate->throwException('tarefa', '0021');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 21;
    }
}
