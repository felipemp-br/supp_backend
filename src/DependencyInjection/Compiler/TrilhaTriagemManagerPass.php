<?php
/**
 * /src/DependencyInjection/Compiler/TriggersManagerPass.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DependencyInjection\Compiler;

use ReflectionClass;
use ReflectionException;
use RuntimeException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Attributes\Trilha;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\TrilhaTriagemManager;
use SuppCore\AdministrativoBackend\Utils\Graph;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Class AdaptadorManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TrilhaTriagemManagerPass implements CompilerPassInterface
{
    /**
     * Verifica as dependencias entre as trilhas de triagem e configura as mesmas.
     *
     * @codeCoverageIgnore
     *
     * @param ContainerBuilder $container
     *
     * @throws InvalidArgumentException
     * @throws ServiceNotFoundException
     * @throws ReflectionException
     */
    public function process(ContainerBuilder $container): void
    {
        // always first check if the primary service is defined
        if (!$container->has(TrilhaTriagemManager::class)) {
            return;
        }
        $trilhaTriagemManager = $container->getDefinition(TrilhaTriagemManager::class);
        $trilhas = array_map(
            fn (string $trilhaId) => $container->getDefinition($trilhaId),
            array_keys(
                $container->findTaggedServiceIds(
                    'supp_core.administrativo_backend.inteligencia_artificial.trilha_triagem.trilha'
                )
            )
        );
        $prompts = array_map(
            fn (string $promptId) => $container->getDefinition($promptId),
            array_keys(
                $container->findTaggedServiceIds(
                    'supp_core.administrativo_backend.inteligencia_artificial.trilha_triagem.prompt'
                )
            )
        );
        $graph = new Graph();
        foreach ($trilhas as $trilhaTriagem) {
            $graph->addVertice($trilhaTriagem->getClass());
            $reflectionClass = new ReflectionClass($trilhaTriagem->getClass());
            $attributes = $reflectionClass->getAttributes(Trilha::class);
            if (empty($attributes)) {
                throw new RuntimeException(
                    sprintf(
                        'A trilha de triagem [%s] precisa utilizar o attribute %s',
                        $trilhaTriagem->getClass(),
                        Trilha::class
                    )
                );
            }
            /** @var Trilha $trilhaAttribute */
            $trilhaAttribute = $attributes[0]->newInstance();
            $trilhaTriagem->addMethodCall(
                'setDependsOn',
                [$trilhaAttribute->getDependsOn()]
            );
            $trilhaTriagem->addMethodCall(
                'setNomeTrilha',
                [$trilhaAttribute->getNome()]
            );
            $trilhaTriagem->addMethodCall(
                'setPrompts',
                [$trilhaAttribute->getPrompts(), $prompts]
            );

            if (!empty($trilhaAttribute->getDependsOn())) {
                foreach ($trilhaAttribute->getDependsOn() as $dependency) {
                    $graph->addEdge($trilhaTriagem->getClass(), $dependency);
                }
            }
        }
        if ($graph->isCyclic($cyclicElement)) {
            throw new RuntimeException(
                sprintf(
                    'Erro de dependência ciclica na trilha de triagem [%s]',
                    $cyclicElement
                ),
                500
            );
        }
        $sortedDependecies = array_reverse($graph->topologicalSort(false));
        usort(
            $trilhas,
            function ($trilhaA, $trilhaB) use ($sortedDependecies) {
                $indexA = array_search($trilhaA->getClass(), $sortedDependecies);
                $indexB = array_search($trilhaB->getClass(), $sortedDependecies);
                return $indexA - $indexB;
            }
        );
        $trilhaTriagemManager->addMethodCall('setTrilhasTriagem', [$trilhas]);
    }
}
