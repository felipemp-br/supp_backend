<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Etiqueta/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Etiqueta;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Etiqueta;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoPessoaUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=Verifica se o usuário possui permissão de conveniado
 * @classeSwagger=Rule0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TokenStorageInterface $tokenStorage;

    private AuthorizationCheckerInterface $authorizationChecker;

    private VinculacaoPessoaUsuarioRepository $vinculacaoPessoaUsuarioRepository;

    /**
     * Rule0001 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        VinculacaoPessoaUsuarioRepository $vinculacaoPessoaUsuarioRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->vinculacaoPessoaUsuarioRepository = $vinculacaoPessoaUsuarioRepository;
    }

    public function supports(): array
    {
        return [
            Etiqueta::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Etiqueta|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Etiqueta|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ((false === $this->authorizationChecker->isGranted('ROLE_COLABORADOR'))) {
            $vinculacaoUsuario = $this->vinculacaoPessoaUsuarioRepository
            ->findOneBy(['usuarioVinculado' => $this->tokenStorage->getToken()->getUser()]);

            if (null === $vinculacaoUsuario) {
                $this->rulesTranslate->throwException('etiqueta', '0001');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
