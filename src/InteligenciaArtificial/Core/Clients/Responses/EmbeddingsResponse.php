<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Responses;

use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Prompts\PromptInterface;

/**
 * CompletionsResponse.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EmbeddingsResponse
{
    /**
     * Constructor.
     *
     * @param array           $embeddings
     * @param int             $promptTokens
     * @param int             $totalTokens
     * @param string          $driver
     * @param string          $model
     * @param PromptInterface $prompt
     */
    public function __construct(
        private readonly array $embeddings,
        private readonly int $promptTokens,
        private readonly int $totalTokens,
        private readonly string $driver,
        private readonly string $model,
        private readonly PromptInterface $prompt
    ) {
    }

    /**
     * Retorna a matriz de embeddings.
     *
     * @return array
     */
    public function getResponse(): array
    {
        return $this->embeddings;
    }

    /**
     * Retorna o total de tokens do prompt.
     *
     * @return int
     */
    public function getPromptTokens(): int
    {
        return $this->promptTokens;
    }

    /**
     * Retorna o total de tokens consumidos.
     *
     * @return int
     */
    public function getTotalTokens(): int
    {
        return $this->totalTokens;
    }

    /**
     * Retorna o prompt executado.
     *
     * @return PromptInterface
     */
    public function getPrompt(): PromptInterface
    {
        return $this->prompt;
    }

    /**
     * Retorna o driver usado.
     *
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * Retorna o modelo usado.
     *
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }
}
