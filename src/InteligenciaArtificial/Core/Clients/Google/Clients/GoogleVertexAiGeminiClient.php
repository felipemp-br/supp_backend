<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Clients;

use Google\Auth\Credentials\ServiceAccountCredentials;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\ClientContext;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\CompletionsContext;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\CompletionsContextItem;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Configs\GoogleVertexAiClientConfig;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Enums\FinishReasonEnum;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Enums\HarmBlockThresholdEnum;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Enums\HarmCategoryEnum;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Exceptions\GeminiReasonException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\InteligenciaArtificialClientInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Responses\CompletionsChunkResponse;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Responses\CompletionsResponse;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Responses\EmbeddingsResponse;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\ClientAuthenticationException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\ClientRateLimitExeededException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\EmptyDocumentContentException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificalException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificalExceptionInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificialRequestErrorException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\MaximumInputTokensExceededException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\TokenBalanceInsufficientException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnauthorizedDocumentAccessException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnsupportedComponenteDigitalMimeTypeException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Helpers\DocumentoHelper;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Prompts\PromptInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Services\InteligenciaArtificialLogService;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

/**
 * GoogleVertexAiGeminiClient.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GoogleVertexAiGeminiClient implements InteligenciaArtificialClientInterface
{
    private array $googleAuthToken = [];
    private int $googleAuthTokenExpirationTime = 0;
    private ?string $persona = null;
    private ?ClientContext $clientContext = null;

    /**
     * @var callable|null
     */
    private mixed $promptGetTextCallback = null;

    private const DEFAULT_SAFETY_SETTINGS = [
        [
            'category' => HarmCategoryEnum::HARM_CATEGORY_HARASSMENT,
            'threshold' => HarmBlockThresholdEnum::BLOCK_NONE,
        ],
        [
            'category' => HarmCategoryEnum::HARM_CATEGORY_HATE_SPEECH,
            'threshold' => HarmBlockThresholdEnum::BLOCK_NONE,
        ],
        [
            'category' => HarmCategoryEnum::HARM_CATEGORY_SEXUALLY_EXPLICIT,
            'threshold' => HarmBlockThresholdEnum::BLOCK_NONE,
        ],
        [
            'category' => HarmCategoryEnum::HARM_CATEGORY_DANGEROUS_CONTENT,
            'threshold' => HarmBlockThresholdEnum::BLOCK_NONE,
        ],
    ];

    /**
     * Constructor.
     *
     * @param string                           $driver
     * @param GoogleVertexAiClientConfig       $clientConfig
     * @param DocumentoHelper                  $documentoHelper
     * @param InteligenciaArtificialLogService $logService
     */
    public function __construct(
        private readonly string $driver,
        private readonly GoogleVertexAiClientConfig $clientConfig,
        private readonly DocumentoHelper $documentoHelper,
        private readonly InteligenciaArtificialLogService $logService,
    ) {
    }

    /**
     * Retorna o nome do driver.
     *
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * Retorna o nome do modelo default.
     *
     * @return string
     */
    public function getCompletionsModel(): string
    {
        return $this->clientConfig->getCompletionsConfig()->getModel();
    }

    /**
     * Retorna o nome do modelo default.
     *
     * @return string
     */
    public function getEmbeddinsModel(): string
    {
        return $this->clientConfig->getEmbeddingsConfig()->getModel();
    }

    /**
     * @param ClientContext|null $clientContext
     *
     * @return self
     */
    public function setClientContext(?ClientContext $clientContext = null): self
    {
        $this->clientContext = $clientContext;

        return $this;
    }

    /**
     * Returns client.
     *
     * @return HttpClientInterface
     *
     * @throws InteligenciaArtificalExceptionInterface
     */
    private function getClient(): HttpClientInterface
    {
        $currentTime = time();
        if ($currentTime >= $this->googleAuthTokenExpirationTime) {
            try {
                $googleAuthClient = new ServiceAccountCredentials(
                    'https://www.googleapis.com/auth/cloud-platform',
                    $this->clientConfig->getCredentials()
                );
                $this->googleAuthToken = $googleAuthClient->fetchAuthToken();
            } catch (Throwable $e) {
                throw new ClientAuthenticationException($this->getDriver(), $e);
            }
            $this->googleAuthTokenExpirationTime = $currentTime + $this->googleAuthToken['expires_in'] - 600;
        }

        return HttpClient::create(
            [
                'base_uri' => sprintf(
                    'https://%s',
                    $this->clientConfig->getServiceEndpoint()
                ),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => sprintf(
                        'Bearer %s',
                        $this->googleAuthToken['access_token']
                    ),
                ],
            ]
        );
    }

    /**
     * Define a persona a ser executado para esta instancia do client na execução de todos os prompts.
     *
     * @param string|null $persona
     *
     * @return self
     */
    public function setPersona(?string $persona): self
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Define o callback de execução que será chamado em todo getText de um prompt.
     *
     * @param callable $callback
     *
     * @return self
     */
    public function setPromptGetTextCallback(callable $callback): InteligenciaArtificialClientInterface
    {
        $this->promptGetTextCallback = $callback;

        return $this;
    }

    /**
     * Limpa o callback de getText do prompt.
     *
     * @return self
     */
    public function cleanPromptGetTextCallback(): InteligenciaArtificialClientInterface
    {
        $this->promptGetTextCallback = null;

        return $this;
    }

    /**
     * Retorna os completions.
     *
     * @param string|null             $model
     * @param int|null                $maxTokens
     * @param int|null                $temperature
     * @param CompletionsContext|null $context
     *
     * @throws ClientRateLimitExeededException
     * @throws EmptyDocumentContentException
     * @throws InteligenciaArtificalException
     * @throws InteligenciaArtificialRequestErrorException
     * @throws MaximumInputTokensExceededException
     * @throws TokenBalanceInsufficientException
     * @throws UnauthorizedDocumentAccessException
     * @throws UnsupportedComponenteDigitalMimeTypeException
     * @throws InteligenciaArtificalExceptionInterface
     */
    public function getCompletions(
        PromptInterface $prompt,
        ?string $model = null,
        ?int $maxTokens = null,
        ?int $temperature = null,
        ?CompletionsContext $context = null
    ): CompletionsResponse {
        $completionsConfig = $this->clientConfig->getCompletionsConfig();
        $model ??= $completionsConfig->getModel();
        $maxTokens ??= $completionsConfig->getMaxTokens();
        $temperature ??= $completionsConfig->getTemperature();
        $callback = $this->promptGetTextCallback;
        if (!$context) {
            $context = new CompletionsContext();
            if ($this->persona) {
                $context->addContext(
                    new CompletionsContextItem('user', $this->persona)
                );
                $context->addContext(
                    new CompletionsContextItem('model', 'ok')
                );
            }
        }
        if ($prompt->getPersona()) {
            $context->addContext(
                new CompletionsContextItem(
                    'user',
                    $prompt->getPersona()
                )
            );
            $context->addContext(
                new CompletionsContextItem('model', 'ok')
            );
        }
        $context->addContext(
            new CompletionsContextItem(
                'user',
                $callback ? $callback($prompt) : $this->promptToText($prompt)
            )
        );
        try {
            $response = $this->getClient()->request(
                'POST',
                sprintf(
                    '/v1/projects/%s/locations/%s/publishers/%s/models/%s:generateContent',
                    $this->clientConfig->getProject(),
                    $this->clientConfig->getLocation(),
                    $completionsConfig->getPublisher(),
                    $model,
                ),
                [
                    'body' => json_encode([
                        'contents' => array_map(
                            fn (CompletionsContextItem $context) => [
                                'role' => $context->getRole(),
                                'parts' => [
                                    [
                                        'text' => $context->getContent(),
                                    ],
                                ],
                            ],
                            $context->getContext()
                        ),
                        'generationConfig' => [
                            'response_mime_type' => 'application/json',
                            ...($temperature ? ['temperature' => $temperature] : []),
                            ...($maxTokens ? ['maxOutputTokens' => $maxTokens] : []),
                        ],
                        'safetySettings' => self::DEFAULT_SAFETY_SETTINGS,
                    ]),
                ]
            );
            $responseData = $response->toArray();
            $reason = FinishReasonEnum::tryFrom($responseData['candidates'][0]['finishReason']);
            if (FinishReasonEnum::STOP === $reason) {
                $context->addContext(
                    new CompletionsContextItem(
                        $responseData['candidates'][0]['content']['role'],
                        $responseData['candidates'][0]['content']['parts'][0]['text']
                    )
                );

                $completionsResponse = new CompletionsResponse(
                    $responseData['candidates'][0]['content']['parts'][0]['text'],
                    $responseData['usageMetadata']['promptTokenCount'],
                    $responseData['usageMetadata']['candidatesTokenCount'],
                    $responseData['usageMetadata']['totalTokenCount'],
                    $this->getDriver(),
                    $model,
                    $context
                );

                return $this->logService->logCompletions(
                    $completionsResponse,
                    context: $this->clientContext
                );
            }
            switch ($reason) {
                case FinishReasonEnum::MAX_TOKENS:
                    throw new MaximumInputTokensExceededException(
                        $this->getDriver(),
                        new GeminiReasonException($reason)
                    );
                default:
                    throw new InteligenciaArtificialRequestErrorException(
                        $this->getDriver(),
                        new GeminiReasonException($reason)
                    );
            }
        } catch (InteligenciaArtificalExceptionInterface $e) {
            $this->logService->logError(
                $e,
                $this->clientContext
            );
            throw $this->logService->logError(
                $e,
                $this->clientContext
            );
        } catch (ClientExceptionInterface $e) {
            switch ($e->getCode()) {
                case 400:
                    throw $this->logService->logError(
                        new MaximumInputTokensExceededException(
                            $this->getDriver(),
                            $e
                        ),
                        $this->clientContext
                    );
                case 402:
                    throw $this->logService->logError(
                        new TokenBalanceInsufficientException(
                            $this->getDriver(),
                            $e
                        ),
                        $this->clientContext
                    );
                case 429:
                    $headers = $response->getHeaders(false);
                    throw $this->logService->logError(
                        new ClientRateLimitExeededException(
                            $this->getDriver(),
                            $e,
                            (int) ($headers['Retry-After'] ?? 60)
                        ),
                        $this->clientContext
                    );
                default:
                    throw $this->logService->logError(
                        new InteligenciaArtificialRequestErrorException(
                            $this->getDriver(),
                            $e
                        ),
                        $this->clientContext
                    );
            }
        } catch (Throwable $e) {
            throw $this->logService->logError(
                new InteligenciaArtificialRequestErrorException(
                    $this->getDriver(),
                    $e
                ),
                $this->clientContext
            );
        }
    }

    /**
     * Retorna os completions.
     *
     * @param PromptInterface         $prompt
     * @param callable                $streamCallback
     * @param string|null             $model
     * @param int|null                $maxTokens
     * @param int|null                $temperature
     * @param CompletionsContext|null $context
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
     */
    public function getStreamedCompletions(
        PromptInterface $prompt,
        callable $streamCallback,
        ?string $model = null,
        ?int $maxTokens = null,
        ?int $temperature = null,
        ?CompletionsContext $context = null
    ): CompletionsResponse {
        $completionsConfig = $this->clientConfig->getCompletionsConfig();
        $model ??= $completionsConfig->getModel();
        $maxTokens ??= $completionsConfig->getMaxTokens();
        $temperature ??= $completionsConfig->getTemperature();
        $callback = $this->promptGetTextCallback;
        if (!$context) {
            $context = new CompletionsContext();
            if ($this->persona) {
                $context->addContext(
                    new CompletionsContextItem('user', $this->persona)
                );
                $context->addContext(
                    new CompletionsContextItem('model', 'ok')
                );
            }
        }
        if ($prompt->getPersona()) {
            $context->addContext(
                new CompletionsContextItem(
                    'user',
                    $prompt->getPersona()
                )
            );
            $context->addContext(
                new CompletionsContextItem('model', 'ok')
            );
        }
        $context->addContext(
            new CompletionsContextItem(
                'user',
                $callback ? $callback($prompt) : $this->promptToText($prompt)
            )
        );
        try {
            $client = $this->getClient();
            $response = $client->request(
                'POST',
                sprintf(
                    '/v1/projects/%s/locations/%s/publishers/%s/models/%s:streamGenerateContent',
                    $this->clientConfig->getProject(),
                    $this->clientConfig->getLocation(),
                    $completionsConfig->getPublisher(),
                    $model,
                ),
                [
                    'body' => json_encode([
                        'contents' => array_map(
                            fn (CompletionsContextItem $context) => [
                                'role' => $context->getRole(),
                                'parts' => [
                                    [
                                        'text' => $context->getContent(),
                                    ],
                                ],
                            ],
                            $context->getContext()
                        ),
                        'generationConfig' => [
                            ...($temperature ? ['temperature' => $temperature] : []),
                            ...($maxTokens ? ['maxOutputTokens' => $maxTokens] : []),
                        ],
                        'safetySettings' => self::DEFAULT_SAFETY_SETTINGS,
                    ]),
                ]
            );
            $content = '';
            $promptTokenCount = $candidatesTokenCount = $totalTokenCount = 0;
            foreach ($client->stream($response) as $chunk) {
                if (is_null($chunk->getContent()) || '' === $chunk->getContent()) {
                    $streamCallback(
                        new CompletionsChunkResponse(
                            '',
                            $chunk->isTimeout(),
                            $chunk->isFirst(),
                            $chunk->isLast(),
                            $chunk->getOffset(),
                            $chunk->getError()
                        )
                    );
                    continue;
                }
                $json = null;
                switch (true) {
                    case str_starts_with($chunk->getContent(), '['):
                    case str_starts_with($chunk->getContent(), ','):
                        $json = json_decode(substr($chunk->getContent(), 1), true);
                        break;
                }
                if (!$json) {
                    $streamCallback(
                        new CompletionsChunkResponse(
                            '',
                            $chunk->isTimeout(),
                            $chunk->isFirst(),
                            $chunk->isLast(),
                            $chunk->getOffset(),
                            $chunk->getError()
                        )
                    );
                    continue;
                }
                if (isset($json['candidates'][0]['finishReason'])) {
                    $reason = FinishReasonEnum::tryFrom($json['candidates'][0]['finishReason']);
                    if (FinishReasonEnum::STOP !== $reason) {
                        switch ($reason) {
                            case FinishReasonEnum::MAX_TOKENS:
                                throw new MaximumInputTokensExceededException(
                                    $this->getDriver(),
                                    new GeminiReasonException($reason)
                                );
                            default:
                                throw new InteligenciaArtificialRequestErrorException(
                                    $this->getDriver(),
                                    new GeminiReasonException($reason)
                                );
                        }
                    }
                }
                if (isset($json['usageMetadata'])) {
                    $promptTokenCount = $json['usageMetadata']['promptTokenCount'];
                    $candidatesTokenCount = $json['usageMetadata']['candidatesTokenCount'];
                    $totalTokenCount = $json['usageMetadata']['totalTokenCount'];
                }
                $content .= $buffer = $json['candidates'][0]['content']['parts'][0]['text'];
                $streamCallback(
                    new CompletionsChunkResponse(
                        $buffer,
                        $chunk->isTimeout(),
                        $chunk->isFirst(),
                        $chunk->isLast(),
                        $chunk->getOffset(),
                        $chunk->getError()
                    )
                );
            }
            $context->addContext(
                new CompletionsContextItem(
                    'model',
                    $content
                )
            );

            $completionsResponse = new CompletionsResponse(
                $content,
                $promptTokenCount,
                $candidatesTokenCount,
                $totalTokenCount,
                $this->getDriver(),
                $model,
                $context
            );

            return $this->logService->logCompletions(
                $completionsResponse,
                true,
                $this->clientContext
            );
        } catch (InteligenciaArtificalExceptionInterface $e) {
            $this->logService->logError(
                $e,
                $this->clientContext
            );
            throw $this->logService->logError(
                $e,
                $this->clientContext
            );
        } catch (ClientExceptionInterface $e) {
            switch ($e->getCode()) {
                case 400:
                    throw $this->logService->logError(
                        new MaximumInputTokensExceededException(
                            $this->getDriver(),
                            $e
                        ),
                        $this->clientContext
                    );
                case 402:
                    throw $this->logService->logError(
                        new TokenBalanceInsufficientException(
                            $this->getDriver(),
                            $e
                        ),
                        $this->clientContext
                    );
                case 429:
                    $headers = $response->getHeaders(false);
                    throw $this->logService->logError(
                        new ClientRateLimitExeededException(
                            $this->getDriver(),
                            $e,
                            (int) ($headers['Retry-After'] ?? 60)
                        ),
                        $this->clientContext
                    );
                default:
                    throw $this->logService->logError(
                        new InteligenciaArtificialRequestErrorException(
                            $this->getDriver(),
                            $e
                        ),
                        $this->clientContext
                    );
            }
        } catch (Throwable $e) {
            throw $this->logService->logError(
                new InteligenciaArtificialRequestErrorException(
                    $this->getDriver(),
                    $e
                ),
                $this->clientContext
            );
        }
    }

    /**
     * Retorna os embeddings.
     *
     * @param PromptInterface $prompt
     * @param string|null     $model
     *
     * @return EmbeddingsResponse
     *
     * @throws ClientRateLimitExeededException
     * @throws InteligenciaArtificialRequestErrorException
     * @throws MaximumInputTokensExceededException
     * @throws TokenBalanceInsufficientException
     * @throws InteligenciaArtificalExceptionInterface
     */
    public function getEmbeddings(PromptInterface $prompt, ?string $model = null): EmbeddingsResponse
    {
        $embeddingsConfig = $this->clientConfig->getEmbeddingsConfig();
        $model ??= $embeddingsConfig->getModel();
        $callback = $this->promptGetTextCallback;
        try {
            $response = $this->getClient()->request(
                'POST',
                sprintf(
                    '/v1/projects/%s/locations/%s/publishers/%s/models/%s:predict',
                    $this->clientConfig->getProject(),
                    $this->clientConfig->getLocation(),
                    $embeddingsConfig->getPublisher(),
                    $model,
                ),
                [
                    'body' => json_encode([
                        'instances' => [
                            [
                                'content' => $callback ? $callback($prompt) : $prompt->getText(),
                            ],
                        ],
                    ]),
                ]
            );
            $responseData = $response->toArray();

            $embeddingsResponse = new EmbeddingsResponse(
                $responseData['predictions'][0]['embeddings']['values'] ?? [],
                intval($responseData['predictions'][0]['embeddings']['statistics']['token_count']),
                intval($responseData['predictions'][0]['embeddings']['statistics']['token_count']),
                $this->getDriver(),
                $model,
                $prompt
            );

            return $this->logService->logEmbeddings(
                $embeddingsResponse,
                $this->clientContext
            );
        } catch (InteligenciaArtificalExceptionInterface $e) {
            $this->logService->logError(
                $e,
                $this->clientContext
            );
            throw $this->logService->logError(
                $e,
                $this->clientContext
            );
        } catch (ClientExceptionInterface $e) {
            switch ($e->getCode()) {
                case 400:
                    throw $this->logService->logError(
                        new MaximumInputTokensExceededException(
                            $this->getDriver(),
                            $e
                        ),
                        $this->clientContext
                    );
                case 402:
                    throw $this->logService->logError(
                        new TokenBalanceInsufficientException(
                            $this->getDriver(),
                            $e
                        ),
                        $this->clientContext
                    );
                case 429:
                    $headers = $response->getHeaders(false);
                    throw $this->logService->logError(
                        new ClientRateLimitExeededException(
                            $this->getDriver(),
                            $e,
                            (int) ($headers['Retry-After'] ?? 60)
                        ),
                        $this->clientContext
                    );
                default:
                    throw $this->logService->logError(
                        new InteligenciaArtificialRequestErrorException(
                            $this->getDriver(),
                            $e
                        ),
                        $this->clientContext
                    );
            }
        } catch (Throwable $e) {
            throw $this->logService->logError(
                new InteligenciaArtificialRequestErrorException(
                    $this->getDriver(),
                    $e
                ),
                $this->clientContext
            );
        }
    }

    /**
     * Count request tokens.
     *
     * @see https://cloud.google.com/vertex-ai/docs/reference/rest/v1beta1/projects.locations.endpoints/countTokens#google.cloud.aiplatform.v1beta1.PredictionService.CountTokens
     *
     * @param string $project
     * @param string $location
     * @param string $publisher
     * @param string $model
     * @param array  $content
     *
     * @return array
     *
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function countTokens(
        string $project,
        string $location,
        string $publisher,
        string $model,
        array $content
    ): array {
        $response = $this->getClient()->request(
            'POST',
            sprintf(
                '/v1beta1/projects/%s/locations/%s/publishers/%s/models/%s:countTokens',
                $project,
                $location,
                $publisher,
                $model,
            ),
            [
                'body' => json_encode($content),
            ]
        );

        return $response->toArray();
    }

    /**
     * Retorna o texto do prompt.
     *
     * @param PromptInterface $prompt
     *
     * @return string
     *
     * @throws EmptyDocumentContentException
     * @throws UnsupportedComponenteDigitalMimeTypeException
     * @throws UnauthorizedDocumentAccessException
     * @throws InteligenciaArtificalException
     */
    protected function promptToText(PromptInterface $prompt): string
    {
        $text = $prompt->getText();
        if ($prompt->getDocumento()) {
            $documento = $this->documentoHelper->extractTextFromDocumento($prompt->getDocumento());
            if (!$documento) {
                throw new EmptyDocumentContentException($prompt->getDocumento()->getId());
            }
            $text = <<<EOT
                ###$documento###
                
                $text
            EOT;
        }

        return $text;
    }
}
