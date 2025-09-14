<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Modelo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Modelo as ModeloDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Modelo;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0005
 * Rule para validar se o usuário tem permissão para excluir o Componente Digital.
 *
 * @author Willian Santos <willian.santos@datainfo.inf.br>
 */
class Rule0005 implements RuleInterface
{
    private TokenStorageInterface $tokenStorage;

    private RulesTranslate $rulesTranslate;

    private AuthorizationCheckerInterface $authorizationChecker;

    /**
     * Rule0005 constructor.
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        RulesTranslate $rulesTranslate,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->rulesTranslate = $rulesTranslate;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function supports(): array
    {
        return [
            ModeloDTO::class => [
                'befodeDelete',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        /** @var ComponenteDigital[] $componentesDigitais */
        /** @var Modelo $entity */
        $componentesDigitais = $entity->getDocumento()->getComponentesDigitais();
        $isPermissao = true;

        foreach ($componentesDigitais as $componenteDigital) {
            if (false === $this->authorizationChecker->isGranted('DELETE', $componenteDigital)) {
                $isPermissao = false;
            }
        }

        if (false === $isPermissao) {
            $this->rulesTranslate->throwException('modelo', '0003');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 4;
    }
}
