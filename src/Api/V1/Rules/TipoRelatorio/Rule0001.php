<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/TipoRelatorio/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\TipoRelatorio;

use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoRelatorio;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=Verifica se o usuário não é Super Usuário
 * @classeSwagger=Rule0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    private \SuppCore\AdministrativoBackend\Rules\RulesTranslate $rulesTranslate;

    private \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker;

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
            TipoRelatorio::class => [
                'beforeCreate',
                'beforePatch',
                'beforeDelete'
            ],
        ];
    }

    /**
     * @param TipoRelatorio|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\TipoRelatorio|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (false === $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $this->rulesTranslate->throwException('tipo_relatorio', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
