<?php

declare(strict_types=1);
/**
 * /src/EventSubscriber/JWTCreatedSubscriber.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\EventSubscriber;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\Utils\JSON;
use function array_merge;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Helpers\LoggerAwareTrait;
use SuppCore\AdministrativoBackend\Security\RolesService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class JWTCreatedSubscriber.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class JWTCreatedSubscriber implements EventSubscriberInterface
{
    // Traits
    use LoggerAwareTrait;

    /**
     * @param RolesService $rolesService
     * @param RequestStack $requestStack
     * @param ParameterBagInterface $parameterBag
     * @param TokenStorageInterface $tokenStorage
     * @param UsuarioResource $usuarioResource
     */
    public function __construct(private RolesService $rolesService,
                                private RequestStack $requestStack,
                                private ParameterBagInterface $parameterBag,
                                private TokenStorageInterface $tokenStorage,
                                private UsuarioResource $usuarioResource) {
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @codeCoverageIgnore
     *
     * @return mixed[] The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Events::JWT_CREATED => 'onJWTCreated',
        ];
    }

    /**
     * Subscriber method to attach some custom data to current JWT payload.
     *
     * This method is called when 'lexik_jwt_authentication.on_jwt_created' event is broadcast.
     *
     * @param JWTCreatedEvent $event
     *
     * @throws Exception
     */
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        // Get current original payload
        $payload = $event->getData();

        // Update JWT version data
        $this->setVersion($payload);

        // Update JWT client ip data
        $this->setIP($payload);

        $this->setAuthProviderKey($payload);

        $user = $event->getUser();

        // Add necessary user data to payload
        if ($user instanceof Usuario) {
            $this->setUserData($payload, $user);
        }

        // And set new payload for JWT
        $event->setData($payload);
    }

    /**
     * Method to add all necessary user information to JWT payload.
     *
     * @param array $payload
     * @param Usuario $user
     * @return void
     * @throws Exception
     */
    private function setUserData(array &$payload, Usuario $user): void
    {
        // Set Roles service for Usuario Entity
        $loginData = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'nome' => $user->getNome(),
            'email' => $user->getEmail(),
            'roles' => $this->rolesService->getContextualRoles($user),
            'passwordExpired' => $this->tokenStorage->getToken()?->getFirewallName() === 'login'
                && $this->usuarioResource->isPasswordExpired($user)
        ];

        if ($this->tokenStorage->getToken()->hasAttribute('trusted')) {
            $loginData['trusted'] = $this->tokenStorage->getToken()->getAttribute('trusted');
        }

        // Merge login data to current payload
        $payload = array_merge($payload, $loginData);
    }

    /**
     * Method to set/modify JWT app version dynamically.
     *
     * @param mixed[] $payload
     *
     * @throws Exception
     */
    private function setVersion(array &$payload): void
    {
        $composerData = JSON::decode((string) file_get_contents(__DIR__.'/../../composer.json'));

        $payload['version'] = $composerData->version;
    }

    /**
     * Method to set/modify JWT app version dynamically.
     *
     * @param mixed[] $payload
     *
     * @throws Exception
     */
    private function setIP(array &$payload): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return;
        }

        $payload['ip'] = $request->getClientIp();
    }

    /**
     * Method to set/modify auth provider key with firewall name to identify auth mechanism
     * @param array $payload
     * @return void
     */
    private function setAuthProviderKey(array &$payload): void {
        $providerKey = $this->tokenStorage->getToken()?->getFirewallName();
        $payload['authProviderKey'] = $providerKey;
    }
}
