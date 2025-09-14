<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220225185648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.8.1 to 1.8.2';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_bookmark ADD cor_hexadecimal VARCHAR(255) DEFAULT NULL NULL');
        } else {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_bookmark ADD cor_hexadecimal VARCHAR(255) DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {

    }

    /**
     * @return bool
     */
    public function isTransactional(): bool
    {
        return false;
    }
}
