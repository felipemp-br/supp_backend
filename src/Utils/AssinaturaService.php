<?php

declare(strict_types=1);

/**
 * /src/Utils/AssinaturaService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

use CurlHandle;
use DateInterval;
use DateTime;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use JsonException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ramsey\Uuid\Uuid as Ruuid;
use Random\RandomException;
use Redis;
use RedisException;
use ReflectionException;
use RuntimeException;
use SensitiveParameter;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura as AssinaturaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AssinaturaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoNotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\EARQ\EARQEventoPreservacaoLoggerInterface;
use SuppCore\AdministrativoBackend\Entity\Assinatura;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento;
use SuppCore\AdministrativoBackend\Enums\AssinaturaPadrao;
use SuppCore\AdministrativoBackend\Enums\AssinaturaProtocolo;
use SuppCore\AdministrativoBackend\Helpers\AssinaturaHelper;
use SuppCore\AdministrativoBackend\Helpers\AssinaturaLogHelper;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\Repository\ModalidadeNotificacaoRepository;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Security\LdapService;
use SuppCore\AdministrativoBackend\Security\LoginUnicoGovBrService;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Throwable;

use function array_key_exists;
use function count;
use function is_string;
use function strlen;

/**
 * Class AssinaturaService.
 */
class AssinaturaService
{
    /**
     * @param UsuarioResource $usuarioResource
     * @param AssinaturaResource $assinaturaResource
     * @param ComponenteDigitalResource $componenteDigitalResource
     * @param DocumentoResource $documentoResource
     * @param TransactionManager $transactionManager
     * @param ResponseHandler $responseHandler
     * @param TokenStorageInterface $tokenStorage
     * @param SuppParameterBag $parameterBag
     * @param JWTTokenManagerInterface $JWTManager
     * @param HubInterface $hub
     * @param Redis $redisClient
     * @param EARQEventoPreservacaoLoggerInterface $eventoPreservacaoLogger
     * @param UserPasswordHasherInterface $passwordHasher
     * @param LdapService $ldapService
     * @param LoginUnicoGovBrService $loginUnicoGovBrService
     * @param AssinaturaLogHelper $logger
     * @param PDFToolsInterface $pdfTools
     * @param NotificacaoResource $notificacaoResource
     * @param ModalidadeNotificacaoRepository $modalidadeNotificacaoRepository
     * @param TipoNotificacaoResource $tipoNotificacaoResource
     * @param SuppParameterBag $suppParameterBag
     * @param AssinaturaHelper $assinaturaHelper
     */
    public function __construct(
        protected readonly UsuarioResource $usuarioResource,
        protected readonly AssinaturaResource $assinaturaResource,
        protected readonly ComponenteDigitalResource $componenteDigitalResource,
        protected readonly DocumentoResource $documentoResource,
        protected readonly TransactionManager $transactionManager,
        protected readonly ResponseHandler $responseHandler,
        protected readonly TokenStorageInterface $tokenStorage,
        protected readonly SuppParameterBag $parameterBag,
        protected readonly JWTTokenManagerInterface $JWTManager,
        protected readonly HubInterface $hub,
        protected readonly Redis $redisClient,
        protected readonly EARQEventoPreservacaoLoggerInterface $eventoPreservacaoLogger,
        protected readonly UserPasswordHasherInterface $passwordHasher,
        protected readonly LdapService $ldapService,
        protected readonly LoginUnicoGovBrService $loginUnicoGovBrService,
        protected readonly AssinaturaLogHelper $logger,
        protected readonly PDFToolsInterface $pdfTools,
        protected readonly NotificacaoResource $notificacaoResource,
        protected readonly ModalidadeNotificacaoRepository $modalidadeNotificacaoRepository,
        protected readonly TipoNotificacaoResource $tipoNotificacaoResource,
        protected readonly SuppParameterBag $suppParameterBag,
        protected readonly AssinaturaHelper $assinaturaHelper,
    ) {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
    }

    /**
     * Passo a passo da assinatura NEOID
     * 1. O cliente gera um codigo interno (code_verifier), que não será exposto, e que identifica o pedido de
     *  assinatura que será realizado. Ele também gera o hash deste código interno:.
     *
     *            code_challenge = hash(code_verifier)
     *
     *  Mais à frente o cliente será instado a comprovar que ele possui o code_verifier para o code_challenge que ele
     *  apresentou.
     *
     * 2. O cliente invoca em uma janela avulsa (um popup) uma URL que cairá dentro do sistema do NEOID. Esta
     * URL possui o seguinte formato:
     *
     *      baseUrl + '/authorize?'
     *       + 'client_id='       + client_id       => identificador do sistema de origem (cliente) cadastrado no neoId
     *       + '&login_hint='     + cpf             => identificador do portador do certificado digital que assinará
     *       + '&scope='          + tipoAssinatura  => single_signature / multi_signature / signature_session
     *       + '&redirect_uri='   + redirect_uri    => uri de redirecionamento que cairá dentro do frontend do sistema
     *       + '&code_challenge=' + code_challenge  => identificador do pedido de assinatura gerado mascarado
     *       + '&response_type=code'
     *       + '&code_challenge_method=S256'
     *       + '&state=authorize_neoid'
     *
     * 3. Um QRCODE será exibido na tela do usuário. O aplicativo do usuário ao scanear o QRCODE fará o bind do
     * pedido de assinatura com o seu dispositivo criptogrático específico e pré-autorizará o pedido de assinatura.
     * Ao pré-autorizar o pedido de assinatura, será enviada uma notificação ao sistema do NEOID que encerrará
     * a janela aberta e carregará a URI que o usuário informou. Será passado nesta URI o identificador do pedido
     * de assinatura mascado (code_challenge). Ou seja, nesta etapa, o sistema do NEOID informa que: a) foi feito
     * com sucesso o bind entre o pedido de assinatura e o dispositivo criptográfico; b) foi pré-autorizado o pedido
     * de assinatura identificado pelo code_challenge. Neste instante o usuário poderá assinar.
     *
     * 4. A assinatura é realizada através de duas etapas. O cliente comprova que foi ele que realizou aquele pedido
     * de assinatura pré-autorizado (apresenta o code_challenge e o code_verifier) e com isto recebe um token JWT.
     * Com o token JWT ele realiza a assinatura que pretende enviando o hash dos arquivos para assinar e o tipo de
     * assinatura que pretende.
     *
     * 5. Para comprovar que foi ele que realizou o pedido ele invoca a seguinte URL:
     * baseUrl + '/token?'
     * + 'grant_type=authorization_code'
     * + '&client_id='         + cliend_id           => identificador do sistema de origem (cliente) cadastrado no neoId
     * + '&client_secret='     + client_secret       => senha do sistema de origem (cliente) cadastrado no neoId
     * + '&code='              + code                => identificador do pedido de assinatura
     * + '&code_verifier='     + code_verifier       => identificador do pedido de assinatura mascarado
     * + '&redirect_uri='      + neoid_redirect_uri  => a mesma URO de redirect informada anteriormente
     *
     * 6. Como resposta desta requisição ele receberá um token JWT. A próxima requisição solicitará a assinatura dos
     * arquivos usando o token JWT e terá a seguinte forma:
     *
     *  baseUrl + '/signature'
     *      Content-Type: application/json
     *      Authorization: Bearer
     *
     * Corpo da requisição (em JSON):
     *  {
     *      'hashes' : [
     *          {
     *              'id'                => componente_digital _id
     *              'alias'             => 'componente_digital_' + componente_digital _id,
     *              'hash'              => base64_encode(hash('SHA256', conteudo)),
     *              'hash_algorithm'    => '2.16.840.1.101.3.4.2.1',
     *              'signature_format'  => 'CMS',
     *          },
     *          ...
     *      ]
     * }
     *
     * Corpo da resposta:
     * {
     *      'signatures' : [
     *          {
     *              'id': ...,
     *              'raw_signature': ...
     *          }
     *      ]
     * }
     *
     * 7. Os certificados são recuperados através de uma chama à API propria, a saber:
     *  baseUrl + '/certificate-discovery'
     *
     *      Content-Type: application/json
     *      Authorization: Bearer
     */

    /**
     * @throws Exception
     */
    public static function generateRandomString(int $length = 32): string
    {
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        for ($i = 0, $text = ''; $i < $length; ++$i) {
            $text .= $str[random_int(0, strlen($str) - 1)];
        }

        return $text;
    }

    /**
     * <pre>
     * Baseado na documentação do SerproID
     *      https://serproid.serpro.gov.br/manual-integracao/autorizacao/1-codigo-autorizacao/
     *
     * code_verifier conforme a RFC-7636
     *
     * RFC-7636 section-4.1 Client Creates a Code Verifier:
     *      code_verifier = high-entropy cryptographic random STRING using the
     *      unreserved characters from Section 2.3 of [RFC-3986], with a minimum length of 43 characters
     *      and a maximum length of 128 characters.
     *
     *
     * RFC-3986 section-2.3 Unreserved Characters:
     *      Characters that are allowed in a URI:  [A-Z] / [a-z] / [0-9] / "-" / "." / "_" / "~"
     *</pre>
     *
     * @param int $length
     * @return string
     * @throws RandomException
     */
    public function generateCodeVerifier(int $length = 43): string
    {
        // mínimo de 43 caracteres e máximo 128
        if ($length < 43 || $length > 128) {
            $length = random_int(43, 128);
        }

        // 66 caracteres válidos [A-Z] / [a-z] / [0-9] / "-" / "." / "_" / "~"
        $unreservedCharacters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-._~';
        $text = '';
        for ($i = 0; $i < $length; ++$i) {
            $text .= $unreservedCharacters[random_int(0, 65)];
        }

        return $text;
    }

    /**
     * <pre>
     * code_challenge conforme a RFC-7636
     *
     * RFC-7636 section-4.2 Client Creates the Code Challenge:
     *      code_challenge = BASE64URL-ENCODE(SHA256(ASCII(code_verifier)))
     *</pre>
     *
     * @param string $codeVerifier
     * @return string
     */
    public function generateCodeChallenge(string $codeVerifier): string
    {
        // Aplica o hash SHA-256 ao code_verifier
        $codeVerifierHashed = hash('sha256', $codeVerifier, true);

        // Codifica o hash resultante em Base64URL-safe
        return rtrim(strtr(base64_encode($codeVerifierHashed), '+/', '-_'), '=');
    }

    /**
     * Criar assinatura.
     *
     * @param string $credential
     * @param array $documentos Pode ser array de inteiros (ID) ou Documento
     * @param Usuario $usuario
     * @param bool $pades
     * @param bool $incluiAnexos
     * @param string $authType
     * @return Assinatura[]
     *
     * @throws NonUniqueResultException
     * @throws Throwable
     */
    public function sign(
        #[SensitiveParameter] string $credential,
        array $documentos,
        Usuario $usuario,
        bool $pades = false,
        bool $incluiAnexos = false,
        string $authType = ''
    ): array {
        $componentesDigitais = $this->componentesDigitais($documentos, $incluiAnexos);
        // Verificar se precisa converter HTML para PDF
        // $this->convertToPdf($componentesDigitais, $pades);

        // verifica a credencial apresentada
        // a1:password
        $parts = array_map(static fn ($p) => urldecode($p), explode(':', $credential));
        if ('a1' === mb_strtolower($parts[0])) {
            /* @noinspection PhpUnusedLocalVariableInspection */
            [$protocol, $password] = $parts;

            return $this->internalSignA1($usuario, $componentesDigitais, $pades, $authType);
        }

        // verifica a credencial apresentada
        // a3:processUuid
        if ('a3' === mb_strtolower($parts[0])) {
            /* @noinspection PhpUnusedLocalVariableInspection */
            [$protocol, $processUuid] = $parts;

            return $this->externalSignA3($usuario, $processUuid, $componentesDigitais, $pades);
        }

        // verifica a credencial apresentada
        // neoid:urlencoded(code):urlencoded(code_verifier)
        if ('neoid' === mb_strtolower($parts[0])) {
            /* @noinspection PhpUnusedLocalVariableInspection */
            [$protocol, $code, $codeVerifier] = $parts;

            return $this->internalSignNeoId($usuario, $code, $codeVerifier, $componentesDigitais, $pades);
        }

        return [];
    }


