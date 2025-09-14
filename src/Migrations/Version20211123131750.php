<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;
use Throwable;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211123131750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.6.12 do 1.7.0';
    }

    /**
     * @param Schema $schemaFrom
     * @throws ConnectionException
     * @throws Exception
     * @throws SchemaException
     * @throws Throwable
     */
    public function up(Schema $schemaFrom): void
    {
        try {
            $this->connection->beginTransaction();
            $schemaTo = $this->sm->createSchema();
            $this->documentoUp($schemaTo);
            $this->componenteDigitalUp($schemaTo);
            // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
            foreach ($schemaFrom->getMigrateToSql($schemaTo, $this->platform) as $sql) {
                $this->addSql($sql);
            }
            $this->connection->commit();
        } catch (Throwable $e) {
            $this->connection->rollBack();
            throw  $e;
        }
    }


    /**
     * @param Schema $schemaFrom
     * @throws ConnectionException
     * @throws Throwable
     */
    public function down(Schema $schemaFrom): void
    {
        try {
            $this->connection->beginTransaction();
            $schemaTo = $this->sm->createSchema();
            $this->documentoDown($schemaTo);
            $this->componenteDigitalDown($schemaTo);

            // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
            foreach ($schemaFrom->getMigrateToSql($schemaTo, $this->platform) as $sql) {
                $this->addSql($sql);
            }

            $this->connection->commit();
        }catch (Throwable $e) {
            $this->connection->rollBack();
            throw  $e;
        }
    }

    /**
     * @param Schema $schemaTo
     * @throws Exception
     * @throws SchemaException
     */
    private function documentoUp(Schema $schemaTo): void
    {
        $table = $schemaTo->getTable('ad_documento');

        $table->addColumn(
            'dependencia_software',
            Type::getType('string')->getName(),
            [
                'notnull' => false,
                'length' => 255
            ]
        );

        $table->addColumn(
            'dependencia_hardware',
            Type::getType('string')->getName(),
            [
                'notnull' => false,
                'length' => 255
            ]
        );
    }

    /**
     * @param Schema $schemaTo
     * @throws Exception
     * @throws SchemaException
     */
    private function componenteDigitalUp(Schema $schemaTo): void
    {
        $table = $schemaTo->getTable('ad_componente_digital');

        $table->addColumn(
            'status_verificacao_virus',
            Type::getType('integer')->getName(),
            [
                'notnull' => false
            ]
        );
    }

    /**
     * @param Schema $schemaTo
     * @throws SchemaException
     */
    private function documentoDown(Schema $schemaTo): void
    {
        $table = $schemaTo->getTable('ad_documento');

        $table->dropColumn('dependencia_software');
        $table->dropColumn('dependencia_hardware');
    }

    /**
     * @param Schema $schemaTo
     * @throws SchemaException
     */
    private function componenteDigitalDown(Schema $schemaTo): void
    {
        $table = $schemaTo->getTable('ad_componente_digital');

        $table->dropColumn('status_verificacao_virus');
    }

    /**
     * @return bool
     */
    public function isTransactional(): bool
    {
        return false;
    }
}
