<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Command\Elastic;

use ONGR\ElasticsearchBundle\Command\AbstractIndexServiceAwareCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class TemplateCreateCommand.
 */
#[AsCommand(name: 'ongr:es:template:create', description: 'Creates the ElasticSearch template.')]
class TemplateCreateCommand extends AbstractIndexServiceAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        parent::configure();

        $this->addOption(
            'if-not-exists',
            null,
            InputOption::VALUE_NONE,
            'Don\'t trigger an error, when the template already exists.'
        )->addOption(
            'dump',
            null,
            InputOption::VALUE_NONE,
            'Prints a json output of the index mapping.'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $index = $this->getIndex($input->getOption(parent::INDEX_OPTION));
        $indexName = $index->getIndexName();

        if ($input->getOption('dump')) {
            $io->note('Template mappings:');
            $io->text(
                json_encode(
                    $index->getIndexSettings()->getIndexMetadata(),
                    JSON_PRETTY_PRINT
                )
            );

            return Command::SUCCESS;
        }

        if ($input->getOption('if-not-exists')
            && $index->getClient()->indices()->existsTemplate(['name' => $indexName])) {
            $io->note(
                sprintf(
                    'Template `%s` already exists.',
                    $index->getIndexName()
                )
            );

            return Command::SUCCESS;
        }

        $params = array_merge([
            'name' => $indexName,
            'body' => $index->getIndexSettings()->getIndexMetadata(),
        ]);

        $params['body']['index_patterns'] = [$index->getIndexName().'-*'];
        $params['body']['aliases'] = [$index->getIndexName() => json_decode('{}')];

        $index->getClient()->indices()->putTemplate(array_filter($params));

        $io->text(
            sprintf(
                'Created `<comment>%s</comment>` template.',
                $index->getIndexName()
            )
        );

        return Command::SUCCESS;
    }
}
