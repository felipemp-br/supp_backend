<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoProcesso/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoProcesso;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=Para realizar a vinculação o usuário precisa de poderes de editar ambos os NUPs!
 * @classeSwagger=Rule0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private AuthorizationCheckerInterface $authorizationChecker;

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
            VinculacaoProcesso::class => [
                'beforeCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param VinculacaoProcesso|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ((false === $this->authorizationChecker->isGranted('EDIT', $restDto->getProcesso())) ||
            (false === $this->authorizationChecker->isGranted('EDIT', $restDto->getProcessoVinculado()))
            || ($restDto->getProcesso()->getClassificacao() &&
                $restDto->getProcesso()->getClassificacao()->getId() &&
                (false === $this->authorizationChecker->isGranted(
                'EDIT',
                $restDto->getProcesso()->getClassificacao()
            )))
            || ($restDto->getProcessoVinculado()->getClassificacao() &&
                    $restDto->getProcessoVinculado()->getClassificacao()->getId() &&
                    (false === $this->authorizationChecker->isGranted(
                'EDIT',
                $restDto->getProcessoVinculado()->getClassificacao()
            )))) {
            $this->rulesTranslate->throwException('vinculacaoProcesso', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
