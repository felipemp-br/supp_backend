<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Security;

use Exception;
use Lcobucci\JWT\Encoding\JoseEncoder;
use UnexpectedValueException;

/**
 * Class LoginUnicoGovBrService
 * Classe de integração com o serviço de login único do Gov.br.
 *
 * @see https://manual-roteiro-integracao-login-unico.servicos.gov.br/pt/stable/index.html
 */
class LoginUnicoGovBrService
{
    /**
     * Tipo de autorização "authorization_code".
     */
    public const GRANT_TYPE_AUTH_CODE = 'authorization_code';

    /**
     * Algoritmos suportados.
     *
     * @var array
     */
    private static array $supported_algs = [
        'ES256' => ['openssl', 'SHA256'],
        'HS256' => ['hash_hmac', 'SHA256'],
        'HS384' => ['hash_hmac', 'SHA384'],
        'HS512' => ['hash_hmac', 'SHA512'],
        'RS256' => ['openssl', 'SHA256'],
        'RS384' => ['openssl', 'SHA384'],
        'RS512' => ['openssl', 'SHA512'],
    ];

    /**
     * Chave de acesso, que identifica o serviço consumidor fornecido pelo Login Único para a aplicação cadastrada.
     *
     * @var string
     */
    private string $clientId;

    /**
     * Chave secreta de integração entre cliente e serviço de integração.
     *
     * @var string
     */
    private string $clientSecret;

    /**
     * URI de retorno cadastrada para a aplicação cliente no formato URL Encode. Este parâmetro não pode conter
     * caracteres especiais conforme consta na especificação auth 2.0 Redirection Endpoint.
     *
     * @var string
     */
    private string $redirectUri;

    /**
     * Base URL do serviço de login único do Gov.br.
     *
     * @var string
     */
    private string $ssoUrl;

    /**
     * Base URL da api para consulta de dados do cidadão no Gov.br.
     *
     * @var string
     */
    private string $apiUrl;

    /**
     * Lista de confiabilidades aceitas para um usuário externo ser considerado como ativo.
     *
     * @var array
     */
    private array $confiabilidadesValidas;

    private array $niveisValidos;

    private string $revalidaClientId;

    private string $revalidaClientSecret;

    private string $revalidaOauthUrl;

    private string $revalidaRedirectUri;

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * @return string
     */
    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    /**
     * @return string
     */
    public function getSsoUrl(): string
    {
        return $this->ssoUrl;
    }

    /**
     * @return string
     */
    public function getRevalidaClientId(): string
    {
        return $this->revalidaClientId;
    }

    /**
     * @return string
     */
    public function getRevalidaClientSecret(): string
    {
        return $this->revalidaClientSecret;
    }

    /**
     * @return string
     */
    public function getRevalidaOauthUrl(): string
    {
        return $this->revalidaOauthUrl;
    }

    /**
     * @return string
     */
    public function getRevalidaRedirectUri(): string
    {
        return $this->revalidaRedirectUri;
    }

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * @return array
     */
    public function getConfiabilidadesValidas(): array
    {
        return $this->confiabilidadesValidas;
    }

    /**
     * @return array
     */
    public function getNiveisValidos(): array
    {
        return $this->niveisValidos;
    }

    /**
     * LoginUnicoGovBrService constructor.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUri
     * @param string $ssoUrl
     * @param string $apiUrl
     * @param array  $confiabilidadesValidas     
     * @param array  $niveisValidos
     * @param string $revalidaClientId
     * @param string $revalidaClientSecret
     * @param string $revalidaOauthUrl
     * @param string $revalidaRedirectUri
     */
    public function __construct(
        string $clientId,
        string $clientSecret,
        string $redirectUri,
        string $ssoUrl,
        string $apiUrl,
        array $confiabilidadesValidas,
        array $niveisValidos,
        string $revalidaClientId,
        string $revalidaClientSecret,
        string $revalidaOauthUrl,
        string $revalidaRedirectUri
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->ssoUrl = $ssoUrl;
        $this->apiUrl = $apiUrl;
        $this->confiabilidadesValidas = $confiabilidadesValidas;
        $this->niveisValidos = $niveisValidos;
        $this->revalidaClientId = $revalidaClientId;
        $this->revalidaClientSecret = $revalidaClientSecret;
        $this->revalidaOauthUrl = $revalidaOauthUrl;
        $this->revalidaRedirectUri = $revalidaRedirectUri;
    }

