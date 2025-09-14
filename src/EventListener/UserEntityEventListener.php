<?php

declare(strict_types=1);
/**
 * /src/EventListener/UserEntityEventListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\EventListener;

use function base64_encode;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use LengthException;
use function random_bytes;
use function rtrim;
use RuntimeException;
use function str_replace;
use function strlen;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserEntityEventListener.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class UserEntityEventListener
{
    /**
     * Used password encoder.
     */
    private UserPasswordHasherInterface $userPasswordHasher;

    /**
     * Constructor.
     *
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * Doctrine lifecycle event for 'prePersist' event.
     *
     * @param LifecycleEventArgs $event
     *
     * @throws LengthException
     * @throws RuntimeException
     */
    public function prePersist(LifecycleEventArgs $event): void
    {
        $this->process($event);
    }

    /**
     * Doctrine lifecycle event for 'preUpdate' event.
     *
     * @param LifecycleEventArgs $event
     *
     * @throws LengthException
     * @throws RuntimeException
     */
    public function preUpdate(LifecycleEventArgs $event): void
    {
        $this->process($event);
    }

    /**
     * @param LifecycleEventArgs $event
     *
     * @throws RuntimeException
     * @throws LengthException
     */
    private function process(LifecycleEventArgs $event): void
    {
        // Get user entity object
        $user = $event->getObject();

        // Valid user so lets change password
        if ($user instanceof Usuario) {
            $this->changePassword($user);
        }
    }

    /**
     * Method to change user password whenever it's needed.
     *
     * @param Usuario $usuario
     *
     * @throws LengthException
     * @throws RuntimeException
     */
    private function changePassword(Usuario $usuario): void
    {
        // Get plain password from user entity
        $plainPassword = $usuario->getPlainPassword();

        // Yeah, we have new plain password set, so we need to encode it
        if (!empty($plainPassword) && strlen($plainPassword) < 30) {
            if (strlen($plainPassword) < 8 || strlen($plainPassword) > 16) {
                // throw new LengthException('Wrong size password');
            }

            $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
            $usuario->setSalt($salt);

            // Password hash callback
            $callback = fn ($plainPassword): string => $this->userPasswordHasher->hashPassword(
                $usuario,
                $plainPassword
            );

            // Set new password and encode it with user encoder
            $usuario->setPassword($callback, $plainPassword);
            $usuario->setEncoder('sodium');

            // And clean up plain password from entity
            $usuario->eraseCredentials();
        }
        if (!empty($plainPassword) && strlen($plainPassword) > 30) {
            $callback = fn ($plainPassword): string => $plainPassword;
            $usuario->setPassword($callback, $plainPassword);
        }
    }
}
