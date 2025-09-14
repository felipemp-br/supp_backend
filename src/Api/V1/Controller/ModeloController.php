<?php

declare(strict_types=1);
/**
 * /src/Controller/ModeloController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use ONGR\ElasticsearchBundle\Service\IndexService;
use ONGR\ElasticsearchDSL\Highlight\Highlight;
use ONGR\ElasticsearchDSL\Query\Compound\BoolQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\ExistsQuery;
use ONGR\ElasticsearchDSL\Sort\FieldSort;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModeloResource;
use SuppCore\AdministrativoBackend\Elastic\ElasticQueryBuilderService;
use SuppCore\AdministrativoBackend\Elastic\ScriptScoreQuery;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method ModeloResource getResource()
 */
#[Route(path: '/v1/administrativo/modelo')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'Modelo')]
class ModeloController extends Controller
{
    // Traits
    use Actions\Colaborador\FindOneAction;
    use Actions\Colaborador\FindAction;
    use Actions\Colaborador\CreateAction;
    use Actions\Colaborador\UpdateAction;
    use Actions\Colaborador\PatchAction;
    use Actions\Root\DeleteAction;
    use Actions\Colaborador\CountAction;

    private readonly IndexService $modeloIndex;

    private readonly ElasticQueryBuilderService $elasticQueryBuilderService;

    public function __construct(
        ModeloResource $resource,
        ResponseHandler $responseHandler,
        IndexService $modeloIndex,
        ElasticQueryBuilderService $elasticQueryBuilderService
    ) {
        $this->init($resource, $responseHandler);
        $this->modeloIndex = $modeloIndex;
        $this->elasticQueryBuilderService = $elasticQueryBuilderService;
    }

    /**
     * Endpoint action para localizar uma modelo no elasticsearch.
     *
     * @throws Throwable
     */
    #[Route(path: '/search', methods: ['GET'])]
    #[IsGranted('ROLE_COLABORADOR')]
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
        $context = RequestHandler::getContext($request);

        try {
            $criteria = RequestHandler::getCriteria($request);

            if ((!isset($context['isAdmin'])) || (true !== $context['isAdmin'])) {
                $criteria['ativo'] = 'eq:true';
            }

            $this->elasticQueryBuilderService->init(
                'modelo'
            );

            $boolQuery = $this->elasticQueryBuilderService->proccessCriteria($criteria);

            if ($this->elasticQueryBuilderService->getDenseVector()) {
                $boolQuery->add(new ExistsQuery('dense_vector'), BoolQuery::MUST);
                $scriptScoreQuery = new ScriptScoreQuery(
                    $boolQuery,
                    [
                        'source' => "cosineSimilarity(params.dense_vector, doc['dense_vector']) + 1.0",
                        'params' => [
                            'dense_vector' => $this->elasticQueryBuilderService->getDenseVector(),
                        ],
                    ]
                );
                $search = $this->modeloIndex->createSearch()->addQuery($scriptScoreQuery);
            } else {
                $search = $this->modeloIndex->createSearch()->addQuery($boolQuery);
            }

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

            $results = $this->modeloIndex->findRaw($search);
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
}
