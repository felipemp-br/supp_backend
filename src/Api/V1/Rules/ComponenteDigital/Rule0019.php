<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ComponenteDigital/Rule0019.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ComponenteDigital;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoPessoaUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0019.
 *
 * @descSwagger=Caso informado um hash no criação do componente, é preciso validar a existência no filesystem!
 * @classeSwagger=Rule0019
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0019 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    protected AuthorizationCheckerInterface $authorizationChecker;

    protected TokenStorageInterface $tokenStorage;

    protected VinculacaoPessoaUsuarioRepository $vinculacaoPessoaUsuarioRepository;

    /**
     * Rule0019 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        VinculacaoPessoaUsuarioRepository $vinculacaoPessoaUsuarioRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->vinculacaoPessoaUsuarioRepository = $vinculacaoPessoaUsuarioRepository;
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'beforeCreate',
                'beforePatch',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param ComponenteDigital|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\ComponenteDigital|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (false === $this->authorizationChecker->isGranted('ROLE_COLABORADOR')) {
            //verifica se possui alguma vinculação, se não possuir, não pode atualizar componente digital
            $vinculacaoUsuario = $this->vinculacaoPessoaUsuarioRepository
                ->findOneBy(['usuarioVinculado' => $this->tokenStorage->getToken()->getUser()]);

            if (!$vinculacaoUsuario) {
                $this->rulesTranslate->throwException('componenteDigital', '0002');
            }

            if (null !== $restDto->getDocumentoAvulsoOrigem()) {
                //verifica se o componente digital está vinculado ao usuário logado conveniado
                $vinculacaoUsuario = $this->vinculacaoPessoaUsuarioRepository
                ->findOneBy(['usuarioVinculado' => $this->tokenStorage->getToken()->getUser(),
                    'pessoa' => $restDto->getDocumentoAvulsoOrigem()->getPessoaDestino(), ]);

                if ($vinculacaoUsuario) {
                    if (null !== $restDto->getDocumentoAvulsoOrigem()) {
                        if ($vinculacaoUsuario->getPessoa()->getId()
                        !== $restDto->getDocumentoAvulsoOrigem()->getPessoaDestino()->getId()) {
                            $this->rulesTranslate->throwException('componenteDigital', '0002');
                        }
                    }
                } else {
                    $this->rulesTranslate->throwException('componenteDigital', '0002');
                }
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
