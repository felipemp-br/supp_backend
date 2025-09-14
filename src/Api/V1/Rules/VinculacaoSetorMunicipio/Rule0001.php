<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoSetorMunicipio;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoSetorMunicipio;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Rule0001 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    protected AuthorizationCheckerInterface $authorizationChecker;

    /**
     * Rule0001 constructor.
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
            VinculacaoSetorMunicipio::class => [
                'beforeCreate',
                'beforeDelete',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $this->rulesTranslate->throwException('vinculacaoSetorMunicipio', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
