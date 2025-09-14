<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Controller/DistribuicaoEstagiarioController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use Exception;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Service\DistribuicaoEstagiarioService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controller para distribuição automática de tarefas para estagiários.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Route(path: '/v1/administrativo/distribuicao-estagiario')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'DistribuicaoEstagiario')]
class DistribuicaoEstagiarioController
{
    public function __construct(
        private DistribuicaoEstagiarioService $distribuicaoEstagiarioService,
        private TokenStorageInterface $tokenStorage
    ) {
    }

    /**
     * Executa a distribuição de tarefas para estagiários usando fila circular sequencial.
     *
     * Este endpoint implementa o algoritmo de fila circular onde:
     * 1. Estagiários que nunca receberam tarefas vão primeiro (ordem alfabética)
     * 2. Entre os que já receberam, o próximo na fila é quem recebeu há mais tempo
     * 3. Após receber uma tarefa, o estagiário vai para o final da fila
     * 4. A distribuição ocorre separadamente por gênero de tarefa
     * 5. Garante distribuição equitativa sem considerar nível de experiência
     */
    #[Route(path: '/executar', methods: ['POST'])]
    #[IsGranted('ROLE_COORDENADOR')]
    #[OA\RequestBody(
        description: 'Dados para executar a distribuição de estagiários',
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['estagiarios', 'setor_id'],
                    properties: [
                        new OA\Property(
                            property: 'estagiarios',
                            type: 'array',
                            items: new OA\Items(type: 'integer'),
                            description: 'Array com os IDs dos usuários estagiários selecionados'
                        ),
                        new OA\Property(
                            property: 'setor_id', 
                            type: 'integer', 
                            description: 'ID do setor responsável'
                        ),
                        new OA\Property(
                            property: 'genero_id', 
                            type: 'integer', 
                            description: 'ID do gênero de tarefa (contexto administrativo ou judicial) - opcional'
                        ),
                        new OA\Property(
                            property: 'unidade_id', 
                            type: 'integer', 
                            description: 'ID da unidade (opcional)'
                        )
                    ],
                    example: [
                        'estagiarios' => [1, 2, 3, 4],
                        'setor_id' => 123,
                        'genero_id' => 1,
                        'unidade_id' => 456
                    ]
                )
            ),
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'Distribuição executada com sucesso',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'object',
                properties: [
                    new OA\Property(property: 'success', type: 'boolean', description: 'Indica se a distribuição foi bem-sucedida'),
                    new OA\Property(
                        property: 'distribuicoes_realizadas',
                        type: 'array',
                        description: 'Detalhes das distribuições realizadas por gênero',
                        items: new OA\Items(
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'genero_tarefa', type: 'string', description: 'Nome do gênero da tarefa'),
                                new OA\Property(property: 'tarefas_distribuidas', type: 'integer', description: 'Número de tarefas distribuídas'),
                                new OA\Property(property: 'estagiarios_contemplados', type: 'integer', description: 'Número de estagiários que receberam tarefas')
                            ]
                        )
                    ),
                    new OA\Property(property: 'total_tarefas', type: 'integer', description: 'Total de tarefas distribuídas'),
                    new OA\Property(
                        property: 'criterios_aplicados',
                        type: 'array',
                        description: 'Critérios aplicados na distribuição',
                        items: new OA\Items(type: 'string')
                    )
                ],
                example: [
                    'success' => true,
                    'distribuicoes_realizadas' => [
                        [
                            'genero_tarefa' => 'Judicial',
                            'tarefas_distribuidas' => 15,
                            'estagiarios_contemplados' => 3
                        ],
                        [
                            'genero_tarefa' => 'Administrativo',
                            'tarefas_distribuidas' => 8,
                            'estagiarios_contemplados' => 2
                        ]
                    ],
                    'total_tarefas' => 23,
                    'criterios_aplicados' => ['fila_circular', 'sequencial', 'ultimo_recebeu']
                ]
            )
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Erro na requisição',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'error', type: 'string', description: 'Mensagem de erro'),
                ],
                type: 'object',
                example: [
                    'error' => 'Pelo menos um estagiário deve ser selecionado'
                ]
            )
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Não autorizado - Apenas coordenadores podem executar distribuições'
    )]
    #[OA\Response(
        response: 403,
        description: 'Acesso negado - Permissões insuficientes'
    )]
    public function executarDistribuicao(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!$data || !isset($data['estagiarios']) || !isset($data['setor_id'])) {
                return new JsonResponse(['error' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
            }

            $estagiariosIds = $data['estagiarios'];
            $setorId = (int) $data['setor_id'];
            $generoId = isset($data['genero_id']) ? (int) $data['genero_id'] : null;
            $unidadeId = isset($data['unidade_id']) ? (int) $data['unidade_id'] : null;

            if (empty($estagiariosIds) || !is_array($estagiariosIds)) {
                return new JsonResponse(['error' => 'Lista de estagiários é obrigatória'], Response::HTTP_BAD_REQUEST);
            }

            // Obter usuário executor atual
            /** @var Usuario $usuarioExecutor */
            $usuarioExecutor = $this->tokenStorage->getToken()->getUser();
            
            // Buscar entidade do setor
            $entityManager = $this->distribuicaoEstagiarioService->getEntityManager();
            $setor = $entityManager->getRepository(Setor::class)->find($setorId);
            
            if (!$setor) {
                return new JsonResponse(['error' => 'Setor não encontrado'], Response::HTTP_BAD_REQUEST);
            }

            // Executar a distribuição
            $resultado = $this->distribuicaoEstagiarioService->executarDistribuicaoEstagiarios(
                $estagiariosIds,
                $usuarioExecutor,
                $setor,
                $unidadeId,
                $generoId
            );

            return new JsonResponse($resultado);

        } catch (Exception $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}