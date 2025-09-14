<?php

declare(strict_types=1);
/**
 * /src/Scheduler/CronjobManagerScheduler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Scheduler;

use DateTime;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Exception;
use Psr\Log\LoggerInterface;
use Redis;
use RedisException;
use SuppCore\AdministrativoBackend\Entity\Cronjob;
use SuppCore\AdministrativoBackend\Repository\CronjobRepository;
use SuppCore\AdministrativoBackend\Scheduler\Message\CronjobManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Messenger\Event\WorkerRunningEvent;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\Event\FailureEvent;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Throwable;

/**
 * Class CronjobManagerScheduler.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsSchedule('cronjob_manager')]
#[AsDoctrineListener('postPersist', 500, 'default')]
#[AsDoctrineListener('postUpdate', 500, 'default')]
#[AsDoctrineListener('postSoftDelete', 500, 'default')]
class CronjobManagerScheduler implements ScheduleProviderInterface
{
    private ?DateTime $workerStartTime = null;

    public function __construct(
        #[Autowire(service: 'scheduler.cache')]
        private readonly CacheInterface $cache,
        private readonly CronjobRepository $cronjobRepository,
        #[Autowire(service: 'lock.scheduler.factory')]
        private readonly LockFactory $lockFactory,
        private readonly Redis $redis,
        private readonly LoggerInterface $logger,
        private readonly EventDispatcherInterface $dispatcher,
    ) {
    }

    /**
     * @throws RedisException
     */
    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof Cronjob) {
            $this->redis->set('last_cronjob_update_time', (new DateTime('now'))->getTimestamp());
        }
    }

    /**
     * @throws RedisException
     */
    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof Cronjob) {
            $changeset = $args->getObjectManager()->getUnitOfWork()->getEntityChangeSet($entity);
            if (isset($changeset['ativo']) || isset($changeset['periodicidade']) || isset($changeset['apagadoEm'])) {
                $this->redis->set('last_cronjob_update_time', (new DateTime('now'))->getTimestamp());
            }
        }
    }

    /**
     * @throws RedisException
     */
    public function postSoftDelete(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof Cronjob) {
            $this->redis->set('last_cronjob_update_time', (new DateTime('now'))->getTimestamp());
        }
    }

    /**
     * @throws RedisException
     */
    #[AsEventListener]
    public function stopWorkerOnCronjobChange(WorkerRunningEvent $event): void
    {
        if (in_array('scheduler_cronjob_manager', $event->getWorker()->getMetadata()->getTransportNames())) {
            if (null === $this->workerStartTime) {
                $this->workerStartTime = new DateTime('now');
            } elseif ($this->redis->exists('last_cronjob_update_time')
                && (int) $this->redis->get('last_cronjob_update_time') > $this->workerStartTime->getTimestamp()) {
                $event->getWorker()->stop();
            }
        }
    }

    /**
     * @throws Throwable
     */
    public function getSchedule(): Schedule
    {
        try {
            $this->logger->info('scheduler_cronjob_manager: Executando Scheduler');

            $cronjobs = $this->cronjobRepository->findBy(['ativo' => true]);

            if (0 === count($cronjobs)) {
                throw new Exception('Nenhum cronjob ativo foi encontrado');
            }

            return (new Schedule($this->dispatcher))
                ->with(
                    ...array_map(function ($cronjob) {
                        return RecurringMessage::cron(
                            $cronjob->getPeriodicidade(),
                            new CronjobManager($cronjob->getId())
                        );
                    }, $cronjobs)
                )
                ->lock($this->lockFactory->createLock('cronjob_manager'))
                ->onFailure(function (FailureEvent $failureEvent) {
                    $this->logger->error('scheduler_cronjob_manager: '.$failureEvent->getMessage());
                });
        } catch (Throwable $exception) {
            $this->logger->error('scheduler_cronjob_manager: '.$exception->getMessage());
            throw $exception;
        }
    }
}
