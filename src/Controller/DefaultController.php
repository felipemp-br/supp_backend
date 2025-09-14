<?php

declare(strict_types=1);
/**
 * /src/Controller/DefaultController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Controller;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use LogicException;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid as Ruuid;
use Redis;
use RuntimeException;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Counter\CounterManager;
use SuppCore\AdministrativoBackend\Fields\StylesManager;
use SuppCore\AdministrativoBackend\Helpers\Configuration\ConfigurationManager;
use SuppCore\AdministrativoBackend\Utils\HealthzService;
use SuppCore\AdministrativoBackend\Utils\JSON;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;
use Twig\Environment;

use function file_get_contents;

/**
 * Class DefaultController.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Route(path: '/')]
class DefaultController
{
    /**
     * Route for application health check. This action will make some simple tasks to ensure that application is up
     * and running like expected.
     *
     * @see https://kubernetes.io/docs/tasks/configure-pod-container/configure-liveness-readiness-probes/
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     */
    #[Route(path: '/healthz', methods: ['GET'])]
    #[OA\Tag(name: 'Default')]
    #[OA\Response(
        response: 200,
        description: 'success',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'timestamp', type: 'string'),
                ],
                type: 'object',
                example: [
                    'timestamp' => '2018-01-01T13:08:05+00:00',
                ]
            )
        )
    )]
    public function healthzAction(
        HealthzService $healthzService
    ): Response {
        $healthz = $healthzService->check();

        return new JsonResponse(
            [
                'timestamp' => $healthz->getTimestamp(),
            ]
        );
    }

    /**
     * Route for get API version.
     *
     * @throws LogicException
     */
    #[Route(path: '/ok', methods: ['GET'])]
    #[OA\Tag(name: 'Default')]
    #[OA\Response(response: 200, description: 'success')]
    public function okAction(): Response
    {
        return new Response('OK');
    }

    /**
     * Route for get API version.
     *
     * @throws LogicException
     */
    #[Route(path: '/version', methods: ['GET'])]
    #[OA\Tag(name: 'Default')]
    #[OA\Response(
        response: 200,
        description: 'success',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'version', description: 'Version number', type: 'string'),
                ],
                type: 'object',
                example: [
                    'version' => '1.2.3',
                ]
            )
        )
    )]
    public function versionAction(): JsonResponse
    {
        $composerData = JSON::decode((string) file_get_contents(__DIR__.'/../../composer.json'));

        $data = [
            'version' => $composerData->version,
        ];

        return new JsonResponse($data);
    }

    /**
     * Route for get API version.
     */
    #[Route(path: '/config', methods: ['GET'])]
    #[OA\Tag(name: 'Default')]
    #[OA\Response(
        response: 200,
        description: 'success',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'version', description: 'Version number', type: 'string'),
                    new OA\Property(property: 'environment', description: 'Enviroment name', type: 'string'),
                ],
                type: 'object',
                example: [
                    'version' => '1.2.3',
                    'environment' => 'PRODUÇÂO',
                ]
            )
        )
    )]
    public function configAction(
        ConfigurationManager $configurationManager,
        ParameterBagInterface $parameterBag
    ): JsonResponse {
        $composerData = JSON::decode((string) file_get_contents(__DIR__.'/../../composer.json'));
        $environment = $parameterBag->get('supp_core.administrativo_backend.environment');
        $name = $parameterBag->get('supp_core.administrativo_backend.nome_sistema');
        $sigla = $parameterBag->get('supp_core.administrativo_backend.sigla_sistema');
        $email = $parameterBag->get('supp_core.administrativo_backend.email_suporte');
        $logo = $parameterBag->get('supp_core.administrativo_backend.logo_sistema');
        $icone = $parameterBag->get('supp_core.administrativo_backend.icone_sistema');
        $barramento = $parameterBag->get('integracao_barramento')['ativo'];
        $mercure = $parameterBag->get('supp_core.administrativo_backend.url_mercure');
        $assinador = $parameterBag->get('supp_core.administrativo_backend.assinador_version');

        $govBR = [
            'client_id' => $parameterBag->get('supp_core.administrativo_backend.sso_gov_br_client_id'),
            'redirect_uri' => $parameterBag->get('supp_core.administrativo_backend.sso_gov_br_redirect_uri'),
            'sso_url' => $parameterBag->get('supp_core.administrativo_backend.sso_gov_br_sso_url'),
            'revalida_oauth_url' => $parameterBag->get(
                'supp_core.administrativo_backend.sso_gov_br_revalida_oauth_url'
            ),
            'revalida_client_id' => $parameterBag->get(
                'supp_core.administrativo_backend.sso_gov_br_revalida_client_id'
            ),
            'revalida_redirect_uri' => $parameterBag->get(
                'supp_core.administrativo_backend.sso_gov_br_revalida_redirect_uri'
            ),
        ];
        $ldapConf = $parameterBag->get('supp_core.administrativo_backend.ldap_conf');
        $ldapName = '';
        if (is_array($ldapConf) && isset($ldapConf[0]['name'])) {
            $ldapName = $ldapConf[0]['name'];
        }

        // Permite desabilitar alguns tipos de login (env's LOGIN_INTERNO_ATIVO, LOGIN_LDAP_ATIVO e LOGIN_GOVBR_ATIVO)
        $tiposLogin = [];
        foreach (['supp_core.administrativo_backend.login_ldap_ativo',
            'supp_core.administrativo_backend.login_interno_ativo',
            'supp_core.administrativo_backend.login_govbr_ativo'] as $tipoLogin) {
            if ($parameterBag->get($tipoLogin)) {
                $tiposLogin[] = explode('.', $tipoLogin)[2];
            }
        }

        $neoId = [
            'url' => $parameterBag->get('supp_core.administrativo_backend.neoid_url'),
            'client_id' => $parameterBag->get('supp_core.administrativo_backend.neoid_client_id'),
            'redirect_uri' => $parameterBag->get('supp_core.administrativo_backend.neoid_redirect_uri')
        ];

        $configuracoesExternas = $configurationManager->getConfigurations();

        $configuracoes = [
            'version' => $composerData->version,
            'environment' => $environment,
            'name' => $name,
            'sigla' => $sigla,
            'logo' => $logo,
            'icone' => $icone,
            'email' => $email,
            'govBR' => $govBR,
            'ldap' => $ldapName,
            'barramento' => $barramento,
            'mercure' => $mercure,
            'assinador' => $assinador,
            'tiposLogin' => $tiposLogin,
            'neoId' => $neoId
        ];

        return new JsonResponse(array_merge($configuracoesExternas, $configuracoes));
    }

    /**
     * Route for get API version.
     */
    #[Route(path: '/mercure', methods: ['GET'])]
    #[OA\Tag(name: 'Default')]
    #[OA\Response(
        response: 200,
        description: 'success',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'success', description: 'Mercure ativation', type: 'boolean'),
                ],
                type: 'object',
                example: [
                    'success' => true,
                ]
            )
        )
    )]
    public function mercureAction(
        CounterManager $counterManager,
        LoggerInterface $logger,
        HubInterface $hub,
        TokenStorageInterface $tokenStorage,
        Redis $redis,
        ParameterBagInterface $parameterBag,
    ): JsonResponse {
        try {
            // Ativa o cache de contadores
            $keyCache = 'contadores_'.$tokenStorage->getToken()->getUserIdentifier();
            if (!$redis->hExists($keyCache, 'ativo')) {
                $redis->hSet($keyCache, 'ativo', true);
                $redis->expire(
                    $keyCache,
                    (int) $parameterBag->get(
                        'supp_core.administrativo_backend.cached_counters_expiration'
                    )
                );
            }

            $counterManager->proccess();

            $transaction = [
                'uuid' => Ruuid::uuid4()->toString(),
                'action' => 'ALIVE_NOW',
                'payload' => [
                    'processUUID' => 'all',
                ],
            ];

            $update = new Update(
                '/assinador/'.$tokenStorage->getToken()->getUser()->getUserIdentifier(),
                json_encode($transaction)
            );

            $hub->publish($update);

            $data = [
                'success' => true,
            ];
        } catch (Throwable $t) {
            $logger->critical($t->getMessage().' - '.$t->getTraceAsString());
            $data = [
                'success' => false,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Route for post frontend log errors.
     */
    #[Route(path: '/log_collector', methods: ['POST'])]
    #[OA\Tag(name: 'Default')]
    #[OA\RequestBody(
        description: 'Frontend log error',
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    example: [
                        'message' => 'error',
                        'stack' => 'stacktrace',
                    ]
                )
            ),
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'success', description: 'Success', type: 'boolean'),
                ],
                type: 'object',
                example: [
                    'success' => true,
                ]
            )
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 400,
                    'message' => 'Bad Request',
                ]
            )
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 401,
                    'message' => 'Bad credentials',
                ]
            )
        )
    )]
    public function logCollectorAction(
        Request $request,
        LoggerInterface $frontendLogger
    ): JsonResponse {
        try {
            $error = $request->getContent();

            if ('' !== $error && '0' !== $error) {
                $frontendLogger->error($error);
            }

            $data = [
                'success' => true,
            ];
        } catch (Throwable $t) {
            $data = [
                'success' => false,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Route for get API version.
     */
    #[Route(path: '/get_static_roles', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    #[OA\Tag(name: 'Default')]
    #[OA\Response(
        response: 200,
        description: 'success',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'success', description: 'Get static roles', type: 'boolean'),
                ],
                type: 'object',
                example: [
                    'success' => true,
                ]
            )
        )
    )]
    public function getStaticRoles(
        ParameterBagInterface $parameterBag
    ): JsonResponse {
        $staticRoles = $parameterBag->get('static_roles');

        return new JsonResponse($staticRoles);
    }

    /**
     * Route for get CkEditor Css.
     */
    #[Route(path: '/contentCss/{componenteDigitalId}', methods: ['GET'])]
    public function getContentCss(
        int $componenteDigitalId,
        Request $request,
        Environment $twig,
        StylesManager $stylesManager,
        ComponenteDigitalResource $componenteDigitalResource
    ): Response {
        $mode = $request->get('mode') ? $request->get('mode') : 'ckeditor';
        if (0 === $componenteDigitalId) {
            throw new RuntimeException('Parâmetro não informado!');
        }
        $componenteDigital = $componenteDigitalResource->findOne($componenteDigitalId);
        $css = $stylesManager->select($componenteDigital);
        if ([] === $css) {
            throw new RuntimeException('CSS não encontrado!');
        }
        $response = new Response();
        $response->setContent(
            $twig->render($css[$mode])
        );
        $response->headers->set('Content-Type', 'text/css');

        return $response;
    }
}
