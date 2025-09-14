<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Command\Elastic;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class IndexPopulateCommand.
 */
#[AsCommand(name: 'ongr:es:index:populate', description: 'Populate data to elasticsearch index.')]
class IndexPopulateCommand extends Command
{
    /**
     * IndexPopulateCommand constructor.
     *
     * @param MessageBusInterface    $bus
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        parent::configure();

        $this->addOption(
            'message',
            'm',
            InputOption::VALUE_REQUIRED,
            'Message that holds data.'
        )->addOption(
            'batch',
            'b',
            InputOption::VALUE_REQUIRED,
            'Set the batch size',
            1
        )->addOption(
            'startId',
            'sid',
            InputOption::VALUE_OPTIONAL,
            'Set the first id to import',
            1
        )->addOption(
            'endId',
            'eid',
            InputOption::VALUE_OPTIONAL,
            'Set the last id to import'
        )->addOption(
            'entity',
            'ent',
            InputOption::VALUE_OPTIONAL,
            'Entity to get max id'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Initialize options array
        $message = $input->getOption('message');
        $batch = (int) $input->getOption('batch');

        $startId = (int) $input->getOption('startId');

        if (!$startId) {
            $startId = 1;
        }

        $endId = (int) $input->getOption('endId');

        if (!$endId) {
            $entity = $input->getOption('entity');
            $query = $this->entityManager->createQuery(
                'SELECT MAX(e.id) FROM '.$entity.' e'
            );
            $endId = (int) $query->getArrayResult()[0][1];
        }

        $qtdIds = ($endId - $startId) + 1;
        $qtdBatches = (int) ceil($qtdIds / $batch);

        $progress = new ProgressBar($output, $qtdBatches);
        $progress->setRedrawFrequency(100);
        $progress->start();
        $i = 0;

        for ($ciclo = 0; $ciclo < $qtdBatches; ++$ciclo) {
            $messageInstance = new $message();
            $de = $startId + ($ciclo * $batch);
            $ate = $de + $batch - 1;
            if ($ate > $endId) {
                $ate = $endId;
            }
            $messageInstance->setStartId($de);
            $messageInstance->setEndId($ate);
            try {
                $this->bus->dispatch($messageInstance);
            } catch (\Throwable) {
                $io->error('Erro conexão com o rabbitmq');
                sleep(5);
            }
            ++$i;

            $progress->advance();
        }

        $progress->finish();

        $io->success((string) $i.' jobs created!');

        return Command::SUCCESS;
    }
}
