<?php

/** @noinspection PhpUndefinedClassInspection */
declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Compartilhamento/Rule0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Compartilhamento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Compartilhamento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Security\RolesServiceInterface;

/**
 * Class Rule0005.
 *
 * @descSwagger=Apenas colaboradores podem receber compartilhamento de tarefas!
 * @classeSwagger=Rule0005
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0005 implements RuleInterface
{
    /**
     * Rule0005 constructor.
     *
     * @param RulesTranslate        $rulesTranslate
     * @param RolesServiceInterface $rolesService
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private RolesServiceInterface $rolesService
    ) {
    }

    public function supports(): array
    {
        return [
            Compartilhamento::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (!in_array('ROLE_COLABORADOR', $this->rolesService->getContextualRoles($restDto->getUsuario()))) {
            $this->rulesTranslate->throwException('compartilhamento', '0005');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
