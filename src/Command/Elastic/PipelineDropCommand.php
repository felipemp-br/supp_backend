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
 * Class PipelineDropCommand.
 */
#[AsCommand(name: 'ongr:es:pipeline:drop', description: 'Drops a ElasticSearch pipeline.')]
class PipelineDropCommand extends AbstractIndexServiceAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $index = $this->getIndex($input->getOption(parent::INDEX_OPTION));
        $client = $index->getClient();

        $params = [
            'id' => 'attachment_componente_digital_conteudo',
        ];

        $result = $client->ingest()->deletePipeline($params);

        if (isset($result['acknowledged']) && $result['acknowledged']) {
            $io->text(
                sprintf(
                    'Dropped `<comment>%s</comment>` pipeline.',
                    'attachment_componente_digital_conteudo'
                )
            );
        }

        $params = [
            'id' => 'attachment_documento_componentes_digitais_conteudo',
        ];

        $result = $client->ingest()->deletePipeline($params);

        if (isset($result['acknowledged']) && $result['acknowledged']) {
            $io->text(
                sprintf(
                    'Dropped `<comment>%s</comment>` pipeline.',
                    'attachment_documento_componentes_digitais_conteudo'
                )
            );
        }

        return Command::SUCCESS;
    }
}
