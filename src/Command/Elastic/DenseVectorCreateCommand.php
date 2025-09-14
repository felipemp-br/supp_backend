<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Command\Elastic;

use Exception;
use SuppCore\AdministrativoBackend\Elastic\OpenSearchClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class DenseVectorCreateCommand.
 */
#[AsCommand(name: 'opensearch:densevector:create', description: 'Cria o indice NLP')]
class DenseVectorCreateCommand extends Command
{
    /**
     * @throws Exception
     */
    public function __construct(
        private readonly OpenSearchClient $openSearchClient
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $client = $this->openSearchClient->getClient();

        $response = $client->indices()->exists(['index' => 'nlp-index']);

        if ($client->indices()->exists(['index' => 'nlp-index'])) {
            $io->text(
                sprintf(
                    'Index `<comment>%s</comment>` already exists.',
                    'nlp-index'
                )
            );

            return 0;
        }

        $response = $client->indices()->create([
            'index' => 'nlp-index',
            'body' => [
                'settings' => [
                    'number_of_shards' => 2,
                    'number_of_replicas' => 1,
                    'index.knn' => true,
                    'knn.algo_param.ef_search' => 100,
                ],
                'mappings' => [
                    'properties' => [
                        'embedding' => [
                            'type' => 'knn_vector',
                            'dimension' => 1536,
                            'method' => [
                                'name' => 'hnsw',
                                'engine' => 'nmslib',
                                'parameters' => [
                                    'ef_construction' => 256,
                                    'm' => 48,
                                ],
                            ],
                        ],
                        'texto' => [
                            'type' => 'text',
                        ],
                        'md5' => [
                            'type' => 'text',
                        ],
                        'componente_digital_id' => [
                            'type' => 'integer',
                        ],
                        'documento_id' => [
                            'type' => 'integer',
                        ],
                        'repositorio_id' => [
                            'type' => 'integer',
                        ],
                        'modelo_id' => [
                            'type' => 'integer',
                        ],
                        'atualizado_em' => [
                            'type' => 'date',
                        ],
                    ],
                ],
            ],
        ]);

        if (isset($result['acknowledged']) && $result['acknowledged']) {
            $io->text(
                sprintf(
                    'Created `<comment>%s</comment>` mapping.',
                    'dense_vector'
                )
            );
        }

        return Command::SUCCESS;
    }
}