    /**
     * Realiza a requisição para consulta dos dados do usuário autenticado no gov.br
     * com base no código repassado e retorna para consulta do usuário e acesso.
     *
     * @param string $code
     *
     * @return array
     */
    public function getAuthorizationData(string $code, ?string $redirectUri = null): array
    {
        $postFields = 'grant_type='.self::GRANT_TYPE_AUTH_CODE
            ."&code=$code&redirect_uri="
            .urlencode($this->getRedirectUri() . ($redirectUri ? "?returnUrl=$redirectUri":""));

        $headers = [
            'Content-Type:application/x-www-form-urlencoded',
            'Authorization: Basic '
            .base64_encode($this->getClientId().':'.$this->getClientSecret()),
        ];

        $chToken = curl_init();
        curl_setopt($chToken, CURLOPT_URL, $this->getSsoUrl() . '/token');
        curl_setopt($chToken, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($chToken, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chToken, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($chToken, CURLOPT_HTTPHEADER, $headers);
        $response = json_decode(curl_exec($chToken), true);
        curl_close($chToken);

        return $response;
    }

    /**
     * Fluxo de revalidação de senha do usuário govBr
     *
     * @param string $code
     * @param string $state
     *
     * @return array
     */
    public function getAuthorizationDataRevalida(string $code, string $state): array
    {
        $postFields = 'grant_type=' . self::GRANT_TYPE_AUTH_CODE
            . "&code=$code&redirect_uri="
            . urlencode($this->getRevalidaRedirectUri())
            . "&state=$state&client_id=" . $this->getRevalidaClientId();

        $headers = [
            'Content-Type:application/x-www-form-urlencoded',
            'Authorization: Basic '
                . base64_encode($this->getRevalidaClientId() . ':' . $this->getRevalidaClientSecret()),
        ];

        $chToken = curl_init();
        curl_setopt($chToken, CURLOPT_URL, $this->getRevalidaOauthUrl() . '/token');
        curl_setopt($chToken, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($chToken, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chToken, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($chToken, CURLOPT_HTTPHEADER, $headers);
        $response = json_decode(curl_exec($chToken), true);
        curl_close($chToken);

        return $response;
    }

    /**
     * Realiza a requisição para consulta das confiabilidades do usuário no gov.br.
     *
     * @param string $cpf
     * @param string $accessToken
     *
     * @return array
     */
    public function getConfiabilidadesUsuarioData(string $cpf, string $accessToken): array
    {
        $headers = [
            'Accept: application/json',
            'Authorization: Bearer '.$accessToken,
        ];

        $chToken = curl_init();
        curl_setopt(
            $chToken,
            CURLOPT_URL,
            $this->getApiUrl()."/confiabilidades/v2/contas/$cpf/categorias"
        );
        curl_setopt($chToken, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chToken, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($chToken, CURLOPT_HTTPHEADER, $headers);
        $response = json_decode(curl_exec($chToken), true);
        curl_close($chToken);

        return $response;
    }

    /**
     * Realiza a requisição para consulta dos niveis do usuário no gov.br.
     *
     * @param string $cpf
     * @param string $accessToken
     *
     * @return array
     */
    public function getNiveisUsuarioData(string $cpf, string $accessToken): array
    {
        $headers = [
            'Accept: application/json',
            'Authorization: Bearer '.$accessToken,
        ];

        $chToken = curl_init();
        curl_setopt(
            $chToken,
            CURLOPT_URL,
            $this->getApiUrl()."/confiabilidades/v3/contas/$cpf/niveis?response-type=ids"
        );
        curl_setopt($chToken, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chToken, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($chToken, CURLOPT_HTTPHEADER, $headers);
        $response = json_decode(curl_exec($chToken), true);
        curl_close($chToken);

        return $response;
    }

    /**
     * Realiza a requisição para retorno do jwk para validação dos dados recebidos pelo serviço.
     *
     * @param bool $revalida
     * @return array
     */
    public function getJwkData(bool $revalida = false): array
    {
        $chJwk = curl_init();
        curl_setopt($chJwk, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($chJwk, CURLOPT_URL, (!$revalida ? $this->getSsoUrl() . '/jwk' : $this->getRevalidaOauthUrl() . '/jwks'));
        curl_setopt($chJwk, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($chJwk), true);
        curl_close($chJwk);

        return $response;
    }

    /**
     * Realiza a requisição para retorno da lista de CNPJs do CPF autenticado.
     *
     * @param string $cpf
     * @param string $accessToken
     *
     * @return array
     */
    public function getEmpresas(string $cpf, string $accessToken): array
    {
        $headers = [
            'Accept: application/json',
            'Authorization: Bearer '.$accessToken,
        ];

        $chToken = curl_init();
        curl_setopt(
            $chToken,
            CURLOPT_URL,
            $this->getApiUrl()."/empresas/v2/empresas?filtrar-por-participante=$cpf"
        );
        curl_setopt($chToken, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chToken, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($chToken, CURLOPT_HTTPHEADER, $headers);
        $response = json_decode(curl_exec($chToken), true);
        curl_close($chToken);

        return $response;
    }

    /**
     * Realiza a requisição para retorno dos detalhes do CNPJ do CPF autenticado.
     *
     * @param string $cnpj
     * @param string $cpf
     * @param string $accessToken
     *
     * @return array
     */
    public function getEmpresa(string $cnpj, string $cpf, string $accessToken): array
    {
        $headers = [
            'Accept: application/json',
            'Authorization: Bearer '.$accessToken,
        ];

        $chToken = curl_init();
        curl_setopt(
            $chToken,
            CURLOPT_URL,
            $this->getApiUrl()."/empresas/v2/empresas/$cnpj/participantes/$cpf"
        );
        curl_setopt($chToken, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chToken, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($chToken, CURLOPT_HTTPHEADER, $headers);
        $response = json_decode(curl_exec($chToken), true);
        curl_close($chToken);

        return $response;
    }

    /**
     * Decodifica e valida o token repassado junto as credenciais retornadas.
     *
     * @param string $token
     * @param array  $credentials
     *
     * @return object
     */
    public function decodeToken(string $token, array $credentials): object
    {
        $rsaPublicKey = $this->getRsaPublicKey($credentials);
        $tokenParts = explode('.', $token);
        list($tokenHeaderEncoded, $tokenBodyEncoded, $tokenCryptoEncoded) = $tokenParts;

        if (null === ($header = json_decode((new JoseEncoder())->base64UrlDecode($tokenHeaderEncoded)))) {
            throw new UnexpectedValueException('Encoding do cabeçalho inválido.');
        }
        if (null === ($payload = json_decode((new JoseEncoder())->base64UrlDecode($tokenBodyEncoded)))) {
            throw new UnexpectedValueException('Encoding do body inválido.');
        }

        if (false === ($sig = (new JoseEncoder())->base64UrlDecode($tokenCryptoEncoded))) {
            throw new UnexpectedValueException('Encoding da assinatura inválido.');
        }

        if (empty($header->alg)) {
            throw new UnexpectedValueException('Algoritmo vazio.');
        }

        if (!in_array($header->alg, array_keys(self::$supported_algs))) {
            throw new UnexpectedValueException('Algoritmo da origem de autenticação não suportada.');
        }

        $this->validateSignature("$tokenHeaderEncoded.$tokenBodyEncoded", $sig, $rsaPublicKey, $header->alg);

        $timestamp = time();
        $leeway = 3 * 60;

        if (isset($payload->nbf) && $payload->nbf > ($timestamp + $leeway)) {
            throw new Exception('Não é possível utilizar o token antes de '.\date(\DateTime::ISO8601, $payload->nbf));
        }

        if (isset($payload->iat) && $payload->iat > ($timestamp + $leeway)) {
            throw new Exception('Não é possível utilizar o token antes de '.\date(\DateTime::ISO8601, $payload->iat));
        }

        if (isset($payload->exp) && ($timestamp - $leeway) >= $payload->exp) {
            throw new Exception('Token expirado.');
        }

        return json_decode((new JoseEncoder())->base64UrlDecode($tokenBodyEncoded));
    }

    /**
     * Extrai e retorna a public RSA Key das credenciais retornadas.
     *
     * @param array $credentials
     *
     * @return string
     */
    private function getRsaPublicKey(array $credentials): string
    {
        $modulus = (new JoseEncoder())->base64UrlDecode($credentials['keys'][0]['n']);

        $publicExponent = (new JoseEncoder())->base64UrlDecode($credentials['keys'][0]['e']);
        $components = [
            'modulus' => pack('Ca*a*', 2, $this->encodeLength(strlen($modulus)), $modulus),
            'publicExponent' => pack(
                'Ca*a*',
                2,
                $this->encodeLength(strlen($publicExponent)),
                $publicExponent
            ),
        ];

        $rsaPublicKey = pack(
            'Ca*a*a*',
            48,
            $this->encodeLength(
                strlen($components['modulus']) + strlen($components['publicExponent'])
            ),
            $components['modulus'],
            $components['publicExponent']
        );

        $rsaOID = pack('H*', '300d06092a864886f70d0101010500');

        $rsaPublicKey = chr(0).$rsaPublicKey;
        $rsaPublicKey = chr(3).$this->encodeLength(strlen($rsaPublicKey)).$rsaPublicKey;
        $rsaPublicKey = pack(
            'Ca*a*',
            48,
            $this->encodeLength(strlen($rsaOID.$rsaPublicKey)),
            $rsaOID.$rsaPublicKey
        );

        $rsaPublicKey = "-----BEGIN PUBLIC KEY-----\r\n"
            .chunk_split(base64_encode($rsaPublicKey), 64)
            .'-----END PUBLIC KEY-----';

        return $rsaPublicKey;
    }

    /**
     * Método que verifica a assinatura das credenciais retornadas.
     *
     * @param string $body
     * @param string $signature
     * @param string $rsaPublicKey
     * @param string $alg
     */
    protected function validateSignature(string $body, string $signature, string $rsaPublicKey, string $alg): void
    {
        list($function, $algorithm) = self::$supported_algs[$alg];

        switch ($function) {
            case 'openssl':
                $success = \openssl_verify($body, $signature, $rsaPublicKey, $algorithm);
                if (0 === $success) {
                    throw new Exception('Assinatura do certificado inválida.');
                } elseif (-1 === $success) {
                    throw new \DomainException('OpenSSL error: '.\openssl_error_string());
                }
                break;
            case 'hash_hmac':
            default:
                $hash = \hash_hmac($algorithm, $body, $rsaPublicKey, true);
                if (\function_exists('hash_equals')) {
                    if (!\hash_equals($signature, $hash)) {
                        throw new Exception('Assinatura do certificado inválida.');
                    }
                }
                $len = \min(mb_strlen($signature), mb_strlen($hash));

                $status = 0;
                for ($i = 0; $i < $len; ++$i) {
                    $status |= (\ord($signature[$i]) ^ \ord($hash[$i]));
                }
                $status |= (mb_strlen($signature) ^ mb_strlen($hash));

                if (0 === !$status) {
                    throw new Exception('Assinatura do certificado inválida.');
                }
        }
    }

    /**
     * Returns encoded length.
     *
     * @param int $length
     *
     * @return string
     */
    private function encodeLength(int $length): string
    {
        if ($length <= 0x7F) {
            return chr($length);
        }

        $temp = ltrim(pack('N', $length), chr(0));

        return pack('Ca*', 0x80 | strlen($temp), $temp);
    }

    /**
     * Retorna os dados do usuário dos serviços do gov.br encapsulados no DTO.
     *
     * @param string $code
     *
     * @return array|null
     *
     * @throws Exception
     */
    public function retornaDadosUsuario(string $code, ?string $redirectUri = null): ?array
    {
        $authData = $this->getAuthorizationData($code, $redirectUri);
        if ($this->responseHasError($authData)) {
            return null;
        }
        $jwkData = $this->getJwkData();

        $decodedToken = $this->decodeToken($authData['id_token'], $jwkData);

        return [
            'username' => $decodedToken->sub,
            'nome' => $decodedToken->name,
            'email' => $decodedToken->email,
            'access_token' => $authData['access_token'],
            'id_token' => $authData['id_token'],
        ];
    }

    /**
     * Revalida a assinatura do usuário govBr.
     *
     * @param string $code
     * @param string $state
     *
     * @return array|null
     *
     * @throws Exception
     */
    public function retornaDadosRevalida(string $code, string $state): ?string
    {
        $authData = $this->getAuthorizationDataRevalida($code, $state);
        if ($this->responseHasError($authData)) {
            throw new Exception('Revalidação de senha expirada, por favor repita o processo.');
        }

        return $authData['access_token'];
    }

    /**
     * Confere se o token de revalidação continua valido
     * pois ele pode ser repassado em várias chamadas de assinatura em lote
     * 
     * @param string $accessToken gerado na chamada revalidação de senha
     * @param string $usernameValido cpf do usuário logado para conferir se é o mesmo usado na revalidação de senha
     * 
     * @return bool
     */
    public function decodeTokenRevalida(string $accessToken, string $usernameValido): bool
    {
        
        // true pra recuperar chave pública do serviço de revalidação de senha
        $jwkData = $this->getJwkData(true);

        $decodedToken = $this->decodeToken($accessToken, $jwkData);

        return $decodedToken->sub == $usernameValido;
    }

    /**
     * Valida as niveis.
     *
     * @param array $niveisUsuario
     *
     * @return bool
     */
    public function validaNiveisUsuario(array $niveisUsuario): bool
    {
        $niveisValidos = $this->getNiveisValidos();

        foreach ($niveisUsuario as $nivel) {
            if (in_array($nivel['id'], $niveisValidos)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Valida erro de retorno das consultas ao gov.br.
     *
     * @param array $result
     *
     * @return bool
     */
    private function responseHasError(array $result): bool
    {
        return empty($result) || ('invalid_grant' == isset($result['error']) || isset($result['errors']));
    }
}
