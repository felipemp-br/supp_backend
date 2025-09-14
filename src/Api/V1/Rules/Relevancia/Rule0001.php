<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Relevancia/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Relevancia;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Relevancia;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger  =Usuário não possui poderes para editar o NUP!
 * @classeSwagger=Rule0001
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
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
            Relevancia::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Relevancia|RestDtoInterface|null $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Relevancia|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ((false === $this->authorizationChecker->isGranted('EDIT', $restDto->getProcesso())) ||
            ($restDto->getProcesso()->getClassificacao() &&
                $restDto->getProcesso()->getClassificacao()->getId() &&
                (false === $this->authorizationChecker->isGranted(
                        'EDIT',
                        $restDto->getProcesso()->getClassificacao()
                    )))) {
            $this->rulesTranslate->throwException('relevancia', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
