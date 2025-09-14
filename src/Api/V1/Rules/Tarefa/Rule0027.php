<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Rules/Tarefa/Rule0027.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Repository\TarefaRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

use function count;

/**
 * Class Rule0027.
 *
 * @descSwagger=Não é possível excluir a única tarefa aberta do processo.
 *
 * @classeSwagger=Rule0027
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0027 implements RuleInterface
{
    /**
     * Rule0027 constructor.
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly TarefaRepository $tarefaRepository,
    ) {
    }

    /**
     * @return array[]
     */
    public function supports(): array
    {
        return [
            TarefaEntity::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null        $restDto
     * @param TarefaEntity|EntityInterface $entity
     * @param string                       $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(
        ?RestDtoInterface $restDto,
        TarefaEntity|EntityInterface $entity,
        string $transactionId,
    ): bool {
        // vide issue457 e issue1950
        // obs: há regra no judicial que impede deletar tarefa criada a partir de uma intimação
        if ($entity->getProcesso()?->getId()
            && 'JUDICIAL' !== $entity->getEspecieTarefa()?->getGeneroTarefa()->getNome()
            && 1 === count($this->tarefaRepository->findAbertaByProcessoId($entity->getProcesso()?->getId()))
        ) {
            $this->rulesTranslate->throwException('tarefa', '0027');
        }

        return true;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }
}
