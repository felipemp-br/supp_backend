<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\DTO\TarefaMensagem as TarefaMensagemDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaMensagemResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Transaction\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException; // Adicionado para JSON inválido
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use OpenApi\Attributes as OA;
use Throwable;
use Symfony\Component\Serializer\SerializerInterface;     // <<-- ADICIONAR IMPORT
use Symfony\Component\Validator\Validator\ValidatorInterface; // <<-- ADICIONAR IMPORT
use Symfony\Component\Validator\ConstraintViolationListInterface; // Adicionado para type hint
use SuppCore\AdministrativoBackend\Utils\JSON; // <<-- ADICIONAR IMPORT SE NÃO ESTIVER GLOBALMENTE ACESSÍVEL
use Symfony\Component\Validator\Exception\ValidatorException; // <<-- ADICIONAR IMPORT

/**
 * Controller para gerenciar mensagens de chat associadas a tarefas.
 *
 * @method TarefaMensagemResource getResource()
 */
#[Route(path: '/v1/administrativo')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'TarefaMensagem', description: "Endpoints para mensagens do chat de tarefas")]
class TarefaMensagemController extends Controller
{
    private TarefaResource $tarefaResource;
    private TokenStorageInterface $tokenStorage;
    private SerializerInterface $serializer;     // <<-- NOVA PROPRIEDADE
    private ValidatorInterface $validator;       // <<-- NOVA PROPRIEDADE

    public function __construct(
        TarefaMensagemResource $tarefaMensagemResource,
        TarefaResource $tarefaResource,
        ResponseHandler $responseHandler,
        TokenStorageInterface $tokenStorage,
        SerializerInterface $serializer,         // <<-- NOVA INJEÇÃO
        ValidatorInterface $validator           // <<-- NOVA INJEÇÃO
    ) {
        $this->init($tarefaMensagemResource, $responseHandler);
        $this->tarefaResource = $tarefaResource;
        $this->tokenStorage = $tokenStorage;
        $this->serializer = $serializer;         // <<-- NOVA ATRIBUIÇÃO
        $this->validator = $validator;           // <<-- NOVA ATRIBUIÇÃO
    }

    /**
     * Lista mensagens de uma tarefa específica.
     */
    #[Route(
        path: '/tarefa/{tarefaId}/mensagens',
        name: 'api_v1_administrativo_tarefa_mensagens_list',
        requirements: ['tarefaId' => '\d+'],
        methods: ['GET']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    #[OA\Get( /* ... Mantenha suas definições OA\Get como estavam ... */ )]
// No TarefaMensagemController.php

public function findMensagensByTarefaAction(Request $request, int $tarefaId): Response
{
    /** @var TarefaEntity $tarefaEntity */
    $tarefaEntity = $this->tarefaResource->findOne($tarefaId, null, null, null);

    $tarefaCriterioValor = 'eq:' . $tarefaEntity->getId();
    $baseCriteria = ['tarefa.id' => $tarefaCriterioValor];

    // Garantir que não estamos pegando critérios inesperados do request para este teste
    $requestCriteria = RequestHandler::getCriteria($request) ?? [];
    // error_log("Critérios do RequestHandler: " . print_r($requestCriteria, true)); // Para debug
    $criteria = array_merge($baseCriteria, $requestCriteria);
    
    $orderBy = RequestHandler::getOrderBy($request);
    if (empty($orderBy)) {
        $orderBy = ['dataHoraEnvio' => 'ASC'];
    }
    $limit = RequestHandler::getLimit($request); // Padrão pode ser 10 ou 25
    if ($limit === null || $limit <= 0 || $limit > 500) { // Garante que o limite não seja inválido ou excessivo
        $limit = 500; // Nosso limite padrão alto
    }
    $offset = RequestHandler::getOffset($request);
    //$populate = RequestHandler::getPopulate($request, $this->getResource());
        // SOLICITAR A POPULAÇÃO DA RELAÇÃO 'usuario'
    $populateRequest = RequestHandler::getPopulate($request, $this->getResource()); // Pega o que veio da URL
    $populateSpecific = ['usuario']; // O que queremos especificamente para este endpoint
    // Combina o populate do request com o que queremos especificamente, evitando duplicatas
    $populate = array_unique(array_merge($populateRequest, $populateSpecific));

    // error_log("Critérios FINAIS para find: " . print_r($criteria, true)); // Para debug
    // error_log("OrderBy para find: " . print_r($orderBy, true)); // Para debug

    $resultFromResource = $this->getResource()->find($criteria, $orderBy, $limit, $offset, [], $populate);
    
    $actualEntities = $resultFromResource['entities'];
    $totalFromResource = $resultFromResource['total'];

    // error_log("Entidades encontradas: " . count($actualEntities)); // Para debug
    // error_log("Total do resource: " . $totalFromResource); // Para debug
    // foreach ($actualEntities as $idx => $entity) {
    //     if ($entity instanceof \SuppCore\AdministrativoBackend\Api\V1\DTO\TarefaMensagem) {
    //         error_log("Msg DTO $idx: ID " . $entity->getId() . " Conteudo: " . $entity->getConteudo());
    //     } elseif ($entity instanceof \SuppCore\AdministrativoBackend\Entity\TarefaMensagem) {
    //          error_log("Msg Entidade $idx: ID " . $entity->getId() . " Conteudo: " . $entity->getConteudo());
    //     }
    // }

    $responseData = [
        'entities' => $actualEntities,
        'total'    => $totalFromResource,
    ];

    $response = $this->getResponseHandler()->createResponse($request, $responseData);

    // Adicionar headers para prevenir cache (para desenvolvimento/teste)
    $response->setCache([
        'must_revalidate'  => true,
        'no_cache'         => true,
        'no_store'         => true,
        'private'          => true, // Garante que proxies não cacheiem
        'max_age'          => 0,
    ]);
    // Alternativamente, de forma mais simples para desabilitar totalmente:
    // $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    // $response->headers->set('Pragma', 'no-cache');
    // $response->setExpires(new \DateTime('-1 year'));


    return $response;
}

