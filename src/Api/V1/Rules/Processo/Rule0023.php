<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Processo/Rule0023.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0023.
 *
 * @descSwagger=Um documento avulso do barramento não pode ser autuado
 *
 * @classeSwagger=Rule0023
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0023 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TokenStorageInterface $tokenStorage;

    private AuthorizationCheckerInterface $authorizationChecker;

    /**
     * Rule0023 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function supports(): array
    {
        return [
            Processo::class => [
                'beforeAutuar',
            ],
        ];
    }

    /**
     * @param Processo|RestDtoInterface|null $restDto
     * @param ProcessoEntity|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($entity->getOrigemDados()
            && 'BARRAMENTO_PEN' === $entity->getOrigemDados()->getFonteDados()) {
            $this->rulesTranslate->throwException('processo', '0024');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
