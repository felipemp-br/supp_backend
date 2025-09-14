<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Documento/Rule0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Documento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0002.
 *
 * @descSwagger  =Usuário não possui poderes para editar o NUP!
 * @classeSwagger=Rule0002
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private AuthorizationCheckerInterface $authorizationChecker;

    /**
     * Rule0002 constructor.
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
            Documento::class => [
                'beforeUpdate',
                'beforePatch',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param Documento|RestDtoInterface|null $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Documento|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $processo = $entity->getJuntadaAtual()?->getVolume()->getProcesso();
        if ($entity->getJuntadaAtual() &&
            (
                (false === $this->authorizationChecker->isGranted('EDIT', $processo)) ||
                ($processo->getClassificacao() &&
                    $processo->getClassificacao()->getId() &&
                    (false === $this->authorizationChecker->isGranted('EDIT', $processo->getClassificacao()))
                ))
        ) {
            $this->rulesTranslate->throwException('documento', '0002');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
