<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\OpenAi\Configs;

/**
 * OpenAiGptCompletionsConfig.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class OpenAiGptCompletionsConfig
{
    /**
     * Constructor.
     *
     * @see https://platform.openai.com/docs/api-reference/chat
     *
     * @param string|null $model
     * @param string|null $publisher
     * @param float|null  $temperature
     * @param int|null    $maxTokens
     * @param string|null $version
     */
    public function __construct(
        private readonly ?string $model = null,
        private ?string $publisher = 'v1',
        private ?float $temperature = 0.2,
        private ?int $maxTokens = 4060,
        private readonly ?string $version = null
    ) {
        if (!$this->publisher) {
            $this->publisher = 'v1';
        }
        if (!$this->temperature) {
            $this->temperature = 0.2;
        }
        if (!$this->maxTokens) {
            $this->maxTokens = 4060;
        }
    }

    /**
     * @return string|null
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * @return string|null
     */
    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    /**
     * @return float|null
     */
    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    /**
     * @return int|null
     */
    public function getMaxTokens(): ?int
    {
        return $this->maxTokens;
    }

    /**
     * @return string|null
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }
}
