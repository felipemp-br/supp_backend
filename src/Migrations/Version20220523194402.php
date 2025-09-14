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
final class Version20220523194402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.8.3 to 1.8.4';
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
    public function vinculacaoEtiquetaUp(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_vinc_etiqueta');
        $table->addColumn(
            'sugestao',
            Type::getType('boolean')->getName(),
            [
                'notnull' => false
            ]
        );
        $table->addColumn(
            'data_hora_aprov_sugestao',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false
            ]
        );
        $table->addColumn(
            'usuario_aprov_sugestao',
            Type::getType('integer')->getName(),
            [
                'notnull' => false
            ]
        );

        $table->addForeignKeyConstraint(
            'ad_usuario',
            ['usuario_aprov_sugestao'],
            ['id'],
            []
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
    private function vinculacaoEtiquetaDown(Schema $schema): void {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_vinc_etiqueta');
        $table->dropColumn('sugestao');
        $table->dropColumn('data_hora_aprov_sugestao');
        $table->dropColumn('usuario_aprov_sugestao');

        foreach ($table->getForeignKeys() as $fk) {
            if ($fk->getForeignTableName() === 'ad_usuario' && !array_diff(['usuario_aprov_sugestao'], $fk->getLocalColumns())) {
                $table->removeForeignKey($fk->getName());
            }
        }

        $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaTo);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schemaDiff->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }
}
