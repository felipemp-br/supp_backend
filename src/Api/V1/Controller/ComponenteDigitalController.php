<?php

declare(strict_types=1);
/**
 * /src/Controller/ComponenteDigitalController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use DateTime;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use LogicException;
use ONGR\ElasticsearchBundle\Service\IndexService;
use ONGR\ElasticsearchDSL\Highlight\Highlight;
use ONGR\ElasticsearchDSL\Sort\FieldSort;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Elastic\ElasticQueryBuilderService;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Repository\DocumentoRepository;
use SuppCore\AdministrativoBackend\Repository\JuntadaRepository;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use SuppCore\AdministrativoBackend\Transaction\Context;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;
use Twig\Environment;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method ComponenteDigitalResource getResource()
 */
#[Route(path: '/v1/administrativo/componente_digital')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'ComponenteDigital')]
class ComponenteDigitalController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\User\CreateAction;
    use Actions\Colaborador\UpdateAction;
    use Actions\User\PatchAction;
    use Actions\Colaborador\DeleteAction;
    use Actions\User\CountAction;
    use Actions\Colaborador\UndeleteAction;

    public function __construct(
        ComponenteDigitalResource $resource,
        ResponseHandler $responseHandler,
        private readonly IndexService $componenteDigitalIndex,
        private readonly ElasticQueryBuilderService $elasticQueryBuilderService,
        private readonly PaginatorInterface $paginator,
        private readonly DocumentoResource $documentoResource,
        private readonly JuntadaRepository $juntadaRepository,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly DocumentoRepository $documentoRepository

    ) {
        $this->init($resource, $responseHandler);
    }

    /**
     * Endpoint action to download.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws Throwable
     */
    #[Route(
        path: '/{id}/download',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_USER')]
    public function downloadAction(
        Request $request,
        int $id,
        Environment $twig,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $asPdf = false;
            $asXls = false;

            if (isset($context['asPdf']) && (true === $context['asPdf'])) {
                $asPdf = true;
            }

            if (isset($context['asXls']) && (true === $context['asXls'])) {
                $asXls = true;
            }

            $versao = null;

            if (isset($context['versao'])) {
                $versao = $context['versao'];
            }

            $componenteDigitalEntity = $this->getResource()->download(
                $id,
                $transactionId,
                true,
                $asPdf,
                $versao,
                true,
                $asXls
            );

            if ($asPdf || $asXls) {
                $componenteDigitalEntity->setAllowUnsafe(true);
            }

            $compararVersao = null;

            if (isset($context['compararVersao'])) {
                $compararVersao = $context['compararVersao'];
                $conteudo1 = $componenteDigitalEntity->getConteudo();

                $componenteDigitalCompararEntity = $this->getResource()->download(
                    $id,
                    $transactionId,
                    true,
                    $asPdf,
                    $compararVersao,
                    true,
                    $asXls
                );

                $conteudo2 = $componenteDigitalCompararEntity->getConteudo();

                $usuarioAlteracao = [];
                $usuarioAlteracao['nome'] = $componenteDigitalEntity->getAtualizadoPor()->getNome();
                $usuarioAlteracao['criadoEm'] = $componenteDigitalEntity->getApagadoEm();

                preg_match("/<body[^>]*>(.*?)<\/body>/is", $conteudo1, $matches1);
                preg_match("/<body[^>]*>(.*?)<\/body>/is", $conteudo2, $matches2);

                $conteudoHTML = $twig->render(
                    'Resources/Ckeditor/comparar/compararVersoes.html.twig',
                    [
                        'versao1' => strip_tags($matches1[1]),
                        'versao2' => strip_tags($matches2[1]),
                        'tipoDocumento' => $componenteDigitalEntity->getDocumento()->getTipoDocumento()->getNome(),
                        'usuarioAlteracao' => $usuarioAlteracao,
                    ]
                );

                $componenteDigitalEntity->setConteudo($conteudoHTML);
                $componenteDigitalEntity->setAllowUnsafe(true);
            }
            $this->transactionManager->commit();

            // Fetch data from database
            return $this
                ->getResponseHandler()
                ->createResponse($request, $componenteDigitalEntity);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to download.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws Throwable
     */
    #[Route(
        path: '/{processoId}/download_latest',
        requirements: [
            'processoId' => '\d+',
        ],
        methods: ['GET']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_USER')]
    public function downloadLatestAction(
        Request $request,
        int $processoId,
        Environment $twig,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];
        $id = null;

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $juntada = $this->juntadaRepository->findLastNaoVinculadaByProcessoId($processoId);

            if (!$juntada) {
                return new JsonResponse(['message' => 'sem_juntadas'], status: 422);
            }

            if (!$juntada->getAtivo()) {
                return new JsonResponse(['message' => 'desentranhada'], status: 422);
            }

            $componenteDigital = $this->getResource()->getRepository()->findFirstByJuntadaIdAndProcessoId(
                $juntada->getId()
            );
            if ($componenteDigital) {
                $id = $componenteDigital->getId();
            } else {
                return new JsonResponse(['message' => 'sem_componentes_digitais'], 422);
            }

            try {
                $componenteDigitalEntity = $this->getResource()->download(
                    $id,
                    $transactionId,
                    true,
                    false,
                    null,
                    true,
                    false
                );
            } catch (Throwable) {
                throw new Exception('acesso_negado', 422);
            }

            $this->transactionManager->commit();

            // Fetch data from database
            return $this
                ->getResponseHandler()
                ->createResponse($request, $componenteDigitalEntity);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action para localizar um componente digital no elasticsearch.
     *
     * @throws Throwable
     */
    #[Route(path: '/search', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    #[RestApiDoc]
    public function searchAction(Request $request, ?array $allowedHttpMethods = null): Response
    {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        // Determine used parameters
        $orderBy = RequestHandler::getOrderBy($request);
        $limit = RequestHandler::getLimit($request);
        $offset = RequestHandler::getOffset($request);
        $populate = RequestHandler::getPopulate($request, $this->getResource());

        try {
            $criteria = RequestHandler::getCriteria($request);

            $this->elasticQueryBuilderService->init(
                'componente_digital'
            );

            $boolQuery = $this->elasticQueryBuilderService->proccessCriteria($criteria);

            $search = $this->componenteDigitalIndex->createSearch()->addQuery($boolQuery);

            foreach ($orderBy as $key => $value) {
                if ($key && $value) {
                    $search->addSort(
                        new FieldSort(
                            $this->elasticQueryBuilderService->processaProperty($key),
                            null,
                            [
                                'order' => $value,
                            ]
                        )
                    );
                }
            }

            if ([] === $search->getSorts()) {
                $search->addSort(
                    new FieldSort(
                        'criado_em',
                        null,
                        [
                            'order' => 'desc',
                        ]
                    )
                );
            }
            $search->setSize($limit);
            $search->setFrom($offset);
            $search->setTrackTotalHits(true);
            $search->setSource(false);

            $highlight = new Highlight();
            $highlight->addField(
                'attachment.content',
                [
                    'fragment_size' => 1_000,
                    'number_of_fragments' => 1,
                ],
            );
            $highlight->setTags(['<span style="color: red;">'], ['</span>']);

            $search->addHighlight($highlight);

            $results = $this->componenteDigitalIndex->findRaw($search);
            $result = [];
            $result['entities'] = [];

            foreach ($results as $document) {
                $entity = $this->getResource()->getRepository()->find((int) $document['_id'], $populate);
                if (null !== $entity) {
                    if (isset($document['highlight'])) {
                        $entity->setHighlights(
                            $document['highlight']['attachment.content'][0]
                        );
                    }
                    $result['entities'][] = $entity;
                }
            }

            $result['total'] = $results->count();

            return $this
                ->getResponseHandler()
                ->createResponse(
                    $request,
                    $result
                );
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }

    /**
     * Endpoint action to revert hash componente digital.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{id}/reverter',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[IsGranted('ROLE_USER')]
    #[RestApiDoc]
    public function reverterAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['PATCH'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $data = $this
                ->getResource()
                ->reverter(
                    $id,
                    $this->processFormMapper($request, self::METHOD_PATCH, $id),
                    $transactionId,
                    true
                );

            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $data);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to approve pedido.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws Throwable
     */
    #[Route(path: '/aprovar', methods: ['POST'])]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function aprovarAction(
        Request $request,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['POST'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $componenteDigitalDTO = new ComponenteDigitalDTO();
            $documentoEntity = $this->documentoResource->findOne($request->get('documentoOrigem'));
            $componenteDigitalDTO->setDocumentoOrigem($documentoEntity);

            $data = $this
                ->getResource()
                ->aprovar($componenteDigitalDTO, $transactionId, true);

            $this->transactionManager->commit($transactionId);

            return $this
                ->getResponseHandler()
                ->createResponse($request, $data, Response::HTTP_CREATED);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }

    /**
     * Endpoint action to convertToPdf.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{id}/convertToPdf',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_USER')]
    public function convertToPdfAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['PATCH'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            // To use and save on the database
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $data = $this
                ->getResource()
                ->convertPDF($id, $transactionId, true);

            // Commit changes in the database
            $this->transactionManager->commit($transactionId);

            return $this
                ->getResponseHandler()
                ->createResponse($request, $data, Response::HTTP_OK);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to convertToHtml.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{id}/convertToHtml',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_USER')]
    public function convertToHtmlAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['PATCH'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $data = $this
                ->getResource()
                ->convertPdfInternoToHTML($id, $transactionId, true);
            $this->transactionManager->commit($transactionId);

            return $this
                ->getResponseHandler()
                ->createResponse($request, $data, Response::HTTP_OK);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to download.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @return Response
     *
     * @throws Throwable
     */
    #[Route(
        path: '/{id}/download_p7s',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_USER')]
    public function downloadP7sAction(Request $request, int $id, ?array $allowedHttpMethods = null)
    {
        $allowedHttpMethods ??= ['GET'];
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $componenteDigitalDTO = $this->getResource()->downloadP7s($id, $transactionId);

            $this->transactionManager->commit();

            return $this->getResponseHandler()
                ->createResponse($request, $componenteDigitalDTO);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to download.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @return Response
     *
     * @throws Throwable
     */
    #[Route(
        path: '/{id}/download_vinculado',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_USER')]
    public function downloadVinculadoAction(Request $request, int $id, ?array $allowedHttpMethods = null)
    {
        $allowedHttpMethods ??= ['GET'];
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $componenteDigitalEntityOrDTO = $this->getResource()->downloadVinculado($id, $transactionId);

            $this->transactionManager->commit();

            return $this->getResponseHandler()
                ->createResponse($request, $componenteDigitalEntityOrDTO);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to download a rendered content.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws Throwable
     */
    #[Route(path: '/render_html_content', methods: ['POST'])]
    #[RestApiDoc]
    #[IsGranted('ROLE_USER')]
    public function renderHtmlContent(Request $request, ?array $allowedHttpMethods = null): Response
    {
        $allowedHttpMethods ??= ['POST'];
        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }
            $componenteDigitalEntity = $this->getResource()
                ->renderHtmlContent($request->get('conteudo'), $request->get('fileName'), $transactionId);

            $this->transactionManager->commit();

            return $this->getResponseHandler()
                ->createResponse($request, $componenteDigitalEntity);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }

    /**
     * Endpoint action to compares a html content with content of the digital component.
     *
     * @throws Throwable
     */
    #[Route(
        path: '/{id}/compara_component_digital_com_html',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['POST']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_USER')]
    public function comparaComponenteDigitalComHtml(
        Request $request,
        int $id,
        Environment $twig,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['POST'];
        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $asPdf = false;
            $asXls = false;

            if (isset($context['asPdf']) && (true === $context['asPdf'])) {
                $asPdf = true;
            }

            if (isset($context['asXls']) && (true === $context['asXls'])) {
                $asXls = true;
            }

            $versao = null;

            if (isset($context['versao'])) {
                $versao = $context['versao'];
            }

            $componenteDigitalEntity = $this->getResource()->download(
                $id,
                $transactionId,
                true,
                $asPdf,
                $versao,
                true,
                $asXls
            );
            $componenteDigitalEntity2 = $this->getResource()
                ->renderHtmlContent($request->get('conteudo'), $componenteDigitalEntity->getFileName(), $transactionId);
            $conteudo1 = $componenteDigitalEntity2->getConteudo();
            $conteudo2 = $componenteDigitalEntity->getConteudo();

            $usuarioAlteracao = [];
            $usuarioAlteracao['nome'] = $request->get('usuario');
            $usuarioAlteracao['criadoEm'] = new DateTime($request->get('data'));

            preg_match("/<body[^>]*>(.*?)<\/body>/is", $conteudo1, $matches1);
            preg_match("/<body[^>]*>(.*?)<\/body>/is", $conteudo2, $matches2);

            $conteudoHTML = $twig->render(
                'Resources/Ckeditor/comparar/compararVersoes.html.twig',
                [
                    'versao1' => strip_tags($matches1[1]),
                    'versao2' => strip_tags($matches2[1]),
                    'tipoDocumento' => $componenteDigitalEntity->getDocumento()->getTipoDocumento()->getNome(),
                    'usuarioAlteracao' => $usuarioAlteracao,
                ]
            );

            $componenteDigitalEntity->setConteudo($conteudoHTML);
            $componenteDigitalEntity->setAllowUnsafe(true);

            $this->transactionManager->commit();

            return $this->getResponseHandler()
                ->createResponse($request, $componenteDigitalEntity);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }

    /**
     * @param Request $request
     * @param string $uuid
     * @return Response
     *
     * @throws Throwable
     */
    #[Route(
        path: '/documento/{uuid}',
        requirements: [
            'uuid' => '[\w-]{36}',
        ],
        methods: ['GET']
    )]
    #[IsGranted('ROLE_USER')]
    #[RestApiDoc]
    public function documentoUuidAction(Request $request, string $uuid): Response
    {
        $documento = $this->documentoRepository->findOneBy(["uuid" => $uuid]);
        $usuario = $this->tokenStorage->getToken()->getUser();

        if(!$documento) {
            throw new \Exception("Documento $uuid não encontrado!");
        } else if ($usuario !== $documento->getCriadoPor()){
            throw new \Exception("Acesso Negado.");
        }

        try {
            $transactionId = $this->transactionManager->begin();
            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $asPdf = false;
            $asXls = false;
            $versao = null;

            if (isset($context['asPdf']) && (true === $context['asPdf'])) {
                $asPdf = true;
            }

            if (isset($context['asXls']) && (true === $context['asXls'])) {
                $asXls = true;
            }

            /** @var ComponenteDigital $componenteDigital */
            $componenteDigital = $documento->getComponentesDigitais()->first();

            $componenteDigitalEntity = $this->resource->download(
                $componenteDigital->getId(),
                $transactionId,
                true,
                $asPdf,
                $versao,
                true,
                $asXls
            );

            $this->transactionManager->commit($transactionId);

            return $this
                ->getResponseHandler()
                ->createResponse($request, $componenteDigitalEntity);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $documento->getId());
        }
    }
}
