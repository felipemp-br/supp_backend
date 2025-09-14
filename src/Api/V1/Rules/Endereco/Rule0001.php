<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Rules/Endereco/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Endereco;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Endereco;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger  =O Endereco está vinculado a outro! Exclua primeiro a vinculação!
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
            Endereco::class => [
                'beforeUpdate',
                'beforePatch',
                'beforeDelete',
                'skipWhenCommand', // necessário ao módulo judicial
            ],
        ];
    }

    /**
     * @param Endereco|RestDtoInterface|null $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Endereco|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) { //super admin
            return true;
        }

        if ($entity->getPessoa()->getPessoaValidada()) {
            $this->rulesTranslate->throwException('endereco', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
    
}
