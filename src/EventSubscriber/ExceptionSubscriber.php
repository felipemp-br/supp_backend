<?php

declare(strict_types=1);
/**
 * /src/EventSubscriber/ExceptionSubscriber.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\EventSubscriber;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\ORMException;
use function get_class;
use function getenv;
use InvalidArgumentException;
use LogicException;
use SuppCore\AdministrativoBackend\Helpers\LoggerAwareTrait;
use SuppCore\AdministrativoBackend\Utils\JSON;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Throwable;
use UnexpectedValueException;

/**
 * Class ExceptionSubscriber.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ExceptionSubscriber implements EventSubscriberInterface
{
    // Traits
    use LoggerAwareTrait;

    private TokenStorageInterface $tokenStorage;

    private string $environment;

    /**
     * ExceptionSubscriber constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        $this->environment = (string) getenv('APP_ENV');
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
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    /**
     * Method to handle kernel exception.
     *
     * @param ExceptionEvent $event
     *
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     * @throws LogicException
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $maxLevel = 5;
        $currentLevel = 1;
        $exepctionList = [];
        do {
            $exepctionList[] = $exception;
            $exception = $exception->getPrevious();
            $currentLevel++;
        } while ($exception !== null && $currentLevel <= $maxLevel);
        $exepctionList = array_reverse($exepctionList);
        foreach ($exepctionList as $index => $exception) {
            $this->logger->critical(
                $this->formatExceptionMessage(
                    $exception,
                    $index+1
                )
            );
        }

        // Create new response
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode($this->getStatusCode($event->getThrowable()));
        $response->setContent(JSON::encode($this->getErrorMessage($event->getThrowable(), $response)));

        // Send the modified response object to the event
        $event->setResponse($response);
    }

    /**
     * @param Throwable $exception
     * @param int       $index
     *
     * @return string
     */
    private function formatExceptionMessage(
        Throwable $exception,
        int $index = 0
    ): string {
        return sprintf(
            "Exception(%s): %s in %s:%s \n Stack trace: \n %s",
            $index,
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );
    }

    /**
     * Method to get "proper" status code for exception response.
     *
     * @param Throwable $exception
     *
     * @return int
     */
    private function getStatusCode(Throwable $exception): int
    {
        // Get current token, and determine if request is made from logged in user or not
        $token = $this->tokenStorage->getToken();
        $isUser = !(null === $token);

        return $this->determineStatusCode($exception, $isUser);
    }

    /**
     * Method to get actual error message.
     *
     * @param Throwable $exception
     * @param Response  $response
     *
     * @return array
     */
    private function getErrorMessage(Throwable $exception, Response $response): array
    {
        // Set base of error message
        $error = [
            'message' => $this->getExceptionMessage($exception),
            'code' => $exception->getCode(),
            'status' => $response->getStatusCode(),
        ];

        // Attach more info to error response in dev environment
        if ('dev' === $this->environment) {
            $error += [
                'debug' => [
                    'exception' => get_class($exception),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTrace(),
                    'traceString' => $exception->getTraceAsString(),
                ],
            ];
        }

        return $error;
    }

    /**
     * Helper method to convert exception message for user. This method is used in 'production' environment so, that
     * application won't reveal any sensitive error data to users.
     *
     * @param Throwable $exception
     *
     * @return string
     */
    private function getExceptionMessage(Throwable $exception): string
    {
        return 'dev' === $this->environment
            ? $exception->getMessage()
            : $this->getMessageForProductionEnvironment($exception);
    }

    /**
     * @param Throwable $exception
     *
     * @return string
     */
    private function getMessageForProductionEnvironment(Throwable $exception): string
    {
        $message = $exception->getMessage();

        // Within AccessDeniedHttpException we need to hide actual real message from users
        if ($exception instanceof AccessDeniedHttpException || $exception instanceof AccessDeniedException) {
            $message = 'Access denied.';
        } elseif ($exception instanceof Exception || $exception instanceof ORMException) { // Database errors
            $message = 'Database error.';
        }

        return $message;
    }

    /**
     * @param Throwable $exception
     * @param bool      $isUser
     *
     * @return int
     */
    private function determineStatusCode(Throwable $exception, bool $isUser): int
    {
        // Default status code is always 500
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

        // HttpExceptionInterface is a special type of exception that holds status code and header details
        if ($exception instanceof AuthenticationException) {
            $statusCode = Response::HTTP_UNAUTHORIZED;
        } elseif ($exception instanceof AccessDeniedException) {
            $statusCode = $isUser ? Response::HTTP_FORBIDDEN : Response::HTTP_UNAUTHORIZED;
        } elseif ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() > 0) {
            $statusCode = $exception->getStatusCode();
        }

        return $statusCode;
    }
}
