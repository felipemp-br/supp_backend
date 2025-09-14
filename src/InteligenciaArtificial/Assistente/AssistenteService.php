<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Assistente;

use SuppCore\AdministrativoBackend\Elastic\OpenSearchClient;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Assistente\Models\AssistentePrompt;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\ClientContext;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Responses\CompletionsChunkResponse;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Responses\CompletionsResponse;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\ClientRateLimitExeededException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\EmptyDocumentContentException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificalException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificalExceptionInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificialRequestErrorException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\MaximumInputTokensExceededException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\TokenBalanceInsufficientException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnauthorizedDocumentAccessException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnsupportedComponenteDigitalMimeTypeException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnsupportedUriException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Helpers\DocumentoHelper;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Prompts\SimplePrompt;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Services\InteligenciaArtificialService;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

/**
 * AssistenteService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AssistenteService
{
    /**
     * Constructor.
     *
     * @param InteligenciaArtificialService $inteligenciaArtificialService
     * @param OpenSearchClient              $openSearchClient
     * @param HubInterface                  $hub
     * @param DocumentoHelper               $documentoHelper
     * @param SuppParameterBag              $suppParameterBag
     */
    public function __construct(
        protected readonly InteligenciaArtificialService $inteligenciaArtificialService,
        protected readonly OpenSearchClient $openSearchClient,
        protected readonly HubInterface $hub,
        protected readonly DocumentoHelper $documentoHelper,
        protected readonly SuppParameterBag $suppParameterBag,
    ) {
    }

    /**
     * @param AssistentePrompt $assistentePrompt
     * @param string           $messageUuid
     * @param string           $channel
     *
     * @return CompletionsResponse
     *
     * @throws ClientRateLimitExeededException
     * @throws EmptyDocumentContentException
     * @throws InteligenciaArtificalException
     * @throws InteligenciaArtificialRequestErrorException
     * @throws MaximumInputTokensExceededException
     * @throws TokenBalanceInsufficientException
     * @throws UnauthorizedDocumentAccessException
     * @throws UnsupportedComponenteDigitalMimeTypeException
     * @throws UnsupportedUriException
     */
    public function streamedCall(
        AssistentePrompt $assistentePrompt,
        string $messageUuid,
        string $channel
    ): CompletionsResponse {
        $client = $this->inteligenciaArtificialService->getClient();
        $persona = $assistentePrompt->getPersona();
        $client->setPersona($this->getPersona());
        if ($assistentePrompt->getRag() && $assistentePrompt->getUserPrompt()) {
            if ($this->suppParameterBag->has('supp_core.administrativo_backend.ia.assistente')) {
                $assistenteConfig = $this->suppParameterBag->get('supp_core.administrativo_backend.ia.assistente');
                if (isset($assistenteConfig['rag']) && true === $assistenteConfig['rag']) {
                    $embeddings = $this->searchEmbeddings($assistentePrompt);
                    if ($embeddings) {
                        $persona = $persona ? $persona.'\n ' : '';
                        // @codingStandardsIgnoreStart
                        $persona .= <<<EOT
                            Apenas se cabível e fizer sentido, para fins de contexto, leve em consideração ao produzir as respostas as seguintes informações, delimitadas por '###', sem mencionar essa instrução: 
                            ###
                            {$embeddings}
                            ###
                        EOT;
                        // @codingStandardsIgnoreEnd
                    }
                }
            }
        }
        $action = $assistentePrompt->getActionPrompt() ?? 'Redija o texto de acordo com a pergunta abaixo:';

        $prompt = new SimplePrompt(
            <<<EOT
                {$action}
                {$assistentePrompt->getUserPrompt()}
            EOT,
            $assistentePrompt->getDocumento(),
            $persona
        );
        $client->setClientContext(
            new ClientContext(
                self::class,
                $assistentePrompt->getContext()
            )
        );

        $content = '';
        return $client->getStreamedCompletions(
            $prompt,
            function (CompletionsChunkResponse $chunk) use ($messageUuid, $channel, &$content) {
                $content .= $chunk->getContent();
                if ($chunk->isLast()) {
                    $this->hub->publish(
                        new Update(
                            ['assistente_ia/'.$channel],
                            json_encode(
                                [
                                    'assistente_ia' => [
                                        'uuid' => $messageUuid,
                                        'message' => $content,
                                        'final' => true,
                                    ],
                                ]
                            )
                        )
                    );

                    return;
                }
                if ($chunk->getContent()) {
                    $this->hub->publish(
                        new Update(
                            ['assistente_ia/'.$channel],
                            json_encode(
                                [
                                    'assistente_ia' => [
                                        'uuid' => $messageUuid,
                                        'message' => $content,
                                        'final' => false,
                                    ],
                                ]
                            )
                        )
                    );
                }
            }
        );
    }

    /**
     *  Verifica e retorna os embeddings do texto do prompt.
     *
     * @param AssistentePrompt $assistentePrompt
     * @param float            $baseScore
     *
     * @return string|null
     *
     * @throws InteligenciaArtificalExceptionInterface
     * @throws UnsupportedUriException
     */
    protected function searchEmbeddings(AssistentePrompt $assistentePrompt, float $baseScore = 0.80): ?string
    {
        $embeddings = $this->inteligenciaArtificialService
            ->getClient()
            ->setClientContext(
                new ClientContext(
                    self::class,
                    $assistentePrompt->getContext()
                )
            )
            ->getEmbeddings(new SimplePrompt($assistentePrompt->getUserPrompt()));
        if (!empty($embeddings->getResponse())) {
            $openSearchClient = $this->openSearchClient->getClient();
            $response = $openSearchClient->search([
                'index' => 'nlp-index',
                '_source' => [
                    'texto',
                ],
                'size' => 1,
                'body' => [
                    'query' => [
                        'knn' => [
                            'embedding' => [
                                'vector' => $embeddings->getResponse(),
                                'k' => 5,
                            ],
                        ],
                    ],
                ],
            ]);
            if (isset($response['hits']['hits'])) {
                $data = array_map(
                    fn (array $hit) => $hit['_source']['texto'],
                    array_filter(
                        $response['hits']['hits'],
                        fn ($hit) => $hit['_score'] > $baseScore
                    )
                );
                if (!empty($data)) {
                    return implode(' ', $data);
                }
            }
        }

        return null;
    }

    /**
     * @return string|null
     */
    protected function getPersona(): ?string
    {
        return <<<EOT
            Se apresente como o Assistente de IA da Advocacia-Geral da União,
            com grandes conhecimentos jurídicos, e conhecimento na elaboração de documentos oficiais,
            como despachos, ofícios, notas técnicas, pareceres, etc, respondendo uma pergunta.
            Use apenas a língua portuguesa.
            Limite sua resposta ao universo do direito, legislação, atos normativos e redação oficial.
            Se você não souber a resposta, não invente, simplesmente diga que pode ajudar.
            Responda sempre no formato HTML, com as tags 'p' para parágrafo, 'h1' para título,
            'h2' para subtítulo, 'b' para negrito, 'i' para itálico.
            Não use outras tags HTML diferentes de 'p', 'h1' e 'h2', 'b', 'i'.
            Cite legislação e jurisprudência apenas se ela estiver presente no trecho delimitado por '###',
            sem mencionar essa instrução.
            Não referencie fatos, pessoas, organizações ou situações específicas.
            Use apenas argumentos favoráveis ao interesse público e ao Estado.
            Não retorna de forma alguma marcações markdown ou code block como ```html, ```json ou similares.
        EOT;
    }
}
