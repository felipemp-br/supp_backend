<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210423203432 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'migration from 1.3.0 do 1.3.1';
    }

    public function up(Schema $schema) : void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('DROP INDEX UNIQ_25E47CF2C3A9576E8515ADFB8 ON ad_vinc_modelo');
            $this->addSql(
                'DROP INDEX UNIQ_CF785297BED76CEB8515ADFB8 ON ad_vinc_repositorio'
            );
        } else {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('DROP INDEX UNIQ_25E47CF2C3A9576E8515ADFB87F70880FF6A170EDB38439E4D94F126 ON ad_vinc_modelo');
            $this->addSql(
                'DROP INDEX UNIQ_CF785297BED76CEB8515ADFB87F70880FF6A170EDB38439E4D94F126 ON ad_vinc_repositorio'
            );
        }
    }

    public function down(Schema $schema) : void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql(
                'CREATE UNIQUE INDEX UNIQ_25E47CF2C3A9576E8515ADFB8 ON ad_vinc_modelo (modelo_id, orgao_central_id, especie_setor_id, apagado_em, usuario_id, setor_id)'
            );
            $this->addSql(
                'CREATE UNIQUE INDEX UNIQ_CF785297BED76CEB8515ADFB8 ON ad_vinc_repositorio (repositorio_id, orgao_central_id, especie_setor_id, apagado_em, usuario_id, setor_id)'
            );
        } else {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql(
                'CREATE UNIQUE INDEX UNIQ_25E47CF2C3A9576E8515ADFB87F70880FF6A170EDB38439E4D94F126 ON ad_vinc_modelo (modelo_id, orgao_central_id, especie_setor_id, apagado_em, usuario_id, setor_id)'
            );
            $this->addSql(
                'CREATE UNIQUE INDEX UNIQ_CF785297BED76CEB8515ADFB87F70880FF6A170EDB38439E4D94F126 ON ad_vinc_repositorio (repositorio_id, orgao_central_id, especie_setor_id, apagado_em, usuario_id, setor_id)'
            );
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
