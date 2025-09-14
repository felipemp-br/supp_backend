<?php

declare(strict_types=1);
/**
 * /src/EventSubscriber/JWTDecodedSubscriber.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Helpers\LoggerAwareTrait;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;
use SuppCore\AdministrativoBackend\Utils\JSON;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class JWTDecodedSubscriber.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class JWTDecodedSubscriber implements EventSubscriberInterface
{
    // Traits
    use LoggerAwareTrait;

    /**
     * @param RequestStack          $requestStack
     * @param ParameterBagInterface $parameterBag
     * @param TokenStorageInterface $tokenStorage
     * @param UsuarioRepository     $usuarioRepository
     */
    public function __construct(private RequestStack $requestStack,
        private ParameterBagInterface $parameterBag,
        private TokenStorageInterface $tokenStorage,
        private UsuarioRepository $usuarioRepository)
    {
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
            Events::JWT_DECODED => 'onJWTDecoded',
        ];
    }

    /**
     * Subscriber method to make some custom JWT payload checks.
     *
     * This method is called when 'lexik_jwt_authentication.on_jwt_decoded' event is broadcast.
     *
     * @param JWTDecodedEvent $event
     */
    public function onJWTDecoded(JWTDecodedEvent $event): void
    {
        // No need to continue event is invalid
        if (!$event->isValid()) {
            return;
        }

        $request = $this->requestStack->getCurrentRequest();

        $this->checkPayload($event, $request);

        if (null === $request) {
            $this->logger->error('Request not available');

            $event->markAsInvalid();
        }
    }

    /**
     * Method to check payload data.
     *
     * @param JWTDecodedEvent $event
     * @param Request|null    $request
     */
    private function checkPayload(JWTDecodedEvent $event, ?Request $request = null): void
    {
        $payload = $event->getPayload();
        $composerData = JSON::decode((string) file_get_contents(__DIR__.'/../../composer.json'));

        if (!isset($payload['version']) ||
            $payload['version'] !== $composerData->version) {
            $event->markAsInvalid();
        }

        if (null === $request) {
            return;
        }

        if (!isset($payload['ip']) ||
            $payload['ip'] !== $request->getClientIp()) {
            // TODO $event->markAsInvalid();
        }

        if ((!isset($payload['passwordExpired']) || true === $payload['passwordExpired'])
            && !($request->get('ignore_expired_password')
                && true === $request->get('ignore_expired_password'))) {
            $event->markAsInvalid();
        }
    }
}
