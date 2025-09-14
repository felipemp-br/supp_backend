<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoRole/Rule0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoRole;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoRole;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0004.
 *
 * @descSwagger=Apenas administradores podem incluir vinculacoes de roles!
 * @classeSwagger=Rule0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0004 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate
    ) {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            VinculacaoRole::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (('ROLE_ADMIN' === $restDto->getRole()) &&
            !$restDto->getUsuario()?->getColaborador()) {
            $this->rulesTranslate->throwException('vinculacao_role', '0004');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
