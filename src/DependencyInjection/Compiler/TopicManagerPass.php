<?php

declare(strict_types=1);
/**
 * /src/DependencyInjection/TopicManagerPass.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DependencyInjection\Compiler;

use SuppCore\AdministrativoBackend\Integracao\Datalake\TopicManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class TopicManagerPass.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TopicManagerPass implements CompilerPassInterface
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
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(TopicManager::class)) {
            return;
        }

        $TopicManager = $container->getDefinition(TopicManager::class);

        foreach (array_keys($container->findTaggedServiceIds('integracao_datalake.topic_producer')) as $id) {
            $TopicManager->addMethodCall('addTopicProducer', [new Reference($id)]);
        }

        foreach (array_keys($container->findTaggedServiceIds('integracao_datalake.topic_consumer')) as $id) {
            $TopicManager->addMethodCall('addTopicConsumer', [new Reference($id)]);
        }
    }
}
