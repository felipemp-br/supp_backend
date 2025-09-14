<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Usuario/Rule0013.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Usuario;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\TarefaRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0013.
 *
 * @descSwagger=O usuario não pode ser inativado pois possui tarefas pendentes de conclusão!
 * @classeSwagger=Rule0013
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0013 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TarefaRepository $tarefaRepository;

    /**
     * Rule0013 constructor.
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
            Usuario::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Usuario|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Usuario|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ((false === $restDto->getEnabled()) &&
            $this->tarefaRepository->hasAbertaByUsuarioResponsavelId($restDto->getId())) {
            $this->rulesTranslate->throwException('usuario', '0013');
        }

        return true;
    }

    public function getOrder(): int    {
        return 1;
    }
}
