<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Relevancia/Rule0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Relevancia;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Relevancia;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0003.
 *
 * @descSwagger=Usuário não possui poderes para editar o NUP!
 * @classeSwagger=Rule0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private AuthorizationCheckerInterface $authorizationChecker;

    /**
     * Rule0003 constructor.
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
            Relevancia::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param \SuppCore\AdministrativoBackend\Api\V1\DTO\Relevancia|RestDtoInterface|null $restDto
     * @param Relevancia|EntityInterface                                                  $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ((false === $this->authorizationChecker->isGranted('EDIT', $entity->getProcesso()))
            || ($entity->getProcesso()->getClassificacao() &&
                $entity->getProcesso()->getClassificacao()->getId() &&
                (false === $this->authorizationChecker->isGranted(
                'EDIT',
                $entity->getProcesso()->getClassificacao()
            )))) {
            $this->rulesTranslate->throwException('relevancia', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 3;
    }
}
