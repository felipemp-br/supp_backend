<?php
/**
 * /src/DependencyInjection/Compiler/RelatorioManagerPass.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DependencyInjection\Compiler;

use SuppCore\AdministrativoBackend\Helpers\TipoRelatorio\TipoRelatorioManager;
use function array_keys;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RelatorioManagerPass.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoRelatorioManagerPass implements CompilerPassInterface
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
        if (!$container->has(TipoRelatorioManager::class)) {
            return;
        }

        $tipoRelatorioManagerPass = $container->getDefinition(TipoRelatorioManager::class);

        foreach (array_keys($container->findTaggedServiceIds('tipo_relatorio_drivers.driver')) as $id) {
            $tipoRelatorioManagerPass->addMethodCall('addDriverTipoRelatorio', [new Reference($id)]);
        }
    }
}
