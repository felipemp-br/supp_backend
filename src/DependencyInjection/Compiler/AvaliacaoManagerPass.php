<?php
/**
 * /src/DependencyInjection/Compiler/AvaliacaoManagerPass.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DependencyInjection\Compiler;

use function array_keys;
use SuppCore\AdministrativoBackend\Integracao\Avaliacao\AvaliacaoManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class AvaliacaoManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AvaliacaoManagerPass implements CompilerPassInterface
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
        if (!$container->has(AvaliacaoManager::class)) {
            return;
        }

        $avaliacaoManager = $container->getDefinition(AvaliacaoManager::class);

        foreach (array_keys($container->findTaggedServiceIds('integracao_avaliacao.avaliacao')) as $id) {
            $avaliacaoManager->addMethodCall('addAvaliacao', [new Reference($id)]);
        }
    }
}
