<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Configs;

/**
 * GoogleVertexAiEmbeddingsConfig.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GoogleVertexAiEmbeddingsConfig
{
    /**
     * Constructor.
     *
     * @see https://cloud.google.com/vertex-ai/docs/reference/rest/v1/projects.locations.endpoints/predict
     *
     * @param string      $model
     * @param string|null $publisher
     */
    public function __construct(
        private readonly string $model,
        private string|null $publisher = 'google'
    ) {
        if (!$this->publisher) {
            $this->publisher = 'google';
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
}
