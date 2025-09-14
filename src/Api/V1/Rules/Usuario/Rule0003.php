<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Usuario;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Lotacao;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0003.
 *
 * @descSwagger=Verifica se é coordenador da unidade ou órgão central do usuário ou se é o próprio usuário.
 * @classeSwagger=Rule0003
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Rule0003 implements RuleInterface
{
    private TokenStorageInterface $tokenStorage;

    private RulesTranslate $rulesTranslate;

    protected AuthorizationCheckerInterface $authorizationChecker;

    private CoordenadorService $coordenadorService;

    /**
     * Rule0003 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        CoordenadorService $coordenadorService
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->coordenadorService = $coordenadorService;
    }

    public function supports(): array
    {
        return [
            UsuarioDTO::class => [
                'skipWhenCommand',
                'beforeResetaSenha',
            ],
        ];
    }

    /**
     * @param UsuarioDTO|RestDtoInterface|null $restDto
     * @param UsuarioEntity|EntityInterface    $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN') ||
            $this->tokenStorage->getToken()->getUser()->getId() === $entity->getId()
        ) { //super admin ou o próprio usuario
            return true;
        }

        $isCoordenador = false;
        /** @var Lotacao $lotacao */
        foreach ($entity->getColaborador()->getLotacoes() as $lotacao) {
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorUnidade([$lotacao->getSetor()->getUnidade()]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral(
                    [$lotacao->getSetor()->getUnidade()->getModalidadeOrgaoCentral()]
                );
        }

        if (!$isCoordenador) {
            $this->rulesTranslate->throwException('usuario', '0008');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
