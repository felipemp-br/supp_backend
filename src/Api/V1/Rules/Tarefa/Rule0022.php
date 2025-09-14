<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0022.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0022.
 *
 * @descSwagger=Não é possível alterar a espécie de tarefa de uma tarefa de workflow!
 * @classeSwagger=Rule0022
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0022 implements RuleInterface
{

    public function __construct(private RulesTranslate $rulesTranslate) {
    }

    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param TarefaDTO|RestDtoInterface|null $restDto
     * @param TarefaEntity|EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(
        TarefaDTO|RestDtoInterface|null $restDto,
        TarefaEntity|EntityInterface $entity,
        string $transactionId
    ): bool
    {
        if ($restDto->getEspecieTarefa() !== $entity->getEspecieTarefa() && $entity->getVinculacaoWorkflow()) {
            $this->rulesTranslate->throwException('tarefa', '0022');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 21;
    }
}
