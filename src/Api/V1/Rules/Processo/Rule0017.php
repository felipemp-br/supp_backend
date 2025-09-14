<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Processo/Rule0017.php.
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
 * Class Rule0017.
 *
 * @descSwagger=Valida se o usuário tem permissão para visualizar a classificação do processo.
 * @classeSwagger=Rule0017
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0017 implements RuleInterface
{
    /**
     * Rule0017 constructor.
     */
    public function __construct(private RulesTranslate $rulesTranslate,
                                private AuthorizationCheckerInterface $authorizationChecker) {
    }

    public function supports(): array
    {
        return [
            Processo::class => [
                'beforeUpdate',
                'beforePatch',
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
        if ($restDto->getClassificacao() &&
            ($restDto->getClassificacao()->getId() !== $entity->getClassificacao()->getId()) &&
            (false === $this->authorizationChecker->isGranted('VIEW', $restDto->getClassificacao()))) {
            $this->rulesTranslate->throwException('processo', '0018');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
