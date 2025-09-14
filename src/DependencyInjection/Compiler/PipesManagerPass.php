<?php
/**
 * /src/DependencyInjection/Compiler/PipesManagerPass.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DependencyInjection\Compiler;

use Exception;
use SuppCore\AdministrativoBackend\Mapper\Pipes\OverridePipeInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipesManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

use function array_keys;

/**
 * Class PipesManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PipesManagerPass implements CompilerPassInterface
{
    /**
     * This process will attach all REST resource objects to collection class, where we can use those on certain cases.
     *
     * @codeCoverageIgnore
     *
     * @param ContainerBuilder $container
     *
     * @throws InvalidArgumentException
     * @throws ServiceNotFoundException
     * @throws Exception
     */
    public function process(ContainerBuilder $container): void
    {
        // always first check if the primary service is defined
        if (!$container->has(PipesManager::class)) {
            return;
        }

        $pipes = [];

        foreach (array_keys($container->findTaggedServiceIds('pipes_manager.pipe')) as $id) {
            if (array_key_exists($id, $pipes)) {
                continue;
            }

            $definition = $container->getDefinition($id);
            $definition->setPublic(true);

            /** @var PipeInterface $pipe */
            $pipe = $container->getReflectionClass($id)->newInstanceWithoutConstructor();

            $pipes[$id] = $pipe;

            if ($pipe instanceof OverridePipeInterface) {
                foreach ($pipe->overridePipes() as $overridePipe) {
                    if ($overridePipe === $id) {
                        throw new Exception(sprintf('Não é possível fazer override da própria pipe %s', $id));
                    }

                    $pipes[$overridePipe] = null;
                }
            }
        }

        // Remove pipes nulas antes de ordenar
        $pipes = array_filter($pipes);

        // Ordena pelas ordens das pipes
        uasort($pipes, fn(PipeInterface $a, PipeInterface $b) => $a->getOrder() <=> $b->getOrder());

        $parameters = [];
        foreach ($pipes as $pipeClass => $pipe) {
            foreach ($pipe->supports() as $className => $contexts) {
                foreach ($contexts as $context) {
                    $parameters['Pipes_For_'.str_replace('\\', '_', $className.'_'.$context)][] = $pipeClass;
                }
            }
        }

        foreach ($parameters as $key => $parameter) {
            $container->setParameter($key, $parameter);
        }
    }
}
