<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Rules/DocumentoIdentificador/Rule0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\DocumentoIdentificador;

use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoIdentificador;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0002.
 *
 * @descSwagger  =As pessoas representadas pela instituição não podem ser alteradas pelos Usuários!
 * @classeSwagger=Rule0002
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    /**
     * Rule0002 constructor.
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private AuthorizationCheckerInterface $authorizationChecker
    ) {
    }

    public function supports(): array
    {
        return [
            DocumentoIdentificador::class => [
                'beforeCreate',
                'skipWhenCommand', // necessário ao mod judicial
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) { //super admin
            return true;
        }

        if ($restDto->getPessoa()->getPessoaValidada()) {
            $this->rulesTranslate->throwException('documentoIdentificador', '0002');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
