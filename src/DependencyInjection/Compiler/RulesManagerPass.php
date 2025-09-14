<?php
/**
 * /src/DependencyInjection/Compiler/RulesManagerPass.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DependencyInjection\Compiler;

use ReflectionException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use function array_keys;
use SuppCore\AdministrativoBackend\Rules\RulesManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Class RulesManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RulesManagerPass implements CompilerPassInterface
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
        if (!$container->has(RulesManager::class)) {
            return;
        }

        $rules = [];

        foreach (array_keys($container->findTaggedServiceIds('rules_manager.rule')) as $id) {
            $definition = $container->getDefinition($id);
            $definition->setPublic(true);

            /** @var RuleInterface $rule */
            $rule = $container->getReflectionClass($id)->newInstanceWithoutConstructor();

            $rules[$id] = $rule;
        }

        // Ordena pelas ordens das rules
        uasort($rules, fn(RuleInterface $a, RuleInterface $b) => $a->getOrder() <=> $b->getOrder());

        $parameters = [];
        foreach ($rules as $ruleClass => $rule) {
            foreach ($rule->supports() as $className => $contexts) {
                foreach ($contexts as $context) {
                    if (str_contains($className, '\\Entity\\')) {
                        $parameters['Rules_For_Entity_'.str_replace('\\', '_', $className)][] = $ruleClass;
                    }
                    $parameters['Rules_For_'.str_replace('\\', '_', $className.'_'.$context)][] = $ruleClass;
                }
            }
        }

        foreach ($parameters as $key => $parameter) {
            $container->setParameter($key, $parameter);
        }
    }
}
