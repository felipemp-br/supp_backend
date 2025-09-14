<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Schema\ColumnDiff;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\TableDiff;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Query\Query;
use Throwable;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220221131838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.8.1 to 1.8.2';
    }

    /**
     * @param Schema $schema
     * @return void
     * @throws ConnectionException
     * @throws Exception
     * @throws Throwable
     * @throws \Doctrine\DBAL\Exception
     */
    public function up(Schema $schema): void
    {
        try {
            $this->connection->beginTransaction();
            $this->workflowUp($schema);
            $this->especieDocumentoAvulsoUp($schema);
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
     * @throws SchemaException
     * @throws \Doctrine\DBAL\Exception
     */
    protected function workflowUp(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_workflow');
        $table->addColumn(
            'genero_processo_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addIndex(['genero_processo_id']);

        $table->addForeignKeyConstraint(
            'ad_genero_processo',
            ['genero_processo_id'],
            ['id']
        );

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schema->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }

        $this->addSql(
            $this->connection->createQueryBuilder()
                ->update('ad_workflow')
                ->set('genero_processo_id',
                    '(' .
                    $this->connection->createQueryBuilder()
                        ->select('agp.id')
                        ->distinct()
                        ->from('ad_genero_processo', 'agp')
                        ->where(
                            $this->connection->getExpressionBuilder()->eq(
                                'agp.nome',
                                '\'ADMINISTRATIVO\''
                            )
                        )
                        ->getSQL()
                    . ')'
                )
                ->getSQL()
        );

        //Forçando mudança de campo antes de refletir as alterações no platform
        $col1 = $table->getColumn('genero_processo_id');
        $col2 = $table->getColumn('genero_processo_id')->setNotnull(true);

        $colDiff = new ColumnDiff(
            'genero_processo_id',
            $col2,
            ['genero_processo_id'],
            $col1
        );

        $diff = new TableDiff(
            'ad_workflow',
            [],
            [$colDiff]
        );

        foreach ($this->sm->getDatabasePlatform()->getAlterTableSQL($diff) as $sql) {
            $this->addSql($sql);
        }

    }

    /**
     * @param Schema $schema
     * @return void
     * @throws SchemaException
     * @throws \Doctrine\DBAL\Exception
     */
    protected function especieDocumentoAvulsoUp(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_especie_doc_avulso');
        $table->addColumn(
            'workflow_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addIndex(['workflow_id']);

        $table->addForeignKeyConstraint(
            'ad_workflow',
            ['workflow_id'],
            ['id']
        );

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schema->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    /**
     * @param Schema $schema
     * @return void
     * @throws ConnectionException
     * @throws Exception
     * @throws Throwable
     * @throws \Doctrine\DBAL\Exception
     */
    public function down(Schema $schema): void
    {
        try {
            $this->connection->beginTransaction();
            $this->workflowDown($schema);
            $this->especieDocumentoAvulsoDown($schema);
//            dd(implode('; ', array_map(fn(Query $q) => $q->getStatement(), $this->getSql())).';');
            $this->connection->commit();
        }catch (Throwable $e) {
            $this->connection->rollBack();
            throw  $e;
        }
    }

    /**
     * @param Schema $schema
     * @throws SchemaException|Exception
     */
    protected function workflowDown(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_workflow');
        $table->dropColumn('genero_processo_id');

        foreach ($table->getIndexes() as $index) {
            if (!array_diff(['genero_processo_id'], $index->getColumns())) {
                $table->dropIndex($index->getName());
                break;
            }
        }

        foreach ($table->getForeignKeys() as $fk) {
            if ($fk->getForeignTableName() === 'ad_genero_processo' && !array_diff(['genero_processo_id'], $fk->getColumns())) {
                $table->removeForeignKey($fk->getName());
                break;
            }
        }

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schema->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }
    }
    /**
     * @param Schema $schema
     * @throws SchemaException|Exception
     */
    protected function especieDocumentoAvulsoDown(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_especie_doc_avulso');
        $table->dropColumn('workflow_id');

        foreach ($table->getIndexes() as $index) {
            if (!array_diff(['workflow_id'], $index->getColumns())) {
                $table->dropIndex($index->getName());
                break;
            }
        }

        foreach ($table->getForeignKeys() as $fk) {
            if ($fk->getForeignTableName() === 'ad_workflow' && !array_diff(['workflow_id'], $fk->getColumns())) {
                $table->removeForeignKey($fk->getName());
                break;
            }
        }

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schema->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }
    }
}
