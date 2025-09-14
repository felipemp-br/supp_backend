<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0028.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SolicitacaoAutomatizadaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0028.
 *
 * @descSwagger=A tarefa está ligada a uma Solicitação Automatizada e não pode ser excluída.
 * @classeSwagger=Rule0028
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0028 implements RuleInterface
{
    /**
     * Rule0028 constructor.
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly SolicitacaoAutomatizadaResource $solicitacaoAutomatizadaResource
    ) {
    }

    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'beforeDelete',
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
    public function validate(TarefaDTO|RestDtoInterface|null $restDto, TarefaEntity|EntityInterface $entity, string $transactionId): bool
    {
        $solicitacaoAutomatizada = $this->solicitacaoAutomatizadaResource->findOneBy(
            ['processo' => $restDto->getTarefa()->getProcesso()]
        );
        if ($solicitacaoAutomatizada) {
            $this->rulesTranslate->throwException('tarefa', '0028');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 28;
    }
}
