<?php

declare(strict_types=1);
/**
 * /src/Compiler/StopwatchCompilerPass.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Stopwatch;

use function str_starts_with;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class StopwatchCompilerPass.
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class StopwatchCompilerPass implements CompilerPassInterface
{
    private const SERVICE_TAGS = [
        'security.voter',
        'kernel.event_subscriber',
        'validator.constraint_validator',
        'validator.initializer',
        'app.stopwatch',
    ];

    public function process(ContainerBuilder $container): void
    {
        foreach (self::SERVICE_TAGS as $tag) {
            foreach ($container->findTaggedServiceIds($tag) as $serviceId => $tags) {
                if (!str_starts_with($serviceId, 'Supp')) {
                    continue;
                }

                $definition = new Definition($container->getDefinition($serviceId)->getClass());
                $definition->setDecoratedService($serviceId);
                $definition->setFactory([new Reference(StopwatchDecorator::class), 'decorate']);
                $definition->setArguments([new Reference($serviceId.'.stopwatch.inner')]);

                $container->setDefinition($serviceId.'.stopwatch', $definition);
            }
        }
    }
}
