<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Usuario/Rule0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Usuario;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0005.
 *
 * @descSwagger= Apenas usuário ou um super admin pode resetar a senha de um usuário externo!
 * @classeSwagger=Rule0005
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0005 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    protected AuthorizationCheckerInterface $authorizationChecker;

    /**
     * Rule0003 constructor.
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
            Usuario::class => [
                'beforeResetaSenha',
            ],
        ];
    }

    /**
     * @param Usuario|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Usuario|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->authorizationChecker->isGranted('ROLE_USUARIO_EXTERNO')) {
            return true;
        }

        if ((false === $this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN'))
            && (null === $entity->getColaborador())) {
            $this->rulesTranslate->throwException('usuario', '0005');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
