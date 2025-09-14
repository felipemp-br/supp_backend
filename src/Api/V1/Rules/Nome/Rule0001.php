<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Rules/Nome/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Nome;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Nome;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger  =As pessoas validadas pela instituição não podem ser alteradas pelos Usuários!
 * @classeSwagger=Rule0001
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    /**
     * Rule0001 constructor.
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private AuthorizationCheckerInterface $authorizationChecker
    ) {
    }

    public function supports(): array
    {
        return [
            Nome::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
                'skipWhenCommand', // necessário ao módulo judicial
            ],
        ];
    }

    /**
     * @param Nome|RestDtoInterface|null $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Nome|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) { //super admin
            return true;
        }

        if ($restDto->getPessoa()->getPessoaValidada()) {
            $this->rulesTranslate->throwException('nome', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
    
}
