<?php
/**
 * @noinspection LongLine
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);
/**
 * src/Integracao/Dossie/DossieManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Integracao\Datalake;

use Exception;

/**
 * Class DossieManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class KafkaTopicManager
{
    /**
     * @var array|KafkaTopicProcessorInterface[]
     */
    private array $kafkaTopicProcessors = [];

    /**
     */
    public function __construct(private readonly KafkaConectorInterface $kafkaConector)
    {
    }


    /**
     * @param KafkaTopicProcessorInterface $kafkaTopicProcessor
     * @noinspection PhpUnused
     */
    public function addKafkaTopicProcessor(KafkaTopicProcessorInterface $kafkaTopicProcessor): void
    {
        $this->kafkaTopicProcessors[$kafkaTopicProcessor->getTopic()] [] = $kafkaTopicProcessor;
    }

    /**
     * @return KafkaTopicProcessorInterface[]
     */
    public function getKafkaTopicProcessors(): array
    {
        return $this->kafkaTopicProcessors;
    }

    /**
     * @throws Exception
     */
    public function processKafkaTopics() : void
    {
        $topics = $this->kafkaConector->consumeTopics(array_keys($this->kafkaTopicProcessors));
        foreach ($topics as $topic) {
            $processors = $this->kafkaTopicProcessors[$topic['topic']];
            foreach ($processors as $processor) {
                $processor->processTopicData($topic['data']);
            }
        }
    }
}
