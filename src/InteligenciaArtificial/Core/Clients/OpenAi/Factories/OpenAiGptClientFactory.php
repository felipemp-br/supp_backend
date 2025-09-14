<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\OpenAi\Factories;

use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\InteligenciaArtificialClientInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\OpenAi\Clients\OpenAiGptClient;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\OpenAi\Configs\OpenAiGptClientConfig;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\OpenAi\Configs\OpenAiGptCompletionsConfig;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\OpenAi\Configs\OpenAiGptEmbeddingsConfig;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\ClientMissingConfigException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnsupportedUriException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Helpers\DocumentoHelper;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Services\InteligenciaArtificialClientFactoryInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Services\InteligenciaArtificialLogService;

/**
 * OpenAiGptClientFactory.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class OpenAiGptClientFactory implements InteligenciaArtificialClientFactoryInterface
{
    /**
     * Constructor.
     *
     * @param DocumentoHelper                  $documentoHelper
     * @param InteligenciaArtificialLogService $logService
     */
    public function __construct(
        private readonly DocumentoHelper $documentoHelper,
        private readonly InteligenciaArtificialLogService $logService,
    ) {
    }

    /**
     * Verifica se o client suporta a uri informada.
     *
     * @param string $uri
     *
     * @return bool
     */
    public function supports(string $uri): bool
    {
        @[, $driver] = explode(':', $uri);

        return in_array($driver, ['gpt-3.5', 'gpt-4o', 'gpt-4']);
    }

    /**
     * @param string $uri
     *
     * @return InteligenciaArtificialClientInterface
     *
     * @throws ClientMissingConfigException
     * @throws UnsupportedUriException
     */
    public function createClient(string $uri): InteligenciaArtificialClientInterface
    {
        return match (true) {
            str_starts_with($uri, 'ia:gpt-3.5') => $this->createGptClient35($uri),
            str_starts_with($uri, 'ia:gpt-4o') => $this->createGptClient4o($uri),
            str_starts_with($uri, 'ia:gpt-4') => $this->createGptClient4($uri),
            default => throw new UnsupportedUriException(),
        };
    }

    /**
     * Create client to version gpt-3.5.
     *
     *  O padrão de uri esperado é:
     *
     *  **ia:gpt-3.5:configs(completionsPublisher=PUBLISHER&embeddingsPublisher=PUBLISHER&serviceEndpoint=SERVICE_ENDPOINT&credentials=CREDENTIALS&completionsVersion=API_VERSION&embeddingsVersion=API_VERSION&completionsTemperature=TEMPERATURE&completionsMaxTokens=MAX_TOKENS&completionsModel=MODEL&embeddingsModel=MODEL)**
     *
     *  **PUBLISHER:** O default é 'v1', mas no caso de uso de um modelo customizado será necessário fornecer o modelo publicado, exemplo:
     *
     *  * openai/deployments/SapiensEmbedding
     *  * openai/deployments/SapiensOpenAIv432k
     *
     *  **SERVICE_ENDPOINT:** Fornecer o service endpoint. No caso do default do OpenAi, fornecer: api.openai.com. No caso de um serviço customizado fornecer a url, exemplo: suppopenai.openai.azure.com.
     *
     *  **CREDENTIALS:** String da api-key.
     *
     *  **TEMPERATURE:** (opcional) Valor float para precisão de retorno.
     *
     *  **MAX_TOKENS:** (opcional) Valor inteiro para quantidade máxima de tokens a ser utilizado.
     *
     *  **MODEL:** (opcional) Modelo a ser utilizado, ver lista para [completions](https://platform.openai.com/docs/models/gpt-3-5-turbo) e [embeddings](https://platform.openai.com/docs/guides/embeddings/embedding-models).
     *
     *  **API_VERSION:** (opcional) Fornecer a versão da api, ex: 2023-03-15-preview. **Importante:** Em alguns casos a versão da api pode ser obrigatória.
     *
     * @param string $uri
     *
     * @return InteligenciaArtificialClientInterface
     *
     * @throws ClientMissingConfigException
     */
    private function createGptClient35(
        string $uri,
    ): InteligenciaArtificialClientInterface {
        $config = $this->getClientConfig($uri);

        return new OpenAiGptClient(
            'gpt-3.5',
            $config,
            $this->documentoHelper,
            $this->logService
        );
    }

    /**
     * Create client to version gpt-4.
     *
     * O padrão de uri esperado é:
     *
     * **ia:gpt-4:configs(completionsPublisher=PUBLISHER&embeddingsPublisher=PUBLISHER&serviceEndpoint=SERVICE_ENDPOINT&credentials=CREDENTIALS&completionsVersion=API_VERSION&embeddingsVersion=API_VERSION&completionsTemperature=TEMPERATURE&completionsMaxTokens=MAX_TOKENS&completionsModel=MODEL&embeddingsModel=MODEL)**
     *
     * **PUBLISHER:** O default é 'v1', mas no caso de uso de um modelo customizado será necessário fornecer o modelo publicado, exemplo:
     *
     * * openai/deployments/SapiensEmbedding
     * * openai/deployments/SapiensOpenAIv432k
     *
     * **SERVICE_ENDPOINT:** Fornecer o service endpoint. No caso do default do OpenAi, fornecer: api.openai.com. No caso de um serviço customizado fornecer a url, exemplo: suppopenai.openai.azure.com.
     *
     * **CREDENTIALS:** String da api-key.
     *
     * **TEMPERATURE:** (opcional) Valor float para precisão de retorno.
     *
     * **MAX_TOKENS:** (opcional) Valor inteiro para quantidade máxima de tokens a ser utilizado.
     *
     * **MODEL:** (opcional) Modelo a ser utilizado, ver lista para [completions](https://platform.openai.com/docs/models/gpt-4-and-gpt-4-turbo) e [embeddings](https://platform.openai.com/docs/guides/embeddings/embedding-models).
     *
     * **API_VERSION:** (opcional) Fornecer a versão da api, ex: 2023-03-15-preview. **Importante:** Em alguns casos a versão da api pode ser obrigatória.
     *
     * @param string $uri
     *
     * @return InteligenciaArtificialClientInterface
     *
     * @throws ClientMissingConfigException
     */
    private function createGptClient4(string $uri): InteligenciaArtificialClientInterface
    {
        $config = $this->getClientConfig($uri);

        return new OpenAiGptClient(
            'gpt-4',
            $config,
            $this->documentoHelper,
            $this->logService
        );
    }

    /**
     * Create client to version gpt-4o.
     *
     * O padrão de uri esperado é:
     *
     * **ia:gpt-4:configs(completionsPublisher=PUBLISHER&embeddingsPublisher=PUBLISHER&serviceEndpoint=SERVICE_ENDPOINT&credentials=CREDENTIALS&completionsVersion=API_VERSION&embeddingsVersion=API_VERSION&completionsTemperature=TEMPERATURE&completionsMaxTokens=MAX_TOKENS&completionsModel=MODEL&embeddingsModel=MODEL)**
     *
     * **PUBLISHER:** O default é 'v1', mas no caso de uso de um modelo customizado será necessário fornecer o modelo publicado, exemplo:
     *
     * * openai/deployments/SapiensEmbedding
     * * openai/deployments/SapiensOpenAIv432k
     *
     * **SERVICE_ENDPOINT:** Fornecer o service endpoint. No caso do default do OpenAi, fornecer: api.openai.com. No caso de um serviço customizado fornecer a url, exemplo: suppopenai.openai.azure.com.
     *
     * **CREDENTIALS:** String da api-key.
     *
     * **TEMPERATURE:** (opcional) Valor float para precisão de retorno.
     *
     * **MAX_TOKENS:** (opcional) Valor inteiro para quantidade máxima de tokens a ser utilizado.
     *
     * **MODEL:** (opcional) Modelo a ser utilizado, ver lista para [completions](https://platform.openai.com/docs/models/gpt-4-and-gpt-4-turbo) e [embeddings](https://platform.openai.com/docs/guides/embeddings/embedding-models).
     *
     * **API_VERSION:** (opcional) Fornecer a versão da api, ex: 2023-03-15-preview. **Importante:** Em alguns casos a versão da api pode ser obrigatória.
     *
     * @param string $uri
     *
     * @return InteligenciaArtificialClientInterface
     *
     * @throws ClientMissingConfigException
     */
    private function createGptClient4o(string $uri): InteligenciaArtificialClientInterface
    {
        $config = $this->getClientConfig($uri);

        return new OpenAiGptClient(
            'gpt-4',
            $config,
            $this->documentoHelper,
            $this->logService
        );
    }

    /**
     * Retorna as configurações do client.
     *
     * @param string $uri
     *
     * @return OpenAiGptClientConfig
     *
     * @throws ClientMissingConfigException
     */
    protected function getClientConfig(string $uri): OpenAiGptClientConfig
    {
        $uriParts = explode(':', $uri);
        preg_match(
            '/configs\((.*?)\)/',
            $uriParts[2],
            $configsContent
        );
        parse_str($configsContent[1], $configs);

        $invalidField = $this->checkFields(
            [
                'serviceEndpoint',
                'credentials',
            ],
            $configs
        );
        if ($invalidField) {
            throw new ClientMissingConfigException(OpenAiGptClient::class, $invalidField);
        }

        return new OpenAiGptClientConfig(
            urldecode(
                str_replace(
                    'https://',
                    '',
                    $configs['serviceEndpoint']
                )
            ),
            $configs['credentials'],
            new OpenAiGptCompletionsConfig(
                $configs['completionsModel'] ?? null,
                $configs['completionsPublisher'] ?? null,
                $configs['completionsTemperature'] ? (float) $configs['completionsTemperature'] : null,
                $configs['completionsMaxTokens'] ? (int) $configs['completionsMaxTokens'] : null,
                $configs['completionsVersion'] ?? null,
            ),
            new OpenAiGptEmbeddingsConfig(
                $configs['embeddingsModel'] ?? null,
                $configs['embeddingsPublisher'] ?? null,
                $configs['embeddingsVersion'] ?? null,
            )
        );
    }

    /**
     * Verifica se a lista de campos foi preenchida.
     *
     * @param array $fields
     * @param array $data
     *
     * @return string|null
     */
    private function checkFields(array $fields, array $data): ?string
    {
        foreach ($fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return $field;
            }
        }

        return null;
    }
}
