<?php

declare(strict_types=1);
/**
 * /src/Security/SsoGovBrAuthenticator.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoPessoaUsuario;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoPessoaUsuarioResource;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\ModalidadeGeneroPessoaRepository;
use SuppCore\AdministrativoBackend\Repository\ModalidadeQualificacaoPessoaRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

/**
 * Class SsoGovBrAuthenticator.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SsoGovBrAuthenticator implements AuthenticatorInterface
{
    /**
     * SsoGovBrAuthenticator constructor.
     *
     * @param LoginUnicoGovBrService $loginUnicoGovBrService
     * @param UsuarioResource        $usuarioResource
     * @param TransactionManager     $transactionManager
     * @param RolesService           $rolesService
     * @param SsoGovBrUsuario        $ssoGovBrUsuario
     */
    public function __construct(
        private LoginUnicoGovBrService $loginUnicoGovBrService,
        private UsuarioResource $usuarioResource,
        private TransactionManager $transactionManager,
        private RolesService $rolesService,
        private SsoGovBrUsuario $ssoGovBrUsuario,
        private readonly PessoaResource $pessoaResource,
        private readonly ModalidadeGeneroPessoaRepository $modalidadeGeneroPessoaRepository,
        private readonly ModalidadeQualificacaoPessoaRepository $modalidadeQualificacaoPessoaRepository,
        private readonly VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource,
        private readonly ParameterBagInterface $parameterBag,
    ) {
    }

    /**
     * @return LoginUnicoGovBrService
     */
    public function getLoginUnicoGovBrService(): LoginUnicoGovBrService
    {
        return $this->loginUnicoGovBrService;
    }

    /**
     * @return UsuarioResource
     */
    public function getUsuarioResource(): UsuarioResource
    {
        return $this->usuarioResource;
    }

    /**
     * @return TransactionManager
     */
    public function getTransactionManager(): TransactionManager
    {
        return $this->transactionManager;
    }

    /**
     * @return RolesService
     */
    public function getRolesService(): RolesService
    {
        return $this->rolesService;
    }

    /**
     * Verifica se o autenticador é suportado.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return (bool) $request->get('code');
    }

    /**
     * Cria um passport para a solicitação atual.
     *
     * @param Request $request
     *
     * @return Passport
     *
     * @throws Exception
     */
    public function authenticate(Request $request): Passport
    {
        $credentials = $this->getLoginUnicoGovBrService()->retornaDadosUsuario($request->get('code'), $request->get('redirectUri'));

        if (!$credentials) {
            throw new BadCredentialsException('Dados incorretos!');
        }

        $usuario = $this->getUsuarioResource()->getRepository()->findUserByUsernameOrEmail($credentials['username']);

        if (null === $usuario) {
            $usuario = $this->createUser($credentials);
        } else  {
            $vinculacaoPessoaUsuario = $this->vinculacaoPessoaUsuarioResource->findOneBy(
                ['usuarioVinculado' => $usuario]
            );
            if (null === $vinculacaoPessoaUsuario) {
                $this->createPessoa($usuario);
            }
        }

        $this->ssoGovBrUsuario->setUsuario($usuario);
        $this->ssoGovBrUsuario->setAccessToken($credentials['access_token']);
        $this->ssoGovBrUsuario->setIdToken($credentials['id_token']);

        return new Passport(new UserBadge($usuario->getUserIdentifier()), new CustomCredentials(
            function ($credentials, UserInterface $usuario) {
                return $usuario->getUserIdentifier() === $credentials['username'];
            },
            $credentials
        ));
    }

    /**
     * Listener de falha de autenticação.
     *
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * Listener de evento de autenticação realizada com sucesso.
     *
     * @param Request        $request
     * @param TokenInterface $token
     * @param string         $firewallName
     *
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    /**
     * Cria o usuário.
     *
     * @param array $credentials
     *
     * @return Usuario
     *
     * @throws Exception
     */
    public function createUser(array $credentials): UserInterface
    {
        $usuarioDTO = (new UsuarioDTO())
            ->setUsername($credentials['username'])
            ->setEmail($credentials['email'])
            ->setNome($credentials['nome'])
            ->setEnabled(true);

        $usuarioDTO->setPlainPassword($this->getUsuarioResource()->generateStrongPassword());

        $transactionId = $this->getTransactionManager()->begin();
        $usuario = $this->getUsuarioResource()->create($usuarioDTO, $transactionId);
        $this->getTransactionManager()->commit();

        return $usuario;
    }

    /**
     * Cria um token autenticado para o usuário fornecido.
     *
     * @param Passport $passport
     * @param string   $firewallName
     *
     * @return TokenInterface
     */
    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        $niveis = $this->getLoginUnicoGovBrService()
                    ->getNiveisUsuarioData(
                    $this->ssoGovBrUsuario->getUserIdentifier(),
                    $this->ssoGovBrUsuario->getAccessToken()
                );

        $confiavel = $this->getLoginUnicoGovBrService()
                    ->validaNiveisUsuario($niveis);

        $token = new PostAuthenticationToken(
            $passport->getUser(),
            $firewallName,
            $this->getRolesService()->getContextualRoles($passport->getUser())
        );

        if ($confiavel) {
            $token->setAttribute('trusted', 'ssoGovBr');
        }

        return $token;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws Exception
     */
    public function createPessoa(Usuario $usuario): void
    {
        $transactionId = $this->getTransactionManager()->begin();

        $pessoa = $this->pessoaResource->findOneBy([
            'numeroDocumentoPrincipal' => $usuario->getUsername()
        ]);

        if(!$pessoa){
            $pessoaDTO = new Pessoa();
            $pessoaDTO->setNome($usuario->getNome());
            $pessoaDTO->setNumeroDocumentoPrincipal($usuario->getUsername());
            $pessoaDTO->setModalidadeGeneroPessoa(
                $this->modalidadeGeneroPessoaRepository->findOneBy(
                    ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_genero_pessoa.const_1')]
                )
            );
            $pessoaDTO->setModalidadeQualificacaoPessoa(
                $this->modalidadeQualificacaoPessoaRepository->findOneBy(
                    ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_genero_pessoa.const_2')]
                )
            );

            $pessoa = $this->pessoaResource->create(
                $pessoaDTO,
                $transactionId
            );
        }

        // vinculação
        $vinculacaoPessoaUsuarioDTO = new VinculacaoPessoaUsuario();
        $vinculacaoPessoaUsuarioDTO->setUsuarioVinculado($usuario);
        $vinculacaoPessoaUsuarioDTO->setPessoa($pessoa);

        $this->vinculacaoPessoaUsuarioResource->create(
            $vinculacaoPessoaUsuarioDTO,
            $transactionId
        );

        $this->getTransactionManager()->commit();
    }
}
