<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Processo/Rule0007.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Repository\VinculacaoPessoaUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0007.
 *
 * @descSwagger  =Em caso de usuário externo, verifica se o mesmo possui unidade vinculada
 * @classeSwagger=Rule0007
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0007 implements RuleInterface
{
    /**
     * @param RulesTranslate $rulesTranslate
     * @param TokenStorageInterface $tokenStorage
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param VinculacaoPessoaUsuarioRepository $vinculacaoPessoaUsuarioRepository
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly VinculacaoPessoaUsuarioRepository $vinculacaoPessoaUsuarioRepository,
        private readonly ParameterBagInterface $parameterBag,
    ) {
    }

    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'beforeCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param ProcessoDTO|RestDtoInterface|null $restDto
     * @param ProcessoEntity|EntityInterface $entity
     * @param string $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(
        ProcessoDTO|RestDtoInterface|null $restDto,
        ProcessoEntity|EntityInterface $entity,
        string $transactionId
    ): bool {
        if (false === $this->authorizationChecker->isGranted('ROLE_COLABORADOR')) {
            $rolesComAcessoProcesso = array_intersect(
                $this->tokenStorage->getToken()->getRoleNames(),
                $this->parameterBag->get('supp_core.administrativo_backend.roles_processo')
            );

            if (!$rolesComAcessoProcesso) {
                $vinculacaoUsuario = $this->vinculacaoPessoaUsuarioRepository
                    ->findOneBy(['usuarioVinculado' => $this->tokenStorage->getToken()->getUser()]);

                if (null === $vinculacaoUsuario) {
                    $this->rulesTranslate->throwException('processo', '0006');
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
