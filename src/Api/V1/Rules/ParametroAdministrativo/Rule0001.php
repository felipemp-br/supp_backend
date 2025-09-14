<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ParametroAdministrativo/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ParametroAdministrativo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ParametroAdministrativo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger  =Valida se o usuário tem permissão para excluir editar e alterar.
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
        private readonly RulesTranslate $rulesTranslate,
        private readonly AuthorizationCheckerInterface $authorizationChecker
    ) {
    }

    public function supports(): array
    {
        return [
            ParametroAdministrativo::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN') ||
            $this->authorizationChecker->isGranted('ROLE_COORDENADOR')
        ) {
            return true;
        }

        $this->rulesTranslate->throwException('parametroAdministrativo', '0001');
    }

    public function getOrder(): int
    {
        return 1;
    }
}
