<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0024.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Api\V1\Resource\LotacaoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0024.
 *
 * @descSwagger=Usuários inativados e/ou usuários não lotados no Setor Responsável não podem receber Tarefas
 * @classeSwagger=Rule0024
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0024 implements RuleInterface
{

    /**
     * Rule0024 constructor.
     */
    public function __construct(
        protected RulesTranslate $rulesTranslate,
        protected LotacaoResource $lotacaoResource
    ) {}

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'afterCreate',
                'afterUpdate',
                'afterPatch'
            ],
        ];
    }

    /**
     * @param Tarefa|RestDtoInterface|null $restDto
     * @param TarefaEntity|EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(Tarefa|RestDtoInterface|null $restDto, TarefaEntity|EntityInterface $entity, string $transactionId): bool
    {
        if (!$restDto->getUsuarioResponsavel()->getEnabled()) {
            $this->rulesTranslate->throwException('tarefa', '0024a');
        }

        $isLotado = $this->lotacaoResource->getRepository()->findLotacaoBySetorAndColaborador(
            $restDto->getSetorResponsavel(),
            $restDto->getUsuarioResponsavel()->getColaborador()
        );

        //SE NÃO ESTIVER LOTADO E FOR NO MOMENTO DA REDISTRIBUIÇÃO
        if (!$isLotado &&
            $restDto->getUsuarioResponsavel() !== $entity->getUsuarioResponsavel()) {
            $this->rulesTranslate->throwException('tarefa', '0024b');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 13;
    }
}
