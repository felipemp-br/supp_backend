<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\OpenAi\Clients;

use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\ClientContext;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\CompletionsContext;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\CompletionsContextItem;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\InteligenciaArtificialClientInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\OpenAi\Configs\OpenAiGptClientConfig;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Responses\CompletionsChunkResponse;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Responses\CompletionsResponse;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Responses\EmbeddingsResponse;
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
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

/**
 * OpenAiGptDriver.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class OpenAiGptClient implements InteligenciaArtificialClientInterface
{
    /**
     * @var callable|null
     */
    private mixed $promptGetTextCallback = null;
    private ?string $persona = null;
    private ?ClientContext $clientContext = null;

    /**
     * Constructor.
     *
     * @param string                           $driver
     * @param OpenAiGptClientConfig            $clientConfig
     * @param DocumentoHelper                  $documentoHelper
     * @param InteligenciaArtificialLogService $logService
     */
    public function __construct(
        private readonly string $driver,
        private readonly OpenAiGptClientConfig $clientConfig,
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
     */
    private function getClient(): HttpClientInterface
    {
        return HttpClient::create([
            'base_uri' => sprintf(
                'https://%s',
                $this->clientConfig->getServiceEndpoint()
            ),
            'headers' => [
                'Content-Type' => 'application/json',
                'api-key' => $this->clientConfig->getCredentials(),
                'Authorization' => sprintf(
                    'Bearer %s',
                    $this->clientConfig->getCredentials()
                ),
            ],
        ]);
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
     * @param PromptInterface         $prompt
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
                $context->addContext(new CompletionsContextItem('system', $this->persona));
            }
        }
        if ($prompt->getPersona()) {
            $context->addContext(
                new CompletionsContextItem(
                    'system',
                    $prompt->getPersona()
                )
            );
        }
        $context->addContext(
            new CompletionsContextItem(
                'user',
                $callback ? $callback($prompt, $this) : $this->promptToText($prompt)
            )
        );
        try {
            $response = $this->getClient()->request(
                'POST',
                sprintf(
                    '/%s/chat/completions%s',
                    $completionsConfig->getPublisher(),
                    $completionsConfig->getVersion() ? '?api-version='.$completionsConfig->getVersion() : ''
                ),
                [
                    'body' => json_encode(
                        [
                            'messages' => array_map(
                                fn (CompletionsContextItem $context) => [
                                    'role' => $context->getRole(),
                                    'content' => $context->getContent(),
                                ],
                                $context->getContext()
                            ),
                            'stream' => false,
                            'n' => 1,
                            ...($model ? ['model' => $model] : []),
                            ...($temperature ? ['temperature' => $temperature] : []),
                            ...($maxTokens ? ['max_tokens' => $maxTokens] : []),
                        ],
                        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                    ),
                ]
            );
            $content = $response->toArray();

            $completionsResponse = new CompletionsResponse(
                $content['choices'][0]['message']['content'],
                intval($content['usage']['prompt_tokens']),
                intval($content['usage']['completion_tokens']),
                intval($content['usage']['total_tokens']),
                $this->getDriver(),
                $model,
                $context->addContext(
                    new CompletionsContextItem(
                        $content['choices'][0]['message']['role'],
                        $content['choices'][0]['message']['content']
                    )
                )
            );

            return $this->logService->logCompletions(
                $completionsResponse,
                context: $this->clientContext,
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
                            max(
                                $this->parseRateLimitReset($headers['x-ratelimit-reset-requests'] ?? '60s'),
                                $this->parseRateLimitReset($headers['x-ratelimit-reset-tokens'] ?? '60s'),
                            )
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
     * @throws InteligenciaArtificalExceptionInterface
     */
    public function getStreamedCompletions(
        PromptInterface $prompt,
        callable $streamCallback,
        ?string $model = null,
        ?int $maxTokens = null,
        ?int $temperature = null,
        ?CompletionsContext $context = null
    ): CompletionsResponse {
        $model ??= $this->clientConfig->getCompletionsConfig()->getModel();
        $maxTokens ??= $this->clientConfig->getCompletionsConfig()->getMaxTokens();
        $temperature ??= $this->clientConfig->getCompletionsConfig()->getTemperature();
        $callback = $this->promptGetTextCallback;
        if (!$context) {
            $context = new CompletionsContext();
            if ($this->persona) {
                $context->addContext(new CompletionsContextItem('system', $this->persona));
            }
        }
        if ($prompt->getPersona()) {
            $context->addContext(
                new CompletionsContextItem(
                    'system',
                    $prompt->getPersona()
                )
            );
        }
        $context->addContext(
            new CompletionsContextItem(
                'user',
                $callback ? $callback($prompt, $this) : $this->promptToText($prompt)
            )
        );
        try {
            $completionsConfig = $this->clientConfig->getCompletionsConfig();
            $client = $this->getClient();
            $response = $client->request(
                'POST',
                sprintf(
                    '/%s/chat/completions%s',
                    $completionsConfig->getPublisher(),
                    $completionsConfig->getVersion() ? '?api-version='.$completionsConfig->getVersion() : ''
                ),
                [
                    'body' => json_encode([
                        'messages' => array_map(
                            fn (CompletionsContextItem $context) => [
                                'role' => $context->getRole(),
                                'content' => $context->getContent(),
                            ],
                            $context->getContext()
                        ),
                        'n' => 1,
                        'stream' => true,
                        'stream_options' => [
                            'include_usage' => true,
                        ],
                        ...($model ? ['model' => $model] : []),
                        ...($temperature ? ['temperature' => $temperature] : []),
                        ...($maxTokens ? ['max_tokens' => $maxTokens] : []),
                    ]),
                ]
            );
            $promptTokens = null;
            $completionsTokens = null;
            $totalTokens = null;
            $content = '';
            $previousIncompleteBlock = null;
            $regex = '/^data:\s*\{"choices":/s';
            foreach ($client->stream($response) as $chunk) {
                $chunkContent = $chunk->getContent();
                if (is_null($chunk->getContent()) || '' === $chunkContent) {
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
                if ($previousIncompleteBlock) {
                    $chunkContent = $previousIncompleteBlock . $chunkContent;
                    $previousIncompleteBlock = null;
                }
                $chunkBlocks = explode(
                    "\n\n",
                    $chunkContent
                );
                foreach ($chunkBlocks as $block) {
                    if (preg_match($regex, $block)) {
                        $jsonString = preg_replace('/^data:\s*/', '', $block);
                        if (json_validate($jsonString)) {
                            $data = json_decode($jsonString, true);
                            $contentBlock = '';
                            if (isset($data['choices'][0]['delta']['content'])) {
                                $contentBlock = $data['choices'][0]['delta']['content'];
                            }
                            $content .= $contentBlock;
                            if (isset($data['usage'])) {
                                $completionsTokens = $data['usage']['completion_tokens'];
                                $promptTokens = $data['usage']['prompt_tokens'];
                                $totalTokens = $data['usage']['total_tokens'];
                            }
                            $streamCallback(
                                new CompletionsChunkResponse(
                                    $contentBlock,
                                    $chunk->isTimeout(),
                                    $chunk->isFirst(),
                                    $chunk->isLast(),
                                    $chunk->getOffset(),
                                    $chunk->getError()
                                )
                            );
                        } else {
                            $previousIncompleteBlock .= $block;
                        }
                    } else {
                        $previousIncompleteBlock .= $block;
                    }
                }
            }
            $promptTokens ??= count(
                array_filter(
                    explode(
                        ' ',
                        implode(
                            ' ',
                            array_map(
                                fn (CompletionsContextItem $contextItem) => $contextItem->getContent(),
                                $context->getContext()
                            )
                        )
                    ),
                    fn ($word) => !empty($word)
                )
            );
            $completionsTokens ??= count(
                explode(
                    ' ',
                    $content
                )
            );

            $totalTokens ??= $promptTokens + $completionsTokens;

            $completionsResponse = new CompletionsResponse(
                $content,
                $promptTokens,
                $completionsTokens,
                $totalTokens,
                $this->getDriver(),
                $model,
                $context->addContext(
                    new CompletionsContextItem(
                        'assistant',
                        $content
                    )
                )
            );

            return $this->logService->logCompletions(
                $completionsResponse,
                true,
                context: $this->clientContext,
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
                            max(
                                $this->parseRateLimitReset($headers['x-ratelimit-reset-requests'] ?? '60s'),
                                $this->parseRateLimitReset($headers['x-ratelimit-reset-tokens'] ?? '60s'),
                            )
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
     * Retorna os embeddings constantes no prompt fornecido.
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
        $model ??= $this->clientConfig->getEmbeddingsConfig()->getModel();
        $callback = $this->promptGetTextCallback;
        $embeddingsConfig = $this->clientConfig->getEmbeddingsConfig();
        try {
            $response = $this->getClient()->request(
                'POST',
                sprintf(
                    '/%s/embeddings%s',
                    $embeddingsConfig->getPublisher(),
                    $embeddingsConfig->getVersion() ? '?api-version='.$embeddingsConfig->getVersion() : ''
                ),
                [
                    'body' => json_encode([
                        'input' => $callback ? $callback($prompt) : $prompt->getText(),
                        'stream' => false,
                        ...($model ? ['model' => $model] : []),
                    ]),
                ]
            );
            $content = $response->toArray();

            $embeddingsResponse = new EmbeddingsResponse(
                $content['data'][0]['embedding'] ?? [],
                intval($content['usage']['prompt_tokens']),
                intval($content['usage']['total_tokens']),
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
                            max(
                                $this->parseRateLimitReset($headers['x-ratelimit-reset-requests'] ?? '60s'),
                                $this->parseRateLimitReset($headers['x-ratelimit-reset-tokens'] ?? '60s'),
                            )
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

    /**
     * Faz o parse dos valores do rate limit que vem nos formatos:
     * 6s, 5m, 5m20s, 60, etc...
     *
     * @param string|int $value
     *
     * @return int
     */
    protected function parseRateLimitReset(string|int $value): int
    {
        if (is_numeric($value)) {
            return (int) $value;
        }
        $seconds = 0;
        preg_match_all('/(\d+)([smh])/', $value, $matches, PREG_SET_ORDER);
        foreach ($matches as [$full, $num, $unit]) {
            $seconds += match ($unit) {
                'h' => $num * 60 * 60,
                'm' => $num * 60,
                's' => $num,
                default => 60
            };
        }

        return $seconds;
    }
}