    /**
     * Recebe a credencial no formato neoid:[UUID - cód.Autorização]
     * e retorna neoid:[UUID - cód.Autorização]:[code verifier]
     *
     * @param Usuario $usuario
     * @param string $credential
     * @return string
     * @throws Exception
     */
    public function checkNeoIdCredential(Usuario $usuario, string $credential): string
    {
        $parts = explode(':', $credential);
        // Código de autorização  neoid:[UUID - cód.Autorização]
        $code = $parts[1] ?? null;
        try {
            // O neoIdQrCode(), chamado no início, grava no Redis uma estrutura com codeVerifier e codeChallenge
            $cachedData = $this->redisClient->get(__CLASS__.':NEOID:'.$usuario->getUsername());
            $cachedData = $cachedData ?
                json_decode($cachedData, true, 512, JSON_THROW_ON_ERROR) :
                throw new Exception('Credenciais não encontradas para assinatura NeoId');

            // Solicitar Access Token ou recuperar do Redis
            // É um e somente um Access Token por código de autorização
            $this->internalTokenNeoId($code, $cachedData['codeVerifier']);

            return "neoid:$code:$cachedData[codeVerifier]";
        } catch (RedisException) {
            throw new RuntimeException(
                'Credenciais não encontradas para assinatura NeoId. Problema no Banco de Dados NonSQL!'
            );
        }
    }

    /**
     * @param Usuario $usuario
     * @param bool $singleSignature
     * @return RedirectResponse
     * @throws Exception
     */
    public function neoIdQrCode(Usuario $usuario, bool $singleSignature = false): RedirectResponse
    {
        $codeVerifier = $this->generateCodeVerifier();
        $codeChallenge = $this->generateCodeChallenge($codeVerifier);

        $this->redisClient->set(
            __CLASS__.':NEOID:'.$usuario->getUsername(),
            json_encode(compact('codeVerifier', 'codeChallenge'), JSON_THROW_ON_ERROR)
        );

        $this->redisClient->expire(__CLASS__.':NEOID:'.$usuario->getUsername(), 360);

        $scope = $singleSignature ? 'single_signature' : 'signature_session';
        $url = $this->parameterBag->get('supp_core.administrativo_backend.neoid_url').'/authorize/?'
            .'response_type=code'
            .'&client_id='.$this->parameterBag->get('supp_core.administrativo_backend.neoid_client_id')
            .'&code_challenge='.$codeChallenge
            .'&code_challenge_method=S256'
            .'&redirect_uri='.$this->parameterBag->get('supp_core.administrativo_backend.neoid_redirect_uri')
            .'&scope='.$scope
            .'&state=authorize_neoid'
            .'&login_hint='.$usuario->getUsername();

        return new RedirectResponse($url);
    }

    /**
     * @param Usuario $usuario
     * @param array   $componentesDigitais
     * @param bool    $pades
     * @param string  $authType
     *
     * @return array|Assinatura[]
     *
     * @throws Throwable
     */
    protected function internalSignA1(
        Usuario $usuario,
        array $componentesDigitais,
        bool $pades = false,
        string $authType = ''
    ): array {
        if ($pades) {
            return $this->internalSignA1Pades($usuario, $componentesDigitais, $authType);
        }

        return $this->internalSignA1Cades($usuario, $componentesDigitais);
    }

    /**
     * Assinatura com certificado interno A1 e política CAdES.
     *
     * @param Usuario $usuario
     * @param array $componentesDigitais
     * @return array assinaturas
     * @throws Throwable
     */
    protected function internalSignA1Cades(Usuario $usuario, array $componentesDigitais): array
    {
        if (!$componentesDigitais) {
            return [];
        }

        $assinaturas = [];

        $password = $this->parameterBag->get(
            'supp_core.administrativo_backend.certificado_a1_institucional_password'
        );

        $certPathPfx = $this->parameterBag->get(
            'supp_core.administrativo_backend.certificado_a1_institucional_pfx'
        );

        $signerProxyParams = [];
        $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');

        if ($signerProxy) {
            $signerProxyParams = explode(' ', $signerProxy);
        }

        $assinaturaConfig = $this->getAssinaturaConfig(AssinaturaPadrao::CAdES);

        foreach ($componentesDigitais as $componenteDigital) {
            $transactionId = null;
            try {
                $hash = $componenteDigital->getHash();

                $params = [
                    'java',
                    '-Duser.timezone=America/Sao_Paulo',
                    '-jar',
                    '/usr/local/bin/supp-signer.jar',
                    '--mode=sign',
                    '--certificate='.$certPathPfx,
                    '--password='.$password,
                    '--hash='.$hash,
                    $assinaturaConfig['test'] ? '--test' : '',
                ];

                $process = new Process(
                    array_merge($params, $signerProxyParams)
                );

                try {
                    $process->run();
                } catch (Throwable $t) {
                    $this->logger->critical(
                        preg_replace("/--password=([^']*)'/i", "--password=****'", $t->getMessage())
                    );

                    throw new RuntimeException(
                        'SUPP-Signer: Indisponibilidade temporária. '
                        .'O processo de assinatura CAdES não foi concluído. '
                        .'Por favor, aguarde e tente novamente mais tarde!'
                    );
                }

                $filenameSign = '/tmp/'.$hash.'.p7s';
                $filenamePem = '/tmp/'.$hash.'.pem';
                $filenameDer = '/tmp/'.$hash.'.der';

                // executes after the command finishes
                if ($process->isSuccessful()) {
                    $signature = file_get_contents($filenameSign);
                    unlink($filenameSign);
                    $cadeiaPEM = file_get_contents($filenamePem);
                    unlink($filenamePem);
                    $cadeiaDER = file_get_contents($filenameDer);
                    unlink($filenameDer);

                    $this->eventoPreservacaoLogger->logEPRES3AssinaturaValida($componenteDigital);
                } else {
                    $this->eventoPreservacaoLogger->logEPRES3AssinaturaInvalida($componenteDigital);
                    if (is_file($filenameSign)) {
                        unlink($filenameSign);
                    }
                    if (is_file($filenamePem)) {
                        unlink($filenamePem);
                    }
                    if (is_file($filenameDer)) {
                        unlink($filenameDer);
                    }
                    // Ler o erro que o Log4J escreveu no console
                    $erro = 'Command: '.$process->getCommandLine()."\n\nError: ".$process->getOutput();
                    $this->logger->critical(preg_replace("/--password=([^']*)'/i", "--password=****'", $erro));

                    throw new RuntimeException('SUPP-Signer: Erro ao criar assinatura!');
                }

                if (empty($signature)) {
                    throw new RuntimeException(
                        'Falha na assinatura do componente digital, ID:'
                        .$componenteDigital->getId()
                        .'. Erro ao ler o arquivo (*.p7s)'
                    );
                }
                if (empty($cadeiaPEM)) {
                    throw new RuntimeException(
                        'Falha na assinatura do componente digital, ID:'
                        .$componenteDigital->getId()
                        .' Erro ao ler o arquivo (*.pem)'
                    );
                }
                if (empty($cadeiaDER)) {
                    throw new RuntimeException(
                        'Falha na assinatura do componente digital, ID:'
                        .$componenteDigital->getId()
                        .' Erro ao ler o arquivo (*.der)'
                    );
                }

                $transactionId = $this->transactionManager->beginNewTransaction();
                // Assinatura em A1
                $assinaturaDTO = new AssinaturaDTO();
                $assinaturaDTO->setComponenteDigital($componenteDigital);
                $assinaturaDTO->setAssinatura(base64_encode($signature));
                $assinaturaDTO->setCadeiaCertificadoPEM($cadeiaPEM);
                $assinaturaDTO->setCadeiaCertificadoPkiPath($cadeiaDER);
                $assinaturaDTO->setAlgoritmoHash('SHA256withRSA');
                $assinaturaDTO->setPadrao(AssinaturaPadrao::CAdES->value);
                $assinaturaDTO->setProtocolo(AssinaturaProtocolo::A1->value);
                $this->checkChavePublica($assinaturaDTO);

                $assinaturas[] = $assinatura = $this->assinaturaResource->create($assinaturaDTO, $transactionId);
                $assinatura->setCriadoPor($usuario);

                $this->transactionManager->commit($transactionId);

                // Avisar sobre o progresso das assinaturas via Mercure
                $this->publishOnMercure(
                    $usuario->getUsername(),
                    'SIGN_PROGRESS',
                    'Assinatura do componente digital ID '.$componenteDigital->getId().' concluída',
                    $componenteDigital->getId()
                );
            } catch (Throwable $throwable) {
                if (!empty($transactionId)) {
                    $this->transactionManager->resetTransaction($transactionId);
                }

                throw $throwable;
            }
        }

        return $assinaturas;
    }

