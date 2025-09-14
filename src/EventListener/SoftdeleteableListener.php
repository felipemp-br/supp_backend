<?php

declare(strict_types=1);
/**
 * /src/EventListener/SoftdeleteableListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\EventListener;

use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class SoftdeleteableListener.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SoftdeleteableListener
{
    /**
     * SoftdeleteableListener constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param LifecycleEventArgs $lifeCycleEvent
     *
     * @throws AccessDeniedException
     * @throws ORMInvalidArgumentException
     */
    public function preSoftDelete(LifecycleEventArgs $lifeCycleEvent): void
    {
        $token = $this->tokenStorage->getToken();
        $object = $lifeCycleEvent->getObject();
        $uow = $lifeCycleEvent->getObjectManager()->getUnitOfWork();
        $meta = $lifeCycleEvent->getObjectManager()->getClassMetadata(get_class($object));

        if (!method_exists($object, 'setApagadoPor')) {
            $this->logger->info(
                "Objeto deletado: " . get_class($object) .
                " " . $object->getId() . " " .
                " não possui o campo setApagadoPor"
            );
            return;
        }

        $updateColumns = [];
        if (null !== $token) {
            $reflProp = $meta->getReflectionProperty('apagadoPor');
            $oldValue = $reflProp->getValue($object);

            $newValue = $token->getUser();
            $reflProp->setValue($object, $newValue);

            $lifeCycleEvent->getObjectManager()->persist($object);
            $this->logger->info(
                "Objeto deletado: " . get_class($object) .
                " " . $object->getId() . " " .
                " pelo usuário: " . $token->getUser()->getUsername()
            );
            $uow->propertyChanged($object, 'apagadoPor', $oldValue, $newValue);
            $updateColumns = array_merge(
                $updateColumns,
                ['apagadoPor' => [$oldValue, $newValue]]
            );
        } else {
            $this->logger->info(
                "Objeto deletado: " . get_class($object) .
                " " . $object->getId() . " " .
                " não possui usuário informado"
            );
        }

        if (count($updateColumns)) {
            $uow->scheduleExtraUpdate($object, $updateColumns);
        }
    }
}
