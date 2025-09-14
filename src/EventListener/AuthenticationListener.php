<?php

declare(strict_types=1);
/**
 * /src/EventListener/AuthenticationListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\EventListener;

use Redis;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

/**
 * Class AuthenticationListener.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AuthenticationListener implements EventSubscriberInterface
{
    private Redis $redisClient;

    protected RequestStack $requestStack;

    /**
     * AuthenticationListener constructor.
     *
     * @param Redis        $redisClient
     * @param RequestStack $requestStack
     */
    public function __construct(
        Redis $redisClient,
        RequestStack $requestStack
    ) {
        $this->redisClient = $redisClient;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AuthenticationSuccessEvent::class => 'onAuthenticationSuccess',
            LoginFailureEvent::class => 'onLoginFailure',
        ];
    }

    /**
     * Called when authentication is nearing success.
     *
     * @param AuthenticationSuccessEvent $event
     *
     * @return void|null
     */
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if ($currentRequest) {
            $clientIp = $currentRequest->getClientIp();
            $token = $event->getAuthenticationToken();

            if ($token instanceof UsernamePasswordToken) {
                $username = $token->getUserIdentifier();
            } elseif ($token instanceof PostAuthenticationToken) {
                $username = $token->getUser()->getUserIdentifier();
                if ($token->hasAttribute('username')) {
                    $username = $token->getAttribute('username');
                }
            } else {
                return null;
            }

            $clientId = 'auth_'.$clientIp.':'.$username;

            $this->isBLocked($clientId);

            $this->redisClient->hset('auth_tentativas', $clientId, 0);
        }
    }

    /**
     * Called after an AuthenticationException was thrown during authentication.
     *
     * @param LoginFailureEvent $event
     *
     * @return void|null
     */
    public function onLoginFailure(LoginFailureEvent $event)
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if ($currentRequest && 'login' === $event->getFirewallName()) {
            $clientIp = $currentRequest->getClientIp();

            $username = $currentRequest->get('username');

            $clientId = 'auth_'.$clientIp.':'.$username;

            $this->isBLocked($clientId);

            $tentativas = $this->redisClient->hget('auth_tentativas', $clientId);

            if ($tentativas < 4) {
                $this->redisClient->hincrby('auth_tentativas', $clientId, 1);

                $message = 'Dados não conferem, você possui mais ';
                $message .= (4 - $tentativas).' tentativas antes de ser bloqueado!';

                throw new BadRequestHttpException($message, null, 401);
            } else {
                // blacklist por 10 minutos
                $this->redisClient->set($clientId, 'auth_blocked');
                $this->redisClient->expire($clientId, 600);
                $this->redisClient->hset('auth_tentativas', $clientId, 0);
            }
        }
    }

    /**
     * @param string $clientId
     */
    private function isBLocked(string $clientId): void
    {
        // está na blacklist?
        if ($this->redisClient->exists($clientId)) {
            $message = <<<MSG
O usuário foi bloqueado por 10 (dez) minutos, 
pois a senha foi digitada incorretamente por 5 (cinco) vezes!
MSG;
            throw new BadRequestHttpException($message, null, 403);
        }
    }
}
