<?php
/**
 * /src/DependencyInjection/Compiler/StylesManagerPass.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DependencyInjection\Compiler;

use SuppCore\AdministrativoBackend\Fields\StylesManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;
use function array_keys;

/**
 * Class StylesManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class StylesManagerPass implements CompilerPassInterface
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
        if (!$container->has(StylesManager::class)) {
            return;
        }

        $fieldsManager = $container->getDefinition(StylesManager::class);

        foreach (array_keys($container->findTaggedServiceIds('styles_manager.style')) as $id) {
            $fieldsManager->addMethodCall('addStyle', [new Reference($id)]);
        }
    }
}
