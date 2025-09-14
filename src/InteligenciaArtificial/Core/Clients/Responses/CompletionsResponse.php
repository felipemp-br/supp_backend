<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Responses;

use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\CompletionsContext;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InvalidCompletionsJsonResponseException;

/**
 * CompletionsResponse.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CompletionsResponse
{
    /**
     * Constructor.
     *
     * @param string             $content
     * @param int                $promptTokens
     * @param int                $completionsTokens
     * @param int                $totalTokens
     * @param string             $driver
     * @param string             $model
     * @param CompletionsContext $context
     */
    public function __construct(
        private readonly string $content,
        private readonly int $promptTokens,
        private readonly int $completionsTokens,
        private readonly int $totalTokens,
        private readonly string $driver,
        private readonly string $model,
        private readonly CompletionsContext $context
    ) {
    }

    /**
     * Retorna a responsa da ia.
     *
     * @return string
     */
    public function getResponse(): string
    {
        return $this->content;
    }

    /**
     * Retorna a resposta da IA na forma de um array.
     *
     * @param bool $throwException
     *
     * @return array|null
     *
     * @throws InvalidCompletionsJsonResponseException
     */
    public function getJsonResponse(bool $throwException = true): ?array
    {
        $content = $this->content;
        if (!json_validate($content)) {
            $content = preg_replace('/^[^\[{]*/s', '', $content);
            $content = preg_replace('/([]}])[^}\]]*$/', '$1', $content);
        }
        if (json_validate($content)) {
            return json_decode($content, true);
        }
        if ($throwException) {
            throw new InvalidCompletionsJsonResponseException($this);
        }
        return null;
    }

    /**
     * @param int $limit
     *
     * @return string
     */
    public function getShortResponse(int $limit = 300): string
    {
        $content = mb_strimwidth($this->content, 0, $limit);
        if (mb_strlen($this->content) > $limit) {
            $content .= '...';
        }

        return $content;
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
     * Retorna a quantidade de tokens gastos pelo completion.
     *
     * @return int
     */
    public function getCompletionsTokens(): int
    {
        return $this->completionsTokens;
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
     * Retorna o contexto.
     *
     * @return CompletionsContext
     */
    public function getContext(): CompletionsContext
    {
        return $this->context;
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
