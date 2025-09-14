<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506145351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Cria a tabela cs_vinc_mod_consultivo';
    }

    public function up(Schema $schema): void
    {
         // Cria a tabela principal
         $this->addSql('CREATE TABLE IF NOT EXISTS public.cs_vinc_mod_consultivo (
            id integer NOT NULL,
            mod_consultivo_id integer,
            modelo_id integer,
            documento_id integer,
            unidade_id integer,
            especie_atividade_id integer,
            atividade_id integer,
            criado_por integer,
            atualizado_por integer,
            apagado_por integer,
            criado_em timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
            atualizado_em timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
            apagado_em timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
            uuid uuid NOT NULL,
            CONSTRAINT cs_vinc_mod_consultivo_pkey PRIMARY KEY (id))');
            
        // Adiciona as foreign keys
        $this->addSql('ALTER TABLE IF EXISTS public.cs_vinc_mod_consultivo
            ADD CONSTRAINT fk_5832811c45598ad7 FOREIGN KEY (especie_atividade_id)
                REFERENCES public.ad_especie_atividade (id) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE NO ACTION');
        
        $this->addSql('ALTER TABLE IF EXISTS public.cs_vinc_mod_consultivo
            ADD CONSTRAINT fk_5832811c45c0cf75 FOREIGN KEY (documento_id)
                REFERENCES public.ad_documento (id) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE NO ACTION');
        
        $this->addSql('ALTER TABLE IF EXISTS public.cs_vinc_mod_consultivo
            ADD CONSTRAINT fk_5832811ca22d979b FOREIGN KEY (atividade_id)
                REFERENCES public.ad_atividade (id) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE NO ACTION');
        
        $this->addSql('ALTER TABLE IF EXISTS public.cs_vinc_mod_consultivo
            ADD CONSTRAINT fk_5832811ca395bb94 FOREIGN KEY (apagado_por)
                REFERENCES public.ad_usuario (id) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE NO ACTION');
        
        $this->addSql('ALTER TABLE IF EXISTS public.cs_vinc_mod_consultivo
            ADD CONSTRAINT fk_5832811caf2b1a92 FOREIGN KEY (atualizado_por)
                REFERENCES public.ad_usuario (id) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE NO ACTION');
        
        $this->addSql('ALTER TABLE IF EXISTS public.cs_vinc_mod_consultivo
            ADD CONSTRAINT fk_5832811cc3a9576e FOREIGN KEY (modelo_id)
                REFERENCES public.ad_modelo (id) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE NO ACTION');
        
        $this->addSql('ALTER TABLE IF EXISTS public.cs_vinc_mod_consultivo
            ADD CONSTRAINT fk_5832811cd69a0167 FOREIGN KEY (mod_consultivo_id)
                REFERENCES public.cs_mod_consultivo (id) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE NO ACTION');
        
        $this->addSql('ALTER TABLE IF EXISTS public.cs_vinc_mod_consultivo
            ADD CONSTRAINT fk_5832811cedf4b99b FOREIGN KEY (unidade_id)
                REFERENCES public.ad_setor (id) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE NO ACTION');
        
        $this->addSql('ALTER TABLE IF EXISTS public.cs_vinc_mod_consultivo
            ADD CONSTRAINT fk_5832811cf69c7d9b FOREIGN KEY (criado_por)
                REFERENCES public.ad_usuario (id) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE NO ACTION');
            
        // Cria os índices
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_5832811c45598ad7
            ON public.cs_vinc_mod_consultivo USING btree
            (especie_atividade_id ASC NULLS LAST)');
            
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_5832811c45c0cf75
            ON public.cs_vinc_mod_consultivo USING btree
            (documento_id ASC NULLS LAST)');
            
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_5832811ca22d979b
            ON public.cs_vinc_mod_consultivo USING btree
            (atividade_id ASC NULLS LAST)');
            
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_5832811ca395bb94
            ON public.cs_vinc_mod_consultivo USING btree
            (apagado_por ASC NULLS LAST)');
            
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_5832811caf2b1a92
            ON public.cs_vinc_mod_consultivo USING btree
            (atualizado_por ASC NULLS LAST)');
            
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_5832811cc3a9576e
            ON public.cs_vinc_mod_consultivo USING btree
            (modelo_id ASC NULLS LAST)');
            
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_5832811cd69a0167
            ON public.cs_vinc_mod_consultivo USING btree
            (mod_consultivo_id ASC NULLS LAST)');
            
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_5832811cedf4b99b
            ON public.cs_vinc_mod_consultivo USING btree
            (unidade_id ASC NULLS LAST)');
            
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_5832811cf69c7d9b
            ON public.cs_vinc_mod_consultivo USING btree
            (criado_por ASC NULLS LAST)');
            
        $this->addSql('CREATE UNIQUE INDEX IF NOT EXISTS uniq_5832811cd17f50a6
            ON public.cs_vinc_mod_consultivo USING btree
            (uuid ASC NULLS LAST)');

    }

    public function down(Schema $schema): void
    {
         // Drop indexes primeiro
         $this->addSql('DROP INDEX IF EXISTS public.idx_5832811c45598ad7');
         $this->addSql('DROP INDEX IF EXISTS public.idx_5832811c45c0cf75');
         $this->addSql('DROP INDEX IF EXISTS public.idx_5832811ca22d979b');
         $this->addSql('DROP INDEX IF EXISTS public.idx_5832811ca395bb94');
         $this->addSql('DROP INDEX IF EXISTS public.idx_5832811caf2b1a92');
         $this->addSql('DROP INDEX IF EXISTS public.idx_5832811cc3a9576e');
         $this->addSql('DROP INDEX IF EXISTS public.idx_5832811cd69a0167');
         $this->addSql('DROP INDEX IF EXISTS public.idx_5832811cedf4b99b');
         $this->addSql('DROP INDEX IF EXISTS public.idx_5832811cf69c7d9b');
         $this->addSql('DROP INDEX IF EXISTS public.uniq_5832811cd17f50a6');
         
         // Finalmente, drop da tabela
         $this->addSql('DROP TABLE IF EXISTS public.cs_vinc_mod_consultivo');

    }
}
