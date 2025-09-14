<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210422203714 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'migration from 1.3.0 to 1.4.0';
    }

    /**
     * @throws ConnectionException
     * @throws \Throwable
     */
    public function up(Schema $schemaFrom) : void
    {
        try {
            $this->connection->beginTransaction();
            $schemaTo = $this->sm->createSchema();
            $this->chatUp($schemaTo);
            $this->chatMensagemUp($schemaTo);
            $this->chatParticipanteUp($schemaTo);
            $this->chatConstraintUp($schemaTo);
            $this->usuarioUp($schemaTo);

            // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
            foreach ($schemaFrom->getMigrateToSql($schemaTo, $this->platform) as $sql) {
                $this->addSql($sql);
            }

            $this->connection->commit();
        }catch (\Throwable $e) {
            $this->connection->rollBack();
            throw  $e;
        }
    }

    /**
     * @throws ConnectionException
     * @throws \Throwable
     */
    public function down(Schema $schemaFrom) : void
    {
        try {
            $this->connection->beginTransaction();
            $schemaTo = $this->sm->createSchema();
            $this->chatDown($schemaTo);
            $this->usuarioDown($schemaTo);

            // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
            foreach ($schemaFrom->getMigrateToSql($schemaTo, $this->platform) as $sql) {
                $this->addSql($sql);
            }

            $this->connection->commit();
        }catch (\Throwable $e) {
            $this->connection->rollBack();
            throw  $e;
        }
    }

    public function usuarioUp(Schema $schemaTo) : void
    {
        $usuario = $schemaTo->getTable('ad_usuario');

        $usuario->addColumn(
            'img_perfil_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $usuario->addColumn(
            'img_chancela_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $usuario->addForeignKeyConstraint(
            'ad_componente_digital',
            ['img_perfil_id'],
            ['id']
        );

        $usuario->addForeignKeyConstraint(
            'ad_componente_digital',
            ['img_chancela_id'],
            ['id']
        );

        $usuario->addUniqueIndex(['img_perfil_id']);
        $usuario->addUniqueIndex(['img_chancela_id']);
    }

    public function chatUp(Schema $schemaTo) : void
    {
        $chat = $schemaTo->createTable('ad_chat');

        $chat->addColumn(
            'id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
                'autoincrement' => true,
            ]
        );

        $chat->addColumn(
            'capa_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chat->addColumn(
            'ultima_mensagem_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chat->addColumn(
            'criado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chat->addColumn(
            'atualizado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chat->addColumn(
            'apagado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chat->addColumn(
            'nome',
            Type::getType('string')->getName(),
            [
                'notnull' => false,
                'length' => 255,
            ]
        );

        $chat->addColumn(
            'descricao',
            Type::getType('string')->getName(),
            [
                'notnull' => false,
                'length' => 255,
            ]
        );

        $chat->addColumn(
            'ativo',
            Type::getType('boolean')->getName(),
            [
                'notnull' => true,
            ]
        );

        $chat->addColumn(
            'grupo',
            Type::getType('boolean')->getName(),
            [
                'notnull' => true,
            ]
        );

        $chat->addColumn(
            'criado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chat->addColumn(
            'atualizado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chat->addColumn(
            'apagado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chat->addColumn(
            'uuid',
            Type::getType('guid')->getName(),
            [
                'notnull' => true,
                'comment' => '(DC2Type:guid)',
            ]
        );

        $chat->addUniqueIndex(['uuid']);
        $chat->addIndex(['capa_id']);
        $chat->addIndex(['ultima_mensagem_id']);
        $chat->addIndex(['criado_por']);
        $chat->addIndex(['atualizado_por']);
        $chat->addIndex(['apagado_por']);
        $chat->setPrimaryKey(['id']);
    }

    public function chatMensagemUp(Schema $schemaTo) : void
    {
        $chatMensagem = $schemaTo->createTable('ad_chat_mensagem');

        $chatMensagem->addColumn(
            'id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
                'autoincrement' => true,
            ]
        );

        $chatMensagem->addColumn(
            'usuario_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
            ]
        );

        $chatMensagem->addColumn(
            'chat_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
            ]
        );

        $chatMensagem->addColumn(
            'mensagem_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chatMensagem->addColumn(
            'componente_digital_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chatMensagem->addColumn(
            'criado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chatMensagem->addColumn(
            'atualizado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chatMensagem->addColumn(
            'apagado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chatMensagem->addColumn(
            'criado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chatMensagem->addColumn(
            'atualizado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chatMensagem->addColumn(
            'apagado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chatMensagem->addColumn(
            'uuid',
            Type::getType('guid')->getName(),
            [
                'notnull' => true,
                'comment' => '(DC2Type:guid)',
            ]
        );

        $chatMensagem->addColumn(
            'mensagem',
            Type::getType('string')->getName(),
            [
                'notnull' => false,
                'length' => 255,
            ]
        );

        $chatMensagem->addUniqueIndex(['uuid']);
        $chatMensagem->addIndex(['usuario_id']);
        $chatMensagem->addIndex(['chat_id']);
        $chatMensagem->addIndex(['mensagem_id']);
        $chatMensagem->addIndex(['componente_digital_id']);
        $chatMensagem->addIndex(['criado_por']);
        $chatMensagem->addIndex(['atualizado_por']);
        $chatMensagem->addIndex(['apagado_por']);
        $chatMensagem->setPrimaryKey(['id']);
    }

    public function chatParticipanteUp(Schema $schemaTo) : void
    {
        $chatParticipante = $schemaTo->createTable('ad_chat_participante');

        $chatParticipante->addColumn(
            'id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
                'autoincrement' => true,
            ]
        );

        $chatParticipante->addColumn(
            'usuario_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
            ]
        );

        $chatParticipante->addColumn(
            'mensagens_nao_lidas',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chatParticipante->addColumn(
            'criado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chatParticipante->addColumn(
            'atualizado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chatParticipante->addColumn(
            'chat_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
            ]
        );

        $chatParticipante->addColumn(
            'administrador',
            Type::getType('boolean')->getName(),
            [
                'notnull' => true,
            ]
        );

        $chatParticipante->addColumn(
            'ultima_visualizacao',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chatParticipante->addColumn(
            'criado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chatParticipante->addColumn(
            'atualizado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false,
            ]
        );

        $chatParticipante->addColumn(
            'uuid',
            Type::getType('guid')->getName(),
            [
                'notnull' => true,
                'comment' => '(DC2Type:guid)',
            ]
        );

        $chatParticipante->addUniqueIndex(['uuid']);
        $chatParticipante->addIndex(['usuario_id']);
        $chatParticipante->addIndex(['chat_id']);
        $chatParticipante->addIndex(['criado_por']);
        $chatParticipante->addIndex(['atualizado_por']);
        $chatParticipante->setPrimaryKey(['id']);
    }

    private function chatConstraintUp(Schema $schemaTo) : void
    {
        $chat = $schemaTo->getTable('ad_chat');
        $chatMensagem = $schemaTo->getTable('ad_chat_mensagem');
        $chatParticipante = $schemaTo->getTable('ad_chat_participante');

        $chat->addForeignKeyConstraint(
            'ad_componente_digital',
            ['capa_id'],
            ['id']
        );

        $chat->addForeignKeyConstraint(
            'ad_chat_mensagem',
            ['ultima_mensagem_id'],
            ['id']
        );

        $chat->addForeignKeyConstraint(
            'ad_usuario',
            ['criado_por'],
            ['id']
        );

        $chat->addForeignKeyConstraint(
            'ad_usuario',
            ['atualizado_por'],
            ['id']
        );

        $chat->addForeignKeyConstraint(
            'ad_usuario',
            ['apagado_por'],
            ['id']
        );

        $chatMensagem->addForeignKeyConstraint(
            'ad_usuario',
            ['usuario_id'],
            ['id']
        );

        $chatMensagem->addForeignKeyConstraint(
            'ad_chat',
            ['chat_id'],
            ['id']
        );

        $chatMensagem->addForeignKeyConstraint(
            'ad_chat_mensagem',
            ['mensagem_id'],
            ['id']
        );

        $chatMensagem->addForeignKeyConstraint(
            'ad_componente_digital',
            ['componente_digital_id'],
            ['id']
        );

        $chatMensagem->addForeignKeyConstraint(
            'ad_usuario',
            ['criado_por'],
            ['id']
        );

        $chatMensagem->addForeignKeyConstraint(
            'ad_usuario',
            ['atualizado_por'],
            ['id']
        );

        $chatMensagem->addForeignKeyConstraint(
            'ad_usuario',
            ['apagado_por'],
            ['id']
        );

        $chatParticipante->addForeignKeyConstraint(
            'ad_usuario',
            ['usuario_id'],
            ['id']
        );

        $chatParticipante->addForeignKeyConstraint(
            'ad_usuario',
            ['criado_por'],
            ['id']
        );

        $chatParticipante->addForeignKeyConstraint(
            'ad_usuario',
            ['atualizado_por'],
            ['id']
        );

        $chatParticipante->addForeignKeyConstraint(
            'ad_chat',
            ['chat_id'],
            ['id']
        );
    }

    public function chatDown(Schema $schemaTo) : void
    {
        $chat = $schemaTo->getTable('ad_chat');
        $chatMensagem = $schemaTo->getTable('ad_chat_mensagem');
        $chatParticipante = $schemaTo->getTable('ad_chat_participante');

        foreach ($chat->getForeignKeys() as $chatFK) {
            $chat->removeForeignKey($chatFK->getName());
        }

        foreach ($chatMensagem->getForeignKeys() as $chatMensagemFK) {
            $chatMensagem->removeForeignKey($chatMensagemFK->getName());
        }

        foreach ($chatParticipante->getForeignKeys() as $chatParticipanteFK) {
            $chatParticipante->removeForeignKey($chatParticipanteFK->getName());
        }

        $schemaTo->dropTable($chat->getName());
        $schemaTo->dropTable($chatMensagem->getName());
        $schemaTo->dropTable($chatParticipante->getName());
    }

    public function usuarioDown(Schema $schemaTo) : void
    {
        $usuario = $schemaTo->getTable('ad_usuario');
        foreach ($usuario->getForeignKeys() as $foreignKey) {

            if ($foreignKey->getForeignTableName() === 'ad_componente_digital') {
                if (in_array('img_perfil_id', $foreignKey->getColumns())) {
                    $usuario->removeForeignKey($foreignKey->getName());
                }
                if (in_array('img_chancela_id', $foreignKey->getColumns())) {
                    $usuario->removeForeignKey($foreignKey->getName());
                }
            }
        }
        $usuario->dropColumn('img_perfil_id');
        $usuario->dropColumn('img_chancela_id');
    }

    public function isTransactional(): bool
    {
        return false;
    }

}
