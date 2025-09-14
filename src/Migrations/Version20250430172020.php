<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250430172020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Cria a tabela ad_vinc_menu_coordenador';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE ad_vinc_menu_coordenador_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE ad_vinc_menu_coordenador (
            id INTEGER NOT NULL,
            coordenador_id INTEGER DEFAULT NULL,
            modelos BOOLEAN NOT NULL DEFAULT TRUE,
            repositorios BOOLEAN NOT NULL DEFAULT TRUE,
            etiquetas BOOLEAN NOT NULL DEFAULT TRUE,
            usuarios BOOLEAN NOT NULL DEFAULT TRUE,
            unidades BOOLEAN NOT NULL DEFAULT TRUE,
            avisos BOOLEAN NOT NULL DEFAULT TRUE,
            teses BOOLEAN NOT NULL DEFAULT TRUE,
            coordenadores BOOLEAN NOT NULL DEFAULT TRUE,
            setores BOOLEAN NOT NULL DEFAULT TRUE,
            contas_emails BOOLEAN NOT NULL DEFAULT TRUE,
            competencias BOOLEAN NOT NULL DEFAULT TRUE,
            dominios BOOLEAN NOT NULL DEFAULT TRUE,
            gerenciamento_tarefas BOOLEAN NOT NULL DEFAULT TRUE,
            criado_por INTEGER DEFAULT NULL,
            atualizado_por INTEGER DEFAULT NULL,
            apagado_por INTEGER DEFAULT NULL,
            criado_em TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            atualizado_em TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            apagado_em TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            uuid UUID NOT NULL,
            PRIMARY KEY(id)
        )');
        
        // Adição de chave estrangeira para coordenador
        $this->addSql('ALTER TABLE ad_vinc_menu_coordenador 
            ADD CONSTRAINT FK_MENU_COORDENADOR 
            FOREIGN KEY (coordenador_id) 
            REFERENCES ad_coordenador (id) 
            NOT DEFERRABLE INITIALLY IMMEDIATE');
        
        // Adição de chaves estrangeiras para os campos de auditoria
        $this->addSql('ALTER TABLE ad_vinc_menu_coordenador 
            ADD CONSTRAINT FK_MENU_COORDENADOR_CRIADO_POR 
            FOREIGN KEY (criado_por) 
            REFERENCES ad_usuario (id) 
            NOT DEFERRABLE INITIALLY IMMEDIATE');
            
        $this->addSql('ALTER TABLE ad_vinc_menu_coordenador 
            ADD CONSTRAINT FK_MENU_COORDENADOR_ATUALIZADO_POR 
            FOREIGN KEY (atualizado_por) 
            REFERENCES ad_usuario (id) 
            NOT DEFERRABLE INITIALLY IMMEDIATE');
            
        $this->addSql('ALTER TABLE ad_vinc_menu_coordenador 
            ADD CONSTRAINT FK_MENU_COORDENADOR_APAGADO_POR 
            FOREIGN KEY (apagado_por) 
            REFERENCES ad_usuario (id) 
            NOT DEFERRABLE INITIALLY IMMEDIATE');
        
        // Adição de índices
        $this->addSql('CREATE INDEX IDX_MENU_COORDENADOR_ID ON ad_vinc_menu_coordenador (id)');
        $this->addSql('CREATE INDEX IDX_MENU_COORDENADOR_COORD_ID ON ad_vinc_menu_coordenador (coordenador_id)');
        $this->addSql('CREATE INDEX IDX_MENU_COORDENADOR_CRIADO_POR ON ad_vinc_menu_coordenador (criado_por)');
        $this->addSql('CREATE INDEX IDX_MENU_COORDENADOR_ATUALIZADO_POR ON ad_vinc_menu_coordenador (atualizado_por)');
        $this->addSql('CREATE INDEX IDX_MENU_COORDENADOR_APAGADO_POR ON ad_vinc_menu_coordenador (apagado_por)');
        
        // Garante que coordenador_id seja único (relação OneToOne)
        $this->addSql('CREATE UNIQUE INDEX UNIQ_MENU_COORDENADOR_COORD_ID ON ad_vinc_menu_coordenador (coordenador_id)');
        
        // Índice para UUID
        $this->addSql('CREATE UNIQUE INDEX UNIQ_MENU_COORDENADOR_UUID ON ad_vinc_menu_coordenador (uuid)');
        
        // Índice composto para soft delete
        $this->addSql('CREATE INDEX IDX_MENU_COORDENADOR_SOFT_DELETE ON ad_vinc_menu_coordenador (apagado_em, id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE ad_vinc_menu_coordenador_id_seq CASCADE');
        $this->addSql('DROP TABLE ad_vinc_menu_coordenador');
    }
}