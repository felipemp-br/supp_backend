<?php

declare(strict_types=1);
/**
 * /src/Controller/TarefaController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use LogicException;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Utils\TrataDistribuicaoServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method TarefaResource getResource()
 */
#[Route(path: '/v1/administrativo/tarefa')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'Tarefa')]
class TarefaController extends Controller
{
    // Traits
    use Actions\Colaborador\FindOneAction;
    use Actions\Colaborador\FindAction;
    use Actions\Colaborador\CreateAction;
    use Actions\Colaborador\UpdateAction;
    use Actions\Colaborador\PatchAction;
    use Actions\Colaborador\DeleteAction;
    use Actions\Colaborador\UndeleteAction;
    use Actions\Colaborador\CountAction;

    public function __construct(
        TarefaResource $resource,
        ResponseHandler $responseHandler,
        private readonly UsuarioResource $usuarioResource
    ) {
        $this->init($resource, $responseHandler);
    }

    /**
     * Endpoint action to change lida status.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{id}/toggle_lida',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function toggleLidaAction(
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

            $tarefaResource = $this->getResource();
            $tarefaDTO = $tarefaResource->getDtoForEntity($id, Tarefa::class);
            $tarefaEntity = $tarefaResource->toggleLida($id, $tarefaDTO, $transactionId, true);

            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $tarefaEntity);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to give ciência.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{id}/ciencia',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function cienciaAction(
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

            $tarefaResource = $this->getResource();
            $tarefaDTO = $tarefaResource->getDtoForEntity($id, Tarefa::class);
            $tarefaEntity = $tarefaResource->ciencia($id, $tarefaDTO, $transactionId, true);

            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $tarefaEntity);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action para distribuir tarefas do usuário.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/distribuir_tarefas_usuario/{id}',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[IsGranted('ROLE_ADMIN')]
    #[RestApiDoc]
    public function distribuirTarefasUsuarioAction(
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

            $this->transactionManager->addContext(
                new Context('distribuicaoTarefasUsuario', true),
                $transactionId
            );

            $tarefaResource = $this->getResource();
            $usuario = $tarefaResource->distribuirTarefasUsuario(
                $id,
                $transactionId,
                true
            );

            $this->transactionManager->removeContext('distribuicaoTarefasUsuario', $transactionId);

            // @todo Verificar se é caso de mover o endpoint para o UsuarioController
            $this->getResponseHandler()->setResource($this->usuarioResource);

            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $usuario);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action para consultar dados do gráfico de tarefas.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(path: '/grafico_semanal', methods: ['GET'])]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function graficoTarefasAction(
        Request $request,
        TokenStorageInterface $tokenStorage,
        ?array $allowedHttpMethods = null
    ): JsonResponse {
        $allowedHttpMethods ??= ['GET'];

        $this->validateRestMethod($request, $allowedHttpMethods);

        $tarefaResource = $this->getResource();

        /** @var Usuario $usuario */
        $usuario = $tokenStorage->getToken()->getUser();

        return new JsonResponse($tarefaResource->obterDadosGraficoTarefas($usuario));
    }

    /**
     * Endpoint action para consultar dados do gráfico de tarefas mensal.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(path: '/grafico_mensal/{periodo}', methods: ['GET'])]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function graficoTarefasMesesAction(
        Request $request,
        int $periodo,
        TokenStorageInterface $tokenStorage,
        ?array $allowedHttpMethods = null
    ): JsonResponse {
        $allowedHttpMethods ??= ['GET'];

        $this->validateRestMethod($request, $allowedHttpMethods);

        $tarefaResource = $this->getResource();

        /** @var Usuario $usuario */
        $usuario = $tokenStorage->getToken()->getUser();

        // Se período de 1 mês, mostrar as últimas 4 semanas e não o total do mês
        if ($periodo === 1) {
            return new JsonResponse($tarefaResource->obterDadosGraficoTarefas($usuario));
        }
        return new JsonResponse($tarefaResource->obterDadosGraficoTarefasMeses($usuario, $periodo));
    }

    /**
     * Endpoint action para distribuir tarefas em bloco.
     *
     * @throws Throwable
     */
    #[Route(path: '/distribuicao_bloco/{id}', methods: ['PATCH'])]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function distribuicaoBlocoAction(
        Request $request,
        int $id,
        TrataDistribuicaoServiceInterface $trataDistribuicaoService,
    ): Response {
        $allowedHttpMethods ??= ['PATCH'];

        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $tarefas = $request->get('tarefas');

            $data = [];

            $transactionId = $this->transactionManager->begin();

            // Distribuição Manual
            if ($request->get('usuarioResponsavel')) {
                foreach ($tarefas as $tarefa) {
                    /* @var Tarefa $tarefaDto */
                    $tarefaDto = $this->processFormMapper($request, self::METHOD_PATCH, $tarefa);
                    $tarefaDto->setDistribuicaoAutomatica(false);
                    $data [] = $this->getResource()->update($tarefaDto->getId(), $tarefaDto, $transactionId);
                }
            } else {
                $transactionId = $this->transactionManager->begin();
                $this->transactionManager->addContext(
                    new Context('distribuicaoAutomaticaEmBloco', true),
                    $transactionId
                );
                $tarefaDto = $this->processFormMapper($request, self::METHOD_PATCH, $tarefas[0]);
                $tarefaEntity = $this->getResource()->getRepository()->find($tarefas[0]);

                $usuarios = $this->getResource()->retornaUsuariosRegraDistribuicao(
                    $tarefaDto,
                    $this->getResource()->retornaRegraDistribuicao($tarefaDto, $tarefaEntity)
                );

                foreach ($tarefas as $tarefa) {
                    /* @var Tarefa $tarefaDto */
                    $tarefaDto = $this->processFormMapper($request, self::METHOD_PATCH, $tarefa);
                    $trataDistribuicaoService->tratarDistribuicaoAutomatica($tarefaDto, $usuarios);
                    $data [] = $this->getResource()->update($tarefaDto->getId(), $tarefaDto, $transactionId);
                }

                $this->transactionManager->removeContext('distribuicaoAutomaticaEmBloco', $transactionId);
            }

            $this->transactionManager->commit($transactionId);

            $return = [
                'entities' => $data,
                'total' => count($data),
            ];

            return $this->getResponseHandler()->createResponse($request, $return);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }
}
