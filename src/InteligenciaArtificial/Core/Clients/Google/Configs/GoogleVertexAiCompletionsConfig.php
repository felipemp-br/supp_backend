<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Configs;

/**
 * GoogleVertexAiCompletionsConfig.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GoogleVertexAiCompletionsConfig
{
    /**
     * Constructor.
     *
     * @see https://cloud.google.com/vertex-ai/docs/reference/rest/v1/projects.locations.endpoints/generateContent
     *
     * @param string      $model
     * @param string|null $publisher
     * @param float|null  $temperature
     * @param int|null    $maxTokens
     */
    public function __construct(
        private readonly string $model,
        private ?string $publisher = 'google',
        private ?float $temperature = 0.2,
        private ?int $maxTokens = 4060
    ) {
        if (!$this->publisher) {
            $this->publisher = 'google';
        }
        if (!$this->temperature) {
            $this->temperature = 0.2;
        }
        if (!$this->maxTokens) {
            $this->maxTokens = 4060;
        }
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getPublisher(): string
    {
        return $this->publisher;
    }

    /**
     * @return float
     */
    public function getTemperature(): float
    {
        return $this->temperature;
    }

    /**
     * @return int
     */
    public function getMaxTokens(): int
    {
        return $this->maxTokens;
    }
}
