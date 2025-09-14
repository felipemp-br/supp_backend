<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0029.php.
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
 * Class Rule0029.
 *
 * @see AbstractSolicitacaoAutomatizadaDriver::createTarefa
 * @descSwagger=Não é possível criar ou atualizar uma tarefa relacionada a solicitação automatizada de forma manual.
 * @classeSwagger=Rule0029
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0029 implements RuleInterface
{
    /**
     * @param RulesTranslate                  $rulesTranslate
     * @param SolicitacaoAutomatizadaResource $solicitacaoAutomatizadaResource
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
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param TarefaDTO|RestDtoInterface|null $restDto
     * @param TarefaEntity|EntityInterface    $entity
     * @param string                          $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(
        TarefaDTO|RestDtoInterface|null $restDto,
        TarefaEntity|EntityInterface $entity,
        string $transactionId
    ): bool {
        $solicitacaoAutomatizada = $this->solicitacaoAutomatizadaResource->findOneBy(
            ['processo' => $restDto->getProcesso()]
        );
        if ($solicitacaoAutomatizada) {
            $this->rulesTranslate->throwException('tarefa', '0029');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 28;
    }
}
