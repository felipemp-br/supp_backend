<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoProcesso/Rule0015.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoProcesso;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0015.
 *
 * @descSwagger=Para realizar a exclusão o usuário precisa de poderes de editar ambos os NUPs!
 * @classeSwagger=Rule0015
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0015 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private AuthorizationCheckerInterface $authorizationChecker;

    /**
     * Rule0015 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate,
                                AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->rulesTranslate = $rulesTranslate;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function supports(): array
    {
        return [
            VinculacaoProcesso::class => [
                'beforeDelete',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param \SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso|RestDtoInterface|null $restDto
     * @param VinculacaoProcesso|EntityInterface                                                  $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ((false === $this->authorizationChecker->isGranted('EDIT', $entity->getProcesso())) ||
            (false === $this->authorizationChecker->isGranted('EDIT', $entity->getProcessoVinculado())) ||
            ($entity->getProcesso()->getClassificacao() &&
                $entity->getProcesso()->getClassificacao()->getId() &&
                (false === $this->authorizationChecker->isGranted(
                    'EDIT',
                    $entity->getProcesso()->getClassificacao()
            )))
            || ($entity->getProcessoVinculado()->getClassificacao() &&
                    $entity->getProcessoVinculado()->getClassificacao()->getId() &&
                        (false === $this->authorizationChecker->isGranted(
                    'EDIT',
                    $entity->getProcessoVinculado()->getClassificacao()
            )))) {
            $this->rulesTranslate->throwException('vinculacaoProcesso', '0015');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
