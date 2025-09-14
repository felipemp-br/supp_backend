<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0011.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AfastamentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\LotacaoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0011.
 *
 * @descSwagger=O usuário está afastado e não pode receber novas tarefas!
 * @classeSwagger=Rule0011
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0011 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private AuthorizationCheckerInterface $authorizationChecker;

    private TokenStorageInterface $tokenStorage;

    private AfastamentoResource $afastamentoResource;

    private LotacaoResource $lotacaoResource;

    /**
     * Rule0011 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        AfastamentoResource $afastamentoResource,
        LotacaoResource $lotacaoResource
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->afastamentoResource = $afastamentoResource;
        $this->lotacaoResource = $lotacaoResource;
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'beforeCreate',
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
        // Se distribuição manual forçada, pular validação
        if ($restDto->getDistribuicaoManualForcada()) {
            return true;
        }

        if ($restDto->getUsuarioResponsavel() &&
            $restDto->getUsuarioResponsavel()->getColaborador()) {
            $temAfastamento = $this->afastamentoResource->getRepository()
                ->findAfastamento(
                    $restDto->getUsuarioResponsavel()->getColaborador()->getId(),
                    $restDto->getDataHoraFinalPrazo()
                );

            if ($temAfastamento) {
                $this->rulesTranslate->throwException('tarefa', '0011');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 13;
    }
}
