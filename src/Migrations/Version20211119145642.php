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
final class Version20211119145642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.6.12 do 1.7.0';
    }

    /**
     * @param Schema $schemaFrom
     * @throws Throwable
     * @throws ConnectionException
     */
    public function up(Schema $schemaFrom): void
    {
        try {
            $this->connection->beginTransaction();
            $schemaTo = $this->sm->createSchema();
            $this->modalidadeCopiaUp($schemaTo);
            $this->documentoUp($schemaTo);
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
     * @param Schema $schemaTo
     * @throws Exception
     */
    private function modalidadeCopiaUp(Schema $schemaTo): void
    {
        $table = $schemaTo->createTable('ad_mod_copia');

        $table->addColumn(
            'id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
                'autoincrement' => true,
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
            'valor',
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
            'uuid',
            Type::getType('guid')->getName(),
            [
                'notnull' => true,
                'comment' => '(DC2Type:guid)'
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
                    'valor',
                ]
            )
            ->addUniqueIndex(
                [
                    'valor',
                    'apagado_em'
                ]
            );
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
            $this->modalidadeCopiaDown($schemaTo);

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
            'mod_copia_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false
            ]
        );

        $table->addForeignKeyConstraint(
            'ad_mod_copia',
            ['mod_copia_id'],
            ['id']
        );
    }

    /**
     * @param Schema $schemaTo
     */
    private function modalidadeCopiaDown(Schema $schemaTo): void
    {
        $schemaTo->dropTable('ad_mod_copia');
    }

    /**
     * @param Schema $schemaTo
     * @throws SchemaException
     */
    private function documentoDown(Schema $schemaTo): void
    {
        $table = $schemaTo->getTable('ad_documento');

        $table->dropColumn('mod_copia_id');
    }

    /**
     * @return bool
     */
    public function isTransactional(): bool
    {
        return false;
    }
}
