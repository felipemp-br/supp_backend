<?php

declare(strict_types=1);
/**
 * /src/Command/Datalake/ProcessKafkaTopicCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\Datalake;

use Exception;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\Integracao\Datalake\KafkaTopicManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ProcessKafkaTopicCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(name: 'supp:administrativo:datalake:kafka', description: 'Processa topicos do kafka')]
class ProcessKafkaTopicCommand extends Command
{
    /**
     * ProcessKafkaTopicCommand constructor.
     *
     * @param KafkaTopicManager $kafkaTopicManager
     * @param SuppParameterBag  $parameterBag
     */
    public function __construct(
        private readonly KafkaTopicManager $kafkaTopicManager,
        private readonly SuppParameterBag $parameterBag,
    ) {
        parent::__construct();
        $this->addOption('onlyOnce', 'o', InputOption::VALUE_NONE, 'Processa uma única vez os tópicos');
    }

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->parameterBag->has('supp_core.administrativo_backend.datalake.kafka.enabled')
            || !$this->parameterBag->get('supp_core.administrativo_backend.datalake.kafka.enabled')) {
            throw new Exception('Serviço de tópicos Kafka desabilitado neste ambiente.');
        }

        if (!$this->parameterBag->has('supp_core.administrativo_backend.datalake.kafka.config')) {
            throw new Exception('Serviço de tópicos Kafka habilitado mas não configurado neste ambiente.');
        }

        while (true) {
            $this->kafkaTopicManager->processKafkaTopics();
            if ($input->getOption('onlyOnce')) {
                break;
            }
            sleep(2);
        }

        return Command::SUCCESS;
    }
}
