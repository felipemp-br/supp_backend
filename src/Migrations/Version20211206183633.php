<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Uuid as Ruuid;
use Throwable;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211206183633 extends AbstractMigration
{

    private array $vinculacaoWorkflowUpData = [];
    private array $tarefaUpData = [];
    private array $vinculacaoEspecieProcessoWorkflowUpData = [];

    private array $tarefaDownData = [];
    private array $especieProcessoDownData = [];
    private array $processoDownData = [];

    public function getDescription(): string
    {
        return 'migration from 1.7.0 to 1.8.0';
    }

    public function up(Schema $schema): void
    {
        try {
            $this->connection->beginTransaction();
            $this->prepareUpData();
            $this->vinculacaoEspecieProcessoWorkflowUp($schema);
            $this->transicaoWorkflowUp($schema);
            $this->vinculacaoWorkflowUp($schema);
            $this->tarefaUp($schema);
            $this->processoUp($schema);
            $this->especieProcessoUp($schema);
            $this->vinculacaoTransicaoWorkflowUp($schema);
//            dd(implode('; ', array_map(fn(Query $q) => $q->getStatement(), $this->getSql())).';');
            $this->connection->commit();
        } catch (Throwable $e) {
            $this->connection->rollBack();
            throw  $e;
        }
    }

    /**
     * @param Schema $schema
     * @throws Exception
     */
    protected function vinculacaoWorkflowUp(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->createTable('ad_vinc_workflow');
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
                'comment' => '(DC2Type:guid)',
            ]
        );
        $table->addColumn(
            'workflow_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
            ]
        );
        $table->addColumn(
            'tarefa_atual_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
            ]
        );
        $table->addColumn(
            'tarefa_inicial_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
            ]
        );
        $table->addColumn(
            'concluido',
            Type::getType('boolean')->getName(),
            [
                'notnull' => true,
            ]
        );

        $this->blameableUp($table);
        $this->timestampableUp($table);
        $this->softdeletableUp($table);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['uuid']);
        $table->addUniqueIndex(['workflow_id', 'tarefa_inicial_id', 'apagado_em']);
        $table->addIndex(['workflow_id']);
        $table->addIndex(['tarefa_inicial_id']);
        $table->addIndex(['tarefa_atual_id']);

        $table->addForeignKeyConstraint(
            'ad_tarefa',
            ['tarefa_atual_id'],
            ['id']
        );

        $table->addForeignKeyConstraint(
            'ad_tarefa',
            ['tarefa_inicial_id'],
            ['id']
        );

        $table->addForeignKeyConstraint(
            'ad_workflow',
            ['workflow_id'],
            ['id']
        );


        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schema->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }

        foreach ($this->vinculacaoWorkflowUpData as $data) {
            $data['uuid'] = Ruuid::uuid4()->toString();
            // devido a bug não é possível concluir nenhum workflow no sistema na versão anterior
            $data['concluido'] = false;
            $types = [
                Types::INTEGER,
                Types::INTEGER,
                Types::INTEGER,
                Types::STRING,
                Types::BOOLEAN,
            ];

            $this->addSql(
                $this->generateInsert('ad_vinc_workflow', array_keys($data), array_values($data), $types)
            );
        }
    }

    private function parseValues($value, $type): mixed
    {
        if ($value === null) {
            return 'NULL';
        }

        return match ($type) {
            Types::STRING,
            Types::TEXT,
            Types::BLOB,
            Types::BINARY,
            Types::GUID => '\'' . $value . '\'',
            Types::INTEGER,
            Types::BIGINT => (int) $value,
            Types::BOOLEAN => $this->connection->convertToDatabaseValue($value, Types::BOOLEAN)
        };
    }

    private function generateInsert($table, $keys, $values, $types): string
    {
        $insert = 'INSERT INTO ' . $table . ' (' . implode(', ', $keys)
            . ') VALUES (' . implode(', ', array_map(fn($item) => '%s', $values)) . ')';

        return sprintf(
            $insert,
            ...
            array_map(
                fn($value, $index) => $this->parseValues($value, $types[$index]),
                $values,
                array_keys($values)
            )
        );
    }

    /**
     * @param Schema $schema
     * @throws Exception
     */
    protected function tarefaUp(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_tarefa');

        $table->addColumn(
            'vinculacao_workflow_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );
        $table->addIndex(['vinculacao_workflow_id']);

        $table->addForeignKeyConstraint(
            'ad_vinc_workflow',
            ['vinculacao_workflow_id'],
            ['id']
        );

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

        foreach ($this->tarefaUpData as $data) {
            $this->addSql(
                $this->connection->createQueryBuilder()
                    ->update('ad_tarefa')
                    ->set(
                        'vinculacao_workflow_id',
                        '(' .
                        $this->connection->createQueryBuilder()
                            ->select('avw.id')
                            ->distinct()
                            ->from('ad_vinc_workflow', 'avw')
                            ->where(
                                $this->connection->getExpressionBuilder()->eq(
                                    'avw.tarefa_atual_id',
                                    $data['tarefa_atual_id']
                                )
                            )
                            ->getSQL()
                        . ')'
                    )
                    ->where($this->connection->getExpressionBuilder()->eq('id', $data['tarefa_id']))
                    ->getSQL()
            );
        }
    }

    /**
     * @param Schema $schema
     * @throws Exception
     */
    protected function processoUp(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_processo');
        $table->dropColumn('tarefa_atual_workflow_id');
        foreach ($table->getForeignKeys() as $fk) {
            if ($fk->getForeignTableName() === 'ad_tarefa' &&
                !array_diff(['tarefa_atual_workflow_id'], $fk->getColumns())) {
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
     * @throws Exception
     */
    protected function especieProcessoUp(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_especie_processo');
        $table->dropColumn('workflow_id');
        foreach ($table->getForeignKeys() as $fk) {
            if ($fk->getForeignTableName() === 'ad_workflow' &&
                !array_diff(['workflow_id'], $fk->getColumns())) {
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
     * @throws Exception
     */
    protected function vinculacaoEspecieProcessoWorkflowUp(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->createTable('ad_vinc_esp_proc_workflow');
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
                'comment' => '(DC2Type:guid)',
            ]
        );
        $table->addColumn(
            'workflow_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
            ]
        );
        $table->addColumn(
            'especie_processo_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
            ]
        );

        $this->blameableUp($table);
        $this->timestampableUp($table);
        $this->softdeletableUp($table);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['uuid']);
        $table->addUniqueIndex(['workflow_id', 'especie_processo_id', 'apagado_em']);
        $table->addIndex(['workflow_id']);
        $table->addIndex(['especie_processo_id']);

        $table->addForeignKeyConstraint(
            'ad_workflow',
            ['workflow_id'],
            ['id']
        );

        $table->addForeignKeyConstraint(
            'ad_especie_processo',
            ['especie_processo_id'],
            ['id']
        );

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schema->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }

        foreach ($this->vinculacaoEspecieProcessoWorkflowUpData as $data) {
            $data['uuid'] = Ruuid::uuid4()->toString();

            $types = [
                Types::INTEGER,
                Types::INTEGER,
                Types::INTEGER,
                Types::INTEGER,
                Types::STRING,
                Types::STRING,
                Types::STRING,
            ];

            $this->addSql(
                $this->generateInsert('ad_vinc_esp_proc_workflow', array_keys($data), array_values($data), $types)
            );
        }
    }

    /**
     * @param Schema $schema
     * @throws Exception
     */
    protected function vinculacaoTransicaoWorkflowUp(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->createTable('ad_vinc_trans_workflow');
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
                'comment' => '(DC2Type:guid)',
            ]
        );
        $table->addColumn(
            'workflow_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
            ]
        );
        $table->addColumn(
            'transicao_workflow_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
            ]
        );

        $this->blameableUp($table);
        $this->timestampableUp($table);
        $this->softdeletableUp($table);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['uuid']);
        $table->addUniqueIndex(['workflow_id', 'transicao_workflow_id', 'apagado_em']);
        $table->addIndex(['workflow_id']);
        $table->addIndex(['transicao_workflow_id']);

        $table->addForeignKeyConstraint(
            'ad_workflow',
            ['workflow_id'],
            ['id']
        );

        $table->addForeignKeyConstraint(
            'ad_transicao_workflow',
            ['transicao_workflow_id'],
            ['id']
        );

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schema->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    /**
     * @param Schema $schema
     * @throws Exception
     */
    protected function transicaoWorkflowUp(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_transicao_workflow');

        $table->addColumn(
            'qtd_dias_prazo',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addUniqueIndex(['workflow_id', 'especie_tarefa_from_id', 'especie_atividade_id', 'apagado_em']);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schema->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }

        $this->addSql(
            $this->connection->createQueryBuilder()
                ->update('ad_transicao_workflow')
                ->set('qtd_dias_prazo', 5)
                ->getSQL()
        );
    }

    /**
     * @throws ConnectionException
     * @throws Throwable
     * @throws SchemaException
     */
    public function down(Schema $schema): void
    {
        try {
            $this->connection->beginTransaction();
            $this->prepareDownData();
            $this->especieProcessoDown($schema);
            $this->vinculacaoEspecieProcessoWorkflowDown($schema);
            $this->transicaoWorkflowDown($schema);
            $this->vinculacaoWorkflowDown($schema);
            $this->tarefaDown($schema);
            $this->processoDown($schema);
            $this->vinculacaoTransicaoWorkflowDown($schema);
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
    protected function vinculacaoWorkflowDown(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_vinc_workflow');

        foreach ($table->getIndexes() as $index) {
            if (!array_diff(['workflow_id', 'tarefa_inicial_id', 'apagado_em'], $index->getColumns())) {
                $table->dropIndex($index->getName());
                break;
            }
        }
        $this->blameableDown($table);
        $this->timestampableDown($table);
        $this->softdeletableDown($table);
        $schemaTo->dropTable('ad_vinc_workflow');
        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schema->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    /**
     * @param Schema $schema
     * @throws SchemaException|Exception
     */
    protected function tarefaDown(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_tarefa');
        $table->dropColumn('vinculacao_workflow_id');
        $table->addColumn(
            'workflow_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addForeignKeyConstraint(
            'ad_workflow',
            ['workflow_id'],
            ['id']
        );

        foreach ($table->getIndexes() as $index) {
            if (!array_diff(['vinculacao_workflow_id'], $index->getColumns())) {
                $table->dropIndex($index->getName());
                break;
            }
        }
        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schema->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }

        foreach ($this->tarefaDownData as $data) {
            $this->addSql(
                $this->connection->createQueryBuilder()
                    ->update('ad_tarefa')
                    ->set('workflow_id', $data['workflow_id'])
                    ->where($this->connection->getExpressionBuilder()->eq('id', $data['tarefa_id']))
                    ->getSQL()
            );
        }
    }

    /**
     * @param Schema $schema
     * @throws SchemaException|Exception
     */
    protected function processoDown(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_processo');
        $table->addColumn(
            'tarefa_atual_workflow_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );
        $table->addForeignKeyConstraint(
            'ad_tarefa',
            ['tarefa_atual_workflow_id'],
            ['id']
        );
        $table->addUniqueIndex(['tarefa_atual_workflow_id']);
        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schema->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }

        foreach ($this->processoDownData as $data) {
            $this->addSql(
                $this->connection->createQueryBuilder()
                    ->update('ad_processo')
                    ->set('tarefa_atual_workflow_id', $data['tarefa_atual_workflow_id'])
                    ->where($this->connection->getExpressionBuilder()->eq('id', $data['processo_id']))
                    ->getSQL()
            );
        }
    }

    protected function prepareDownData(): void
    {
        //Tarefa
        $dbalQb = $this->connection->createQueryBuilder();
        $dbalQb
            ->select('at.id as tarefa_id, avw.workflow_id')
            ->from('ad_tarefa', 'at')
            ->join('at', 'ad_vinc_workflow', 'avw', 'at.vinculacao_workflow_id = avw.id')
            ->leftJoin('avw', 'ad_vinc_trans_workflow', 'avtw', 'avw.workflow_id = avtw.workflow_id')
            ->where($dbalQb->expr()->isNotNull('at.vinculacao_workflow_id'))
            ->andWhere($dbalQb->expr()->isNull('avtw.id'));

        $this->tarefaDownData = $this->connection->executeQuery($dbalQb->getSQL())->fetchAllAssociative();

        //Especie Processo
        $dbalQb = $this->connection->createQueryBuilder();
        $dbalQb->select(
            'avepw.workflow_id, avepw.especie_processo_id'
        )
            ->distinct()
            ->from('ad_vinc_esp_proc_workflow', 'avepw');

        $this->especieProcessoDownData = $this->connection->executeQuery($dbalQb->getSQL())->fetchAllAssociative();

        //Processo
        $dbalQb = $this->connection->createQueryBuilder();
        $dbalQb
            ->select('avw.tarefa_atual_id as tarefa_atual_workflow_id, ap.id as processo_id')
            ->distinct()
            ->from('ad_vinc_workflow', 'avw')
            ->join('avw', 'ad_tarefa', 'at', 'avw.tarefa_atual_id = at.id')
            ->join('at', 'ad_processo', 'ap', 'at.processo_id = ap.id')
            ->leftJoin('avw', 'ad_vinc_trans_workflow', 'avtw', 'avtw.workflow_id = avw.workflow_id')
            //OBS: Não pega subworkflows (as tarefas de subworkflows vão deixar de ser do tipo workflow)
            ->where($dbalQb->expr()->isNull('avtw.workflow_id'))
            ->orderBy('avw.tarefa_atual_id', 'ASC');

        $this->processoDownData = $this->connection->executeQuery($dbalQb->getSQL())->fetchAllAssociative();
    }

    protected function prepareUpData(): void
    {
        //Vinculacao Workflow
        $dbalQb = $this->connection->createQueryBuilder();
        $dbalQb->select(
            'aw.id as workflow_id, at.id as tarefa_inicial_id, ap.tarefa_atual_workflow_id as tarefa_atual_id'
        )
            ->distinct()
            ->from('ad_processo', 'ap')
            ->join('ap', 'ad_especie_processo', 'aep', 'ap.especie_processo_id = aep.id')
            ->join('aep', 'ad_workflow', 'aw', 'aep.workflow_id = aw.id')
            ->join(
                'ap',
                'ad_tarefa',
                'at',
                'ap.id = at.processo_id'
            )
            ->where($dbalQb->expr()->isNotNull('at.workflow_id'))
            ->andWhere($dbalQb->expr()->isNotNull('ap.tarefa_atual_workflow_id'))
            ->andWhere($dbalQb->expr()->eq('at.especie_tarefa_id', 'aw.especie_tarefa_inicial_id'));

        $this->vinculacaoWorkflowUpData = $this->connection->executeQuery($dbalQb->getSQL())->fetchAllAssociative();

        //Tarefa
        $dbalQb = $this->connection->createQueryBuilder();
        $dbalQb
            ->select('at.id as tarefa_id, ap.tarefa_atual_workflow_id as tarefa_atual_id')
            ->from('ad_tarefa', 'at')
            ->join('at', 'ad_processo', 'ap', 'at.processo_id = ap.id')
            ->where($dbalQb->expr()->isNotNull('at.workflow_id'));

        $this->tarefaUpData = $this->connection->executeQuery($dbalQb->getSQL())->fetchAllAssociative();

        //Vinculacao Especie Processo Workflow

        $dbalQb = $this->connection->createQueryBuilder();
        $dbalQb->select(
            'ep.id as especie_processo_id, ep.workflow_id, criado_por, atualizado_por, criado_em, atualizado_em'
        )
            ->from('ad_especie_processo', 'ep')
            ->where($dbalQb->expr()->isNotNull('ep.workflow_id'));

        $this->vinculacaoEspecieProcessoWorkflowUpData = $this->connection
            ->executeQuery(
                $dbalQb->getSQL()
            )
            ->fetchAllAssociative();

    }

    /**
     * @param Schema $schema
     * @throws SchemaException|Exception
     */
    protected function especieProcessoDown(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_especie_processo');
        $table->addColumn(
            'workflow_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );
        $table->addForeignKeyConstraint(
            'ad_workflow',
            ['workflow_id'],
            ['id']
        );

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schema->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }

        foreach ($this->especieProcessoDownData as $data) {
            $this->addSql(
                $this->connection->createQueryBuilder()
                    ->update('ad_especie_processo')
                    ->set('workflow_id', $data['workflow_id'])
                    ->where($this->connection->getExpressionBuilder()->eq('id', $data['especie_processo_id']))
                    ->getSQL()
            );
        }
    }

    /**
     * @param Schema $schema
     * @throws Exception
     */
    protected function vinculacaoEspecieProcessoWorkflowDown(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_vinc_esp_proc_workflow');

        $this->blameableDown($table);
        $this->timestampableDown($table);
        $this->softdeletableDown($table);

        foreach ($table->getIndexes() as $index) {
            if (!array_diff(['workflow_id'], $index->getColumns())
                || !array_diff(['especie_processo_id'], $index->getColumns())
                || !array_diff(['workflow_id', 'especie_processo_id', 'apagado_em'], $index->getColumns())) {
                $table->dropIndex($index->getName());
            }
        }

        foreach ($table->getForeignKeys() as $fk) {
            if ($fk->getForeignTableName() === 'ad_workflow' && !array_diff(['workflow_id'], $fk->getColumns())) {
                $table->removeForeignKey($fk->getName());
            }
            if ($fk->getForeignTableName() === 'ad_especie_processo'
                && !array_diff(['especie_processo_id'], $fk->getColumns())) {
                $table->removeForeignKey($fk->getName());
            }
        }

        $schemaTo->dropTable('ad_vinc_esp_proc_workflow');

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schema->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    /**
     * @param Schema $schema
     * @throws Exception
     */
    protected function vinculacaoTransicaoWorkflowDown(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_vinc_trans_workflow');

        $this->blameableDown($table);
        $this->timestampableDown($table);
        $this->softdeletableDown($table);

        foreach ($table->getIndexes() as $index) {
            if (!array_diff(['workflow_id'], $index->getColumns())
                || !array_diff(['transicao_workflow_id'], $index->getColumns())
                || !array_diff(['workflow_id', 'transicao_workflow_id', 'apagado_em'], $index->getColumns())) {
                $table->dropIndex($index->getName());
            }
        }

        foreach ($table->getForeignKeys() as $fk) {
            if ($fk->getForeignTableName() === 'ad_workflow'
                && !array_diff(['workflow_id'], $fk->getColumns())) {
                $table->removeForeignKey($fk->getName());
            }
            if ($fk->getForeignTableName() === 'ad_transicao_workflow'
                && !array_diff(['transicao_workflow_id'], $fk->getColumns())) {
                $table->removeForeignKey($fk->getName());
            }
        }

        $schemaTo->dropTable('ad_vinc_trans_workflow');

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schema->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    /**
     * @param Schema $schema
     * @throws Exception
     */
    protected function transicaoWorkflowDown(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_transicao_workflow');

        $table->dropColumn('qtd_dias_prazo');

        $dropIndex = ['workflow_id', 'especie_tarefa_from_id', 'especie_atividade_id', 'apagado_em'];
        foreach ($table->getIndexes() as $index) {
            if (!array_diff($dropIndex, $index->getColumns())) {
                $table->dropIndex($index->getName());
                break;
            }
        }

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schema->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    /**
     * @param Table $table
     * @throws Exception
     */
    private function blameableUp(Table $table): void
    {
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

        $table->addForeignKeyConstraint(
                'ad_usuario',
                ['criado_por'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_usuario',
                ['atualizado_por'],
                ['id']
            );
    }

    /**
     * @param Table $table
     * @throws Exception
     */
    private function timestampableUp(Table $table): void
    {
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
    }

    /**
     * @param Table $table
     * @return void
     * @throws Exception
     * @throws SchemaException
     */
    private function softdeletableUp(Table $table): void
    {
        $table->addColumn(
            'apagado_por',
            Type::getType('integer')->getName(),
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
        $table->addForeignKeyConstraint(
            'ad_usuario',
            ['apagado_por'],
            ['id']
        );
    }

    /**
     * @param Table $table
     * @throws SchemaException
     */
    private function blameableDown(Table $table): void
    {
        foreach ($table->getForeignKeys() as $fk) {
            if ($fk->getForeignTableName() === 'ad_usuario') {
                if (!array_diff(['criado_por'], $fk->getColumns())
                    || !array_diff(['atualizado_por'], $fk->getColumns())) {
                    $table->removeForeignKey($fk->getName());
                }
            }
        }
    }

    /**
     * @param Table $table
     */
    private function timestampableDown(Table $table): void
    {
    }

    /**
     * @param Table $table
     * @throws Exception
     */
    private function softdeletableDown(Table $table): void
    {
        foreach ($table->getForeignKeys() as $fk) {
            if ($fk->getForeignTableName() === 'ad_usuario') {
                if (!array_diff(['apagado_por'], $fk->getColumns())) {
                    $table->removeForeignKey($fk->getName());
                    break;
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function isTransactional(): bool
    {
        return false;
    }
}
