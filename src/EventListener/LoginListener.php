<?php

declare(strict_types=1);
/**
 * /src/EventListener/LoginListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use SuppCore\AdministrativoBackend\Entity\UserInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Security\RolesServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

/**
 * Class LoginListener.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoginListener implements EventSubscriberInterface
{
    /**
     * @param TokenStorageInterface    $tokenStorage
     * @param RequestStack             $requestStack
     * @param RolesServiceInterface    $rolesService
     * @param JWTTokenManagerInterface $tokenManager
     */
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private RequestStack $requestStack,
        private RolesServiceInterface $rolesService,
        private JWTTokenManagerInterface $tokenManager
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }

    /**
     * Called after an AuthenticationException was thrown during authentication.
     *
     * @param LoginSuccessEvent $event
     *
     * @return void
     *
     * @throws JWTDecodeFailureException
     */
    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        /** @var Usuario $usuario */
        $usuario = $event->getAuthenticatedToken()->getUser();
        $roles = $this->rolesService->getContextualRoles($usuario);

        $token = $event->getAuthenticatedToken();
        if ($token->hasAttribute('trusted')) {
            $roles[] = 'ROLE_'.mb_strtoupper($token->getAttribute('trusted'));
            $usuario->setValidado(true);
        }

        $providerKey = $token?->getFirewallName();

        // comes from refresh token, maintain initial auth provider key
        if ($token instanceof JWTUserToken) {
            $providerKey = ($this->tokenManager->decode($token) ?? [])['authProviderKey'];
        }

        if ($usuario instanceof UserInterface) {
            $newToken = new UsernamePasswordToken(
                $usuario,
                $providerKey,
                $roles
            );
            if ($token->hasAttribute('trusted')) {
                $newToken->setAttribute('trusted', $token->getAttribute('trusted'));
            }

            if ($token->hasAttribute('apiKeyId')) {
                $newToken->setAttribute('apiKeyId', $token->getAttribute('apiKeyId'));
            }

            $this->tokenStorage->setToken($newToken);

            $this->requestStack->getSession()->set('_security_main', serialize($newToken));
        }
    }
}
