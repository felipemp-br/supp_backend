<?php

declare(strict_types=1);
/**
 * /src/Controller/AssinaturaController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use Exception;
use OpenApi\Attributes as OA;
use Redis;
use RedisException;
use RuntimeException;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AssinaturaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Assinatura\Message\AssinaDocumentoMessage;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Enums\AssinaturaPadrao;
use SuppCore\AdministrativoBackend\Enums\AssinaturaProtocolo;
use SuppCore\AdministrativoBackend\Helpers\AssinaturaLogHelper;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use SuppCore\AdministrativoBackend\Security\LoginUnicoGovBrService;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Utils\AssinaturaService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Throwable;

use function in_array;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method AssinaturaResource getResource()
 */
#[Route(path: '/v1/administrativo/assinatura')]
#[OA\Tag(name: 'Assinatura')]
class AssinaturaController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\User\CreateAction;
    use Actions\User\UpdateAction;
    use Actions\User\PatchAction;
    use Actions\User\DeleteAction;
    use Actions\Colaborador\CountAction;

    /**
     * @param AssinaturaResource $resource
     * @param ResponseHandler $responseHandler
     * @param AssinaturaService $assinaturaService
     * @param TokenStorageInterface $tokenStorage
     * @param HubInterface $hub
     * @param ComponenteDigitalResource $componenteDigitalResource
     * @param AssinaturaLogHelper $logger
     * @param SuppParameterBag $suppParameterBag
     */
    public function __construct(
        AssinaturaResource $resource,
        ResponseHandler $responseHandler,
        private readonly AssinaturaService $assinaturaService,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly HubInterface $hub,
        private readonly ComponenteDigitalResource $componenteDigitalResource,
        private readonly AssinaturaLogHelper $logger,
        private readonly SuppParameterBag $suppParameterBag,
    ) {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        $this->init($resource, $responseHandler);
    }

    /**
     * @throws Throwable
     */
    #[Route(path: '/documentos', methods: ['POST'])]
    #[RestApiDoc]
    public function documentos(
        Request $request,
        ?array $allowedHttpMethods = null,
    ): ?Response {
        $allowedHttpMethods ??= ['POST'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        $credential = $request->get('credential');
        $documentosIds = json_decode($request->get('documentos', []));
        $incluiAnexos = (bool) filter_var($request->get('incluiAnexos', false), FILTER_VALIDATE_BOOLEAN);
        $pades = (bool) filter_var($request->get('pades', false), FILTER_VALIDATE_BOOLEAN);
        $removeAssinaturaInvalida = (bool) filter_var(
            $request->get('removeAssinaturaInvalida', true),
            FILTER_VALIDATE_BOOLEAN
        );

        $transactionId = null;
        try {
            /** @var Usuario $usuario */
            $usuario = $this->tokenStorage->getToken()?->getUser();
            if (null === $usuario) {
                throw new RuntimeException('Usuário não encontrado!');
            }

            $assinaturaConfigPades = $this->assinaturaService->getAssinaturaConfig(AssinaturaPadrao::PAdES);
            if (!$assinaturaConfigPades['active'] && $pades) {
                throw new RuntimeException('Assinatura PAdES desabilitada!');
            }

            $assinaturaConfigCades = $this->assinaturaService->getAssinaturaConfig(AssinaturaPadrao::CAdES);
            if (!$assinaturaConfigCades['active'] && !$pades) {
                throw new RuntimeException('Assinatura CAdES desabilitada!');
            }

            if (!$credential) {
                throw new RuntimeException('Credenciais não fornecidas');
            }

            if (empty($documentosIds)) {
                throw new RuntimeException('Nenhum documento foi enviado para o Backend assinar');
            }

            if (in_array(null, $documentosIds, true)) {
                throw new RuntimeException('Lista de Documentos ID contém item nulo');
            }

            if (count(array_filter($documentosIds, 'is_int')) !== count($documentosIds)) {
                throw new RuntimeException('Lista de Documentos ID contém numeração inválida.');
            }

            $componentesDigitais = $this->assinaturaService->componentesDigitais($documentosIds, $incluiAnexos);
            if (empty($componentesDigitais)) {
                throw new RuntimeException('Não foi possível recuperar os componentes digitais dos documentos!');
            }

            $sincrono = (1 === count($componentesDigitais));

            $componenteDigitalSigning = $this->
            assinaturaService->getFirstComponenteDigitalSigning($componentesDigitais);

            if ($componenteDigitalSigning) {
                throw new RuntimeException(
                    'O documento selecionado está em processo de assinatura. Por favor, aguarde! '
                    .$this->assinaturaService->getDocumentoLocation($componenteDigitalSigning)
                );
            }

            [$protocol] = array_map(fn ($p) => urldecode($p), explode(':', $credential));

            $credential = match (mb_strtolower($protocol)) {
                'a1' => $this->assinaturaService->checkA1Credential($credential),
                'neoid' => $this->assinaturaService->checkNeoIdCredential($usuario, $credential),
                default => $credential,
            };

            // Converter em PDF, caso seja necessário
            if ($pades && $sincrono) {
                // Flatten
                if ($removeAssinaturaInvalida) {
                    $this->assinaturaService->flattenComponentesDigitais($componentesDigitais);
                }
                $this->assinaturaService->convertToPdf($componentesDigitais);
            }

            $transactionId = $this->transactionManager->beginNewTransaction();
            $context = RequestHandler::getContext($request);
            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            if ($sincrono) {
                try {
                    // marcar como assinando
                    $this->assinaturaService->setComponenteDigitalSigning($componentesDigitais);

                    // Log de início da assinatura
                    $this->logger->info(
                        'Iniciando assinatura',
                        $usuario,
                        $protocol,
                        $pades,
                        $incluiAnexos,
                        true,
                        $documentosIds,
                        $componentesDigitais
                    );

                    // Síncrono: Executa diretamente
                    $assinaturas = $this->assinaturaService->sign(
                        $credential,
                        $documentosIds,
                        $usuario,
                        $pades,
                        $incluiAnexos,
                        $this->getAuthType()
                    );

                    // O assinador externo já envia esta mensagem via Mercure
                    if ('a3' !== $protocol) {
                        $this->assinaturaService->publishOnMercure(
                            $usuario->getUsername(),
                            'SIGN_FINISHED',
                            'Assinatura concluída',
                            null,
                            $documentosIds[0]
                        );

                        // Log de sucesso da assinatura
                        $this->logger->info(
                            'Sucesso na assinatura',
                            $usuario,
                            $protocol,
                            $pades,
                            $incluiAnexos,
                            true,
                            $documentosIds,
                            $componentesDigitais,
                            $assinaturas
                        );
                    }
                } catch (Throwable $throwable) {
                    $this->logger->critical(
                        'Erro na assinatura',
                        $usuario,
                        $protocol,
                        $pades,
                        $incluiAnexos,
                        true,
                        $documentosIds,
                        null,
                        null,
                        $throwable->getMessage().' in '.$throwable->getFile().':'.$throwable->getLine(),
                        $throwable->getTraceAsString(),
                    );

                    throw $throwable;
                } finally {
                    // Desmarcar como assinando
                    if ('a3' !== $protocol) {
                        $this->assinaturaService->delComponenteDigitalSigning($componentesDigitais);
                    }
                }
            } else {
                // Assíncrono: Vai para a fila assina_documento (Rabbit)
                foreach ($documentosIds as $documentoId) {
                    $this->transactionManager->addAsyncDispatch(
                        (new AssinaDocumentoMessage())
                            ->setUsuarioId($usuario->getId())
                            ->setUsername($usuario->getUsername())
                            ->setDocumentoId($documentoId)
                            ->setCredential($credential)
                            ->setPades($pades)
                            ->setIncluiAnexos($incluiAnexos)
                            ->setAuthType($this->getAuthType())
                            ->setRemoveAssinaturaInvalida($removeAssinaturaInvalida),
                        $transactionId
                    );
                }
            }

            // Realiza o bus dispatch message
            $this->transactionManager->commit($transactionId);

            if (!$sincrono) {
                $this->logger->info(
                    'Documentos enviados à fila',
                    $usuario,
                    $protocol,
                    $pades,
                    $incluiAnexos,
                    false,
                    $documentosIds,
                    $componentesDigitais
                );

                $this->assinaturaService->notificaUsuario(
                    $usuario,
                    null,
                    'Documentos enviados à fila de assinaturas!',
                    false
                );
            }

            return new JsonResponse(['documentosIds' => $documentosIds]);
        } catch (Throwable $exception) {
            if (!empty($transactionId)) {
                $this->transactionManager->resetTransaction($transactionId);
            }
            throw $this->handleRestMethodException($exception);
        }
    }


    /**
     * Endpoint para obter o token de assinatura do NEOID
     * e usá-lo em seguida para a chamada dos endpoint's de assinatura.
     *
     * @throws Throwable
     */
    #[Route(path: '/neoid_qr_code/{id}', methods: ['GET'])]
    #[RestApiDoc]
    public function neoIdQrCode(
        Request $request,
        Usuario $usuario,
        ?array $allowedHttpMethods = null,
    ): Response {
        $allowedHttpMethods ??= ['GET'];
        $this->validateRestMethod($request, $allowedHttpMethods);

        return $this->assinaturaService->neoIdQrCode($usuario);
    }

    /**
     * Endpoint action to get jnlp.
     *
     * @throws RedisException
     */
    #[Route(path: '/{secret}/get_jnlp', methods: ['GET'])]
    #[RestApiDoc]
    public function getJNLPAction(
        string $secret,
        ParameterBagInterface $parameterBag,
        Redis $redisClient,
    ): Response {
        if (0 !== $redisClient->exists($secret)) {
            $argument = $redisClient->get($secret);
            $codebase = $parameterBag->get('supp_core.administrativo_backend.url_sistema_backend').'/';
            $conteudoJNLP = '<?xml version="1.0" encoding="utf-8"?><jnlp codebase="'
                .$codebase
                .'"><information><title>Assinador de Arquivos</title><vendor>Advocacia-Geral da União</vendor>'
                .'<homepage href="https://www.agu.gov.br"/><description>Assinador de arquivos (PKCS#11)</description>'
                .'<description kind="short">Assinador de Arquivos</description><offline-allowed/></information>'
                .'<security><all-permissions/></security><resources><j2se version="1.8+"/>'
                .'<jar eager="true" href="'.$codebase.'assinador-supp-0.0.1.jar" main="true"/></resources>'
                .'<application-desc main-class="agu.security.app.SmartCardSignerApp"><argument>'.$argument.'</argument>'
                .'</application-desc></jnlp>';
        } else {
            throw new AccessDeniedException();
        }

        $response = new Response();

        $response->headers->set('Content-type', 'application/x-java-jnlp-file');
        $response->headers->set('Content-Disposition', 'inline; filename=assinador-supp-0.0.1.jnlp');
        $response->headers->set('Content-length', (string) strlen($conteudoJNLP));
        $response->sendHeaders();
        $response->setContent($conteudoJNLP);

        return $response;
    }

    /**
     * Endpoint para obter o token de Revalidação de senha no GovBr
     * e usá-lo em seguida para a chamada dos endpoint's de assinatura.
     *
     * @throws Throwable
     *
     * @noinspection PhpUnusedParameterInspection
     */
    #[Route(path: '/govbr_token_revalida', methods: ['POST'])]
    #[RestApiDoc]
    public function ssoGovBrRevalidaAction(
        Request $request,
        LoginUnicoGovBrService $loginUnicoGovBrService,
        TokenStorageInterface $tokenStorage,
        ?array $allowedHttpMethods = null,
    ): Response {
        $allowedHttpMethods ??= ['POST'];
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            if ($request->get('state') && $request->get('code')) {
                $tokenRevalida = $loginUnicoGovBrService->retornaDadosRevalida(
                    $request->get('code'),
                    $request->get('state')
                );

                return new JsonResponse([
                    'jwt' => $tokenRevalida,
                ]);
            }

            throw new BadCredentialsException('Dados incorretos!');
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }

    /**
     * Endpoint action to broadcast assinatura status.
     *
     * @throws RedisException
     */
    #[Route(path: '/status', methods: ['POST'])]
    #[RestApiDoc]
    public function publishStatusAction(
        Request $request,
        TokenStorageInterface $tokenStorage,
        HubInterface $hub,
        Redis $redisClient,
    ): JsonResponse {
        $documentosId = [];

        $secret = md5((string) $request->get('jwt'));

        if ('' !== $secret && '0' !== $secret) {
            if (0 !== $redisClient->exists($secret)) {
                $argument = json_decode($redisClient->get($secret), true);

                foreach ($argument['files'] as $file) {
                    if (!in_array($file['documentoId'], $documentosId, true)) {
                        $documentosId[] = $file['documentoId'];
                    }
                }
            }
        } else {
            $documentosId[] = $request->get('documentoId');
        }

        foreach ($documentosId as $documentoId) {
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            $update = new Update(
                $tokenStorage->getToken()?->getUser()?->getUsername(),
                json_encode(
                    [
                        'assinatura' => [
                            'action' => $request->get('action'),
                            'documentoId' => $documentoId,
                        ],
                    ]
                )
            );

            $hub->publish($update);
        }

        return new JsonResponse(
            [
                'status' => 'ok',
            ]
        );
    }

    /**
     * Endpoint para obter o token de assinatura do NEOID
     * e usá-lo em seguida para a chamada dos endpoint's de assinatura.
     *
     * @throws Throwable
     */
    #[Route(path: '/neoid_get_token', methods: ['POST'])]
    #[RestApiDoc]
    public function neoIdGetToken(
        Request $request,
        ParameterBagInterface $parameterBag,
        ?array $allowedHttpMethods = null,
    ): Response {
        $allowedHttpMethods ??= ['POST'];
        $this->validateRestMethod($request, $allowedHttpMethods);

        $code = $request->get('code');
        $code_verifier = $request->get('code_verifier');

        try {
            if ($code && $code_verifier) {
                $postFields = 'grant_type=authorization_code'
                    .'&client_id='.$parameterBag->get('supp_core.administrativo_backend.neoid_client_id')
                    .'&client_secret='.$parameterBag->get('supp_core.administrativo_backend.neoid_client_secret')
                    .'&code='.$code
                    .'&code_verifier='.$code_verifier
                    .'&redirect_uri='.urlencode(
                        $parameterBag->get('supp_core.administrativo_backend.neoid_redirect_uri')
                    );

                $headers = [
                    'Content-Type: application/x-www-form-urlencoded',
                ];

                $chToken = curl_init($parameterBag->get('supp_core.administrativo_backend.neoid_url').'/token');
                curl_setopt(
                    $chToken,
                    CURLOPT_URL,
                    $parameterBag->get('supp_core.administrativo_backend.neoid_url').'/token'
                );
                curl_setopt($chToken, CURLOPT_POSTFIELDS, $postFields);
                curl_setopt($chToken, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($chToken, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($chToken, CURLOPT_HTTPHEADER, $headers);
                $response = json_decode(curl_exec($chToken), true);
                curl_close($chToken);

                return new JsonResponse([
                    'jwt' => $response,
                ]);
            }

            throw new BadCredentialsException('Dados incorretos!');
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }

    /**
     * Endpoint para assinar documentos com certificado NEOID.
     *
     * @throws Throwable
     */
    #[Route(path: '/assina_neoid', methods: ['POST'])]
    #[RestApiDoc]
    public function assinaNeoId(
        Request $request,
        ParameterBagInterface $parameterBag,
        TokenStorageInterface $tokenStorage,
        ?array $allowedHttpMethods,
        ComponenteDigitalResource $componenteDigitalResource,
        AssinaturaResource $assinaturaResource,
    ): Response {
        $allowedHttpMethods ??= ['POST'];
        $this->validateRestMethod($request, $allowedHttpMethods);

        $tipoAssinatura = $request->get('assinatura');
        $tokenNeoId = $request->get('plainPassword');
        $assinadoPor = $request->get('assinadoPor');
        $componentesDigitaisBloco = $request->get('componentesDigitaisBloco');
        $assinaturas = [];

        if ('NEOID' === $tipoAssinatura
            && isset($tokenNeoId)
            && $tokenStorage->getToken()
            && $tokenStorage->getToken()->getUser()
        ) {
            try {
                $transactionId = $this->transactionManager->begin();
                $cpfUsuario = $tokenStorage->getToken()->getUser()->getUserIdentifier();
                if ($cpfUsuario !== $assinadoPor) {
                    throw new RuntimeException('Não foi possível a assinatura, CPF não corresponde ao usuário logado.');
                }

                $hashes = [];
                foreach ($componentesDigitaisBloco as $componenteDigitalId) {
                    $hashes[] = [
                        'id' => "$componenteDigitalId",
                        'alias' => 'componente_digital_'.$componenteDigitalId,
                        'hash' => base64_encode(hash(
                            'SHA256',
                            $componenteDigitalResource->download(
                                $componenteDigitalId,
                                $transactionId
                            )?->getConteudo(),
                            true
                        )),
                        'hash_algorithm' => '2.16.840.1.101.3.4.2.1',
                        'signature_format' => 'CMS',
                    ];
                }

                $data = json_encode([
                    'hashes' => $hashes,
                ]);

                $headers = [
                    'Content-Type: application/json',
                    'Authorization: Bearer '
                    .$tokenNeoId,
                ];

                // realizando a assinatura digital do componenteDigital
                $assinaComponente = curl_init(
                    $parameterBag->get('supp_core.administrativo_backend.neoid_url').'/signature'
                );
                curl_setopt(
                    $assinaComponente,
                    CURLOPT_URL,
                    $parameterBag->get('supp_core.administrativo_backend.neoid_url').'/signature'
                );
                curl_setopt($assinaComponente, CURLOPT_POSTFIELDS, $data);
                curl_setopt($assinaComponente, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($assinaComponente, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($assinaComponente, CURLOPT_HTTPHEADER, $headers);
                $arrayAssinados = json_decode(curl_exec($assinaComponente), true);
                curl_close($assinaComponente);

                // busca o certificado PEM
                $headersCertificado = [
                    'Content-Type: application/json',
                    'Authorization: Bearer '
                    .$tokenNeoId,
                ];

                $buscaCertificado = curl_init(
                    $parameterBag->get('supp_core.administrativo_backend.neoid_url').'/certificate-discovery'
                );
                curl_setopt(
                    $buscaCertificado,
                    CURLOPT_URL,
                    $parameterBag->get('supp_core.administrativo_backend.neoid_url').'/certificate-discovery'
                );
                curl_setopt($buscaCertificado, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($buscaCertificado, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($buscaCertificado, CURLOPT_HTTPHEADER, $headersCertificado);
                $certificado = json_decode(curl_exec($buscaCertificado), true);
                curl_close($buscaCertificado);

                foreach ($arrayAssinados['signatures'] as $cdAssinado) {
                    preg_match(
                        '/(?<=-----BEGIN PKCS7-----)[\s\S]*?(?=-----END PKCS7-----)/',
                        $cdAssinado['raw_signature'],
                        $matches
                    );
                    $conteudo_base64 = preg_replace('/\s+/', '', $matches[0]);

                    $componenteDigital = $componenteDigitalResource->findOne((int) $cdAssinado['id']);

                    $assinaturaNeoId = new Assinatura();
                    $assinaturaNeoId->setComponenteDigital($componenteDigital);
                    $assinaturaNeoId->setAssinatura($conteudo_base64);
                    $assinaturaNeoId->setAlgoritmoHash('SHA256withRSA');
                    $assinaturaNeoId->setCadeiaCertificadoPEM($certificado['certificates'][0]['certificate']);
                    $assinaturas['entities'][] = $assinaturaResource->create($assinaturaNeoId, $transactionId);
                    $assinaturas['total'] = count($assinaturas['entities']);
                }

                $this->transactionManager->commit($transactionId);
            } catch (Exception $e) {
                throw new RuntimeException($e->getMessage());
            }
        }

        return $this
            ->getResponseHandler()
            ->createResponse($request, $assinaturas);
    }


    /**
     *  Recebe, do Assinador SUPP, a assinatura do PDF.
     *  Monta o PDF com a assinatura PAdES e faz update no componente digital.
     *
     * @param Request $request
     * @param array|null $allowedHttpMethods
     * @return Response
     * @throws Throwable
     */
    #[Route(path: '/pades', methods: ['POST'])]
    #[RestApiDoc]
    public function padesAction(
        Request $request,
        ?array $allowedHttpMethods,
    ): Response {
        $allowedHttpMethods ??= ['POST'];
        $this->validateRestMethod($request, $allowedHttpMethods);
        $documentoId = null;
        $componenteDigitalId = null;

        /** @var Usuario $usuario */
        $usuario = $this->tokenStorage->getToken()?->getUser();
        $transactionId = null;
        try {
            $documentoId = $request->get('documentoId');
            $componenteDigitalId = $request->get('componenteDigital');
            $cadeiaCertificadoPEM = $request->get('cadeiaCertificadoPEM');
            $cadeiaCertificadoPkiPath = $request->get('cadeiaCertificadoPkiPath');
            $algoritmoHash = $request->get('algoritmoHash');
            // $hash = $request->get('hash');
            $assinaturaBase64 = trim($request->get('assinatura'));

            // Atualiza o conteúdo no banco de dados
            $transactionId = $this->transactionManager->beginNewTransaction();
            $assinaturaEntity = $this->assinaturaService->addSignatureInPdf(
                $componenteDigitalId,
                $assinaturaBase64,
                $cadeiaCertificadoPEM,
                $cadeiaCertificadoPkiPath,
                $algoritmoHash,
                AssinaturaProtocolo::A3,
                $transactionId,
                $usuario,
            );
            $this->transactionManager->commit($transactionId);

            $this->logger->info(
                'Sucesso na assinatura',
                $usuario,
                'a3',
                true,
                null,
                null,
                [$documentoId],
                [$componenteDigitalId],
                [$assinaturaEntity]
            );

            // o SIGN_FINISHED é enviado pelo próprio Assinador SUPP, via Mercure
        } catch (Throwable $t) {
            if (!empty($transactionId)) {
                $this->transactionManager->resetTransaction($transactionId);
            }

            // Log de erro na assinatura
            $this->logger->critical(
                'Erro na assinatura',
                $usuario,
                'a3',
                true,
                null,
                null,
                [$documentoId],
                (empty($componenteDigitalId) ? null : [$componenteDigitalId]),
                null,
                $t->getMessage().' in '.$t->getFile().':'.$t->getLine(),
                $t->getTraceAsString()
            );

            // Notificação Erro
            $this->assinaturaService->notificaUsuario($usuario, $documentoId, $t->getMessage());

            throw $this->handleRestMethodException($t);
        } finally {
            if (!empty($componenteDigitalId)) {
                $this->assinaturaService->delComponenteDigitalSigning([$componenteDigitalId]);
            }
        }

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'status' => Response::$statusTexts[Response::HTTP_OK],
            'documentoId' => $documentoId,
            'componenteDigitalId' => $componenteDigitalId,
            'message' => 'Assinatura PAdES efetuada com sucesso!',
        ]);
    }

    /**
     *  Recebe, do Assinador SUPP, a assinatura.
     *  Cria a entidade Assinatura padrão CAdES.
     *  obs: foi criado pois na rota padrão o erro não estava sendo mostrado ao usuário.
     *
     * @param Request $request
     * @param array|null $allowedHttpMethods
     * @return Response
     * @throws Throwable
     */
    #[Route(path: '/cades', methods: ['POST'])]
    #[RestApiDoc]
    public function cadesAction(
        Request $request,
        ?array $allowedHttpMethods,
    ): Response {
        $allowedHttpMethods ??= ['POST'];
        $this->validateRestMethod($request, $allowedHttpMethods);
        $documentoId = null;
        $componenteDigitalId = null;

        /** @var Usuario $usuario */
        $usuario = $this->tokenStorage->getToken()?->getUser();
        $transactionId = null;
        try {
            $documentoId = $request->get('documentoId');
            $componenteDigitalId = $request->get('componenteDigital');
            $cadeiaCertificadoPEM = $request->get('cadeiaCertificadoPEM');
            $cadeiaCertificadoPkiPath = $request->get('cadeiaCertificadoPkiPath');
            $algoritmoHash = $request->get('algoritmoHash');
            // $hash = $request->get('hash');
            // $assinatura = mb_strtoupper(bin2hex(base64_decode(trim($request->get('assinatura')))));
            $assinaturaBase64 = trim($request->get('assinatura'));

            // Atualiza o conteúdo no banco de dados
            $transactionId = $this->transactionManager->beginNewTransaction();
            $assinaturaEntity = $this->assinaturaService->createSignatureEntity(
                $componenteDigitalId,
                $assinaturaBase64,
                $cadeiaCertificadoPEM,
                $cadeiaCertificadoPkiPath,
                $algoritmoHash,
                AssinaturaPadrao::CAdES,
                AssinaturaProtocolo::A3,
                $transactionId,
                $usuario
            );
            $this->transactionManager->commit($transactionId);

            // Log de sucesso da assinatura
            $this->logger->info(
                'Sucesso na assinatura',
                $usuario,
                'a3',
                false,
                null,
                null,
                [$documentoId],
                [$componenteDigitalId],
                [$assinaturaEntity]
            );

            // o SIGN_FINISHED é enviado pelo próprio Assinador SUPP, via Mercure
        } catch (Throwable $t) {
            if (!empty($transactionId)) {
                $this->transactionManager->resetTransaction($transactionId);
            }

            // Log de erro na assinatura
            $this->logger->critical(
                'Erro na assinatura',
                $usuario,
                'a3',
                false,
                null,
                null,
                [$documentoId],
                (empty($componenteDigitalId) ? null : [$componenteDigitalId]),
                null,
                $t->getMessage().' in '.$t->getFile().':'.$t->getLine(),
                $t->getTraceAsString()
            );

            // Notificação Erro
            $this->assinaturaService->notificaUsuario($usuario, $documentoId, $t->getMessage());

            throw $this->handleRestMethodException($t);
        } finally {
            if (!empty($componenteDigitalId)) {
                $this->assinaturaService->delComponenteDigitalSigning([$componenteDigitalId]);
            }
        }

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'status' => Response::$statusTexts[Response::HTTP_OK],
            'documentoId' => $documentoId,
            'componenteDigitalId' => $componenteDigitalId,
            'message' => 'Assinatura CAdES efetuada com sucesso!',
        ]);
    }


    /**
     * Recuperar o tipo de autenticação feita pelo usuário
     *
     * @return string
     */
    public function getAuthType(): string
    {
        // ver o tipo de autenticação
        $authType = '';
        if (!empty($this->tokenStorage?->getToken())) {
            if ($this->tokenStorage->getToken()?->hasAttribute('trusted')) {
                // apiKey, ssoGovBr, ldap, x509
                $authType = match (mb_strtolower($this->tokenStorage->getToken()?->getAttribute('trusted'))) {
                    'ldap' => 'Rede AGU',
                    'ssogovbr' => 'gov.br',
                    'x509' => 'certificado',
                    'apikey' => 'api',
                    default => ''
                };
            } elseif ($this->tokenStorage->getToken() instanceof UsernamePasswordToken) {
                // interno
                $authType = 'CPF';
            }
        }
        return $authType;
    }
}
