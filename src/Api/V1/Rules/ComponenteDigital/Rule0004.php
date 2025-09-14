<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ComponenteDigital/Rule0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ComponenteDigital;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0004.
 *
 * @descSwagger  =Usuário não possui poderes para editar o NUP!
 * @classeSwagger=Rule0004
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
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
            ComponenteDigital::class => [
                'beforeUpdate',
                'beforePatch',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param ComponenteDigital|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\ComponenteDigital|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $processo = $entity->getDocumento()->getJuntadaAtual()?->getVolume()->getProcesso();
        if ($entity->getDocumento()->getJuntadaAtual() &&
            (
                (false === $this->authorizationChecker->isGranted('EDIT', $processo)) ||
                ($processo->getClassificacao() &&
                    $processo->getClassificacao()->getId() &&
                    (false === $this->authorizationChecker->isGranted('EDIT', $processo->getClassificacao()))
                )
            )) {
            $this->rulesTranslate->throwException('componenteDigital', '0004');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
