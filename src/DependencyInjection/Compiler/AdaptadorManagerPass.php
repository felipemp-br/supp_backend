<?php
/**
 * /src/DependencyInjection/Compiler/TriggersManagerPass.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DependencyInjection\Compiler;

use function array_keys;
use SuppCore\AdministrativoBackend\Document\Adaptador\AdaptadorManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class AdaptadorManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AdaptadorManagerPass implements CompilerPassInterface
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
     */
    public function process(ContainerBuilder $container): void
    {
        // always first check if the primary service is defined
        if (!$container->has(AdaptadorManager::class)) {
            return;
        }

        $adaptadorManager = $container->getDefinition(AdaptadorManager::class);

        foreach (array_keys($container->findTaggedServiceIds('document_adaptador_manager.adaptador')) as $id) {
            $adaptadorManager->addMethodCall('addAdaptador', [new Reference($id)]);
        }
    }
}
