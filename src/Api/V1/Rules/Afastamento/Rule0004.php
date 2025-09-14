<?php

/** @noinspection PhpUndefinedClassInspection */
declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Afastamento/Rule0004.php.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Afastamento;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Afastamento;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0004.
 *
 * @descSwagger=Afastamento pode ser excluído pelo seu dono ou usuário com perfil de admin e/ou de coordenador dos setores, unidades ou orgãos centrais.
 * @classeSwagger=Rule0004
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Rule0004 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private AuthorizationCheckerInterface $authorizationChecker;

    private TokenStorageInterface $tokenStorage;

    private CoordenadorService $coordenadorService;

    /**
     * Rule0004 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        CoordenadorService $coordenadorService
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->coordenadorService = $coordenadorService;
    }

    public function supports(): array
    {
        return [
            Afastamento::class => [
                'beforeDelete',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param \SuppCore\AdministrativoBackend\Api\V1\DTO\Afastamento|RestDtoInterface|null $restDto
     * @param Afastamento|EntityInterface                                                  $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN') ||
            $this->tokenStorage->getToken()->getUser()->getId() === $entity->getColaborador()->getUsuario()->getId()
        ) { 
            return true;
        }

        $isCoordenador = false;
        /*
         * verificar se é coordenador dos setores, unidades ou orgãos centrais
         */
        foreach ($entity->getColaborador()->getLotacoes() as $lotacao) {
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorSetor([$lotacao->getSetor()]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorUnidade([$lotacao->getSetor()->getUnidade()]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral(
                    [$lotacao->getSetor()->getUnidade()->getModalidadeOrgaoCentral()]
                );
        }

        if (!$isCoordenador) {
            $this->rulesTranslate->throwException('afastamento', '0004');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 4;
    }
}