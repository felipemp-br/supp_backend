<?php

declare(strict_types=1);
/**
 * /src/EventListener/ImmutableListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Doctrine\ORM\Immutable;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use RuntimeException;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;

use function get_class;

/**
 * Class ComponenteDigitalListener.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ImmutableListener
{
    /**
     * @param ImmutableService $immutableService
     * @param LoggerInterface $logger
     */
    public function __construct(private ImmutableService $immutableService, private LoggerInterface $logger)
    {
    }

    /**
     * @throws RuleException
     */
    public function preUpdate(PreUpdateEventArgs $event): void
    {
        $this->verifyImmutable($event);
    }

    /**
     * @throws RuleException
     */
    public function preSoftDelete(LifecycleEventArgs $event): void
    {
        $this->verifyImmutable($event);
    }

    /**
     * @throws RuleException
     */
    public function preRemove(LifecycleEventArgs $event): void
    {
        $this->verifyImmutable($event);
    }

    /**
     * @throws RuleException
     */
    protected function verifyImmutable(LifecycleEventArgs $event): void
    {
        $entity = $event->getObject();
        $immutableAnnotation = $this->immutableService->getImmutableAnnotation($entity);
        if ($immutableAnnotation && $entity instanceof EntityInterface) {
            if ($event instanceof PreUpdateEventArgs
                && array_key_exists($immutableAnnotation->fieldName, $event->getEntityChangeSet())) {
                $entity = clone $event->getEntity();
                $set = 'set'.ucfirst($immutableAnnotation->fieldName);

                if (!method_exists($entity, $set)) {
                    throw new RuntimeException('A entidade ['.get_class($entity)."] não possuí o método [$set()].");
                }

                $entity->$set($event->getOldValue($immutableAnnotation->fieldName));
            }

            if ($this->immutableService->isImmutable($entity)) {
                $entityClass = get_class($entity);
                $changeSet = $event instanceof PreUpdateEventArgs ? json_encode($event->getEntityChangeSet()) : 'N/A';

                $this->logger->critical(
                    sprintf(
                        "Exception: Não é permitido alterar registros marcados como imutáveis.\n"
                        . "Entidade: %s\n"
                        . "Alterações: %s\n"
                        . "Stack trace: %s\n",
                        $entityClass,
                        $changeSet,
                        debug_backtrace()
                    )
                );

                throw new RuleException('Não é permitido alterar registros marcados como imutáveis.');
            }
        }
    }
}
