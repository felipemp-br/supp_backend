<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ComponenteDigital/Rule0002.php.
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
 * Class Rule0002.
 *
 * @descSwagger=Usuário não possui poderes para ver o NUP!
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
            ComponenteDigital::class => [
                'beforeDownload',
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
        if (false === $this->authorizationChecker->isGranted('VIEW', $entity->getDocumento())) {
            $this->rulesTranslate->throwException('componenteDigital', '0002');
        }

        $processo = $entity->getDocumento()->getJuntadaAtual()?->getVolume()->getProcesso();
        if ($entity->getDocumento()->getJuntadaAtual() &&
            (
                (false === $this->authorizationChecker->isGranted('VIEW', $processo)) ||
                ($processo->getClassificacao() &&
                    $processo->getClassificacao()->getId() &&
                    (false === $this->authorizationChecker->isGranted('VIEW', $processo->getClassificacao()))
                )
            )) {
            $this->rulesTranslate->throwException('componenteDigital', '0002');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
