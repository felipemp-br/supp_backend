<?php


namespace SuppCore\AdministrativoBackend\DependencyInjection\Compiler;

use SuppCore\AdministrativoBackend\Crypto\CryptoManager;
use SuppCore\AdministrativoBackend\Filesystem\FilesystemManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class FilesystemManagerPass
 * @package SuppCore\AdministrativoBackend\Crypto
 */
class FilesystemManagerPass implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        // always first check if the primary service is defined
        if (!$container->has(FilesystemManager::class)) {
            return;
        }

        $providersManager = $container->getDefinition(FilesystemManager::class);

        foreach (array_keys($container->findTaggedServiceIds('filesystem.service')) as $id) {
            $providersManager->addMethodCall('addFilesystemService', [new Reference($id), $id]);
        }
    }

}
