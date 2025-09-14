<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210427104416 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'migration from 1.3.1 do 1.4.0';
    }

    public function up(Schema $schema) : void
    {
        // MYSQL
        $this->addSql('CREATE TABLE ad_tipo_notificacao (id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, uuid CHAR(36) NOT NULL, CONSTRAINT UNIQ_85C2F52A54BD530C UNIQUE (nome), CONSTRAINT UNIQ_85C2F52AD17F50A6 UNIQUE (uuid)) COLLATE=utf8_unicode_ci');
        $this->addSql('ALTER TABLE ad_notificacao ADD CONSTRAINT FK_71864047B0930A2C FOREIGN KEY (tipo_notificacao_id) REFERENCES ad_tipo_notificacao (id');
        $this->addSql('ALTER TABLE ad_notificacao ADD contexto VARCHAR(255) NULL');
    }

    public function down(Schema $schema) : void
    {
        // MYSQL
        $this->addSql('DROP TABLE ad_tipo_notificacao');
        $this->addSql('ALTER TABLE ad_notificacao DROP COLUMN contexto');
        $this->addSql('DROP index IDX_71864047B0930A2C ON ad_notificacao');
        $this->addSql('ALTER TABLE ad_notificacao DROP COLUMN tipo_notificacao_id;');
    }

    /**
     * @return bool
     */
    public function isTransactional(): bool
    {
        return false;
    }
}
