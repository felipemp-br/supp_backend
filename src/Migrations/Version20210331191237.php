<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210331191237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.2.0 do 1.2.1';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('CREATE UNIQUE INDEX UNIQ_946119BE2070D7618FD80EEA ON ad_juntada (numeracao_sequencial, volume_id)');
        } else {
            $this->addSql('CREATE UNIQUE INDEX UNIQ_946119BE2070D7618FD80EEA ON ad_juntada (numeracao_sequencial, volume_id)');
        }
        // this up() migration is auto-generated, please modify it to your needs
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('DROP INDEX UNIQ_946119BE2070D7618FD80EEA');
        } else {
            $this->addSql('DROP INDEX UNIQ_946119BE2070D7618FD80EEA ON ad_juntada');
        }
        // this down() migration is auto-generated, please modify it to your needs
    }

    /**
     * @return bool
     */
    public function isTransactional(): bool
    {
        return false;
    }
}
