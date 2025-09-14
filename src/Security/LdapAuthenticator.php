<?php

declare(strict_types=1);
/**
 * /src/Security/LdapAuthenticator.php.
 */

namespace SuppCore\AdministrativoBackend\Security;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

/**
 * Class LdapAuthenticator.
 */
class LdapAuthenticator implements AuthenticatorInterface
{
    private LdapService $ldapService;
    private UsuarioResource $usuarioResource;
    private TransactionManager $transactionManager;
    private RolesService $rolesService;
    private PasswordHasherFactoryInterface $passwordHasherFactory;
    private string $username;

    /**
     * LdapAuthenticator constructor.
     *
     * @param LdapService                    $ldapService
     * @param UsuarioResource                $usuarioResource
     * @param TransactionManager             $transactionManager
     * @param RolesService                   $rolesService
     * @param PasswordHasherFactoryInterface $passwordHasherFactory
     */
    public function __construct(
        LdapService $ldapService,
        UsuarioResource $usuarioResource,
        TransactionManager $transactionManager,
        RolesService $rolesService,
        PasswordHasherFactoryInterface $passwordHasherFactory
    ) {
        $this->ldapService = $ldapService;
        $this->usuarioResource = $usuarioResource;
        $this->transactionManager = $transactionManager;
        $this->rolesService = $rolesService;
        $this->passwordHasherFactory = $passwordHasherFactory;
    }

    /**
     * @return LdapService
     */
    public function getLdapService(): LdapService
    {
        return $this->ldapService;
    }

    /**
     * @return UsuarioResource
     */
    protected function getUsuarioResource(): UsuarioResource
    {
        return $this->usuarioResource;
    }

    /**
     * @return TransactionManager
     */
    protected function getTransactionManager(): TransactionManager
    {
        return $this->transactionManager;
    }

    /**
     * @return RolesService
     */
    protected function getRolesService(): RolesService
    {
        return $this->rolesService;
    }

    /**
     * @return PasswordHasherFactoryInterface
     */
    public function getUserPasswordEncoder(): PasswordHasherFactoryInterface
    {
        return $this->passwordHasherFactory;
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
        return $request->get('username')
            && $request->get('password');
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
        if (!$request->get('username') || !$request->get('password')) {
            throw new BadCredentialsException('Dados incorretos!');
        }

        $this->username = $request->get('username');

        $credentials = [
            'username' => $request->get('username'),
            'password' => $request->get('password'),
        ];

        $userData = $this->getLdapService()->getUserData($credentials['username'], $credentials['password']);

        if (!$userData || !isset($userData['cpf'])) {
            throw new UserNotFoundException(sprintf('Usuario "%s" não encontrado.', $credentials['username']));
        }

        if (empty($userData['cpf'])) {
            throw new BadCredentialsException('As suas credenciais não estão atualizadas. Contate o Administrador');
        }

        // Verificando credênciais LDAP
        if (!$this->checkLdapCredentials($credentials, $userData['ldapUser'])) {
            throw new BadCredentialsException('Dados incorretos!');
        }

        $usuario = $this->getUsuarioResource()->getRepository()->findUserByUsernameOrEmail($userData['cpf']);

        if (null === $usuario) {
            $usuario = $this->createUser($userData);
        }

        return new SelfValidatingPassport(new UserBadge($usuario->getUserIdentifier()), []);
    }

    /**
     * Cria o usuário.
     *
     * @param array $userData
     *
     * @return Usuario
     *
     * @throws Exception
     */
    public function createUser(array $userData): UserInterface
    {
        $strongPassword = $this->usuarioResource->generateStrongPassword();
        $usuarioDTO = (new UsuarioDTO())
            ->setUsername($userData['cpf'])
            ->setEmail($userData['email'])
            ->setNome(mb_strtoupper($userData['nome'], 'UTF-8'))
            ->setEnabled(true)
            ->setPlainPassword($strongPassword);

        $transactionId = $this->getTransactionManager()->begin();
        $usuario = $this->getUsuarioResource()->create($usuarioDTO, $transactionId);
        $this->getTransactionManager()->commit();

        if ($userData['email'] !== $usuario->getEmail()) {
            throw new BadCredentialsException('O email do seu perfil está diferente do LDAP. 
                Por gentileza, contate o Administrador para atualização');
        }

        return $usuario;
    }

    /**
     * Verifica as credênciais do usuário.
     *
     * @param mixed         $credentials
     * @param UserInterface $user
     *
     * @return bool
     */
    public function checkLdapCredentials(mixed $credentials, UserInterface $user): bool
    {
        
        if ($this->ldapService::TYPE_AUTH_AD == $this->ldapService->getLdapTypeAuth()) {
            return true;
        }

        return $credentials['password'] === $user->getPassword();
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
     * @param string         $providerKey
     *
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
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
        $token = new PostAuthenticationToken(
            $passport->getUser(),
            $firewallName,
            $this->getRolesService()->getContextualRoles($passport->getUser())
        );

        $token->setAttribute('username', $this->username);
        $token->setAttribute('trusted', 'ldap');

        return $token;
    }
}