    /**
     *  Assinatura com certificado interno A1 e política PAdES.
     *
     * @param Usuario $usuario
     * @param ComponenteDigital[] $componentesDigitais
     * @param string $authType
     * @return Assinatura[] assinaturas
     * @throws Throwable
     */
    protected function internalSignA1Pades(Usuario $usuario, array $componentesDigitais, string $authType = ''): array
    {
        if (!$componentesDigitais) {
            return [];
        }

        $assinaturas = [];

        $password = $this->parameterBag->get(
            'supp_core.administrativo_backend.certificado_a1_institucional_password'
        );

        $certPathPfx = $this->parameterBag->get(
            'supp_core.administrativo_backend.certificado_a1_institucional_pfx'
        );

        $signerProxyParams = [];
        $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');

        if ($signerProxy) {
            $signerProxyParams = explode(' ', $signerProxy);
        }

        $assinaturaConfig = $this->getAssinaturaConfig(AssinaturaPadrao::PAdES);

        // para o carimbo/chancela
        // $usuario = $this->tokenStorage->getToken()->getUser();
        $cpfUsuario = $usuario->getUserIdentifier();
        $nomeUsuario = $usuario->getNome();

        foreach ($componentesDigitais as $componenteDigital) {
            $transactionId = null;
            try {
                $transactionId = $this->transactionManager->beginNewTransaction();

                // Recupera o conteúdo do PDF
                $pdfContent = $this->componenteDigitalResource->download(
                    $componenteDigital->getId(),
                    $transactionId
                )?->getConteudo();

                if (empty($pdfContent)) {
                    throw new RuntimeException('Erro ao recuperar o conteúdo do PDF!');
                }

                // Salva o PDF no fileSystem
                $hash = hash('SHA256', $pdfContent);
                file_put_contents('/tmp/'.$hash.'.pdf', $pdfContent);

                $params = [
                    'java',
                    '-Duser.timezone=America/Sao_Paulo',
                    '-Dfile.encoding=UTF8',
                    '-jar',
                    '/usr/local/bin/supp-signer.jar',
                    '--mode=pdf-sign',
                    '--certificate='.$certPathPfx,
                    '--password='.$password,
                    '--texto=Assinatura Digital Institucional',
                    '--nome='.base64_encode($nomeUsuario),
                    '--cpf='.$cpfUsuario,
                    '--authType='.$authType,
                    // VR-TD (Vertical Right - Top Down),
                    // VL-BU (Vertical Left - Bottom Up),
                    // HB-LR (Horizontal Bottom - Left Right)
                    '--orientation='.$assinaturaConfig['orientation'],
                    '--visible='.($assinaturaConfig['visible'] ? 'true' : 'false'),
                    '--imageBase64='.(
                        array_key_exists('imageBase64', $assinaturaConfig)
                        ? $assinaturaConfig['imageBase64'] : ''
                    ),
                    '--hash='.$hash,
                    $assinaturaConfig['test'] ? '--test' : '',
                ];

                $process = new Process(array_merge($params, $signerProxyParams));

                // existem no kibana casos raros de timeOut no run()
                try {
                    $process->run();
                } catch (Throwable $t) {
                    $this->logger->critical(
                        preg_replace("/--password=([^']*)'/i", "--password=****'", $t->getMessage())
                    );

                    throw new RuntimeException(
                        'SUPP-Signer: Indisponibilidade temporária. '
                        .'O processo de assinatura PAdES não foi concluído. '
                        .'Por favor, aguarde e tente novamente mais tarde!'
                    );
                }

                $filenamePdf = '/tmp/'.$hash.'.pdf';
                $filenameSign = '/tmp/'.$hash.'.p7s';
                $filenamePem = '/tmp/'.$hash.'.pem';
                $filenameDer = '/tmp/'.$hash.'.der';

                // se o supp-signer terminou com sucesso
                if ($process->isSuccessful()) {
                    $pdfContent = file_get_contents($filenamePdf);
                    unlink($filenamePdf);
                    $signature = file_get_contents($filenameSign);
                    unlink($filenameSign);
                    $cadeiaPEM = file_get_contents($filenamePem);
                    unlink($filenamePem);
                    $cadeiaDER = file_get_contents($filenameDer);
                    unlink($filenameDer);
                    $this->eventoPreservacaoLogger->logEPRES3AssinaturaValida($componenteDigital);

                    $conteudoBase64 = base64_encode($pdfContent);
                } else {
                    $this->eventoPreservacaoLogger->logEPRES3AssinaturaInvalida($componenteDigital);
                    if (is_file($filenamePdf)) {
                        unlink($filenamePdf);
                    }
                    if (is_file($filenameSign)) {
                        unlink($filenameSign);
                    }
                    if (is_file($filenamePem)) {
                        unlink($filenamePem);
                    }
                    if (is_file($filenameDer)) {
                        unlink($filenameDer);
                    }
                    $erro = 'Command: '.$process->getCommandLine()."\n\nError: ".$process->getOutput();
                    $this->logger->critical(preg_replace("/--password=([^']*)'/i", "--password=****'", $erro));
                    throw new RuntimeException('SUPP-Signer: Erro ao criar assinatura!');
                }

                if (empty($pdfContent)) {
                    throw new RuntimeException(
                        'Falha na assinatura do componente digital, ID:'
                        .$componenteDigital->getId().'. Erro ao ler o arquivo (*.PDF)'
                    );
                }

                if (empty($signature)) {
                    throw new RuntimeException(
                        'Falha na assinatura do componente digital, ID:'
                        .$componenteDigital->getId().'. Erro ao ler o arquivo (*.p7s)'
                    );
                }

                if (empty($cadeiaPEM)) {
                    throw new RuntimeException(
                        'Falha na assinatura do componente digital, ID:'
                        .$componenteDigital->getId().' Erro ao ler o arquivo (*.pem)'
                    );
                }

                if (empty($cadeiaDER)) {
                    throw new RuntimeException(
                        'Falha na assinatura do componente digital, ID:'
                        .$componenteDigital->getId().' Erro ao ler o arquivo (*.der)'
                    );
                }

                // Atualizar o componente digital com PDF assinado
                /** @var ComponenteDigitalDTO $componenteDigitalDto */
                $componenteDigitalDto = $this->componenteDigitalResource->getDtoForEntity(
                    $componenteDigital->getId(),
                    ComponenteDigitalDTO::class
                );
                $componenteDigitalDto->setHashAntigo($componenteDigital->getHash());
                $componenteDigitalDto->setConteudo('data:application/pdf;base64,'.$conteudoBase64);

                $this->componenteDigitalResource->update(
                    $componenteDigital->getId(),
                    $componenteDigitalDto,
                    $transactionId,
                    true
                );

                // Assinatura
                $assinaturaDTO = new AssinaturaDTO();
                $assinaturaDTO->setComponenteDigital($componenteDigital);
                $assinaturaDTO->setAssinatura(base64_encode($signature));
                $assinaturaDTO->setCadeiaCertificadoPEM($cadeiaPEM);
                $assinaturaDTO->setCadeiaCertificadoPkiPath($cadeiaDER);
                $assinaturaDTO->setAlgoritmoHash('SHA256withRSA');
                $assinaturaDTO->setPadrao(AssinaturaPadrao::PAdES->value);
                $assinaturaDTO->setProtocolo(AssinaturaProtocolo::A1->value);
                $this->checkChavePublica($assinaturaDTO);

                $assinaturas[] = $assinatura = $this->assinaturaResource->create($assinaturaDTO, $transactionId);
                $assinatura->setCriadoPor($usuario);

                $this->transactionManager->commit($transactionId);

                // Avisar sobre o progresso das assinaturas via Mercure
                $this->publishOnMercure(
                    $usuario->getUsername(),
                    'SIGN_PROGRESS',
                    'Assinatura do componente digital ID '.$componenteDigital->getId().' concluída',
                    $componenteDigital->getId()
                );
            } catch (Throwable $throwable) {
                if (!empty($transactionId)) {
                    $this->transactionManager->resetTransaction($transactionId);
                }

                throw $throwable;
            }
        }

        return $assinaturas;
    }

    /**
     * Remove todas as assinaturas PAdES de um PDF.
     *
     * @param array $arrayDocumentosId
     * @param string $transactionId
     *
     * @return array
     *
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function removeAllSignaturesPades(array $arrayDocumentosId, string $transactionId): array
    {
        // $usuario = $this->tokenStorage->getToken()->getUser();
        $retorno = [];
        $componentesDigitaisEntity = $this->componentesDigitais($arrayDocumentosId, true);
        // Recupera a configuração das assinaturas
        $assinaturaConfig = $this->getAssinaturaConfig(AssinaturaPadrao::PAdES);
        foreach ($componentesDigitaisEntity as $componenteDigitalEntity) {
            // Rules
            if (mb_strtolower('application/pdf') !== mb_strtolower(trim($componenteDigitalEntity->getMimetype()))) {
                throw new RuntimeException('O arquivo não é um PDF. Não contém assinatura tipo PAdES!');
            }
            if (null !== $componenteDigitalEntity->getDocumento()?->getJuntadaAtual()) {
                throw new RuntimeException('Não é permitido excluir assinatura após a juntada do documento!');
            }
            if (!$componenteDigitalEntity->getAssinaturas()->isEmpty()) {
                throw new RuntimeException('Não é permitido excluir assinatura PAdES após uma assinatura CAdES!');
            }

            try {
                /** @var ComponenteDigitalDTO $componenteDigitalDto */
                $componenteDigitalDto = $this->componenteDigitalResource->getDtoForEntity(
                    $componenteDigitalEntity->getId(),
                    ComponenteDigitalDTO::class
                );

                // $pdfContent = $componenteDigitalDto->getConteudo();
                $pdfContent = $this->componenteDigitalResource->download(
                    $componenteDigitalEntity->getId(),
                    $transactionId
                )?->getConteudo();
                // $pdfContent = $this->pdfTools->removeAllSignatures($pdfContent);

                $signerProxyParams = [];
                $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');

                if ($signerProxy) {
                    $signerProxyParams = explode(' ', $signerProxy);
                }

                // insere o PDF no fileSystem
                $hash = hash('SHA256', $pdfContent);
                file_put_contents('/tmp/'.$hash.'.pdf', $pdfContent);

                // remove assinatura PAdES, em ambiente dev não faz validação
                $params = [
                    'java',
                    '-Duser.timezone=America/Sao_Paulo',
                    '-Dfile.encoding=UTF8',
                    '-jar',
                    '/usr/local/bin/supp-signer.jar',
                    '--mode=pdf-remove-sign',
                    // '--cpf='.$usuario->getUserIdentifier(),
                    '--hash='.$hash,
                    // Para remover sem erros uma assinatura feita em modo teste,
                    // é necessário que a remoção seja feita em modo teste.
                    $assinaturaConfig['test'] ? '--test' : '',
                ];

                $process = new Process(array_merge($params, $signerProxyParams));

                // existem no kibana casos raros de timeOut no run()
                try {
                    $process->run();
                } catch (Throwable $t) {
                    $this->logger->critical($t->getMessage());

                    throw new RuntimeException(
                        'SUPP-Signer: Indisponibilidade temporária. '
                        .'O processo de exclusão da assinatura PAdES não foi concluído. '
                        .'Por favor, aguarde e tente novamente mais tarde!'
                    );
                }

                $filenamePdf = '/tmp/'.$hash.'.pdf';
                // se o supp-signer executou com sucesso
                if ($process->isSuccessful()) {
                    $pdfContent = file_get_contents($filenamePdf);
                    unlink($filenamePdf);

                    if (empty($pdfContent)) {
                        throw new RuntimeException(
                            'Falha na exclusão da assinatura PAdES do componente digital, ID:'
                            .$componenteDigitalDto->getId()
                            .'. Erro ao ler o arquivo (*.PDF)'
                        );
                    }
                } else {
                    if (is_file($filenamePdf)) {
                        unlink($filenamePdf);
                    }
                    $output = $process->getOutput();
                    $this->logger->critical('Command: '.$process->getCommandLine()."\n\nError: ".$output);

                    $outErrorPos = strpos($output, 'OutError:');
                    if (false !== $outErrorPos) {
                        throw new RuntimeException(substr($output, $outErrorPos + 9));
                    }

                    throw new RuntimeException('SUPP-Signer: Erro ao excluir assinatura PAdES!');
                }

                $componenteDigitalDto->setHashAntigo($componenteDigitalDto->getHash());
                $componenteDigitalDto->setConteudo('data:application/pdf;base64,'.base64_encode($pdfContent));

                $this->componenteDigitalResource->update(
                    $componenteDigitalDto->getId(),
                    $componenteDigitalDto,
                    $transactionId
                );

