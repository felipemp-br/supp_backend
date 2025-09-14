<?php
declare(strict_types=1);
/**
 * src/Integracao/Dossie/TopicManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Integracao\Datalake;

use Carbon\Carbon;
use Exception;
use SuppCore\AdministrativoBackend\Cronjob\CronjobExpressionServiceInterface;

/**
 * Class TopicManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TopicManager
{
    /**
     * @var array|ConsumerInterface[]
     */
    private array $consumerInterfaces = [];

    /**
     * @var array|ProducerInterface[]
     */
    private array $producerInterfaces = [];

    /**
     * @param CronjobExpressionServiceInterface $cronJobService
     */
    public function __construct(private readonly CronjobExpressionServiceInterface $cronJobService)
    {
    }


    /**
     * @param ProducerInterface $topicProducer
     * @return void
     */
    public function addTopicProducer(ProducerInterface $topicProducer): void
    {
        $this->producerInterfaces[$topicProducer->getTopic()][] = $topicProducer;
    }

    /**
     * @param ConsumerInterface $topicConsumer
     * @return void
     */
    public function addTopicConsumer(ConsumerInterface $topicConsumer): void
    {
        $this->consumerInterfaces[$topicConsumer->getTopico()][] = $topicConsumer;
    }

    /**
     * @return array|ConsumerInterface[]
     */
    public function getConsumerInterfaces(): array
    {
        return $this->consumerInterfaces;
    }

    /**
     * @return array|ProducerInterface[]
     */
    public function getProducerInterfaces(): array
    {
        return $this->producerInterfaces;
    }

    public function processTopics() : void
    {
        $dataExecucao = new Carbon();
        foreach ($this->getProducerInterfaces() as $topic => $producers) {
            foreach($producers as $producer) {
                if ($this->cronJobService->isDue($producer->getPeriodicidade(), $dataExecucao)) {
                    $consumers = $this->getConsumerInterfaces()[$topic] ?? [];
                    $producer->run($consumers);
                }
            }
        }
    }
}
