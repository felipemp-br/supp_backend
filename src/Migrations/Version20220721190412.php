<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\Query\Query;
use Throwable;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220721190412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.8.14 to 1.8.15';
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
            $this->etiquetaUp($schema);
            $this->vinculacaoEtiquetaUp($schema);
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
    public function etiquetaUp(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();

        $table = $schemaTo->getTable('ad_etiqueta');

        $table->addColumn(
            'tipo_exec_acao_sugestao',
            Type::getType('integer')->getName(),
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
     * @throws SchemaException
     */
    public function vinculacaoEtiquetaUp(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();

        $table = $schemaTo->getTable('ad_vinc_etiqueta');

        $table->addColumn(
            'acoes_execucao_sugestao',
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
    }

    public function down(Schema $schema): void
    {
        try {
            $this->connection->beginTransaction();
            $this->etiquetaDown($schema);
            $this->vinculacaoEtiquetaDown($schema);
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
    private function etiquetaDown(Schema $schema): void {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_etiqueta');

        $table->dropColumn('tipo_exec_acao_sugestao');

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
     * @throws SchemaException
     */
    private function vinculacaoEtiquetaDown(Schema $schema): void {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_vinc_etiqueta');

        $table->dropColumn('acoes_execucao_sugestao');

        $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaTo);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schemaDiff->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }
}