                $retorno[] = ['id' => $componenteDigitalDto->getId()];
            } catch (Throwable $t) {
                throw new RuntimeException($t->getMessage());
            }
        }

        return $retorno;
    }

    /**
     * Remove todas as assinaturas PAdES de um PDF e retorna o id do componente digital que teve a assinatura removida.
     *
     * @param int    $componenteDigitalId
     * @param string $transactionId
     *
     * @return int|null
     *
     * @throws Exception
     */
    public function removeSignaturePades(int $componenteDigitalId, string $transactionId): ?int
    {
        try {
            /** @var ComponenteDigitalDTO $componenteDigitalDto */
            $componenteDigitalDto = $this->componenteDigitalResource->getDtoForEntity(
                $componenteDigitalId,
                ComponenteDigitalDTO::class
            );

            if ('application/pdf' !== $componenteDigitalDto->getMimetype()) {
                return null;
            }

            // verificar se tem conteúdo para evitar erro no afterDownload após update sem commit
            $pdfContent = $componenteDigitalDto->getConteudo();

            if (empty($pdfContent)) {
                $pdfContent = $this->componenteDigitalResource->download(
                    $componenteDigitalId,
                    $transactionId
                )?->getConteudo();
            }

            if (empty($pdfContent)
                || $this->getPdfCountSignature($pdfContent) < 1
            ) {
                return null;
            }

            $signerProxyParams = [];
            $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');

            if ($signerProxy) {
                $signerProxyParams = explode(' ', $signerProxy);
            }

            // Recupera a configuração das assinaturas
            $assinaturaConfig = $this->getAssinaturaConfig(AssinaturaPadrao::PAdES);

            // insere o PDF no fileSystem
            $hash = hash('SHA256', $pdfContent);
            file_put_contents('/tmp/'.$hash.'.pdf', $pdfContent);

            // remove assinatura PAdES, em ambiente dev não faz validação
            $params = [
                'java',
                '-Duser.timezone=America/Sao_Paulo',
                '-Dfile.encoding=UTF8',
                '-jar',
                '/usr/local/bin/supp-signer.jar',
                '--mode=pdf-remove-sign',
                // '--cpf='.$usuario->getUserIdentifier(),
                '--hash='.$hash,
                // Para remover sem erros uma assinatura feita em modo teste,
                // é necessário que a remoção seja feita em modo teste.
                $assinaturaConfig['test'] ? '--test' : '',
            ];

            $process = new Process(array_merge($params, $signerProxyParams));

            // existem no kibana casos raros de timeOut no run()
            try {
                $process->run();
            } catch (Throwable $t) {
                $this->logger->critical($t->getMessage());

                throw new RuntimeException(
                    'SUPP-Signer: Indisponibilidade temporária. '
                    .'O processo de exclusão da assinatura PAdES não foi concluído. '
                    .'Por favor, aguarde e tente novamente mais tarde!'
                );
            }

            $filenamePdf = '/tmp/'.$hash.'.pdf';
            // se o supp-signer executou com sucesso
            if ($process->isSuccessful()) {
                $pdfContent = file_get_contents($filenamePdf);
                unlink($filenamePdf);

                if (empty($pdfContent)) {
                    throw new RuntimeException(
                        'Falha na exclusão da assinatura PAdES do componente digital, ID:'
                        .$componenteDigitalId
                        .'. Erro ao ler o arquivo (*.PDF)'
                    );
                }
            } else {
                if (is_file($filenamePdf)) {
                    unlink($filenamePdf);
                }
                $output = $process->getOutput();
                $this->logger->critical('Command: '.$process->getCommandLine()."\n\nError: ".$output);

                $outErrorPos = strpos($output, 'OutError:');
                if (false !== $outErrorPos) {
                    throw new RuntimeException(substr($output, $outErrorPos + 9));
                }

                throw new RuntimeException('SUPP-Signer: Erro ao excluir assinatura PAdES!');
            }

            $componenteDigitalDto->setHash($hash);
            $componenteDigitalDto->setHashAntigo($componenteDigitalDto->getHash());
            $componenteDigitalDto->setConteudo('data:application/pdf;base64,'.base64_encode($pdfContent));

            $this->componenteDigitalResource->update(
                $componenteDigitalDto->getId(),
                $componenteDigitalDto,
                $transactionId,
                true
            );

            return $componenteDigitalDto->getId();
        } catch (Throwable $t) {
            throw new RuntimeException($t->getMessage());
        }
    }

    /**
     * Assinatura utilizando certificado externo A3/A1/Nuvem.
     *
     * @param Usuario $usuario
     * @param string  $processUuid
     * @param array   $componentesDigitais
     * @param bool    $pades
     *
     * @return Assinatura[]
     *
     * @throws Throwable
     */
    protected function externalSignA3(
        Usuario $usuario,
        string $processUuid,
        array $componentesDigitais,
        bool $pades = false
    ): array {
        if ($pades) {
            return $this->externalSignA3Pades($usuario, $processUuid, $componentesDigitais);
        }

        return $this->externalSignA3Cades($usuario, $processUuid, $componentesDigitais);
    }

    /**
     * Assinatura utilizando certificado externo A3/A1/Nuvem e política CAdES.
     * - Envia o hash do componente digital para o Assinador SUPP assinar
     * - O Assinador SUPP retorna a assinatura na rota /v1/administrativo/assinatura.
     *
     * @param Usuario $usuario
     * @param string  $processUuid
     * @param array   $componentesDigitais
     *
     * @return Assinatura[]
     *
     * @throws NonUniqueResultException
     * @throws Throwable
     */
    protected function externalSignA3Cades(
        Usuario $usuario,
        string $processUuid,
        array $componentesDigitais
    ): array {
        $componentesDigitaisBloco = [];

        foreach ($componentesDigitais as $componenteDigital) {
            $componentesDigitaisBloco[] = [
                'componenteDigitalId' => $componenteDigital->getId(),
                'hash' => $componenteDigital->getHash(),
                'documentoId' => $componenteDigital->getDocumento()->getId(),
            ];
        }

        $token = $this->JWTManager->create($usuario);
        $agora = new DateTime();

        // Recupera a configuração das assinaturas
        $assinaturaConfig = $this->getAssinaturaConfig(AssinaturaPadrao::CAdES);

        $transaction = [
            'uuid' => Ruuid::uuid4()->toString(),
            'action' => 'SIGN_HASH_FILES',
            'payload' => [
                'mode' => ($assinaturaConfig['test'] ? 'TEST' : 'REAL'),
                'processUUID' => $processUuid,
                'jwt' => $token,
                'api' => $this->parameterBag->get('supp_core.administrativo_backend.url_sistema_backend')
                    .'/v1/administrativo/assinatura/cades',
                'files' => $componentesDigitaisBloco,
                'algorithmHash' => 'SHA256withRSA',
            ],
            'dateTimeCreate' => $agora->format('d/m/Y H:i:s'),
            'expire' => (count($componentesDigitais) * 10),
        ];

        $update = new Update(
            '/assinador/'.$usuario->getUserIdentifier(),
            json_encode($transaction, JSON_THROW_ON_ERROR)
        );

        $this->hub->publish($update);

        return [];
    }

    /**
     * Assinatura utilizando certificado externo A3/A1/Nuvem e política PAdES.
     * - Envia o hash do componente digital para o Assinador SUPP assinar
     * - O Assinador SUPP retorna a assinatura na rota /v1/administrativo/assinatura/pades.
     *
     * @param Usuario $usuario
     * @param string  $processUuid
     * @param array   $componentesDigitais
     *
     * @return Assinatura[]
     *
     * @throws Throwable
     */
    protected function externalSignA3Pades(Usuario $usuario, string $processUuid, array $componentesDigitais): array
    {
        $componentesDigitaisBloco = [];
        $assinaturas = [];

        // Verificar se precisa converter HTML para PDF
        // $this->convertToPdf($componentesDigitais);

        // Recupera a configuração das assinaturas
        $assinaturaConfig = $this->getAssinaturaConfig(AssinaturaPadrao::PAdES);

        foreach ($componentesDigitais as $componenteDigital) {
            $transactionId = null;
            try {
                $transactionId = $this->transactionManager->beginNewTransaction();

                // Recupera o arquivo
                $componenteDigitalId = $componenteDigital->getId();
                $pdfContent = $this->
                    componenteDigitalResource->download(
                        $componenteDigitalId,
                        $transactionId
                    )?->getConteudo();

                // Prepara o PDF para receber assinatura
                $hash = hash('SHA256', $this->getPdfPreparedContent($componenteDigitalId, $pdfContent));

                $componentesDigitaisBloco[] = [
                    'componenteDigitalId' => $componenteDigitalId,
                    'hash' => $hash,
                    'documentoId' => $componenteDigital->getDocumento()?->getId(),
                ];
            } finally {
                if (!empty($transactionId)) {
                    $this->transactionManager->resetTransaction($transactionId);
                }
            }
        }

        $token = $this->JWTManager->create($usuario);
        $agora = new DateTime();

        // Envia para o Assinador externo
        $transaction = [
            'uuid' => Ruuid::uuid4()->toString(),
            'action' => 'SIGN_HASH_FILES',
            'payload' => [
                'mode' => ($assinaturaConfig['test'] ? 'TEST' : 'REAL'),
                'processUUID' => $processUuid,
                'jwt' => $token,
                'api' => $this->parameterBag->get('supp_core.administrativo_backend.url_sistema_backend')
                    .'/v1/administrativo/assinatura/pades',
                'files' => $componentesDigitaisBloco,
                'algorithmHash' => 'SHA256withRSA',
            ],
            'dateTimeCreate' => $agora->format('d/m/Y H:i:s'),
            'expire' => (count($componentesDigitais) * 10),
        ];

        $update = new Update(
            '/assinador/'.$usuario->getUserIdentifier(),
            json_encode($transaction, JSON_THROW_ON_ERROR)
        );

        $this->hub->publish($update);

        return $assinaturas;
    }

    /**
     * @param Usuario             $usuario
     * @param string              $code
     * @param string              $codeVerifier
     * @param ComponenteDigital[] $componentesDigitais
     * @param bool                $pades
     *
     * @return Assinatura[]
     *
     * @throws Throwable
     */
    protected function internalSignNeoId(
        Usuario $usuario,
        string $code,
        string $codeVerifier,
        array $componentesDigitais,
        bool $pades = false
    ): array {
        // Solicitar Access Token ou recuperar do Redis
        $tokenNeoId = $this->internalTokenNeoId($code, $codeVerifier);

        $assinaturas = [];
        foreach (array_chunk($componentesDigitais, 5) as $chuckComponentesDigitais) {
            $assinaturas = array_merge(
                $assinaturas,
                // atualmente NeoId assina no máximo 5 arquivos por requisição
                $pades ?
                    $this->internalSignChunkNeoIdPades($usuario, $chuckComponentesDigitais, $tokenNeoId) :
                    $this->internalSignChunkNeoIdCades($usuario, $chuckComponentesDigitais, $tokenNeoId)
            );
        }

        return $assinaturas;
    }

    /**
     * Solicitar o Token de Acesso e armazená-lo no Redis (5 min) para posterior recuperação.
     * Se a solicitação estiver válida (código de autorização),
     * o Prestador de Serviço de Confiança SerproID irá gerar um token de acesso (access_token),
     * que será retornado via HTTP code 200 na resposta da solicitação em conjunto
     * com alguns parâmetros adicionais no formato application/json
     *
     * @param string $code Código de Autorização
     * @param string $codeVerifier Chave de prova aleatória usada para gerar o code_challenge (utilizado na autorização)
     *
     * @return string Access Token
     *
     * @throws Exception
     */
    protected function internalTokenNeoId(string $code, string $codeVerifier): string
    {
        $keyCacheAccessToken = __CLASS__.':NEOID:accessToken:'.$code.$codeVerifier;
        // Verificar se existe um access_token válido (expira em 5 minutos)
        $accessToken = $this->redisClient->get($keyCacheAccessToken);
        if (!empty($accessToken)) {
            $this->logger->info('NeoId - usou CacheAccessToken '.$code.$codeVerifier);
            return $accessToken;
        }

        $erroAccessToken = 'NeoId - Erro na tentativa de recuperar Access Token';

        // Solicitar um access_token
        $chToken = null;
        try {
            $postFields = 'grant_type=authorization_code'
                .'&client_id='.$this->parameterBag->get('supp_core.administrativo_backend.neoid_client_id')
                .'&client_secret='.$this->parameterBag->get('supp_core.administrativo_backend.neoid_client_secret')
                .'&code='.$code
                .'&code_verifier='.$codeVerifier
                .'&redirect_uri='.urlencode(
                    $this->parameterBag->get('supp_core.administrativo_backend.neoid_redirect_uri')
                );

            $headers = [
                'Content-Type: application/x-www-form-urlencoded',
            ];

            $chToken = curl_init($this->parameterBag->get('supp_core.administrativo_backend.neoid_url').'/token');

            if (false === $chToken) {
                throw new RuntimeException($erroAccessToken.': Falha ao inicializar o cURL');
            }

            curl_setopt(
                $chToken,
                CURLOPT_URL,
                $this->parameterBag->get('supp_core.administrativo_backend.neoid_url').'/token'
            );
            curl_setopt($chToken, CURLOPT_POSTFIELDS, $postFields);
            curl_setopt($chToken, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chToken, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($chToken, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($chToken);

            if (false === $response) {
                throw new RuntimeException($erroAccessToken.': '.curl_error($chToken));
            }

            // As respostas são no formato application/json
            $responseToken = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

            if (empty($responseToken)) {
                throw new RuntimeException($erroAccessToken.': valor nulo após decodificação do JSON');
            }

            if (isset($responseToken['error_description'])) {
                throw new RuntimeException($erroAccessToken.': '.$responseToken['error_description']);
            }

            if (!isset($responseToken['access_token'])) {
                throw new RuntimeException(
                    $erroAccessToken
                    .': Token de acesso não está presente ou nulo no retorno da solicitação'
                );
            }

            if (isset($responseToken['expires_in'])) {
                $this->logger->info('NeoId - expires_in '.$responseToken['expires_in']);
            }

            /*if (isset($responseToken['scope'])) {
                $this->logger->info('NeoId - scope '.$responseToken['scope']);
            }*/

            // Guardar o token recebido por 15 minutos (padrão escopo signature_session)
            $this->redisClient->set($keyCacheAccessToken, $responseToken['access_token'], 900);

            return $responseToken['access_token'];
        } finally {
            if (isset($chToken) && $chToken instanceof CurlHandle) {
                curl_close($chToken);
            }
        }
    }

    /**
     * Assinatura CAdES via nuvem SERPRO
     *
     * @param Usuario             $usuario
     * @param ComponenteDigital[] $componentesDigitais
     * @param string              $tokenNeoId
     *
     * @return Assinatura[]
     *
     * @throws Throwable
     */
    protected function internalSignChunkNeoIdCades(
        Usuario $usuario,
        array $componentesDigitais,
        string $tokenNeoId
    ): array {
        if (!$componentesDigitais) {
            return [];
        }

        $assinaturas = [];

        $transactionId = null;
        try {
            // Gerar a estrutura JSON com os hashes a serem assinados
            $data = $this->hashesToSign($componentesDigitais, false);

            // Solicitar ao serviço do SERPRO para assinar os hashs
            $assinaturasArray = $this->signatureNeoId($data, $tokenNeoId);

            // Recuperar o certificado PEM autorizado pelo titular
            $certificado = $this->certificateDiscoveryNeoId($tokenNeoId);

            foreach ($assinaturasArray as $cdAssinado) {
                preg_match(
                    '/(?<=-----BEGIN PKCS7-----)[\s\S]*?(?=-----END PKCS7-----)/',
                    $cdAssinado['raw_signature'],
                    $matches
                );
                $conteudo_base64 = preg_replace('/\s+/', '', $matches[0]);

                // O atributo id contém o id do componente digital
                $componenteDigitalEntity = $this->componenteDigitalResource->findOne((int)$cdAssinado['id']);

                if (null === $componenteDigitalEntity) {
                    throw new RuntimeException(
                        'NeoId - Erro ao recuperar Componente Digital Entity ID: '.$cdAssinado['id']
                    );
                }

                $transactionId = $this->transactionManager->beginNewTransaction();
                $assinaturaDTO = new AssinaturaDTO();
                $assinaturaDTO->setComponenteDigital($componenteDigitalEntity);
                $assinaturaDTO->setAssinatura($conteudo_base64);
                $assinaturaDTO->setAlgoritmoHash('SHA256withRSA');
                $assinaturaDTO->setCadeiaCertificadoPEM($certificado);
                $assinaturaDTO->setCadeiaCertificadoPkiPath(
                    $this->assinaturaHelper->convertPemToPkiPath($certificado)
                );
                $assinaturaDTO->setPadrao(AssinaturaPadrao::CAdES->value);
                $assinaturaDTO->setProtocolo(AssinaturaProtocolo::NeoID->value);

                $assinaturas[] = $assinatura = $this->assinaturaResource->create($assinaturaDTO, $transactionId);
                $assinatura->setCriadoPor($usuario);
                $this->transactionManager->commit($transactionId);

                // Avisar sobre o progresso das assinaturas via Mercure
                $this->publishOnMercure(
                    $usuario->getUsername(),
                    'SIGN_PROGRESS',
                    'Assinatura do componente digital ID '.$componenteDigitalEntity->getId().' concluída',
                    $componenteDigitalEntity->getId()
                );
            }

            return $assinaturas;
        } catch (Throwable $throwable) {
            if (!empty($transactionId)) {
                $this->transactionManager->resetTransaction($transactionId);
            }

            throw $throwable;
        }
    }

    /**
     * Realiza a assinatura digital do hash de um conteúdo, utilizando a chave privada do certificado do usuário.
     *
     * @param string $data
     * @param string $tokenNeoId
     * @return array
     * @throws Exception
     */
    protected function signatureNeoId(string $data, string $tokenNeoId): array
    {
        $chAssinatura = null;
        $erroAssinatura = 'NeoId - Erro ao solicitar assinatura';

        try {
            $headers = [
                'Content-Type: application/json',
                'Authorization: Bearer '
                .$tokenNeoId,
            ];

            // Assinatura digital do componenteDigital
            $chAssinatura = curl_init(
                $this->parameterBag->get('supp_core.administrativo_backend.neoid_url').'/signature'
            );

            if (false === $chAssinatura) {
                throw new RuntimeException($erroAssinatura.': Falha ao inicializar o cURL');
            }

            curl_setopt($chAssinatura, CURLOPT_POSTFIELDS, $data);
            curl_setopt($chAssinatura, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chAssinatura, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($chAssinatura, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($chAssinatura);

            if (false === $response) {
                throw new RuntimeException($erroAssinatura.': '.curl_error($chAssinatura));
            }

            // As respostas são no formato application/json
            $responseAssinatura = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

            if (empty($responseAssinatura)) {
                throw new RuntimeException($erroAssinatura.': valor nulo após decodificação do JSON');
            }

            // atributo msg existe quando tem erro (401, 403, 412, 500)
            if (isset($responseAssinatura['msg'])) {
                throw new RuntimeException($erroAssinatura.': '.$responseAssinatura['msg']);
            }

            if (!array_key_exists('signatures', $responseAssinatura)) {
                throw new RuntimeException(
                    $erroAssinatura.': atributo signatures não está presente na resposta'
                );
            }

            if (empty($responseAssinatura['signatures'])) {
                throw new RuntimeException($erroAssinatura.': resposta com lista vazia de assinaturas');
            }

            return $responseAssinatura['signatures'];
        } finally {
            if (isset($chAssinatura) && $chAssinatura instanceof CurlHandle) {
                curl_close($chAssinatura);
            }
        }
    }


    /**
     * Realiza a recuperação do certificado autorizado pelo titular.
     *
     * @param string $tokenNeoId
     * @return string
     * @throws Exception
     */
    protected function certificateDiscoveryNeoId(string $tokenNeoId):string
    {
        $chCertificado = null;
        $erroCertificado = 'NeoId - Erro ao recuperar certificado';
        try {
            // O usuário pode ter N certificados SerproID
            $keyCacheCertificado = __CLASS__.':NEOID:certificado:'.$tokenNeoId;
            // Verificar se existe um certificado válido. Expira em 15 minutos (padrão escopo signature_session)
            $certificado = $this->redisClient->get($keyCacheCertificado);
            if (!empty($certificado)) {
                $this->logger->info('NeoId - usou CacheCertificado');
                return $certificado;
            }

            // busca o certificado PEM
            $headersCertificado = [
                'Content-Type: application/json',
                'Authorization: Bearer '
                .$tokenNeoId,
            ];

            $chCertificado = curl_init(
                $this->parameterBag->get('supp_core.administrativo_backend.neoid_url').'/certificate-discovery'
            );

            if (false === $chCertificado) {
                throw new RuntimeException($erroCertificado.': Falha ao inicializar o cURL');
            }

            curl_setopt(
                $chCertificado,
                CURLOPT_URL,
                $this->parameterBag->get('supp_core.administrativo_backend.neoid_url').'/certificate-discovery'
            );
            curl_setopt($chCertificado, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chCertificado, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($chCertificado, CURLOPT_HTTPHEADER, $headersCertificado);
            $response = curl_exec($chCertificado);

            if (false === $response) {
                throw new RuntimeException($erroCertificado.': '.curl_error($chCertificado));
            }

            // As respostas são no formato application/json
            $responseCertificado = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

            if (empty($responseCertificado)) {
                throw new RuntimeException($erroCertificado.': valor nulo após decodificação do JSON');
            }

            // atributo msg existe quando tem erro (401, 403, 412, 500)
            if (isset($responseCertificado['msg'])) {
                throw new RuntimeException($erroCertificado.': '.$responseCertificado['msg']);
            }

            if (!array_key_exists('certificates', $responseCertificado)) {
                throw new RuntimeException($erroCertificado.': atributo certificates não está presente');
            }

            if (empty($responseCertificado['certificates'])) {
                throw new RuntimeException($erroCertificado.': lista vazia de certificados');
            }

            if (!isset($responseCertificado['certificates'][0]['certificate'])) {
                throw new RuntimeException(
                    $erroCertificado
                    .': atributo certificate não encontrado ou nulo na lista de certificados'
                );
            }

            // o primeiro certificado é o utilizado para assinar e já basta para cadeia de certificados
            $certificado = $responseCertificado['certificates'][0]['certificate'];

            // salva o certificado no Redis por 15 min. (padrão escopo signature_session)
            $this->redisClient->set($keyCacheCertificado, $certificado, 900);

            return $certificado;
        } finally {
            if (isset($chCertificado) && $chCertificado instanceof CurlHandle) {
                curl_close($chCertificado);
            }
        }
    }

    /**
     * Monta um JSON com os hashes a serem assinados
     *
     * @param ComponenteDigital[] $componentesDigitais
     * @param bool $pades
     * @return string
     * @throws Exception
     */
    protected function hashesToSign(array $componentesDigitais, bool $pades = false): string
    {
        $hashes = [];
        foreach ($componentesDigitais as $componenteDigital) {
            $hashes[] = [
                'id' => (string) ($componenteDigital->getId()),
                'alias' => 'componente_digital_'.$componenteDigital->getId(),
                'hash' => $this->getContentBase64FromSha256($componenteDigital->getId(), $pades),
                'hash_algorithm' => '2.16.840.1.101.3.4.2.1',
                'signature_format' => 'CMS',
            ];
        }

        return json_encode([
            'hashes' => $hashes,
        ], JSON_THROW_ON_ERROR);
    }

    /**
     * Assinatura PAdES via nuvem SERPRO
     *
     * @param Usuario             $usuario
     * @param ComponenteDigital[] $componentesDigitais
     * @param string              $tokenNeoId
     *
     * @return Assinatura[]
     *
     * @throws Throwable
     */
    protected function internalSignChunkNeoIdPades(
        Usuario $usuario,
        array $componentesDigitais,
        string $tokenNeoId
    ): array {
        if (!$componentesDigitais) {
            return [];
        }

        $assinaturas = [];

        $transactionId = null;
        try {
            // Gerar a estrutura JSON com os hashes a serem assinados
            $data = $this->hashesToSign($componentesDigitais, true);

            // Solicitar ao serviço do SERPRO para assinar os hashs
            $assinaturasArray = $this->signatureNeoId($data, $tokenNeoId);

            // Recuperar o certificado PEM autorizado pelo titular
            $certificado = $this->certificateDiscoveryNeoId($tokenNeoId);

            foreach ($assinaturasArray as $cdAssinado) {
                preg_match(
                    '/(?<=-----BEGIN PKCS7-----)[\s\S]*?(?=-----END PKCS7-----)/',
                    $cdAssinado['raw_signature'],
                    $matches
                );
                $conteudo_base64 = preg_replace('/\s+/', '', $matches[0]);

                // O atributo id contém o id do componente digital
                $componenteDigitalEntity = $this->componenteDigitalResource->findOne((int) $cdAssinado['id']);

                if (null === $componenteDigitalEntity) {
                    throw new RuntimeException(
                        'NeoId - Erro ao recuperar Componente Digital Entity ID: '.$cdAssinado['id']
                    );
                }

                $transactionId = $this->transactionManager->beginNewTransaction();
                $assinaturas[] = $this->addSignatureInPdf(
                    $componenteDigitalEntity,
                    $conteudo_base64,
                    $certificado,
                    $this->assinaturaHelper->convertPemToPkiPath($certificado),
                    'SHA256withRSA',
                    AssinaturaProtocolo::NeoID,
                    $transactionId,
                    $usuario,
                );
                $this->transactionManager->commit($transactionId);

                // Avisar sobre o progresso das assinaturas via Mercure
                $this->publishOnMercure(
                    $usuario->getUsername(),
                    'SIGN_PROGRESS',
                    'Assinatura do componente digital ID '.$componenteDigitalEntity->getId().' concluída',
                    $componenteDigitalEntity->getId()
                );
            }
        } catch (Throwable $throwable) {
            if (!empty($transactionId)) {
                $this->transactionManager->resetTransaction($transactionId);
            }

            throw $throwable;
        }

        return $assinaturas;
    }

    /**
     * @param array $documentos
     * @param bool  $anexosIncluidos
     *
     * @return ComponenteDigital[]
     *
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function componentesDigitais(array $documentos, bool $anexosIncluidos = false): array
    {
        $componentesDigitaisBloco = [];
        foreach ($documentos as $d) {
            $documento = $d instanceof Documento ? $d : $this->documentoResource->getRepository()->find($d);
            if (!$documento) {
                throw new RuntimeException('Documento não encontrado! ID:'.$d);
            }

            /* @var ComponenteDigital[] $componentesDigitais */
            $componentesDigitais = [];
            if (!$anexosIncluidos) {
                $componentesDigitais = $documento->getComponentesDigitais()->toArray();
            } elseif (null !== $documento->getId()) {
                $componentesDigitais = $this
                        ->componenteDigitalResource
                        ->getRepository()
                        ->findVinculadosByDocumento($documento);
            }

            foreach ($componentesDigitais as $c) {
                $componentesDigitaisBloco[$c->getId()] = $c;
            }
        }

        return $componentesDigitaisBloco;
    }

    /**
     * Verifica as credênciais do usuário.
     *
     * @param mixed                 $credentials
     * @param UserInterface|Usuario $user
     *
     * @return bool
     */
    public function checkLdapCredentials(#[SensitiveParameter] mixed $credentials, UserInterface|Usuario $user): bool
    {
        if ($this->ldapService::TYPE_AUTH_AD) {
            return true;
        }

        return $credentials['password'] === $user->getPassword();
    }

    /**
     * Verifica senha.
     *
     * @param string $credential
     *
     * @return string
     */
    public function checkA1Credential(#[SensitiveParameter] string $credential): string
    {
        if (str_contains($credential, 'interno//')) {
            $credential = str_replace('interno//', '', $credential);

            if (!$this->passwordHasher->isPasswordValid(
                $this->tokenStorage->getToken()?->getUser(),
                $this->base64UrlSafeDecode(explode(':', $credential)[1])
            )
            ) {
                throw new RuntimeException('Senha interna não confere!');
            }
        } elseif (str_contains($credential, 'ldap//')) {
            $credential = str_replace('ldap//', '', $credential);

            $userName = $this->tokenStorage->getToken()?->getUser()?->getEmail();
            if (!$userName) {
                throw new RuntimeException('Não foi possível recuperar o email do usuário no LDAP/AD!');
            }

            $userPasswordTyped = $this->base64UrlSafeDecode(explode(':', $credential)[1]);
            $KeyRedis = 'ldap_cache:'.hash('SHA256', $userName.$userPasswordTyped);

            // recuperar senha no Redis
            try {
                $userPasswordLdap = $this->redisClient->get($KeyRedis);
            } catch (RedisException $e) {
                $this->logger->critical($e->getMessage());
                throw new RuntimeException('Problema interno Redis!');
            }
            // se não tem senha no Redis, tentar obter dados LDAP/AD com senha digitada
            if (false === $userPasswordLdap) {
                try {
                    $userData = $this->ldapService->getUserData($userName, $userPasswordTyped);
                } catch (Throwable $t) {
                    // Can't contact LDAP server
                    // Invalid credentials
                    throw new RuntimeException($t->getMessage());
                }
                if (!$userData) {
                    throw new RuntimeException('Não foi possível recuperar os dados do usuário no LDAP/AD!');
                }
                // se o fluxo chegou até aqui, salva a senha no Redis, por 30 minutos... 1800s
                try {
                    $this->redisClient->set($KeyRedis, $userPasswordTyped, 1800);
                } catch (RedisException $e) {
                    $this->logger->critical($e->getMessage());
                    throw new RuntimeException('Problema interno Redis!');
                }
            } elseif ($userPasswordLdap !== $userPasswordTyped) {
                throw new RuntimeException('Senha LDAP/AD não confere!');
            }
        } elseif (str_contains($credential, 'govBr//')) {
            $credential = str_replace('govBr//', '', $credential);
            $tokenRevalidaGovBr = explode(':', $credential)[1];
            $cpfUsuario = $this->tokenStorage->getToken()?->getUser()?->getUserIdentifier();

            if (!$this->loginUnicoGovBrService->decodeTokenRevalida($tokenRevalidaGovBr, $cpfUsuario)) {
                throw new RuntimeException('Token de revalidação GovBr inválido!');
            }
        }

        return $credential;
    }

    /**
     * @param AssinaturaDTO $assinatura
     *
     * @return void
     */
    public function checkChavePublica(AssinaturaDTO $assinatura): void
    {
        // esse if existe para permitir testes sem uma chave pública válida
        if ('cadeia_teste' !== $assinatura->getCadeiaCertificadoPEM()) {
            $aCertChain = explode('-----END CERTIFICATE-----', $assinatura->getCadeiaCertificadoPEM());
            $fisrtCert = $aCertChain[0].'-----END CERTIFICATE-----';
            $pubkeyid = openssl_pkey_get_public($fisrtCert);

            if (!$pubkeyid) {
                throw new RuntimeException('Não foi possível carregar a chave pública da cadeia de certificados!');
            }
        }
    }

    /**
     * CAdES - Efetua o hash SHA256 do conteúdo do componente digital e depois faz o Base64<br/><br/>
     * PAdES - Prepara o PDF para receber assinatura, efetua o hash SHA256 do conteúdo preparado e depois faz o Base64
     *
     * @param int $componenteDigitalId
     * @param bool $pades
     * @return string
     * @throws Exception
     */
    protected function getContentBase64FromSha256(int $componenteDigitalId, bool $pades = false): string
    {
        $transactionId = null;
        try {
            $transactionId = $this->transactionManager->beginNewTransaction();
            $conteudo = $this->componenteDigitalResource->download(
                $componenteDigitalId,
                $transactionId
            )?->getConteudo();

            if ($pades) {
                return base64_encode(
                    hash('SHA256', $this->getPdfPreparedContent($componenteDigitalId, $conteudo), true)
                );
            }

            return base64_encode(hash('SHA256', $conteudo, true));
        } finally {
            if (!empty($transactionId)) {
                $this->transactionManager->resetTransaction($transactionId);
            }
        }
    }

    /**
     * Prepara o PDF para assinatura, coloca o arquivo no Redis e retorna o conteúdo do PDF preparado.
     *
     * @param int $componenteDigitalId
     * @param $pdfContent
     * @return string
     *
     * @throws Exception
     */
    protected function getPdfPreparedContent(int $componenteDigitalId, $pdfContent): string
    {
        /** @var Usuario $usuario */
        $usuario = $this->tokenStorage->getToken()?->getUser();

        // insere o PDF no fileSystem
        $hash = hash('SHA256', $pdfContent);
        file_put_contents('/tmp/'.$hash.'.pdf', $pdfContent);

        // Proxy
        $signerProxyParams = [];
        $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');
        if ($signerProxy) {
            $signerProxyParams = explode(' ', $signerProxy);
        }

        // Recupera a configuração das assinaturas
        $assinaturaConfig = $this->getAssinaturaConfig(AssinaturaPadrao::PAdES);

        // prepara o PDF para receber a assinatura
        $params = [
            'java',
            '-Duser.timezone=America/Sao_Paulo',
            '-Dfile.encoding=UTF8',
            '-jar',
            '/usr/local/bin/supp-signer.jar',
            '--mode=pdf-prepare',
            '--texto=Assinatura Digital',
            '--nome='.base64_encode($usuario->getNome()),
            '--cpf='.$usuario->getUserIdentifier(),
            // VR-TD (Vertical Right - Top Down),
            //VL-BU (Vertical Left - Bottom Up),
            //HB-LR (Horizontal Bottom - Left Right)
            '--orientation='.$assinaturaConfig['orientation'],
            '--visible='.($assinaturaConfig['visible'] ? 'true' : 'false'),
            '--imageBase64='.(
                array_key_exists('imageBase64', $assinaturaConfig) ? $assinaturaConfig['imageBase64'] : ''
            ),
            '--hash='.$hash,
            $assinaturaConfig['test'] ? '--test' : '',
        ];

        $process = new Process(array_merge($params, $signerProxyParams));

        // existem no kibana casos raros de timeOut no run()
        try {
            $process->run();
        } catch (Throwable $t) {
            $this->logger->critical($t->getMessage());

            throw new RuntimeException(
                'SUPP-Signer: Indisponibilidade temporária.'
                .'O processo de preparação do PDF para comportar assinatura PAdES não foi concluído.'
                .'Por favor, aguarde e tente novamente mais tarde!'
            );
        }

        $filenamePdf = '/tmp/'.$hash.'.pdf';
        // processo executou com sucesso
        if ($process->isSuccessful()) {
            $pdfContent = file_get_contents($filenamePdf);
            unlink($filenamePdf);

            if (empty($pdfContent)) {
                throw new RuntimeException(
                    'Falha na preparação da assinatura PAdES do componente digital, ID:'
                    .$componenteDigitalId.'. Erro ao ler o arquivo (*.PDF)'
                );
            }
            // o cálculo do byteRange e seu hash requer o Contents com apenas '/Contents '
            $pdfContentTemp = preg_replace("/\/Contents <0*>/i", '/Contents ', $pdfContent);
            // $hash = hash('SHA256', $pdfContentTemp);
            // insere no Redis o conteúdo com o /Contents <0*> para ser substituído pela assinatura,
            // mas usando o hash do conteúdo com /Contents
            $this->redisClient->set('PAdES:'.$componenteDigitalId.':'.$usuario->getUserIdentifier(), $pdfContent, 1800);

            return $pdfContentTemp;
        } else {
            if (is_file($filenamePdf)) {
                unlink($filenamePdf);
            }
            $erro = 'Command: '.$process->getCommandLine()."\n\nError: ".$process->getOutput();
            $this->logger->critical(
                preg_replace("/--password=([^']*)'/i", "--password=****'", $erro)
            );

            throw new RuntimeException('SUPP-Signer: Erro ao preparar PDF para assinatura!');
        }
    }

    /**
     * Monta o PDF com a assinatura e cria entidade assinatura.
     *
     * @param int|ComponenteDigital $componenteDigital
     * @param string                $assinaturaBase64
     * @param string                $cadeiaCertificadoPEM          em Base64
     * @param string|null           $cadeiaCertificadoPkiPath em Base64
     * @param string                $algoritmoHash
     * @param string                $transactionId
     * @param Usuario               $usuario
     * @param AssinaturaProtocolo   $assinaturaProtocolo
     *
     * @return Assinatura
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     * @throws Exception
     */
    public function addSignatureInPdf(
        int|ComponenteDigital $componenteDigital,
        string $assinaturaBase64,
        string $cadeiaCertificadoPEM,
        ?string $cadeiaCertificadoPkiPath,
        string $algoritmoHash,
        AssinaturaProtocolo $assinaturaProtocolo,
        string $transactionId,
        Usuario $usuario,
    ): Assinatura {
        if ($componenteDigital instanceof ComponenteDigital) {
            $componenteDigitalId = $componenteDigital->getId();
            $componenteDigitalEntity = $componenteDigital;
        } else {
            $componenteDigitalId = $componenteDigital;
            $componenteDigitalEntity = $this->componenteDigitalResource->findOne($componenteDigitalId);
        }

        if (null === $componenteDigitalEntity) {
            throw new RuntimeException('Componente digital não localizado no Banco de Dados! ID:'.$componenteDigitalId);
        }

        $conteudo = $this->redisClient->get('PAdES:'.$componenteDigitalId.':'.$usuario->getUserIdentifier());
        if (empty($conteudo)) {
            throw new RuntimeException(
                'Conteúdo do PDF não localizado no Redis! Componente Digital ID:'.$componenteDigitalId
            );
        }
        $this->redisClient->del('PAdES:'.$componenteDigitalId.':'.$usuario->getUserIdentifier());

        // Insere a assinatura no PDF preparado advindo do Redis
        if ('assinatura_teste' === $assinaturaBase64) {
            $assinatura = '';
            $assinaturaBase64 = base64_encode($assinaturaBase64);
        } else {
            $assinatura = mb_strtoupper(bin2hex(base64_decode($assinaturaBase64)));
        }
        $conteudo = preg_replace(
            "/\/Contents <0*>/i",
            '/Contents <'.str_pad(
                $assinatura,
                PDFTools::SIGNATURE_MAX_LENGTH,
                '0'
            ).'>',
            $conteudo
        );
        // Neste ponto o conteudo PDF já está com a assinatura interna e chancela


        /** @var ComponenteDigitalDTO $componenteDigitalDto */
        $componenteDigitalDto = $this->componenteDigitalResource->getDtoForEntity(
            $componenteDigitalId,
            ComponenteDigitalDTO::class
        );

        // Atualiza o conteúdo no banco de dados
        // componente digital
        $componenteDigitalDto->setHashAntigo($componenteDigitalEntity->getHash());
        $componenteDigitalDto->setConteudo('data:application/pdf;base64,'.base64_encode($conteudo));

        $this->componenteDigitalResource->update(
            $componenteDigitalId,
            $componenteDigitalDto,
            $transactionId
        );

        // Cria Assinatura
        $assinaturaDTO = new AssinaturaDTO();
        $assinaturaDTO->setComponenteDigital($componenteDigitalEntity);
        $assinaturaDTO->setAssinatura($assinaturaBase64);
        $assinaturaDTO->setAlgoritmoHash($algoritmoHash);
        $assinaturaDTO->setCadeiaCertificadoPEM($cadeiaCertificadoPEM);
        $assinaturaDTO->setCadeiaCertificadoPkiPath($cadeiaCertificadoPkiPath);
        $assinaturaDTO->setPadrao(AssinaturaPadrao::PAdES->value);
        $assinaturaDTO->setProtocolo($assinaturaProtocolo->value);

        $assinatura = $this->assinaturaResource->create($assinaturaDTO, $transactionId);

        $assinatura->setCriadoPor($usuario);

        return $assinatura;
    }

    /**
     * Converte de html para pdf caso esteja tentando assinar PAdES.
     *
     * @param ComponenteDigital[] $componentesDigitais
     *
     * @return void
     *
     * @throws Throwable
     */
    public function convertToPdf(array $componentesDigitais): void
    {
        // Na assinatura PAdES, é criada a entidade Assinatura e alterados:
        // o arquivo PDF (file system), o hash e o conteúdo do Componente Digital
        // Se não finalizar, primeiramente, a transação da conversão, dá erro em validações, como no afterDownload:
        // O conteúdo do Componente Digital não bate com o hash!
        $transactionId = null;

        try {
            $transactionId = $this->transactionManager->beginNewTransaction();
            foreach ($componentesDigitais as $componenteDigital) {
                if (!$componenteDigital->getAssinaturas()->isEmpty()) {
                    // Se a assinatura for diferente de PAdES, erro (ou é PAdES ou CAdES|null)
                    /** @var Assinatura $assinatura */
                    $assinatura = $componenteDigital->getAssinaturas()->first();
                    if (AssinaturaPadrao::PAdES->value !== $assinatura->getPadrao()) {
                        throw new RuntimeException(
                            'Não é possível assinar PAdES em documentos com assinaturas em padrão diferente - '
                            .$this->getDocumentoLocation(
                                $componenteDigital
                            )
                        );
                    }
                }

                // se for text/html, converte para PDF e continua o processo
                if (mb_strtolower('text/html') === mb_strtolower(trim($componenteDigital->getMimetype()))) {
                    $this->componenteDigitalResource->convertHtmlToPDF(
                        $componenteDigital,
                        $transactionId,
                        true
                    );
                } elseif (mb_strtolower('application/pdf') !== mb_strtolower(trim($componenteDigital->getMimetype()))) {
                    throw new RuntimeException(
                        'Não é possível assinatura PAdES em arquivo '.$componenteDigital->getMimetype(
                        ).' ('.$componenteDigital->getFileName().')'
                    );
                }
            }
            $this->transactionManager->commit($transactionId);
        } catch (Throwable $exception) {
            if (!empty($transactionId)) {
                $this->transactionManager->resetTransaction($transactionId);
            }

            throw $exception;
        }
    }

    /**
     * <pre>
     * Faz o achatamento de conteúdo application/pdf nos componentes digitais.
     * Remove metadados, formulários, links, botões, assinaturas digitais...
     *
     * Obs: Este procedimento é recomendado apenas para a remoção de assinaturas digitais corrompidas,
     * pois altera o conteúdo do PDF.
     * O processo adequado para remoção de assinatura digital mantém o PDF em seu estado original!
     * </pre>
     *
     * @param ComponenteDigital[] $componentesDigitais
     *
     * @return void
     *
     * @throws Throwable
     */
    public function flattenComponentesDigitais(array $componentesDigitais): void
    {
        $transactionId = null;

        try {
            $transactionId = $this->transactionManager->beginNewTransaction();
            foreach ($componentesDigitais as $componenteDigital) {
                $this->flattenComponenteDigital($componenteDigital, $transactionId);
            }
            $this->transactionManager->commit($transactionId);
        } catch (Throwable $exception) {
            if (!empty($transactionId)) {
                $this->transactionManager->resetTransaction($transactionId);
            }

            throw $exception;
        }
    }

    /**
     * <pre>
     * Verifica se o conteúdo do componente digital é um application/pdf e faz o achatamento do PDF.
     * Remove metadados, formulários, links, botões, assinaturas digitais...
     *
     * Obs: Este procedimento é recomendado apenas para a remoção de assinaturas digitais corrompidas,
     * pois altera o conteúdo do PDF.
     * O processo adequado para remoção de assinatura digital mantém o PDF em seu estado original!
     * </pre>
     *
     * @param ComponenteDigital $componenteDigitalEntity
     * @param string $transactionId
     * @param bool $verifySignature
     *
     * @return bool true = teve transformação, false = não teve transformação
     */
    public function flattenComponenteDigital(
        ComponenteDigital $componenteDigitalEntity,
        string $transactionId,
        bool $verifySignature = true
    ): bool {
        try {
            // se não for PDF, retorna
            if ((mb_strtolower('application/pdf') !== mb_strtolower(trim($componenteDigitalEntity->getMimetype())))) {
                return false;
            }

            $pdfContent = $componenteDigitalEntity->getConteudo();
            if (empty($pdfContent)) {
                // Recupera o conteúdo do PDF
                $pdfContent = $this->componenteDigitalResource->download(
                    $componenteDigitalEntity->getId(),
                    $transactionId
                )?->getConteudo();
            }

            // se ainda estiver vazio/nulo, retorna
            if (empty($pdfContent)) {
                return false;
            }

            $pdfBase64 = base64_encode($pdfContent);

            // se é pra verificar assinatura e ela é válida, retorna
            if ($verifySignature && $this->assinaturaHelper->isValidPAdES($pdfBase64)) {
                return false;
            }

            // Remove os metadados do PDF
            $pdfBase64 = $this->assinaturaHelper->fattenPdf($pdfBase64);

            // Se o fattenPdf() não retornou conteúdo
            if (empty($pdfBase64)) {
                return false;
            }

            /** @var ComponenteDigitalDTO $componenteDigitalDto */
            $componenteDigitalDto = $this->componenteDigitalResource->getDtoForEntity(
                $componenteDigitalEntity->getId(),
                ComponenteDigitalDTO::class
            );

            // Atualiza o conteúdo no banco de dados
            // componente digital
            $componenteDigitalDto->setHashAntigo($componenteDigitalEntity->getHash());
            $componenteDigitalDto->setConteudo('data:application/pdf;base64,'.$pdfBase64);

            $this->componenteDigitalResource->update(
                $componenteDigitalEntity->getId(),
                $componenteDigitalDto,
                $transactionId
            );
        } catch (Throwable $throwable) {
            $this->logger->error($throwable->getMessage());
        }

        return true;
    }

    /**
     * @param Usuario|string|null $usuario
     * @param int|null            $documentoId
     * @param string              $message
     * @param bool                $mercure
     * @param bool                $bd
     *
     * @return void
     *
     * @throws JsonException
     */
    public function notificaUsuario(
        Usuario|string|null $usuario,
        ?int $documentoId,
        string $message,
        bool $mercure = true,
        bool $bd = true
    ): void {
        if (empty($usuario)) {
            return;
        }

        if (null !== $documentoId && !str_contains($message, ' > Tarefa') && !str_contains($message, ' > Juntada')) {
            $message .= ' - '.$this->getDocumentoLocationById($documentoId);
        }

        // Notifica via Mercure
        if ($mercure) {
            if (null === $documentoId) {
                $documentoId = -1;
            }
            $update = new Update(
                (is_string($usuario) ? $usuario : $usuario->getUsername()),
                json_encode([
                    'assinatura' => [
                        'action' => 'SIGN_ERROR',
                        'documentoId' => $documentoId,
                        'message' => $message,
                    ],
                ], JSON_THROW_ON_ERROR)
            );
            $this->hub->publish($update);
        }

        // Notifica via BD
        if ($bd && !is_string($usuario)) {
            $tempo = new DateTime();
            $tempo->add(new DateInterval('PT2M'));

            $modalidadeNotificacao = $this->modalidadeNotificacaoRepository
                ->findOneBy(
                    ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_notificacao.const_1')]
                );
            $tipoNotificacao = $this->tipoNotificacaoResource->findOneBy(
                ['nome' => $this->parameterBag->get('constantes.entidades.tipo_notificacao.const_6')]
            );
            $transactionId = null;
            try {
                $transactionId = $this->transactionManager->beginNewTransaction();
                $notificacaoDto = new Notificacao();
                $notificacaoDto->setDestinatario($usuario);
                $notificacaoDto->setModalidadeNotificacao($modalidadeNotificacao);
                $notificacaoDto->setTipoNotificacao($tipoNotificacao);
                $notificacaoDto->setConteudo($message);
                $notificacaoDto->setDataHoraExpiracao($tempo);
                $notificacaoDto->setUrgente(true);
                $this->notificacaoResource->create($notificacaoDto, $transactionId);
                $this->transactionManager->commit($transactionId);
            } catch (Throwable) {
                if (!empty($transactionId)) {
                    $this->transactionManager->resetTransaction($transactionId);
                }
            }
        }
    }

    /**
     * Criar a entidade Assinatura.
     *
     * @param int|ComponenteDigital $componenteDigital
     * @param string $assinaturaBase64
     * @param string $cadeiaPEM
     * @param string|null $cadeiaPkiPath
     * @param string $algoritmoHash
     * @param AssinaturaPadrao $padrao
     * @param AssinaturaProtocolo $assinaturaProtocolo
     * @param string $transactionId
     * @param Usuario $usuario
     * @return Assinatura
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createSignatureEntity(
        int|ComponenteDigital $componenteDigital,
        string $assinaturaBase64,
        string $cadeiaPEM,
        ?string $cadeiaPkiPath,
        string $algoritmoHash,
        AssinaturaPadrao $padrao,
        AssinaturaProtocolo $assinaturaProtocolo,
        string $transactionId,
        Usuario $usuario,
    ): Assinatura {
        if ('assinatura_teste' === $assinaturaBase64) {
            $assinaturaBase64 = base64_encode($assinaturaBase64);
        }

        if ($componenteDigital instanceof ComponenteDigital) {
            $componenteDigitalEntity = $componenteDigital;
        } else {
            $componenteDigitalEntity = $this->componenteDigitalResource->findOne($componenteDigital);
        }

        $assinaturaDTO = new AssinaturaDTO();
        $assinaturaDTO->setComponenteDigital($componenteDigitalEntity);
        $assinaturaDTO->setAssinatura($assinaturaBase64);
        $assinaturaDTO->setCadeiaCertificadoPEM($cadeiaPEM);
        $assinaturaDTO->setCadeiaCertificadoPkiPath($cadeiaPkiPath);
        $assinaturaDTO->setAlgoritmoHash($algoritmoHash);
        $assinaturaDTO->setPadrao($padrao->value);
        $assinaturaDTO->setProtocolo($assinaturaProtocolo->value);
        $this->checkChavePublica($assinaturaDTO);

        $assinatura = $this->assinaturaResource->create($assinaturaDTO, $transactionId);
        $assinatura->setCriadoPor($usuario);

        return $assinatura;
    }

    /**
     * Configuração das assinaturas.
     *
     * @param AssinaturaPadrao $padrao
     *
     * @return array
     */
    public function getAssinaturaConfig(AssinaturaPadrao $padrao): array
    {
        if ($this->parameterBag->has('kernel.environment')) {
            $ambiente = mb_strtolower($this->parameterBag->get('kernel.environment'));
        } else {
            $ambiente = 'prod';
        }
        if ($this->suppParameterBag->has('supp_core.administrativo_backend.assinatura.config')) {
            $assinaturaConfig = $this->suppParameterBag->get('supp_core.administrativo_backend.assinatura.config');

            return $assinaturaConfig['ambiente'][$ambiente][$padrao->value];
        }

        if ('dev' === $ambiente) {
            if (AssinaturaPadrao::PAdES === $padrao) {
                return [
                    'active' => true,
                    'orientation' => 'VR-TD',
                    'visible' => true,
                    'convertToHtmlAfterRemove' => true,
                    'test' => true,
                ];
            } else {
                return [
                    'active' => true,
                    'test' => true,
                ];
            }
        } elseif (AssinaturaPadrao::PAdES === $padrao) {
            return [
                'active' => true,
                'orientation' => 'VR-TD',
                'visible' => true,
                'convertToHtmlAfterRemove' => true,
                'test' => false,
            ];
        } else {
            return [
                'active' => true,
                'test' => false,
            ];
        }
    }

    /**
     * Recuperar a localização do Documento NUP/Tarefa/Minuta.
     *
     * @param int|null $documentoId
     *
     * @return string
     */
    public function getDocumentoLocationById(?int $documentoId): string
    {
        if (null === $documentoId) {
            return '';
        }

        $documento = $this->documentoResource->findOne($documentoId);
        if (null !== $documento) {
            return $this->getDocumentoLocation($documento);
        }

        return '';
    }

    /**
     * Recuperar a localização do Documento NUP/Tarefa/Minuta.
     *
     * @param Documento|ComponenteDigital|null $obj
     *
     * @return string
     */
    public function getDocumentoLocation(Documento|ComponenteDigital|null $obj): string
    {
        if (null === $obj) {
            return '';
        }

        // Documento
        $documento = $obj instanceof Documento ? $obj : $obj->getDocumento();
        if (null === $documento) {
            return '';
        }
        // Processo
        $processo = $documento->getProcessoOrigem();
        if (null === $processo) {
            return '';
        }
        // NUP
        $nupStr = $processo->getNUPFormatado();
        // Tarefa
        $tarefa = $documento->getTarefaOrigem();
        // Minutas
        $minutas = $tarefa?->getMinutas();
        // Juntada
        /* @var Juntada $juntada */
        $juntada = $documento->getJuntadaAtual();
        $vinculacaoDocumentoPrincipal = $documento->getVinculacaoDocumentoPrincipal()?->get(0);

        // Documento relacionado à juntada
        if (null !== $juntada) {
            $tipoDocumentoDesc = $documento?->getTipoDocumento()?->getDescricao();

            return $nupStr.' > Juntada '.$juntada->getNumeracaoSequencial().' > '.$tipoDocumentoDesc;
        }

        // Documento relacionado à tarefa | Sem vínculo com documento principal - documento principal
        if (empty($vinculacaoDocumentoPrincipal)) {
            $key = $minutas?->indexOf($documento) + 1;
            $docStr = $documento?->getTipoDocumento()?->getSigla().$key;

            return $nupStr.' > Tarefa '.$tarefa?->getId().' > '.$docStr;
        } else {
            // Documento não relacionado à tarefa | Com vínculo com o documento principal - documento anexo
            /** @var Documento $documentoPrincipal */
            $documentoPrincipal = $vinculacaoDocumentoPrincipal->getDocumento();
            $tarefa = $documentoPrincipal?->getTarefaOrigem();
            $keyMinuta = $tarefa?->getMinutas()->indexOf($documentoPrincipal) + 1;
            $docPrincipalStr = $documentoPrincipal?->getTipoDocumento()?->getSigla().$keyMinuta;
            $docStr = $documento?->getTipoDocumento()?->getSigla();

            $vinculacoesDocumento = $documentoPrincipal->getVinculacoesDocumentos();
            $vinculacao = $vinculacoesDocumento->findFirst(function ($key, $vinculacao) use ($documento) {
                /* @var VinculacaoDocumento $vinculacao */
                return $vinculacao->getDocumentoVinculado()->getId() === $documento->getId();
            });
            $keyAnexo = $vinculacoesDocumento->indexOf($vinculacao) + 1;

            return $nupStr.' > Tarefa '.$tarefa?->getId().' > '.$docPrincipalStr.' > ANEXO'.$keyAnexo.' ('.$docStr.')';
        }
    }

    /**
     * Retorna a quantidade de assinaturas internas ao PDF.
     *
     * @param string|null $pdfContent
     *
     * @return int
     */
    public function getPdfCountSignature(?string $pdfContent): int
    {
        $qtdAssinaturas = $this->assinaturaHelper->getCountSignature($pdfContent);
        if ($qtdAssinaturas < 0) {
            $this->logger->critical("Erro ao realizar contagem de assinaturas no PDF!");
        }

        return $qtdAssinaturas;
    }

    /**
     * Retorna o primeiro componente digital listado no Redis como assinando
     *
     * @param array $componentesDigitais
     * @return ComponenteDigital|null
     */
    public function getFirstComponenteDigitalSigning(array $componentesDigitais): ?ComponenteDigital
    {
        foreach ($componentesDigitais as $componenteDigital) {
            if ($this->redisClient->exists('Assinando:'.$componenteDigital->getId())) {
                return $componenteDigital;
            }
        }

        return null;
    }

    /**
     * Marca, no Redis, os componentes digitais que estão sendo assinados
     *
     * @param array $componentesDigitais
     * @return void
     */
    public function setComponenteDigitalSigning(array $componentesDigitais): void
    {
        foreach ($componentesDigitais as $componenteDigital) {
            $this->redisClient->set(
                'Assinando:'.$componenteDigital->getId(),
                $componenteDigital->getId(),
                120
            );
        }
    }

    /**
     * Remove, do Redis, os componentes digitais que foram marcados como assinando
     * @param array $componentesDigitais
     * @return void
     */
    public function delComponenteDigitalSigning(array $componentesDigitais): void
    {
        $componentesDigitaisKeys = [];
        foreach ($componentesDigitais as $componenteDigital) {
            $componentesDigitaisKeys[] ='Assinando:'
                .($componenteDigital instanceof ComponenteDigital ? $componenteDigital->getId() : $componenteDigital);
        }

        $this->redisClient->del($componentesDigitaisKeys);
    }

    /**
     * Verificar se um usuário já assinou um componente digital
     *
     * @param ComponenteDigital $componenteDigital
     * @param Usuario $usuario
     * @return Assinatura|null
     */
    public function findAssinatura(ComponenteDigital $componenteDigital, Usuario $usuario): ?Assinatura
    {
        return $this->assinaturaResource->findOneBy(
            ['componenteDigital' => $componenteDigital, 'criadoPor' => $usuario],
            null,
            false
        );
    }

    /**
     * Decodificar Base64 URL Safe
     *
     * @param string $base64UrlSafe
     * @return string
     */
    private function base64UrlSafeDecode(string $base64UrlSafe): string
    {
        // Substitui os caracteres específicos de URL
        $base64UrlSafe = str_replace(['-', '_'], ['+', '/'], $base64UrlSafe);

        // Calcula o número de caracteres de padding necessários
        $padding = strlen($base64UrlSafe) % 4;
        if ($padding > 0) {
            $base64UrlSafe .= str_repeat('=', 4 - $padding);
        }

        // Decodifica a string Base64, neste ponto, Base64 comum
        return base64_decode($base64UrlSafe);
    }

    /**
     * Envia mensagem, relacionada à assinatura, para um tópico no Mercure
     * ex: {"assinatura":{"action":"SIGN_FINISHED","documentoId":125,"message":"Assinatura conclu\u00edda"}}
     *
     * @param string      $topic
     * @param string      $action
     * @param string|null $message
     * @param int|null    $componenteDigitalId
     * @param int|null    $documentoId
     *
     * @return void
     */
    public function publishOnMercure(
        string $topic,
        string $action,
        string $message = null,
        int $componenteDigitalId = null,
        int $documentoId = null
    ): void {
        try {
            $contentAction = compact('action', 'documentoId', 'componenteDigitalId', 'message');

            // remover os atributos de valor null
            $content = [
                'assinatura' => array_filter(
                    $contentAction,
                    static function ($value) {
                        return null !== $value;
                    }
                ),
            ];

            // Publicar
            $update = new Update(
                $topic,
                json_encode(
                    $content,
                    JSON_THROW_ON_ERROR
                )
            );
            $this->hub->publish($update);
        } catch (Throwable) {
            $this->logger->critical("Erro ao publicar no Mercure!", $topic);
        }
    }
}