    /**
     * Cria uma nova mensagem em uma tarefa específica.
     */
    #[Route(
        path: '/tarefa/{tarefaId}/mensagens',
        name: 'api_v1_administrativo_tarefa_mensagens_create',
        requirements: ['tarefaId' => '\d+'],
        methods: ['POST']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc] // O marcador RestApiDoc
    #[OA\Post(    // Detalhes OpenAPI para o método POST
        summary: "Cria uma nova mensagem para uma tarefa.",
        parameters: [
            new OA\Parameter(name: "tarefaId", in: "path", description: "ID da Tarefa", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            description: "Dados da nova mensagem. Apenas 'conteudo' é obrigatório no corpo.",
            required: true,
            content: new OA\JsonContent(
                ref: \SuppCore\AdministrativoBackend\Api\V1\DTO\TarefaMensagem::class,
                example: ["conteudo" => "Conteúdo da nova mensagem aqui."]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Mensagem criada com sucesso.",
                content: new OA\JsonContent(ref: \SuppCore\AdministrativoBackend\Api\V1\DTO\TarefaMensagem::class)
            ),
            new OA\Response(response: 400, description: "Dados inválidos (ex: conteúdo em branco, JSON malformado)."),
            new OA\Response(response: 403, description: "Acesso negado."),
            new OA\Response(response: 404, description: "Tarefa não encontrada.")
        ]
    )]
    public function createMensagemAction(Request $request, int $tarefaId): Response
    {
        /** @var TarefaEntity $tarefaEntity */
        $tarefaEntity = $this->tarefaResource->findOne($tarefaId, null, null, null);
        // TODO: Adicionar verificação de permissão para criar mensagem nesta tarefa

        /** @var UsuarioEntity $usuarioLogado */
        $usuarioLogado = $this->tokenStorage->getToken()?->getUser();
        if (!$usuarioLogado instanceof UsuarioEntity) {
            throw new AccessDeniedException('Usuário não autenticado ou tipo de usuário inválido.');
        }

        $transactionId = $this->transactionManager->begin();
        try {
            // 1. Verificar Content-Type e desserializar JSON para o DTO
            if (!str_contains($request->headers->get('Content-Type', ''), 'application/json')) {
                throw new BadRequestHttpException('Content-Type esperado: application/json.');
            }
            $jsonContent = $request->getContent();
            if (empty($jsonContent)) {
                throw new BadRequestHttpException('Corpo da requisição não pode ser vazio para criar uma mensagem.');
            }

            /** @var TarefaMensagemDTO $dto */
            try {
                $dto = $this->serializer->deserialize($jsonContent, TarefaMensagemDTO::class, 'json');
            } catch (\Symfony\Component\Serializer\Exception\ExceptionInterface $e) {
                throw new BadRequestHttpException('JSON inválido no corpo da requisição: ' . $e->getMessage(), $e);
            }

            // 2. Validar o DTO manualmente
            /** @var ConstraintViolationListInterface $errors */
            $errors = $this->validator->validate($dto); // Adicionar grupos de validação se o DTO os usar, ex: ['Default', 'create']
            if (count($errors) > 0) {
                // Lógica replicada de RestMethodHelper::createValidatorException
                $output = [];
                /** @var \Symfony\Component\Validator\ConstraintViolationInterface $error */
                foreach ($errors as $error) {
                    $output[] = [
                        'message' => $error->getMessage(),
                        'propertyPath' => $error->getPropertyPath(),
                        'code' => $error->getCode(),
                    ];
                }
                // Se SuppCore\AdministrativoBackend\Utils\JSON existe e é acessível:
                if (class_exists(\SuppCore\AdministrativoBackend\Utils\JSON::class)) {
                    throw new ValidatorException(JSON::encode($output)); // <--- USA ValidatorException
                } else {
                    // Fallback se a classe JSON não estiver disponível (improvável no seu projeto)
                    throw new BadRequestHttpException('Falha na validação: ' . json_encode($output));
                }
            }

            // 3. Popular campos que não vêm do request ou são definidos pelo sistema
            $dto->setTarefa($tarefaEntity);
            $dto->setUsuario($usuarioLogado);
            // usuarioNome e dataHoraEnvio devem ser definidos pela entidade TarefaMensagem
            // no construtor ou no setUsuario() e ao persistir (via Timestampable).

            // 4. Adicionar contexto transacional
            $requestContext = RequestHandler::getContext($request);
            if (is_array($requestContext)) {
                foreach ($requestContext as $name => $value) {
                    if (is_string($name)) {
                        $this->transactionManager->addContext(new Context($name, $value), $transactionId);
                    }
                }
            }
            
            // 5. Chamar o create do resource.
            // Como validamos o DTO aqui, podemos passar 'true' para skipValidation do DTO no resource.
            // O resource ainda é responsável pela validação da entidade, se houver.
            $entidadeCriada = $this->getResource()->create($dto, $transactionId, true); 
            
            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $entidadeCriada, Response::HTTP_CREATED);

        } catch (Throwable $e) {
            // O handleRestMethodException (herdado) deve tratar as exceções
            // (BadRequestHttpException, AccessDeniedException, ValidatorException, etc.)
            // e convertê-las para a resposta HTTP apropriada.
            throw $this->handleRestMethodException($e);
        }
    }
}