<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Documento/Rule0008.php.
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
 * Class Rule0008.
 *
 * @descSwagger  =Verifica se o usuário possuí acesso a visualização do processo onde o documento está juntado.
 * @classeSwagger=Rule0008
 */
class Rule0008 implements RuleInterface
{

    /**
     * Rule0008 constructor.
     * @param RulesTranslate $rulesTranslate
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private AuthorizationCheckerInterface $authorizationChecker
    ) {
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
    public function validate(?RestDtoInterface $dto, EntityInterface $entity, string $transactionId): bool
    {
        $processo = $entity->getJuntadaAtual()?->getVolume()->getProcesso();
        if ($entity->getJuntadaAtual() &&
            (
                (false === $this->authorizationChecker->isGranted('VIEW', $processo)) ||
                ($processo->getClassificacao() &&
                    $processo->getClassificacao()->getId() &&
                    (false === $this->authorizationChecker->isGranted('VIEW', $processo->getClassificacao()))
                ))) {
            $this->rulesTranslate->throwException('documento', '0008');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 8;
    }
}
