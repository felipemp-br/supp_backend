<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;
use Throwable;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306120702 extends AbstractMigration
{
    /**
     * Migration description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return 'migration from 1.15.0 to 1.16.0';
    }

    /**
     * Schema upgrade.
     *
     * @param Schema $schema Schema.
     * @return void
     * @throws Throwable
     * @throws Exception
     * @throws SchemaException
     */
    public function up(Schema $schema): void
    {
        try {
            $this->connection->beginTransaction();
            $schemaTo = $this->sm->createSchema();

            $table = $schemaTo->getTable('ad_regra_etiqueta');

            $table->addColumn(
                'regra',
                Type::getType('text')->getName(),
                [
                    'notnull' => false
                ]
            );

            $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaTo);

            // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
            foreach ($schemaDiff->toSql($this->platform) as $sql) {
                $this->addSql($sql);
            }

//            dd(implode('; ', array_map(fn(Query $q) => $q->getStatement(), $this->getSql())).';');
            $this->connection->commit();
        } catch (Throwable $e) {
            $this->connection->rollBack();
            throw  $e;
        }
    }

    /**
     * Schema downgrade.
     *
     * @param Schema $schema Schema.
     * @return void
     * @throws Exception
     * @throws SchemaException
     * @throws Throwable
     */
    public function down(Schema $schema): void
    {

        try {
            $this->connection->beginTransaction();
            $schemaTo = $this->sm->createSchema();
            $table = $schemaTo->getTable('ad_regra_etiqueta');

            $table->dropColumn('regra');
            $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaTo);

            // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
            foreach ($schemaDiff->toSql($this->platform) as $sql) {
                $this->addSql($sql);
            }
//            dd(implode('; ', array_map(fn(Query $q) => $q->getStatement(), $this->getSql())).';');
            $this->connection->commit();
        }catch (Throwable $e) {
            $this->connection->rollBack();
            throw  $e;
        }

    }
}
