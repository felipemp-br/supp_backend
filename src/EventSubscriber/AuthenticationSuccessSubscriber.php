<?php

declare(strict_types=1);
/**
 * /src/EventSubscriber/AuthenticationSuccessSubscriber.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\EventSubscriber;

use Doctrine\ORM\NonUniqueResultException;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser as JWTParser;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class AuthenticationSuccessSubscriber.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AuthenticationSuccessSubscriber implements EventSubscriberInterface
{
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
            Events::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess',
        ];
    }

    /**
     * Method to log user successfully login to database.
     *
     * This method is called when 'lexik_jwt_authentication.on_authentication_success' event is broadcast.
     *
     * @param AuthenticationSuccessEvent $event
     *
     * @throws NonUniqueResultException
     */
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $token = $data['token'];
        $jws = (new JWTParser(new JoseEncoder()))->parse((string) $token);
        $payload = $jws->claims()->all();
        $data['exp'] = $payload['exp']->getTimestamp();
        $data['timestamp'] = $payload['iat']->getTimestamp();
        $data['version'] = $payload['version'];
        $data['passwordExpired'] = $payload['passwordExpired'];
        $event->setData($data);
    }
}
