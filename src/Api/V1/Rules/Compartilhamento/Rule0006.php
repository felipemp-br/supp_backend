<?php

/** @noinspection PhpUndefinedClassInspection */
declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Compartilhamento/Rule0006.php.
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
 * @descSwagger=Coloradores inativos não podem receber compartilhamentos!
 * @classeSwagger=Rule0006
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0006 implements RuleInterface
{
    /**
     * Rule0006 constructor.
     *
     * @param RulesTranslate        $rulesTranslate
     */
    public function __construct(
        private RulesTranslate $rulesTranslate
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
        if ($restDto->getUsuario()->getEnabled() < 1) {
            $this->rulesTranslate->throwException('compartilhamento', '0006');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
