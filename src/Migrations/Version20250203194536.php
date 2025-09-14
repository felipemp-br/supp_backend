<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203194536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criar a coluna protocolo na tabela ASSINATURAS/ad_assinatura';
    }

    public function up(Schema $schema): void
    {
        // Ajuste para PostgreSQL: Usar o esquema correto e verificar a existência da tabela
        $platformName = mb_strtolower($this->connection->getDatabasePlatform()->getName());
        $tableName = 'ad_assinatura'; // Nome correto da tabela no PostgreSQL

        // Adicionar a coluna diretamente com SQL, já que o método Schema não suporta introspecção completa
        $this->addSql("
            ALTER TABLE public.$tableName
            ADD COLUMN protocolo VARCHAR(20) DEFAULT NULL
        ");
    }

    public function down(Schema $schema): void
    {
        // Remover a coluna diretamente com SQL
        $tableName = 'ad_assinatura'; // Nome correto da tabela no PostgreSQL

        $this->addSql("
            ALTER TABLE public.$tableName
            DROP COLUMN protocolo
        ");
    }
}