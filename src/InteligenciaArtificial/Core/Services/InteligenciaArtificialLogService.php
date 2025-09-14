<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Services;

use Psr\Log\LoggerInterface;
use ReflectionClass;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\ClientContext;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\InteligenciaArtificialClientInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Responses\CompletionsResponse;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Responses\EmbeddingsResponse;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificalExceptionInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Throwable;

/**
 * InteligenciaArtificialLogService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class InteligenciaArtificialLogService
{
    private const LOG_PREFIX = '[Inteligencia Artificial]';

    /**
     * Constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(
        #[Autowire('@monolog.logger.inteligencia_artificial')]
        protected readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param EmbeddingsResponse $embeddingsResponse
     * @param ClientContext|null $context
     *
     * @return EmbeddingsResponse
     */
    public function logEmbeddings(
        EmbeddingsResponse $embeddingsResponse,
        ?ClientContext $context = null,
    ): EmbeddingsResponse {
        $this->logger->info(
            sprintf(
                '%s Embeddings usage',
                self::LOG_PREFIX,
            ),
            [
                'inteligencia_artificial' => [
                    'service' => $context?->getContextName() ?? $this->findContextName(),
                    'driver' => $embeddingsResponse->getDriver(),
                    'model' => $embeddingsResponse->getModel(),
                    'prompt_tokens' => $embeddingsResponse->getPromptTokens(),
                    'total_tokens' => $embeddingsResponse->getTotalTokens(),
                    ...($context?->getContextData() ?? []),
                ],
            ]
        );

        return $embeddingsResponse;
    }

    /**
     * @param CompletionsResponse $completionsResponse
     * @param bool                $streamed
     * @param ClientContext|null  $context
     *
     * @return CompletionsResponse
     */
    public function logCompletions(
        CompletionsResponse $completionsResponse,
        bool $streamed = false,
        ?ClientContext $context = null
    ): CompletionsResponse {
        $this->logger->info(
            sprintf(
                '%s Completions usage',
                self::LOG_PREFIX,
            ),
            [
                'inteligencia_artificial' => [
                    'service' => $context?->getContextName() ?? $this->findContextName(),
                    'streamed' => $streamed,
                    'driver' => $completionsResponse->getDriver(),
                    'model' => $completionsResponse->getModel(),
                    'prompt_tokens' => $completionsResponse->getPromptTokens(),
                    'completions_tokens' => $completionsResponse->getCompletionsTokens(),
                    'total_tokens' => $completionsResponse->getTotalTokens(),
                    ...($context?->getContextData() ?? []),
                ],
            ]
        );

        return $completionsResponse;
    }

    /**
     * Realiza o log dos erros da inteligência artificial.
     *
     * @param InteligenciaArtificalExceptionInterface $e
     * @param ClientContext|null                      $context
     *
     * @return InteligenciaArtificalExceptionInterface
     */
    public function logError(
        InteligenciaArtificalExceptionInterface $e,
        ?ClientContext $context = null,
    ): InteligenciaArtificalExceptionInterface {
        $formatter = fn (Throwable $e, int $size): array => [
            'code' => $e->getCode(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'message' => $e->getMessage(),
            'trace' => substr($e->getTraceAsString(), 0, $size),
        ];
        $this->logger->error(
            sprintf(
                '%s %s',
                self::LOG_PREFIX,
                $e->getMessage()
            ),
            [
                'inteligencia_artificial' => [
                    'service' => $context?->getContextName() ?? $this->findContextName(),
                    ...$formatter($e, 800),
                    ...($e->getPrevious() ? ['previous' => $formatter($e->getPrevious(), 400)] : []),
                    ...($context?->getContextData() ?? []),
                ],
            ]
        );

        return $e;
    }

    /**
     * Retorna a classe que chamou o log.
     *
     * @return string
     */
    protected function findContextName(): string
    {
        try {
            foreach (debug_backtrace() as $trace) {
                if (isset($trace['class'])) {
                    if (self::class === $trace['class']) {
                        continue;
                    }
                    $reflectionClass = new ReflectionClass($trace['class']);
                    if (!$reflectionClass->implementsInterface(InteligenciaArtificialClientInterface::class)) {
                        return $trace['class'];
                    }
                }
            }
        } catch (Throwable $e) {
        }

        return self::class;
    }
}
