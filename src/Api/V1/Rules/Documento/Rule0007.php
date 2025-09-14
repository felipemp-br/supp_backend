<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Documento/Rule0007.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Documento;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0007.
 *
 * @descSwagger=Verifica se o usuário possuí acesso a visualização do documento.
 * @classeSwagger=Rule0007
 */
class Rule0007 implements RuleInterface
{

    /**
     * Rule0007 constructor.
     * @param RulesTranslate $rulesTranslate
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(private RulesTranslate $rulesTranslate,
                                private AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    public function supports(): array
    {
        return [
            Documento::class => [
                'beforeConvertToPDF',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (!$this->authorizationChecker->isGranted('VIEW', $entity)) {
            $this->rulesTranslate->throwException('documento', '0007');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 7;
    }
}
