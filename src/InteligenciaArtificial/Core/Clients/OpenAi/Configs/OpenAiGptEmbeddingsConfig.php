<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\OpenAi\Configs;

/**
 * OpenAiGptEmbeddingsConfig.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class OpenAiGptEmbeddingsConfig
{
    /**
     * Constructor.
     *
     * @see https://platform.openai.com/docs/api-reference/embeddings
     *
     * @param string|null $model
     * @param string|null $publisher
     * @param string|null $version
     */
    public function __construct(
        private readonly ?string $model = null,
        private ?string $publisher = 'v1',
        private readonly ?string $version = null,
    ) {
        if (!$this->publisher) {
            $this->publisher = 'v1';
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
     * @return string|null
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }
}
