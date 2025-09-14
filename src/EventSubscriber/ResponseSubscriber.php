<?php

declare(strict_types=1);
/**
 * /src/EventSubscriber/ResponseSubscriber.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\EventSubscriber;

use Exception;
use SuppCore\AdministrativoBackend\EventListener\RateListenerRequest;
use SuppCore\AdministrativoBackend\Helpers\LoggerAwareTrait;
use function file_get_contents;
use LogicException;
use SuppCore\AdministrativoBackend\Utils\JSON;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ResponseSubscriber.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ResponseSubscriber implements EventSubscriberInterface
{

    use LoggerAwareTrait;

    private ?Request $request = null;

    /**
     * @param RateListenerRequest $rateListenerRequest
     */
    public function __construct(
        private readonly RateListenerRequest $rateListenerRequest,
    ) {
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
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => [
                'onKernelResponse',
                10,
            ],
        ];
    }

    /**
     * Subscriber method to log every response.
     *
     * @param ResponseEvent $event
     *
     * @throws Exception
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        // Attach new header
        $response->headers->add(['X-API-VERSION' => $this->getApiVersion()]);
        if ($this->rateListenerRequest->getApiRateLimitStatus()) {
            $response->headers->add($this->rateListenerRequest->getApiRateLimitStatus());
        }
    }

    /**
     * Method to get current version from composer.json file.
     *
     * @return string
     *
     * @throws LogicException
     */
    private function getApiVersion(): string
    {
        return JSON::decode((string) file_get_contents(__DIR__.'/../../composer.json'))->version ?? 'unknown';
    }
}
