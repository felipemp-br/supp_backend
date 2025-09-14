<?php

declare(strict_types=1);
/**
 * /src/Rest/Controller.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Monolog\Processor;

use Monolog\LogRecord;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserProcessor.
 */
#[AutoconfigureTag(name: 'monolog.processor')]
#[AsEventListener(event: 'kernel.request', method: 'onKernelRequest')]
class UserProcessor
{
    private ?UserInterface $user = null;

    /**
     * UserProcessor constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    /**
     * @param LogRecord $record
     *
     * @return array
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        if (null !== $this->user) {
            $record->extra['user']['username'] = $this->user->getUsername();
            $record->extra['user']['nome'] = $this->user->getNome();
        } else {
            $record->extra['user']['username'] = 'anonymous';
            $record->extra['user']['nome'] = 'anonymous';
        }

        return $record;
    }

    /**
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        $this->user = $user;
    }
}
