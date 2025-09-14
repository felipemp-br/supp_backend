<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210409120451 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'migration from 1.2.1 to 1.3.0';
    }

    public function up(Schema $schema) : void
    {
        try{
            $this->connection->beginTransaction();

            $this->upAviso($schema);
            $this->upVinculoAviso($schema);
        }catch (\Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    public function down(Schema $schema) : void
    {
        try {
            $this->connection->beginTransaction();

            $this->downAviso($schema);
            $this->downVinculoAviso($schema);
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    private function upAviso(Schema $schema): void
    {

        $adAviso = $schema->createTable('ad_aviso');
        $adAviso->addColumn(
            'id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
                'autoincrement' => true
            ]
        );

        $adAviso->addColumn(
            'sistema',
            Type::getType('boolean')->getName(),
            [
                'notnull' => true
            ]
        );

        $adAviso->addColumn(
            'criado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false
            ]
        );

        $adAviso->addColumn(
            'atualizado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false
            ]
        );

        $adAviso->addColumn(
            'apagado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false
            ]
        );

        $adAviso->addColumn(
            'criado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false
            ]
        );

        $adAviso->addColumn(
            'atualizado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false
            ]
        );

        $adAviso->addColumn(
            'apagado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false
            ]
        );

        $adAviso->addColumn(
            'nome',
            Type::getType('string')->getName(),
            [
                'notnull' => true,
                'length' => 255
            ]
        );

        $adAviso->addColumn(
            'descricao',
            Type::getType('string')->getName(),
            [
                'notnull' => true,
                'length' => 255
            ]
        );

        $adAviso->addColumn(
            'ativo',
            Type::getType('boolean')->getName(),
            [
                'notnull' => true
            ]
        );

        $adAviso->addColumn(
            'uuid',
            Type::getType('guid')->getName(),
            [
                'notnull' => true,
                'comment' => '(DC2Type:guid)'
            ]
        );

        $adAviso->addUniqueIndex(
            [
                'uuid'
            ]
        );

        $adAviso->setPrimaryKey(['id']);

        $adAviso->addForeignKeyConstraint(
            'ad_usuario',
            ['criado_por'],
            ['id'],
            []
        );

        $adAviso->addForeignKeyConstraint(
            'ad_usuario',
            ['atualizado_por'],
            ['id'],
            []
        );

        $adAviso->addForeignKeyConstraint(
            'ad_usuario',
            ['apagado_por'],
            ['id'],
            []
        );
    }

    private function downAviso(Schema $schema): void
    {
        $schema->dropTable('ad_aviso');
    }

    private function upVinculoAviso(Schema $schema): void
    {
        $adVincAviso = $schema->createTable('ad_vinc_aviso');

        $adVincAviso->addColumn(
            'id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
                'autoincrement' => true
            ]
        );

        $adVincAviso->addColumn(
            'aviso_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true
            ]
        );

        $adVincAviso->addColumn(
            'especie_setor_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true
            ]
        );

        $adVincAviso->addColumn(
            'setor_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true
            ]
        );

        $adVincAviso->addColumn(
            'usuario_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true
            ]
        );

        $adVincAviso->addColumn(
            'orgao_central_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true
            ]
        );

        $adVincAviso->addColumn(
            'unidade_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true
            ]
        );

        $adVincAviso->addColumn(
            'criado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false
            ]
        );

        $adVincAviso->addColumn(
            'atualizado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false
            ]
        );

        $adVincAviso->addColumn(
            'apagado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false
            ]
        );

        $adVincAviso->addColumn(
            'criado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false
            ]
        );

        $adVincAviso->addColumn(
            'atualizado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false
            ]
        );

        $adVincAviso->addColumn(
            'apagado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false
            ]
        );

        $adVincAviso->addColumn(
            'uuid',
            Type::getType('guid')->getName(),
            [
                'notnull' => true,
                'comment' => '(DC2Type:guid)'
            ]
        );

        $adVincAviso->addUniqueIndex(
            [
                'uuid'
            ]
        );

        $adVincAviso->setPrimaryKey(['id']);

        $adVincAviso->addForeignKeyConstraint(
            'ad_mod_orgao_central',
            ['orgao_central_id'],
            ['id'],
            []
        );

        $adVincAviso->addForeignKeyConstraint(
            'ad_especie_setor',
            ['especie_setor_id'],
            ['id'],
            []
        );

        $adVincAviso->addForeignKeyConstraint(
            'ad_aviso',
            ['aviso_id'],
            ['id'],
            []
        );

        $adVincAviso->addForeignKeyConstraint(
            'ad_setor',
            ['setor_id'],
            ['id'],
            []
        );

        $adVincAviso->addForeignKeyConstraint(
            'ad_setor',
            ['unidade_id'],
            ['id'],
            []
        );

        $adVincAviso->addForeignKeyConstraint(
            'ad_usuario',
            ['usuario_id'],
            ['id'],
            []
        );

        $adVincAviso->addForeignKeyConstraint(
            'ad_usuario',
            ['criado_por'],
            ['id'],
            []
        );

        $adVincAviso->addForeignKeyConstraint(
            'ad_usuario',
            ['atualizado_por'],
            ['id'],
            []
        );

        $adVincAviso->addForeignKeyConstraint(
            'ad_usuario',
            ['apagado_por'],
            ['id'],
            []
        );
    }

    private function downVinculoAviso(Schema $schema): void
    {
        $schema->dropTable('ad_vinc_aviso');
    }
}
