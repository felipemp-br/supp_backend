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
final class Version20220714190215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.8.13 to 1.8.14';
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
            $this->cronJobUp($schema);
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
    public function cronJobUp(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->createTable('ad_cronjob');

        $table->addColumn(
            'nome',
            Type::getType('string')->getName(),
            [
                'notnull' => true,
                'length' => 255
            ]
        );

        $table->addColumn(
            'periodicidade',
            Type::getType('string')->getName(),
            [
                'notnull' => true,
                'length' => 255
            ]
        );

        $table->addColumn(
            'status_ultima_execucao ',
            Type::getType('integer')->getName(),
            [
                'notnull' => false
            ]
        );

        $table->addColumn(
            'ultimo_pid ',
            Type::getType('integer')->getName(),
            [
                'notnull' => false
            ]
        );

        $table->addColumn(
            'percentual_execucao ',
            Type::getType('float')->getName(),
            [
                'notnull' => false
            ]
        );

        $table->addColumn(
            'comando',
            Type::getType('string')->getName(),
            [
                'notnull' => true,
                'length' => 255
            ]
        );

        $table->addColumn(
            'descricao',
            Type::getType('string')->getName(),
            [
                'notnull' => true,
                'length' => 255
            ]
        );

        $table->addColumn(
            'ativo',
            Type::getType('boolean')->getName(),
            [
                'notnull' => true
            ]
        );
        $table->addColumn(
            'id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
                'autoincrement' => true,
            ]
        );

        $table->addColumn(
            'uuid',
            Type::getType('guid')->getName(),
            [
                'notnull' => true,
                'comment' => '(DC2Type:guid)'
            ]
        );

        $table->addColumn(
            'usuario_ultima_execucao',
            Type::getType('integer')->getName(),
            [
                'notnull' => false
            ]
        );

        $table->addColumn(
            'criado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false
            ]
        );

        $table->addColumn(
            'atualizado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false
            ]
        );

        $table->addColumn(
            'apagado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false
            ]
        );

        $table->addColumn(
            'criado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false
            ]
        );

        $table->addColumn(
            'atualizado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false
            ]
        );

        $table->addColumn(
            'apagado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false
            ]
        );
        $table->addColumn(
            'data_hora_ultima_execucao',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false
            ]
        );

        $table
            ->addUniqueIndex(
                [
                    'uuid'
                ]
            )
            ->setPrimaryKey(['id'])
            ->addForeignKeyConstraint(
                'ad_usuario',
                ['usuario_ultima_execucao'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_usuario',
                ['criado_por'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_usuario',
                ['atualizado_por'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_usuario',
                ['apagado_por'],
                ['id']
            )
            ->addUniqueIndex(
                [
                    'nome',
                    'apagado_em'
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
            $this->cronJobDown($schema);
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
    private function cronJobDown(Schema $schema): void {
        $schemaTo = $this->sm->createSchema();
        $schemaTo->dropTable('ad_cronjob');

        $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaTo);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schemaDiff->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }
}
