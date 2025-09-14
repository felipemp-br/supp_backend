<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\OpenAi\Configs;

//use SensitiveParameter;

/**
 * OpenAiGptClientConfig.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class OpenAiGptClientConfig
{
    /**
     * Constructor.
     *
     * @param string                     $serviceEndpoint
     * @param string                     $credentials
     * @param OpenAiGptCompletionsConfig $completionsConfig
     * @param OpenAiGptEmbeddingsConfig  $embeddingsConfig
     */
    public function __construct(
        private readonly string $serviceEndpoint,
//        #[SensitiveParameter]
        private readonly string $credentials,
        private readonly OpenAiGptCompletionsConfig $completionsConfig,
        private readonly OpenAiGptEmbeddingsConfig $embeddingsConfig
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
     * @return string
     */
    public function getCredentials(): string
    {
        return $this->credentials;
    }

    /**
     * @return OpenAiGptCompletionsConfig
     */
    public function getCompletionsConfig(): OpenAiGptCompletionsConfig
    {
        return $this->completionsConfig;
    }

    /**
     * @return OpenAiGptEmbeddingsConfig
     */
    public function getEmbeddingsConfig(): OpenAiGptEmbeddingsConfig
    {
        return $this->embeddingsConfig;
    }
}
