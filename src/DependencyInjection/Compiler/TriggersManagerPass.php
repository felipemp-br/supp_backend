<?php
/**
 * /src/DependencyInjection/Compiler/TriggersManagerPass.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DependencyInjection\Compiler;

use ReflectionException;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggersManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use function array_keys;

/**
 * Class TriggersManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TriggersManagerPass implements CompilerPassInterface
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
     * @throws ReflectionException
     */
    public function process(ContainerBuilder $container): void
    {
        // always first check if the primary service is defined
        if (!$container->has(TriggersManager::class)) {
            return;
        }

        $triggers = [];

        foreach (array_keys($container->findTaggedServiceIds('triggers_manager.trigger')) as $id) {
            $definition = $container->getDefinition($id);
            $definition->setPublic(true);

            /** @var TriggerInterface $trigger */
            $trigger = $container->getReflectionClass($id)->newInstanceWithoutConstructor();

            $triggers[$id] = $trigger;
        }

        $parameters = [];
        foreach ($triggers as $triggerClass => $trigger) {
            $triggerType = TriggersManager::getTriggerType($trigger);
            foreach ($trigger->supports() as $className => $contexts) {
                foreach (TriggersManager::getSupportedClassNames($className) as $name) {
                    foreach ($contexts as $context) {
                        $key = TriggersManager::getTriggersSupportedKey(
                            $triggerType,
                            $name,
                            $context
                        );
                        $parameters[$key][] = $triggerClass;
                    }
                }
            }
        }

        foreach ($parameters as $key => $parameter) {
            $container->setParameter($key, array_unique($parameter));
        }
    }
}
