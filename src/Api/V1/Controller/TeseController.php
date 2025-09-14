<?php

declare(strict_types=1);
/**
 * /src/Controller/TeseController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use ONGR\ElasticsearchBundle\Service\IndexService;
use ONGR\ElasticsearchDSL\Sort\FieldSort;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tese;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TeseResource;
use SuppCore\AdministrativoBackend\Elastic\ElasticQueryBuilderService;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use SuppCore\AdministrativoBackend\Transaction\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method TeseResource getResource()
 */
#[Route(path: '/v1/administrativo/tese')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'Tese')]
class TeseController extends Controller
{
    // Traits
    use Actions\Colaborador\FindOneAction;
    use Actions\Colaborador\FindAction;
    use Actions\Admin\CreateAction;
    use Actions\Admin\UpdateAction;
    use Actions\Admin\PatchAction;
    use Actions\Admin\DeleteAction;
    use Actions\Colaborador\CountAction;

    public function __construct(
        TeseResource $resource,
        ResponseHandler $responseHandler,
        private readonly IndexService $teseIndex,
        private readonly ElasticQueryBuilderService $elasticQueryBuilderService
    ) {
        $this->init($resource, $responseHandler);
    }

    /**
     * Endpoint action para localizar uma tese no elasticsearch.
     *
     * @throws Throwable
     */
    #[Route(path: '/search', methods: ['GET'])]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function searchAction(Request $request, array $allowedHttpMethods = null): Response
    {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        // Determine used parameters
        $orderBy = RequestHandler::getOrderBy($request);
        $limit = RequestHandler::getLimit($request);
        $offset = RequestHandler::getOffset($request);

        try {
            $criteria = RequestHandler::getCriteria($request);

            $this->elasticQueryBuilderService->init(
                'tese'
            );

            $boolQuery = $this->elasticQueryBuilderService->proccessCriteria($criteria);

            $search = $this->teseIndex->createSearch()->addQuery($boolQuery);

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

            $search->setSize($limit);
            $search->setFrom($offset);
            $search->setTrackTotalHits(true);
            //            $search->setSource(false);

            $results = $this->teseIndex->findRaw($search);

            $result = [];
            $result['entities'] = [];

            foreach ($results as $document) {
                $entity = $this->getResource()->getRepository()->find((int) $document['_id']);
                if (null !== $entity) {
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
     * Endpoint action para fazer o merge de teses.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws Throwable
     */
    #[Route(
        path: '/{teseOrigemId}/merge/{teseDestinoId}',
        requirements: [
            'teseOrigemId' => '\d+',
            'teseDestinoId' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[IsGranted('ROLE_ADMIN')]
    #[RestApiDoc]
    public function mergeTesesAction(
        Request $request,
        int $teseOrigemId,
        int $teseDestinoId,
        array $allowedHttpMethods = null
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

            $teseResource = $this->getResource();
            $teseOrigemDTO = $teseResource->getDtoForEntity($teseOrigemId, Tese::class);
            $teseDestinoDto = $teseResource->getDtoForEntity($teseDestinoId, Tese::class);
            $teseEntity = $teseResource->mergeTeses(
                $teseOrigemId,
                $teseDestinoId,
                $teseOrigemDTO,
                $teseDestinoDto,
                $transactionId
            );

            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $teseEntity);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $teseOrigemId);
        }
    }
}
