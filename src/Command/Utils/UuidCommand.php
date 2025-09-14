<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Command\Utils;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class UuidCommand.
 */
#[AsCommand(name: 'supp:uuid:generate', description: 'Gera uuid na entidade selecionada')]
class UuidCommand extends Command
{
    /**
     * IndexPopulateCommand constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
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
        $batch = (int) $input->getOption('batch');

        $startId = (int) $input->getOption('startId');

        if (!$startId) {
            $startId = 1;
        }

        $endId = (int) $input->getOption('endId');
        $entityName = $input->getOption('entity');

        if (!$endId) {
            $query = $this->entityManager->createQuery(
                'SELECT MAX(e.id) FROM '.$entityName.' e'
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
            $de = $startId + ($ciclo * $batch);
            $ate = $de + $batch - 1;
            if ($ate > $endId) {
                $ate = $endId;
            }
            $query = $this->entityManager->createQuery(
                'SELECT e FROM '.$entityName.' e WHERE e.uuid IS NULL AND e.id >= '.$de.' AND e.id <= '.$ate
            );
            $entities = $query->getResult();
            foreach ($entities as $entity) {
                $entity->setUuid();
                $this->entityManager->persist($entity);
            }
            ++$i;
            $this->entityManager->flush();
            $this->entityManager->clear();
            $progress->advance();
        }

        $progress->finish();

        $io->success((string) $i.' finished!');

        return Command::SUCCESS;
    }
}
