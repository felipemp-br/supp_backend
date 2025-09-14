<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;


use Doctrine\Migrations\AbstractMigration as DoctrineAbstractMigration;
use SuppCore\AdministrativoBackend\Migrations\Helpers\MigrationHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * AbstractMigration.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class AbstractMigration extends DoctrineAbstractMigration
{
    protected ContainerInterface $container;
    protected ?MigrationHelper $migrationHelper;

    /**
     * @param ContainerInterface|null $container
     *
     * @return void
     */
    #[Required]
    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
        $this->migrationHelper = $this->container->get(MigrationHelper::class);
    }

    /**
     * Realiza o debug das queries.
     * @return void
     */
    protected function debug(): void
    {
        dd(implode('; ', array_map(fn($q) => $q->getStatement(), $this->getSql())).';');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
