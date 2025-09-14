<?php


namespace SuppCore\AdministrativoBackend\DependencyInjection\Compiler;

use SuppCore\AdministrativoBackend\Crypto\CryptoManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class CryptoManagerPass
 * @package SuppCore\AdministrativoBackend\Crypto
 */
class CryptoManagerPass implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        // always first check if the primary service is defined
        if (!$container->has(CryptoManager::class)) {
            return;
        }

        $providersManager = $container->getDefinition(CryptoManager::class);

        foreach (array_keys($container->findTaggedServiceIds('crypto.service')) as $id) {
            $providersManager->addMethodCall('addCryptoService', [new Reference($id), $id]);
        }
    }

}
