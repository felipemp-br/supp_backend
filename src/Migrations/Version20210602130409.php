<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210602130409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.4.0 do 1.4.1';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('CREATE SEQUENCE ad_chat_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_chat_mensagem_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_chat_participante_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE TABLE ad_chat (id NUMBER(10) NOT NULL, capa_id NUMBER(10) DEFAULT NULL NULL, ultima_mensagem_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, nome VARCHAR2(255) DEFAULT NULL NULL, descricao VARCHAR2(255) DEFAULT NULL NULL, grupo NUMBER(1) NOT NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_DD52A0CFD17F50A6 ON ad_chat (uuid)');
            $this->addSql('CREATE INDEX IDX_DD52A0CF7D23972 ON ad_chat (capa_id)');
            $this->addSql('CREATE INDEX IDX_DD52A0CF169CFA47 ON ad_chat (ultima_mensagem_id)');
            $this->addSql('CREATE INDEX IDX_DD52A0CFF69C7D9B ON ad_chat (criado_por)');
            $this->addSql('CREATE INDEX IDX_DD52A0CFAF2B1A92 ON ad_chat (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_DD52A0CFA395BB94 ON ad_chat (apagado_por)');
            $this->addSql('CREATE TABLE ad_chat_mensagem (id NUMBER(10) NOT NULL, usuario_id NUMBER(10) NOT NULL, chat_id NUMBER(10) NOT NULL, mensagem_id NUMBER(10) DEFAULT NULL NULL, componente_digital_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, mensagem VARCHAR2(255) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_675CABAAD17F50A6 ON ad_chat_mensagem (uuid)');
            $this->addSql('CREATE INDEX IDX_675CABAADB38439E ON ad_chat_mensagem (usuario_id)');
            $this->addSql('CREATE INDEX IDX_675CABAA1A9A7125 ON ad_chat_mensagem (chat_id)');
            $this->addSql('CREATE INDEX IDX_675CABAA48D9DAD0 ON ad_chat_mensagem (mensagem_id)');
            $this->addSql('CREATE INDEX IDX_675CABAA141B5D3A ON ad_chat_mensagem (componente_digital_id)');
            $this->addSql('CREATE INDEX IDX_675CABAAF69C7D9B ON ad_chat_mensagem (criado_por)');
            $this->addSql('CREATE INDEX IDX_675CABAAAF2B1A92 ON ad_chat_mensagem (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_675CABAAA395BB94 ON ad_chat_mensagem (apagado_por)');
            $this->addSql('CREATE TABLE ad_chat_participante (id NUMBER(10) NOT NULL, usuario_id NUMBER(10) NOT NULL, chat_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, administrador NUMBER(1) NOT NULL, ultima_visualizacao TIMESTAMP(0) DEFAULT NULL NULL, mensagens_nao_lidas NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_17BA12E7D17F50A6 ON ad_chat_participante (uuid)');
            $this->addSql('CREATE INDEX IDX_17BA12E7DB38439E ON ad_chat_participante (usuario_id)');
            $this->addSql('CREATE INDEX IDX_17BA12E71A9A7125 ON ad_chat_participante (chat_id)');
            $this->addSql('CREATE INDEX IDX_17BA12E7F69C7D9B ON ad_chat_participante (criado_por)');
            $this->addSql('CREATE INDEX IDX_17BA12E7AF2B1A92 ON ad_chat_participante (atualizado_por)');
            $this->addSql('ALTER TABLE ad_chat ADD CONSTRAINT FK_DD52A0CF7D23972 FOREIGN KEY (capa_id) REFERENCES ad_componente_digital (id)');
            $this->addSql('ALTER TABLE ad_chat ADD CONSTRAINT FK_DD52A0CF169CFA47 FOREIGN KEY (ultima_mensagem_id) REFERENCES ad_chat_mensagem (id)');
            $this->addSql('ALTER TABLE ad_chat ADD CONSTRAINT FK_DD52A0CFF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat ADD CONSTRAINT FK_DD52A0CFAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat ADD CONSTRAINT FK_DD52A0CFA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat_mensagem ADD CONSTRAINT FK_675CABAADB38439E FOREIGN KEY (usuario_id) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat_mensagem ADD CONSTRAINT FK_675CABAA1A9A7125 FOREIGN KEY (chat_id) REFERENCES ad_chat (id)');
            $this->addSql('ALTER TABLE ad_chat_mensagem ADD CONSTRAINT FK_675CABAA48D9DAD0 FOREIGN KEY (mensagem_id) REFERENCES ad_chat_mensagem (id)');
            $this->addSql('ALTER TABLE ad_chat_mensagem ADD CONSTRAINT FK_675CABAA141B5D3A FOREIGN KEY (componente_digital_id) REFERENCES ad_componente_digital (id)');
            $this->addSql('ALTER TABLE ad_chat_mensagem ADD CONSTRAINT FK_675CABAAF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat_mensagem ADD CONSTRAINT FK_675CABAAAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat_mensagem ADD CONSTRAINT FK_675CABAAA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat_participante ADD CONSTRAINT FK_17BA12E7DB38439E FOREIGN KEY (usuario_id) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat_participante ADD CONSTRAINT FK_17BA12E71A9A7125 FOREIGN KEY (chat_id) REFERENCES ad_chat (id)');
            $this->addSql('ALTER TABLE ad_chat_participante ADD CONSTRAINT FK_17BA12E7F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat_participante ADD CONSTRAINT FK_17BA12E7AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE AD_NOTIFICACAO ADD (apagado_por NUMBER(10) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE AD_NOTIFICACAO ADD CONSTRAINT FK_71864047A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('CREATE INDEX IDX_71864047A395BB94 ON AD_NOTIFICACAO (apagado_por)');
            $this->addSql('ALTER TABLE AD_PESSOA ADD (data_hora_indexacao TIMESTAMP(0) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE AD_PROCESSO ADD (data_hora_indexacao TIMESTAMP(0) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE AD_USUARIO ADD (img_perfil_id NUMBER(10) DEFAULT NULL NULL, img_chancela_id NUMBER(10) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE AD_USUARIO ADD CONSTRAINT FK_EF2F59D9B03FA446 FOREIGN KEY (img_perfil_id) REFERENCES ad_componente_digital (id)');
            $this->addSql('ALTER TABLE AD_USUARIO ADD CONSTRAINT FK_EF2F59D9C497FA28 FOREIGN KEY (img_chancela_id) REFERENCES ad_componente_digital (id)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_EF2F59D9B03FA446 ON AD_USUARIO (img_perfil_id)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_EF2F59D9C497FA28 ON AD_USUARIO (img_chancela_id)');
        } else {
            $this->addSql('CREATE TABLE ad_chat (id INT AUTO_INCREMENT NOT NULL, capa_id INT DEFAULT NULL, ultima_mensagem_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, nome VARCHAR(255) DEFAULT NULL, descricao VARCHAR(255) DEFAULT NULL, grupo TINYINT(1) NOT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_DD52A0CFD17F50A6 (uuid), INDEX IDX_DD52A0CF7D23972 (capa_id), INDEX IDX_DD52A0CF169CFA47 (ultima_mensagem_id), INDEX IDX_DD52A0CFF69C7D9B (criado_por), INDEX IDX_DD52A0CFAF2B1A92 (atualizado_por), INDEX IDX_DD52A0CFA395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_chat_mensagem (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, chat_id INT NOT NULL, mensagem_id INT DEFAULT NULL, componente_digital_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, mensagem VARCHAR(255) DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_675CABAAD17F50A6 (uuid), INDEX IDX_675CABAADB38439E (usuario_id), INDEX IDX_675CABAA1A9A7125 (chat_id), INDEX IDX_675CABAA48D9DAD0 (mensagem_id), INDEX IDX_675CABAA141B5D3A (componente_digital_id), INDEX IDX_675CABAAF69C7D9B (criado_por), INDEX IDX_675CABAAAF2B1A92 (atualizado_por), INDEX IDX_675CABAAA395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_chat_participante (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, chat_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, administrador TINYINT(1) NOT NULL, ultima_visualizacao DATETIME DEFAULT NULL, mensagens_nao_lidas INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_17BA12E7D17F50A6 (uuid), INDEX IDX_17BA12E7DB38439E (usuario_id), INDEX IDX_17BA12E71A9A7125 (chat_id), INDEX IDX_17BA12E7F69C7D9B (criado_por), INDEX IDX_17BA12E7AF2B1A92 (atualizado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE ad_chat ADD CONSTRAINT FK_DD52A0CF7D23972 FOREIGN KEY (capa_id) REFERENCES ad_componente_digital (id)');
            $this->addSql('ALTER TABLE ad_chat ADD CONSTRAINT FK_DD52A0CF169CFA47 FOREIGN KEY (ultima_mensagem_id) REFERENCES ad_chat_mensagem (id)');
            $this->addSql('ALTER TABLE ad_chat ADD CONSTRAINT FK_DD52A0CFF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat ADD CONSTRAINT FK_DD52A0CFAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat ADD CONSTRAINT FK_DD52A0CFA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat_mensagem ADD CONSTRAINT FK_675CABAADB38439E FOREIGN KEY (usuario_id) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat_mensagem ADD CONSTRAINT FK_675CABAA1A9A7125 FOREIGN KEY (chat_id) REFERENCES ad_chat (id)');
            $this->addSql('ALTER TABLE ad_chat_mensagem ADD CONSTRAINT FK_675CABAA48D9DAD0 FOREIGN KEY (mensagem_id) REFERENCES ad_chat_mensagem (id)');
            $this->addSql('ALTER TABLE ad_chat_mensagem ADD CONSTRAINT FK_675CABAA141B5D3A FOREIGN KEY (componente_digital_id) REFERENCES ad_componente_digital (id)');
            $this->addSql('ALTER TABLE ad_chat_mensagem ADD CONSTRAINT FK_675CABAAF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat_mensagem ADD CONSTRAINT FK_675CABAAAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat_mensagem ADD CONSTRAINT FK_675CABAAA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat_participante ADD CONSTRAINT FK_17BA12E7DB38439E FOREIGN KEY (usuario_id) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat_participante ADD CONSTRAINT FK_17BA12E71A9A7125 FOREIGN KEY (chat_id) REFERENCES ad_chat (id)');
            $this->addSql('ALTER TABLE ad_chat_participante ADD CONSTRAINT FK_17BA12E7F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_chat_participante ADD CONSTRAINT FK_17BA12E7AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_notificacao ADD apagado_por INT DEFAULT NULL, ADD apagado_em DATETIME DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_notificacao ADD CONSTRAINT FK_71864047A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('CREATE INDEX IDX_71864047A395BB94 ON ad_notificacao (apagado_por)');
            $this->addSql('ALTER TABLE ad_pessoa ADD data_hora_indexacao DATETIME DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_processo ADD data_hora_indexacao DATETIME DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_usuario ADD img_perfil_id INT DEFAULT NULL, ADD img_chancela_id INT DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_usuario ADD CONSTRAINT FK_EF2F59D9B03FA446 FOREIGN KEY (img_perfil_id) REFERENCES ad_componente_digital (id)');
            $this->addSql('ALTER TABLE ad_usuario ADD CONSTRAINT FK_EF2F59D9C497FA28 FOREIGN KEY (img_chancela_id) REFERENCES ad_componente_digital (id)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_EF2F59D9B03FA446 ON ad_usuario (img_perfil_id)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_EF2F59D9C497FA28 ON ad_usuario (img_chancela_id)');
        }
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_chat_mensagem DROP CONSTRAINT FK_675CABAA1A9A7125');
            $this->addSql('ALTER TABLE ad_chat_participante DROP CONSTRAINT FK_17BA12E71A9A7125');
            $this->addSql('ALTER TABLE ad_chat DROP CONSTRAINT FK_DD52A0CF169CFA47');
            $this->addSql('ALTER TABLE ad_chat_mensagem DROP CONSTRAINT FK_675CABAA48D9DAD0');
            $this->addSql('DROP SEQUENCE ad_chat_id_seq');
            $this->addSql('DROP SEQUENCE ad_chat_mensagem_id_seq');
            $this->addSql('DROP SEQUENCE ad_chat_participante_id_seq');
            $this->addSql('DROP TABLE ad_chat');
            $this->addSql('DROP TABLE ad_chat_mensagem');
            $this->addSql('DROP TABLE ad_chat_participante');
            $this->addSql('ALTER TABLE ad_notificacao DROP CONSTRAINT FK_71864047A395BB94');
            $this->addSql('DROP INDEX IDX_71864047A395BB94');
            $this->addSql('ALTER TABLE ad_notificacao DROP (apagado_por, apagado_em)');
            $this->addSql('ALTER TABLE ad_pessoa DROP (data_hora_indexacao)');
            $this->addSql('ALTER TABLE ad_usuario DROP CONSTRAINT FK_EF2F59D9B03FA446');
            $this->addSql('ALTER TABLE ad_usuario DROP CONSTRAINT FK_EF2F59D9C497FA28');
            $this->addSql('DROP INDEX UNIQ_EF2F59D9B03FA446');
            $this->addSql('DROP INDEX UNIQ_EF2F59D9C497FA28');
            $this->addSql('ALTER TABLE ad_usuario DROP (img_perfil_id, img_chancela_id)');
            $this->addSql('ALTER TABLE ad_processo DROP (data_hora_indexacao)');
        } else {
            $this->addSql('ALTER TABLE ad_chat_mensagem DROP FOREIGN KEY FK_675CABAA1A9A7125');
            $this->addSql('ALTER TABLE ad_chat_participante DROP FOREIGN KEY FK_17BA12E71A9A7125');
            $this->addSql('ALTER TABLE ad_chat DROP FOREIGN KEY FK_DD52A0CF169CFA47');
            $this->addSql('ALTER TABLE ad_chat_mensagem DROP FOREIGN KEY FK_675CABAA48D9DAD0');
            $this->addSql('DROP TABLE ad_chat');
            $this->addSql('DROP TABLE ad_chat_mensagem');
            $this->addSql('DROP TABLE ad_chat_participante');
            $this->addSql('ALTER TABLE ad_notificacao DROP FOREIGN KEY FK_71864047A395BB94');
            $this->addSql('DROP INDEX IDX_71864047A395BB94 ON ad_notificacao');
            $this->addSql('ALTER TABLE ad_notificacao DROP apagado_por, DROP apagado_em');
            $this->addSql('ALTER TABLE ad_pessoa DROP data_hora_indexacao');
            $this->addSql('ALTER TABLE ad_processo DROP data_hora_indexacao');
            $this->addSql('ALTER TABLE ad_usuario DROP FOREIGN KEY FK_EF2F59D9B03FA446');
            $this->addSql('ALTER TABLE ad_usuario DROP FOREIGN KEY FK_EF2F59D9C497FA28');
            $this->addSql('DROP INDEX UNIQ_EF2F59D9B03FA446 ON ad_usuario');
            $this->addSql('DROP INDEX UNIQ_EF2F59D9C497FA28 ON ad_usuario');
            $this->addSql('ALTER TABLE ad_usuario DROP img_perfil_id, DROP img_chancela_id');
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
