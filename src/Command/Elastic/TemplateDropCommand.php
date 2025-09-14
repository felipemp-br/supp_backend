<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Command\Elastic;

use ONGR\ElasticsearchBundle\Command\AbstractIndexServiceAwareCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class TemplateDropCommand.
 */
#[AsCommand(name: 'ongr:es:template:drop', description: 'Drop the ElasticSearch template.')]
class TemplateDropCommand extends AbstractIndexServiceAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $index = $this->getIndex($input->getOption(parent::INDEX_OPTION));
        $indexName = $index->getIndexName();

        $index->getClient()->indices()->deleteTemplate(['name' => $indexName]);

        $io->text(
            sprintf(
                'Dropped `<comment>%s</comment>` template.',
                $index->getIndexName()
            )
        );

        return Command::SUCCESS;
    }
}
