<?php

declare(strict_types=1);
/**
 * /src/Security/X509Authenticator.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use phpseclib3\File\X509;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

/**
 * Class X509Authenticator.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class X509Authenticator implements AuthenticatorInterface
{
    private UsuarioResource $usuarioResource;
    private TransactionManager $transactionManager;
    private RolesService $rolesService;
    private LoggerInterface $logger;

    /**
     * X509Authenticator constructor.
     *
     * @param UsuarioResource          $usuarioResource
     * @param TransactionManager       $transactionManager
     * @param RolesService             $rolesService
     * @param EventDispatcherInterface $eventDispatcher
     * @param LoggerInterface          $logger
     */
    public function __construct(
        UsuarioResource $usuarioResource,
        TransactionManager $transactionManager,
        RolesService $rolesService,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger
    ) {
        $this->usuarioResource = $usuarioResource;
        $this->transactionManager = $transactionManager;
        $this->rolesService = $rolesService;
        $this->logger = $logger;
    }

    /**
     * @return UsuarioResource
     */
    protected function getUsuarioResource(): UsuarioResource
    {
        return $this->usuarioResource;
    }

    /**
     * @return RolesService
     */
    protected function getRolesService(): RolesService
    {
        return $this->rolesService;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return $request->server->has('HTTP_SSL_CLIENT_CERT');
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function authenticate(Request $request): Passport
    {
        $x509 = new X509();
        $this->logger->critical(print_r($request->server->all(), true));
        $c = $x509->loadX509(urldecode($request->server->get('HTTP_SSL_CLIENT_CERT')));

        $cn = null;
        $username = null;
        $nome = null;
        $email = null;

        // cn
        if (isset($c['tbsCertificate']['subject']['rdnSequence'])) {
            foreach ($c['tbsCertificate']['subject']['rdnSequence'] as $rdnSequence) {
                foreach ($rdnSequence as $sequence) {
                    foreach ($sequence as $sequence2) {
                        if (isset($sequence2['type']['id-at-commonName']) &&
                            isset($sequence2['value']['utf8String'])) {
                            $cn = $sequence2['value']['utf8String'];
                            $nome = explode(':', $cn)[0];
                        }
                    }
                }
            }
        }

        // cpf
        if (isset($c['tbsCertificate']['extensions'])) {
            foreach ($c['tbsCertificate']['extensions'] as $extension) {
                if (isset($extension['extnValue'])) {
                    foreach ($extension['extnValue'] as $extensionValue) {
                        if (isset($extensionValue['otherName']['type-id']) &&
                            isset($extensionValue['otherName']['value']['octetString']) &&
                            (('2.16.76.1.3.1' === $extensionValue['otherName']['type-id']) ||
                                ('2.16.76.1.3.4' === $extensionValue['otherName']['type-id']))) {
                            $username =
                                substr($extensionValue['otherName']['value']['octetString'], 8, 11);
                            $email = $username.'@inexistente.com';
                        }
                    }
                }
            }
        }

        // cnpj
        if (isset($c['tbsCertificate']['extensions'])) {
            foreach ($c['tbsCertificate']['extensions'] as $extension) {
                if (isset($extension['extnValue'])) {
                    foreach ($extension['extnValue'] as $extensionValue) {
                        if (isset($extensionValue['otherName']['type-id']) &&
                            isset($extensionValue['otherName']['value']['octetString']) &&
                            ('2.16.76.1.3.3' === $extensionValue['otherName']['type-id'])) {
                            $username =
                                substr($extensionValue['otherName']['value']['octetString'], 0, 14);
                            $email = $username.'@inexistente.com';
                        }
                    }
                }
            }
        }

        $credentials = [
            'username' => $username,
            'nome' => $nome,
            'email' => $email,
        ];

        $usuario = $this->getUsuarioResource()->getRepository()->findUserByUsernameOrEmail($credentials['username']);

        if (null === $usuario) {
            $usuario = $this->createUser($credentials);
        }

        return new Passport(new UserBadge($usuario->getUserIdentifier()), new CustomCredentials(
            function ($credentials, UserInterface $usuario) {
                return $usuario->getUserIdentifier() === $credentials['username'];
            },
            $credentials
        ));
    }

    /**
     * @param array $credentials
     *
     * @return UserInterface
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function createUser(array $credentials): UserInterface
    {
        $usuarioDTO = new UsuarioDTO();
        $usuarioDTO->setUsername($credentials['username']);
        $usuarioDTO->setEmail($credentials['email']);
        $usuarioDTO->setNome($credentials['nome']);
        $usuarioDTO->setEnabled(true);

        $strongPassword = $this->getUsuarioResource()->generateStrongPassword();
        $usuarioDTO->setPlainPassword($strongPassword);

        $transactionId = $this->transactionManager->begin();
        $usuario = $this->getUsuarioResource()->create($usuarioDTO, $transactionId);
        $this->transactionManager->commit($transactionId);

        return $usuario;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
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

        $token->setAttribute('trusted', 'x509');

        return $token;
    }
}
