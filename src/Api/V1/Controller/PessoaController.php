<?php

declare(strict_types=1);
/**
 * /src/Controller/PessoaController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use ONGR\ElasticsearchBundle\Service\IndexService;
use ONGR\ElasticsearchDSL\Sort\FieldSort;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoPessoaBarramento;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoPessoaBarramentoResource;
use SuppCore\AdministrativoBackend\Elastic\ElasticQueryBuilderService;
use SuppCore\AdministrativoBackend\Repository\ModalidadeGeneroPessoaRepository;
use SuppCore\AdministrativoBackend\Repository\ModalidadeQualificacaoPessoaRepository;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use SuppCore\AdministrativoBackend\Transaction\Context;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method PessoaResource getResource()
 */
#[Route(path: '/v1/administrativo/pessoa')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'Pessoa')]
class PessoaController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\Colaborador\CreateAction;
    use Actions\Colaborador\UpdateAction;
    use Actions\Colaborador\PatchAction;
    use Actions\Root\DeleteAction;
    use Actions\Colaborador\CountAction;

    private readonly IndexService $pessoaIndex;

    private readonly ElasticQueryBuilderService $elasticQueryBuilderService;

    public function __construct(
        PessoaResource $resource,
        ResponseHandler $responseHandler,
        IndexService $pessoaIndex,
        ElasticQueryBuilderService $elasticQueryBuilderService,
        private readonly ModalidadeQualificacaoPessoaRepository $modalidadeQualificacaoPessoaRepository,
        private readonly VinculacaoPessoaBarramentoResource $vinculacaoPessoaBarramentoResource
    ) {
        $this->init($resource, $responseHandler);
        $this->pessoaIndex = $pessoaIndex;
        $this->elasticQueryBuilderService = $elasticQueryBuilderService;
    }


    /**
     * Endpoint action para localizar uma pessoa no elasticsearch.
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
            $transactionId = $this->transactionManager->begin();
            $context = RequestHandler::getContext($request);
            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }
            $criteria = RequestHandler::getCriteria($request);

            $this->elasticQueryBuilderService->init(
                'pessoa'
            );

            $boolQuery = $this->elasticQueryBuilderService->proccessCriteria($criteria);

            $search = $this->pessoaIndex->createSearch()->addQuery($boolQuery);
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
            $search->addSort(
                new FieldSort(
                    'pessoa_validada',
                    null,
                    [
                        'order' => 'desc',
                    ]
                )
            );
            $search->addSort(
                new FieldSort(
                    'pessoa_conveniada',
                    null,
                    [
                        'order' => 'desc',
                    ]
                )
            );
            $search->setSize($limit);
            $search->setFrom($offset);
            $search->setTrackTotalHits(true);
            $search->setSource(false);

            $results = $this->pessoaIndex->findRaw($search);

            $result = [];
            $result['entities'] = [];

            foreach ($results as $document) {
                $entity = $this->getResource()->getRepository()->find((int)$document['_id'], $populate);
                if ($entity) {
                    $result['entities'][] = $entity;
                }
            }

            $result['total'] = $results->count();

            // After callback method call
            $className = $this->getResource()->getRepository()->getEntityName();
            $this->getResource()->afterFind($className, $criteria, $orderBy, $limit, $offset, $populate, $result);
            $this->transactionManager->commit($transactionId);

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


    #[Route(path: '/criar_pessoa_barramento', methods: ['post'])]
    #[IsGranted('ROLE_USER')]
    #[RestApiDoc]
    public function criarPessoaBarramento(Request $request, FormFactoryInterface $formFactory)
    {
        $pessoa = new Pessoa();

        $content = $request->getContent();
        $data = json_decode($content, true);
        $modalide = $this->modalidadeQualificacaoPessoaRepository->findOneBy(['valor' => "PESSOA JURÍDICA"]);

        $pessoa->setNome($data['nome']);
        $pessoa->setPessoaValidada(true);
        $pessoa->setPessoaConveniada(false);
        $pessoa->setModalidadeQualificacaoPessoa($modalide);

        $transactionId = $this->transactionManager->begin();
        $resultPessoa = $this->resource->create($pessoa, $transactionId, true);

        $vinculacaoPessoaBarramento = new VinculacaoPessoaBarramento();
        $vinculacaoPessoaBarramento->setPessoa($resultPessoa);
        $vinculacaoPessoaBarramento->setEstrutura($data['numeroDeIdentificacaoDaEstrutura']);
        $vinculacaoPessoaBarramento->setNomeEstrutura($data['nome']);
        $vinculacaoPessoaBarramento->setNomeRepositorio($data['nomeRepositorio']);
        $vinculacaoPessoaBarramento->setRepositorio($data['numeroDeIdentificacaoDoRepositorio']);

        $this->vinculacaoPessoaBarramentoResource->create($vinculacaoPessoaBarramento, $transactionId, true);
        $this->transactionManager->commit($transactionId);

        $pessoaResult = $this->resource->findOne($resultPessoa->getId());

        return $this
            ->getResponseHandler()
            ->createResponse($request, $pessoaResult, Response::HTTP_CREATED);
    }

}
