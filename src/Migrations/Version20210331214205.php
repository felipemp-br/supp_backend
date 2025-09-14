<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\ColumnDiff;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\TableDiff;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;

final class Version20210331214205 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'migration from 1.3.0 to 1.4.0';
    }

    public function up(Schema $schema) : void
    {
        $this->upComponenteDigital($schema);
    }

    public function down(Schema $schema) : void
    {
        $this->downComponenteDigital($schema);
    }

    public function upComponenteDigital(Schema $schemaFrom): void {
        $schemaTo = $this->connection->getSchemaManager()->createSchema();

        $adComponenteDigital = $schemaTo->getTable('ad_componente_digital');

        $adComponenteDigital->addColumn(
            'crypto_service',
            Type::getType('string')->getName(),
            [
                'notnull' => false,
                'length' => 255
            ]
        );

        $adComponenteDigital->addColumn(
            'filesystem_service',
            Type::getType('string')->getName(),
            [
                'notnull' => false,
                'length' => 255
            ]
        );

        foreach ($schemaFrom->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }

    }

    public function downComponenteDigital(Schema $schema): void {
        $adComponenteDigital = $schema->getTable('ad_componente_digital');
        $adComponenteDigital->dropColumn('crypto_service');
        $adComponenteDigital->dropColumn('filesystem_service');
    }

    public function isTransactional() : bool {
        return true;
    }
}
