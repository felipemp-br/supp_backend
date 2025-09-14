<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506144913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Cria a tabela cs_mod_consultivo';
    }

    public function up(Schema $schema): void
    {
         // Criação da tabela cs_mod_consultivo
         $this->addSql('CREATE TABLE IF NOT EXISTS cs_mod_consultivo (
            id integer NOT NULL,
            criado_por integer,
            atualizado_por integer,
            apagado_por integer,
            criado_em timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
            atualizado_em timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
            apagado_em timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
            uuid uuid NOT NULL,
            nome character varying(255) NOT NULL,
            descricao character varying(255) NOT NULL,
            ativo boolean NOT NULL,
            CONSTRAINT cs_mod_consultivo_pkey PRIMARY KEY (id),
            CONSTRAINT fk_b66cb925a395bb94 FOREIGN KEY (apagado_por)
                REFERENCES ad_usuario (id) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE NO ACTION,
            CONSTRAINT fk_b66cb925af2b1a92 FOREIGN KEY (atualizado_por)
                REFERENCES ad_usuario (id) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE NO ACTION,
            CONSTRAINT fk_b66cb925f69c7d9b FOREIGN KEY (criado_por)
                REFERENCES ad_usuario (id) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE NO ACTION
        )');

        // Criação dos índices
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_b66cb925a395bb94 ON cs_mod_consultivo (apagado_por ASC NULLS LAST)');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_b66cb925af2b1a92 ON cs_mod_consultivo (atualizado_por ASC NULLS LAST)');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_b66cb925f69c7d9b ON cs_mod_consultivo (criado_por ASC NULLS LAST)');
        $this->addSql('CREATE UNIQUE INDEX IF NOT EXISTS uniq_b66cb925d17f50a6 ON cs_mod_consultivo (uuid ASC NULLS LAST)');

    }

    public function down(Schema $schema): void
    {
         // Remoção dos índices
         $this->addSql('DROP INDEX IF EXISTS uniq_b66cb925d17f50a6');
         $this->addSql('DROP INDEX IF EXISTS idx_b66cb925f69c7d9b');
         $this->addSql('DROP INDEX IF EXISTS idx_b66cb925af2b1a92');
         $this->addSql('DROP INDEX IF EXISTS idx_b66cb925a395bb94');
         
         // Remoção da tabela
         $this->addSql('DROP TABLE IF EXISTS cs_mod_consultivo');

    }
}
