<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Processo/Rule0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0004.
 *
 * @descSwagger=Apenas um arquivista lotado no setor pode realizar a operação!
 * @classeSwagger=Rule0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;
    private AuthorizationCheckerInterface $authorizationChecker;

    /**
     * Rule0004 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function supports(): array
    {
        return [
            Processo::class => [
                'beforeUpdate',
                'beforePatch',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param Processo|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Processo|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        // não aplica a regra em conversões de gênero
        if ($restDto->getEspecieProcesso() &&
            ($restDto->getEspecieProcesso()->getGeneroProcesso()->getId() !==
                $entity->getEspecieProcesso()->getGeneroProcesso()->getId())) {
            return true;
        }

        if ($restDto->getClassificacao() &&
            ($restDto->getClassificacao()->getId() !== $entity->getClassificacao()->getId()) &&
            (false === $this->authorizationChecker->isGranted('ROLE_ARQUIVISTA') &&
                false === $this->authorizationChecker->isGranted('ROLE_PROTOCOLO'))) {
            $this->rulesTranslate->throwException('processo', '0004');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 4;
    }
}
