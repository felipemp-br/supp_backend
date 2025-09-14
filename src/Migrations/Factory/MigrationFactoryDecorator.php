<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations\Factory;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Version\MigrationFactory;
use Psr\Container\ContainerInterface;

class MigrationFactoryDecorator implements MigrationFactory
{

    public function __construct(
        private readonly MigrationFactory $migrationFactory,
        private readonly ContainerInterface $container
    ) {
    }

    public function createVersion(string $migrationClassName): AbstractMigration
    {
        $version = $this->migrationFactory->createVersion($migrationClassName);
        if (method_exists($version, 'setContainer')) {
            $version->setContainer($this->container);
        }
        return $version;
    }
}
