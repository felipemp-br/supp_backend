<?php

declare(strict_types=1);
/**
 * /src/DependencyInjection/KafkaTopicManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DependencyInjection\Compiler;

use SuppCore\AdministrativoBackend\Integracao\Datalake\KafkaTopicManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class DossieManagerPass.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class KafkaTopicManagerPass implements CompilerPassInterface
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
        if (!$container->has(KafkaTopicManager::class)) {
            return;
        }

        $kafkaTopicManager = $container->getDefinition(KafkaTopicManager::class);

        foreach (array_keys($container->findTaggedServiceIds('integracao_datalake.topic_processor')) as $id) {
            $kafkaTopicManager->addMethodCall('addKafkaTopicProcessor', [new Reference($id)]);
        }
    }
}
