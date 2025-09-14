<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0012.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0012.
 *
 * @descSwagger  =A tarefa é de uso exclusivo dos ARQUIVISTAS!
 * @classeSwagger=Rule0012
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0012 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private AuthorizationCheckerInterface $authorizationChecker;

    /**
     * Rule0012 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        AuthorizationCheckerInterface $authorizationChecker,
        private ParameterBagInterface $parameterBag
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'beforeCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param Tarefa|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Tarefa|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
//        if (false === $this->authorizationChecker->isGranted('ROLE_ARQUIVISTA') &&
//            ($this->parameterBag->get('constantes.entidades.especie_tarefa.const_6') === $restDto->getEspecieTarefa()->getNome() ||
//                $this->parameterBag->get('constantes.entidades.especie_tarefa.const_7') === $restDto->getEspecieTarefa()->getNome(
//                ))) {
//            // temporariamente desabilitada para análise... é a transição que deve ser bloqueada, e não a tarefa
//            // $this->rulesTranslate->throwException('tarefa', '0004');
//        }

        return true;
    }

    public function getOrder(): int
    {
        return 12;
    }
}
