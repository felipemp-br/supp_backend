<?php

declare(strict_types=1);
/**
 * /src/Command/Datalake/ProcessTopicCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\Datalake;

use SuppCore\AdministrativoBackend\Integracao\Datalake\TopicManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ProcessTopicCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(name: 'supp:administrativo:datalake:topicos', description: 'Processa topicos')]
class ProcessTopicCommand extends Command
{
    /**
     * ProcessTopicCommand constructor.
     *
     * @param TopicManager $topicManager
     */
    public function __construct(
        private readonly TopicManager $topicManager
    ) {
        parent::__construct();
    }

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->topicManager->processTopics();

        return Command::SUCCESS;
    }
}
