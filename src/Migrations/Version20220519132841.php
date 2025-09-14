<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Query\Query;
use Throwable;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220519132841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.8.2 to 1.8.3';
    }

    /**
     * @param Schema $schema
     * @return void
     * @throws Exception
     * @throws SchemaException
     * @throws Throwable
     */
    public function up(Schema $schema): void
    {
        try {
            $this->connection->beginTransaction();
            $this->usuarioUp($schema);
//            dd(implode('; ', array_map(fn(Query $q) => $q->getStatement(), $this->getSql())).';');
            $this->connection->commit();
        } catch (Throwable $e) {
            $this->connection->rollBack();
            throw  $e;
        }

    }

    /**
     * @param Schema $schema
     * @return void
     * @throws Exception
     * @throws SchemaException
     */
    public function usuarioUp(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_usuario');
        $table->addColumn(
            'password_atualizado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false
            ]
        );

        $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaTo);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schemaDiff->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    /**
     * @param Schema $schema
     * @return void
     * @throws Exception
     * @throws Throwable
     */
    public function down(Schema $schema): void
    {
        try {
            $this->connection->beginTransaction();
            $this->usuarioDown($schema);
//            dd(implode('; ', array_map(fn(Query $q) => $q->getStatement(), $this->getSql())).';');
            $this->connection->commit();
        }catch (Throwable $e) {
            $this->connection->rollBack();
            throw  $e;
        }

    }

    /**
     * @param Schema $schema
     * @return void
     * @throws Exception
     * @throws SchemaException
     */
    private function usuarioDown(Schema $schema): void {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_usuario');
        $table->dropColumn('password_atualizado_em');
        $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaTo);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schemaDiff->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }
}
