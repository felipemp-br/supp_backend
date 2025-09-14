<?php

declare(strict_types=1);
/**
 * /src/Controller/VinculacaoPessoaBarramentoController.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use JMS\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoPessoaBarramentoResource;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoClient;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method VinculacaoPessoaBarramentoResource getResource()
 */
#[Route(path: '/v1/administrativo/vinculacao_pessoa_barramento')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'VinculacaoPessoaBarramento')]
class VinculacaoPessoaBarramentoController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\User\CreateAction;
    use Actions\User\UpdateAction;
    use Actions\User\PatchAction;
    use Actions\User\DeleteAction;
    use Actions\Colaborador\CountAction;

    private readonly BarramentoClient $barramentoClient;

    private readonly SerializerInterface $serializer;

    public function __construct(
        VinculacaoPessoaBarramentoResource $resource,
        ResponseHandler $responseHandler,
        BarramentoClient $barramentoClient,
        SerializerInterface $serializer
    ) {
        $this->init($resource, $responseHandler);
        $this->barramentoClient = $barramentoClient;
        $this->serializer = $serializer;
    }

    /**
     * Endpoint action para consultar repositorio no barramento.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws Throwable
     */
    #[Route(path: '/consulta_repositorio', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function consultaRepositorio(
        Request $request,
        CacheItemPoolInterface $cache,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        $this->validateRestMethod($request, $allowedHttpMethods);

        $cacheKey = 'consulta_repositorio_cache';

        $repositorio = $cache->get($cacheKey, function (ItemInterface $item) {
            $item->expiresAfter(604800);

            try {
                $consultarRepositoriosResponse = $this->barramentoClient->consultarRepositoriosDeEstruturas();

                $repositorio = [];
                if ($consultarRepositoriosResponse) {
                    foreach ($consultarRepositoriosResponse->repositoriosEncontrados->repositorio as $k => $repositorioBarramento) {
                        $repositorio[$k]['id'] = $repositorioBarramento->id;
                        $repositorio[$k]['nome'] = $repositorioBarramento->nome;
                    }
                }

                return $repositorio;
            } catch (Throwable $exception) {
                throw $this->handleRestMethodException($exception);
            }
        });

        return new JsonResponse($repositorio, Response::HTTP_OK);
    }

    /**
     * Endpoint action para consultar estruturas no barramento.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws Throwable
     */
    #[Route(path: '/consulta_estrutura', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function consultaEstrutura(
        Request $request,
        CacheItemPoolInterface $cache,
        ?array $allowedHttpMethods = null
    ): JsonResponse {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);
        $identificacaoRepositorio = $request->get('repositorio');

        // Defina a chave do cache
        $cacheKey = 'consulta_estrutura_cache_'.$identificacaoRepositorio;

        // Verifique o cache
        $cacheItem = $cache->getItem($cacheKey);

        if (!$cacheItem->isHit()) {
            try {
                $identificacaoRepositorio = $request->get('repositorio');
                $limit = $request->get('limit');
                $offset = $request->get('offset');
                $nomeDaEstrutura = $request->get('nome') ?: null;

                // Chama o client do barramento
                $consultarEstruturasResponse = $this->barramentoClient->consultarEstruturas(
                    (int)$identificacaoRepositorio,
                    (int)$identificacaoEstrutura = null,
                    (string)$nomeDaEstrutura,
                    (int)$limit,
                    (int)$offset
                );

                $estruturas = [];
                $totalRegistros = 0;

                if ($consultarEstruturasResponse && isset($consultarEstruturasResponse->estruturasEncontradas->estrutura)) {
                    $estruturaSearchBarramento = $consultarEstruturasResponse->estruturasEncontradas->estrutura ?: null;
                    $totalRegistros = $consultarEstruturasResponse->estruturasEncontradas->totalDeRegistros ?: 0;

                    if ($estruturaSearchBarramento && $totalRegistros > 0) {
                        if (is_array($estruturaSearchBarramento)) {
                            foreach ($estruturaSearchBarramento as $k => $estruturaBarramento) {
                                $estruturas[$k]['numeroDeIdentificacaoDaEstrutura'] = $estruturaBarramento->numeroDeIdentificacaoDaEstrutura;
                                $estruturas[$k]['nome'] = $estruturaBarramento->nome;
                                $estruturas[$k]['sigla'] = $estruturaBarramento->sigla;
                                $estruturas[$k]['ativo'] = $estruturaBarramento->ativo;

                                if (isset($estruturaBarramento->hierarquia) && $estruturaBarramento->hierarquia && $estruturaBarramento->hierarquia->nivel) {
                                    foreach ($estruturaBarramento->hierarquia->nivel as $keyNivel => $hierarquia) {
                                        if (isset($estruturas[$k]['hierarquia'])) {
                                            $estruturas[$k]['hierarquia'][$keyNivel]['nome'] = $hierarquia->nome;
                                            $estruturas[$k]['hierarquia'][$keyNivel]['sigla'] = $hierarquia->sigla;
                                        }

                                        if (2 === $keyNivel) {
                                            break;
                                        }
                                    }
                                }
                            }
                        } else {
                            $estruturas[0]['numeroDeIdentificacaoDaEstrutura'] = $estruturaSearchBarramento->numeroDeIdentificacaoDaEstrutura;
                            $estruturas[0]['nome'] = $estruturaSearchBarramento->nome;
                            $estruturas[0]['sigla'] = $estruturaSearchBarramento->sigla;
                            $estruturas[0]['ativo'] = $estruturaSearchBarramento->ativo;

                            if ($estruturaSearchBarramento->hierarquia && $estruturaSearchBarramento->hierarquia->nivel) {
                                foreach ($estruturaSearchBarramento->hierarquia->nivel as $keyNivel => $hierarquia) {
                                    $estruturas[0]['hierarquia'][$keyNivel]['nome'] = $hierarquia->nome;
                                    $estruturas[0]['hierarquia'][$keyNivel]['sigla'] = $hierarquia->sigla;
                                    if (2 === $keyNivel) {
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }

                $responseData = [
                    'entities' => $estruturas,
                    'total' => $totalRegistros,
                ];

                // Salve a resposta no cache
                $cacheItem->set($responseData);
                $cacheItem->expiresAfter(604800); // 7 dias em segundos
                $cache->save($cacheItem);

            } catch (Throwable $exception) {
                throw $this->handleRestMethodException($exception);
            }
        } else {
            $responseData = $cacheItem->get();
        }

        return new JsonResponse($responseData, Response::HTTP_OK);
    }
}
