<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Configs;

//use SensitiveParameter;

/**
 * GoogleVertexAiClientConfig.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GoogleVertexAiClientConfig
{
    /**
     * Constructor.
     *
     * @param string                          $serviceEndpoint
     * @param array                           $credentials
     * @param string                          $project
     * @param string                          $location
     * @param GoogleVertexAiCompletionsConfig $completionsConfig
     * @param GoogleVertexAiEmbeddingsConfig  $embeddingsConfig
     */
    public function __construct(
        private readonly string $serviceEndpoint,
//        #[SensitiveParameter]
        private readonly array $credentials,
        private readonly string $project,
        private readonly string $location,
        private readonly GoogleVertexAiCompletionsConfig $completionsConfig,
        private readonly GoogleVertexAiEmbeddingsConfig $embeddingsConfig
    ) {
    }

    /**
     * @return string
     */
    public function getServiceEndpoint(): string
    {
        return $this->serviceEndpoint;
    }

    /**
     * @return array
     */
    public function getCredentials(): array
    {
        return $this->credentials;
    }

    /**
     * @return GoogleVertexAiCompletionsConfig
     */
    public function getCompletionsConfig(): GoogleVertexAiCompletionsConfig
    {
        return $this->completionsConfig;
    }

    /**
     * @return GoogleVertexAiEmbeddingsConfig
     */
    public function getEmbeddingsConfig(): GoogleVertexAiEmbeddingsConfig
    {
        return $this->embeddingsConfig;
    }

    /**
     * @return string
     */
    public function getProject(): string
    {
        return $this->project;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }
}
