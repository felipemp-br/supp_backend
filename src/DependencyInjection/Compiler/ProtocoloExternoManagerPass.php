<?php

declare(strict_types=1);
/**
 * src/DependencyInjection/Compiler/ProtocoloExternoManagerPass.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DependencyInjection\Compiler;

use SuppCore\AdministrativoBackend\Helpers\ProtocoloExterno\ProtocoloExternoManager;
use function array_keys;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ProtocoloExternoManagerPass.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ProtocoloExternoManagerPass implements CompilerPassInterface
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
        if (!$container->has(ProtocoloExternoManager::class)) {
            return;
        }

        $protocoloExternoManagerPass = $container->getDefinition(ProtocoloExternoManager::class);

        foreach (array_keys($container->findTaggedServiceIds('protocolo_externo_drivers.driver')) as $id) {
            $protocoloExternoManagerPass->addMethodCall('addDriverProtocoloExterno', [new Reference($id)]);
        }
    }
}
