<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients;

use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\CompletionsContext;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\ClientContext;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Responses\CompletionsResponse;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Responses\EmbeddingsResponse;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\ClientRateLimitExeededException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\EmptyDocumentContentException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificalException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificialRequestErrorException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\MaximumInputTokensExceededException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\TokenBalanceInsufficientException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnauthorizedDocumentAccessException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnsupportedComponenteDigitalMimeTypeException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Prompts\PromptInterface;

/**
 * InteligenciaArtificialClientInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface InteligenciaArtificialClientInterface
{
    /**
     * Define o callback de execução que será chamado em todo getText de um prompt.
     *
     * @param callable $callback
     *
     * @return self
     */
    public function setPromptGetTextCallback(callable $callback): self;

    /**
     * Limpa o callback de getText do prompt.
     *
     * @return self
     */
    public function cleanPromptGetTextCallback(): self;

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
     */
    public function getCompletions(
        PromptInterface $prompt,
        ?string $model = null,
        ?int $maxTokens = null,
        ?int $temperature = null,
        ?CompletionsContext $context = null
    ): CompletionsResponse;

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
    ): CompletionsResponse;

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
     */
    public function getEmbeddings(
        PromptInterface $prompt,
        ?string $model = null,
    ): EmbeddingsResponse;

    /**
     * Define a persona a ser executado para esta instancia do client na execução de todos os prompts.
     *
     * @param string|null $persona
     *
     * @return self
     */
    public function setPersona(?string $persona): self;

    /**
     * Retorna o nome do driver.
     *
     * @return string
     */
    public function getDriver(): string;

    /**
     * Retorna o nome do modelo default.
     *
     * @return string
     */
    public function getCompletionsModel(): string;

    /**
     * Retorna o nome do modelo default.
     *
     * @return string
     */
    public function getEmbeddinsModel(): string;

    /**
     * @param ClientContext|null $clientContext
     *
     * @return self
     */
    public function setClientContext(?ClientContext $clientContext = null): self;
}
