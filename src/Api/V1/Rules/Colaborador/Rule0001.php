<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Colaborador/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Colaborador;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Colaborador;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\TarefaRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001.
 *
 * @descSwagger=O colaborador não pode ser inativado caso possua tarefas não encerradas.
 * @classeSwagger=Rule0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TarefaRepository $tarefaRepository;

    /**
     * Rule0001 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TarefaRepository $tarefaRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->tarefaRepository = $tarefaRepository;
    }

    public function supports(): array
    {
        return [
            Colaborador::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Colaborador|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Colaborador|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ((false === $restDto->getAtivo()) &&
            $this->tarefaRepository->hasAbertaByUsuarioResponsavelId($restDto->getUsuario()->getId())) {
            $this->rulesTranslate->throwException('colaborador', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
