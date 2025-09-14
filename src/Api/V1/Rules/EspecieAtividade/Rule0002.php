<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/EspecieAtividade/Rule0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\EspecieAtividade;

use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieAtividade;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0002.
 *
 * @descSwagger=Verifica se o usuário possui permissão de superadmin
 * @classeSwagger=Rule0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private AuthorizationCheckerInterface $authorizationChecker;

    /**
     * Rule0002 constructor.
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
            EspecieAtividade::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param EspecieAtividade|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\EspecieAtividade|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ((false === $this->authorizationChecker->isGranted('ROLE_ADMIN'))) {
            $this->rulesTranslate->throwException('especie_atividade', '0002');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
