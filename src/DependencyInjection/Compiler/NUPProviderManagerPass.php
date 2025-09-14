<?php
/**
 * /src/DependencyInjection/Compiler/NUPProviderManagerPass.php.
 */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DependencyInjection\Compiler;

use function array_keys;
use SuppCore\AdministrativoBackend\NUP\NUPProviderManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class NUPProviderManagerPass.
 */
class NUPProviderManagerPass implements CompilerPassInterface
{
    /**
     * @codeCoverageIgnore
     *
     * @param ContainerBuilder $container
     *
     * @throws InvalidArgumentException
     * @throws ServiceNotFoundException
     */
    public function process(ContainerBuilder $container): void
    {
        // always first check if the primary service is defined
        if (!$container->has(NUPProviderManager::class)) {
            return;
        }

        $providersManager = $container->getDefinition(NUPProviderManager::class);

        foreach (array_keys($container->findTaggedServiceIds('nup_provider_manager.provider')) as $id) {
            $providersManager->addMethodCall('addProvider', [new Reference($id)]);
        }
    }
}
