<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210727132038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.5.3 do 1.6.0';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('ALTER TABLE ad_campo MODIFY (NOME VARCHAR2(1024) DEFAULT NULL, DESCRICAO VARCHAR2(1024) DEFAULT NULL, HTML VARCHAR2(1024) DEFAULT NULL)');
            $this->addSql('ALTER TABLE ad_componente_digital MODIFY (DOCUMENTO_ID NUMBER(10) DEFAULT NULL NULL)');
        } else {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_campo CHANGE nome nome VARCHAR(1024) NOT NULL, CHANGE descricao descricao VARCHAR(1024) NOT NULL, CHANGE html html VARCHAR(1024) NOT NULL');
            $this->addSql('ALTER TABLE ad_componente_digital CHANGE documento_id documento_id INT DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('ALTER TABLE ad_campo MODIFY (NOME VARCHAR2(255) DEFAULT NULL, DESCRICAO VARCHAR2(255) DEFAULT NULL, HTML VARCHAR2(255) DEFAULT NULL)');
            $this->addSql('ALTER TABLE ad_componente_digital MODIFY (DOCUMENTO_ID NUMBER(10) NOT NULL)');
        } else {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_campo CHANGE nome nome VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE descricao descricao VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE html html VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
            $this->addSql('ALTER TABLE ad_componente_digital CHANGE documento_id documento_id INT NOT NULL');
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
