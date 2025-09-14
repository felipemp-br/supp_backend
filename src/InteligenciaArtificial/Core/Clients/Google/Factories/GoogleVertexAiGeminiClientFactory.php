<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Factories;

use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Clients\GoogleVertexAiGeminiClient;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Configs\GoogleVertexAiClientConfig;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Configs\GoogleVertexAiCompletionsConfig;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Configs\GoogleVertexAiEmbeddingsConfig;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\InteligenciaArtificialClientInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\ClientMissingConfigException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnsupportedUriException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Helpers\DocumentoHelper;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Services\InteligenciaArtificialClientFactoryInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Services\InteligenciaArtificialLogService;

/**
 * GoogleVertexAiGeminiClientFactory.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GoogleVertexAiGeminiClientFactory implements InteligenciaArtificialClientFactoryInterface
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
        return str_starts_with($uri, 'ia:vertex-ai-gemini-1.0-pro');
    }

    /**
     * Cria um client com base na uri informada.
     *
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
            str_starts_with($uri, 'ia:vertex-ai-gemini-1.0-pro') => $this->createGemini1Pro($uri),
            default => throw new UnsupportedUriException(),
        };
    }

    /**
     * Create Google Vertex AI client.
     *
     * O padrão de uri esperado é:
     *
     * **ia:vertex-ai-gemini-1.0-pro:configs(completionsPublisher=PUBLISHER&embeddingsPublisher=PUBLISHER&location=LOCATION&project=PROJECT&serviceEndpoint=SERVICE_ENDPOINT&credentials=CREDENTIALS&completionsTemperature=TEMPERATURE&completionsMaxTokens=MAX_TOKENS&completionsModel=MODEL&embeddingsModel=MODEL)**
     *
     * **PUBLISHER:** O default é 'google', mas no caso de uso de um modelo customizado será necessário fornecer o nome do publicador.
     *
     * **LOCATION:** O location do serviço, ex: 'us-central1'.
     *
     * **SERVICE_ENDPOINT:** Fornecer o service endpoint conforme [lista da documentação do google](https://cloud.google.com/vertex-ai/docs/reference/rest#rest_endpoints).
     *
     *
     *
     *  **CREDENTIALS:** String do caminho para o arquivo de credenciais ou o base64 do json de credenciais, contendo a estrutura abaixo:
     *
     * ```json
     * {
     *      "type": "service_account",
     *      "project_id": "project_id",
     *      "private_key_id": "private_key_id",
     *      "private_key": "certificate_string",
     *      "client_email": "client_email@project.iam.gserviceaccount.com",
     *      "client_id": "client_id",
     *      "auth_uri": "https://accounts.google.com/o/oauth2/auth",
     *      "token_uri": "https://oauth2.googleapis.com/token",
     *      "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
     *      "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/client_x509_cert_url",
     *      "universe_domain": "googleapis.com"
     * }
     * ```
     *
     * **TEMPERATURE:** (opcional) Valor float para precisão de retorno.
     *
     * **MAX_TOKENS:** (opcional) Valor inteiro para quantidade máxima de tokens a ser utilizado.
     *
     * **MODEL:** Modelo a ser utilizado, ver lista para [generateContent](https://cloud.google.com/vertex-ai/generative-ai/docs/learn/models?hl=pt-br#gemini-models) e [predic](https://cloud.google.com/vertex-ai/generative-ai/docs/learn/model-versioning?hl=pt-br#palm-stable-versions-available).
     *
     * @see https://platform.openai.com/docs/introduction
     *
     * @param string $uri
     *
     * @return InteligenciaArtificialClientInterface
     *
     * @throws ClientMissingConfigException
     */
    private function createGemini1Pro(string $uri): InteligenciaArtificialClientInterface
    {
        return new GoogleVertexAiGeminiClient(
            'vertex-ai-gemini-1.0-pro',
            $this->getClientConfig($uri),
            $this->documentoHelper,
            $this->logService
        );
    }

    /**
     * Retorna as configurações do client.
     *
     * @param string $uri
     *
     * @return GoogleVertexAiClientConfig
     *
     * @throws ClientMissingConfigException
     */
    protected function getClientConfig(string $uri): GoogleVertexAiClientConfig
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
                'completionsModel',
                'embeddingsModel',
                'location',
                'project',
            ],
            $configs
        );
        if ($invalidField) {
            throw new ClientMissingConfigException(GoogleVertexAiGeminiClient::class, $invalidField);
        }
        $credentials = [];
        if (file_exists($configs['credentials'])) {
            $credentials = json_decode(file_get_contents($configs['credentials']), true);
        } else {
            $decoded = base64_decode($configs['credentials']);
            if (json_validate($decoded)) {
                $credentials = json_decode($decoded, true);
            }
        }

        if (empty($credentials)) {
            throw new ClientMissingConfigException(GoogleVertexAiGeminiClient::class, 'credentials');
        }

        return new GoogleVertexAiClientConfig(
            urldecode(
                str_replace(
                    'https://',
                    '',
                    $configs['serviceEndpoint']
                )
            ),
            $credentials,
            $configs['project'],
            $configs['location'],
            new GoogleVertexAiCompletionsConfig(
                $configs['completionsModel'],
                $configs['completionsPublisher'] ?? null,
                $configs['completionsTemperature'] ? (float) $configs['completionsTemperature'] : null,
                $configs['completionsMaxTokens'] ? (int) $configs['completionsMaxTokens'] : null,
            ),
            new GoogleVertexAiEmbeddingsConfig(
                $configs['embeddingsModel'],
                $configs['embeddingsPublisher'] ?? null,
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
