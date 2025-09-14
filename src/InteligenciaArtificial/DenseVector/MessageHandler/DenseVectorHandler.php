<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\DenseVector\MessageHandler;

use Exception;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Elastic\OpenSearchClient;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Fields\Field\RepositorioField;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\ClientContext;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificalExceptionInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Helpers\DocumentoHelper;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Prompts\SimplePrompt;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Services\InteligenciaArtificialService;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\DenseVector\Message\DenseVectorMessage;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Utils\lib\simple_html_dom;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * DenseVectorHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class DenseVectorHandler
{
    /**
     * Constructor.
     *
     * @param DocumentoHelper               $documentoHelper
     * @param InteligenciaArtificialService $inteligenciaArtificialService
     * @param TransactionManager            $transactionManager
     * @param ComponenteDigitalResource     $componenteDigitalResource
     * @param OpenSearchClient              $openSearchClient
     * @param RepositorioField              $repositorioField
     * @param LoggerInterface               $logger
     */
    public function __construct(
        private readonly DocumentoHelper $documentoHelper,
        private readonly InteligenciaArtificialService $inteligenciaArtificialService,
        private readonly TransactionManager $transactionManager,
        private readonly ComponenteDigitalResource $componenteDigitalResource,
        private readonly OpenSearchClient $openSearchClient,
        private readonly RepositorioField $repositorioField,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @param DenseVectorMessage $message
     */
    public function __invoke(DenseVectorMessage $message): void
    {
        try {
            $client = $this->openSearchClient->getClient();

            $transactionId = $this->transactionManager->begin();

            /** @var ComponenteDigital $componenteDigital */
            $componenteDigital = $this->componenteDigitalResource->findOne($message->getId());

            if (!$componenteDigital) {
                return;
            }

            $conteudo = $this->componenteDigitalResource->download(
                $componenteDigital->getId(),
                $transactionId
            )->getConteudo();

            if (!$conteudo) {
                return;
            }

            // muitos modelos contem campos de teses, e precisa renderizar
            $parser = new simple_html_dom();
            $parser->load($conteudo);
            foreach ($parser->find('span') as $span) {
                if (isset($span->{'data-method'})) {
                    $options = explode(', ', $span->{'data-options'});
                    if ('repositorio' === $span->{'data-method'}) {
                        $span->innertext = $this->repositorioField->render($transactionId, [], $options);
                    }

                    if ('repositorio' === $this->getNomeCampo($span->innertext)) {
                        $span->innertext = str_replace(
                            '*'.$this->getNomeCampo($span->innertext).'*',
                            $this->repositorioField->render($transactionId, [], $options),
                            $span->innertext
                        );
                    }
                }
            }

            $conteudo = $parser->innertext;

            $bulkData = [];
            $bulkCounter = 0;
            $iaClient = $this->inteligenciaArtificialService->getClient();
            $iaClient->setClientContext(
                new ClientContext(
                    self::class,
                )
            );
            foreach ($this->documentoHelper->splitConteudoHTML($conteudo) as $chunk) {
                if (str_word_count($chunk) < 10) {
                    continue;
                }
                $md5 = md5($chunk);
                $doc = $client->search([
                    'index' => 'nlp-index',
                    '_source' => [
                        'embedding',
                    ],
                    'size' => 1,
                    'body' => [
                        'query' => [
                            'match' => [
                                'md5' => $md5,
                            ],
                        ],
                    ],
                ]);
                $embedding = $doc['hits']['hits'][0]['_source']['embedding'];
                if (!$embedding) {
                    try {
                        $embeddingResponse = $iaClient->getEmbeddings(
                            new SimplePrompt($chunk)
                        );
                        $embedding = $embeddingResponse->getResponse();
                    } catch (InteligenciaArtificalExceptionInterface $e) {
                        $this->logger->error(
                            sprintf(
                                'Falha ao recuperar embeddings para o dense verctor.',
                            ),
                            [
                                'componente_digital_id' => $componenteDigital->getId(),
                                'message' => $e->getMessage(),
                                'code' => $e->getCode(),
                            ]
                        );
                    }
                }
                if (!$embedding) {
                    continue;
                }
                $bulkData[] = [
                    'index' => [
                        '_index' => 'nlp-index',
                    ],
                ];
                $bulkData[] = [
                    'embedding' => $embedding,
                    'texto' => $chunk,
                    'md5' => $md5,
                    'componente_digital_id' => $componenteDigital->getId(),
                    'atualizado_em' => $componenteDigital->getAtualizadoEm()->format('Y-m-d\TH:i:s'),
                    'documento_id' => $componenteDigital->getDocumento()->getId(),
                    'modelo_id' => $componenteDigital->getDocumento()->getModelo()->getId(),
                ];

                ++$bulkCounter;

                if ($bulkCounter >= 50) {
                    // Enviando os dados ao Elasticsearch em bulks de 50 operações
                    $this->sendElasticRequest($bulkData);
                    // Reinicializando os arrays e contadores
                    $bulkData = [];
                    $bulkCounter = 0;
                }
            }

            if (!empty($bulkData)) {
                $this->sendElasticRequest($bulkData);
            }
        } catch (Throwable $t) {
            $this->logger->critical($t->getMessage().' - '.$t->getTraceAsString());
        }
    }

    /**
     * @param $bulkData
     *
     * @return void
     *
     * @throws Exception
     */
    private function sendElasticRequest($bulkData): void
    {
        $client = $this->openSearchClient->getClient();
        $client->bulk([
            'index' => 'nlp-index',
            'body' => $bulkData,
        ]);
    }

    /**
     * @param $text
     *
     * @return bool|string
     */
    private function getNomeCampo($text): bool|string
    {
        $strings = explode('*', $text);

        if (count($strings) > 1) {
            return trim($strings[1]);
        } else {
            return false;
        }
    }
}
