<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240223223521GestaoDevedor extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.14.0 to 1.15.0';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('CREATE SEQUENCE gd_bem_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_cfg_pes_dev_org_cent_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_diligencia_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_garantia_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_indice_devedor_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_medida_restritiva_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_mod_bem_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_mod_diligencia_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_mod_garantia_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_mod_med_restr_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_mod_qual_representante_id_s START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_mod_qual_socio_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_mod_situacao_esp_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_part_societaria_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_pessoa_devedor_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_situacao_cadastral_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_situacao_especial_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_tipo_indice_devedor_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_tipo_situacao_cad_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE gd_vinc_doc_gestao_devedor_id_ START WITH 1 MINVALUE 1 INCREMENT BY 1');

            $this->addSql('CREATE TABLE gd_bem (id NUMBER(10) NOT NULL, pessoa_id NUMBER(10) NOT NULL, mod_bem_id NUMBER(10) NOT NULL, origem_dados_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, percentual_quota_parte DOUBLE PRECISION NOT NULL, fonte_informacao VARCHAR2(255) DEFAULT NULL NULL, indexador VARCHAR2(255) DEFAULT NULL NULL, valor DOUBLE PRECISION DEFAULT NULL NULL, data_valor TIMESTAMP(0) DEFAULT NULL NULL, status_localizacao VARCHAR2(1) NOT NULL, antieconomico NUMBER(1) DEFAULT 0 NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, nome VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_3B35CD71D17F50A6 ON gd_bem (uuid)');
            $this->addSql('CREATE INDEX IDX_3B35CD71DF6FA0A5 ON gd_bem (pessoa_id)');
            $this->addSql('CREATE INDEX IDX_3B35CD7181D7FB4C ON gd_bem (mod_bem_id)');
            $this->addSql('CREATE INDEX IDX_3B35CD71A654CBCD ON gd_bem (origem_dados_id)');
            $this->addSql('CREATE INDEX IDX_3B35CD71F69C7D9B ON gd_bem (criado_por)');
            $this->addSql('CREATE INDEX IDX_3B35CD71AF2B1A92 ON gd_bem (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_3B35CD71A395BB94 ON gd_bem (apagado_por)');
            $this->addSql('COMMENT ON COLUMN gd_bem.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_cfg_pes_dev_org_cent (id NUMBER(10) NOT NULL, pessoa_devedor_id NUMBER(10) NOT NULL, processo_id NUMBER(10) DEFAULT NULL NULL, mod_orgao_central_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, grande_devedor NUMBER(1) DEFAULT NULL NULL, acompanhamento_prioritario NUMBER(1) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_4E8CE6FCD17F50A6 ON gd_cfg_pes_dev_org_cent (uuid)');
            $this->addSql('CREATE INDEX IDX_4E8CE6FCB3260DAB ON gd_cfg_pes_dev_org_cent (pessoa_devedor_id)');
            $this->addSql('CREATE INDEX IDX_4E8CE6FCAAA822D2 ON gd_cfg_pes_dev_org_cent (processo_id)');
            $this->addSql('CREATE INDEX IDX_4E8CE6FCB7CB576B ON gd_cfg_pes_dev_org_cent (mod_orgao_central_id)');
            $this->addSql('CREATE INDEX IDX_4E8CE6FCF69C7D9B ON gd_cfg_pes_dev_org_cent (criado_por)');
            $this->addSql('CREATE INDEX IDX_4E8CE6FCAF2B1A92 ON gd_cfg_pes_dev_org_cent (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_4E8CE6FCA395BB94 ON gd_cfg_pes_dev_org_cent (apagado_por)');
            $this->addSql('COMMENT ON COLUMN gd_cfg_pes_dev_org_cent.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_diligencia (id NUMBER(10) NOT NULL, mod_diligencia_id NUMBER(10) NOT NULL, processo_id NUMBER(10) DEFAULT NULL NULL, pessoa_id NUMBER(10) NOT NULL, documento_id NUMBER(10) DEFAULT NULL NULL, origem_dados_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, data TIMESTAMP(0) NOT NULL, observacao VARCHAR2(255) DEFAULT NULL NULL, status_diligencia VARCHAR2(1) NOT NULL, fonte_informacao VARCHAR2(255) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_56817890D17F50A6 ON gd_diligencia (uuid)');
            $this->addSql('CREATE INDEX IDX_56817890D117EC65 ON gd_diligencia (mod_diligencia_id)');
            $this->addSql('CREATE INDEX IDX_56817890AAA822D2 ON gd_diligencia (processo_id)');
            $this->addSql('CREATE INDEX IDX_56817890DF6FA0A5 ON gd_diligencia (pessoa_id)');
            $this->addSql('CREATE INDEX IDX_5681789045C0CF75 ON gd_diligencia (documento_id)');
            $this->addSql('CREATE INDEX IDX_56817890A654CBCD ON gd_diligencia (origem_dados_id)');
            $this->addSql('CREATE INDEX IDX_56817890F69C7D9B ON gd_diligencia (criado_por)');
            $this->addSql('CREATE INDEX IDX_56817890AF2B1A92 ON gd_diligencia (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_56817890A395BB94 ON gd_diligencia (apagado_por)');
            $this->addSql('COMMENT ON COLUMN gd_diligencia.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_garantia (id NUMBER(10) NOT NULL, processo_id NUMBER(10) NOT NULL, mod_garantia_id NUMBER(10) NOT NULL, pessoa_id NUMBER(10) DEFAULT NULL NULL, bem_id NUMBER(10) DEFAULT NULL NULL, origem_dados_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, descricao VARCHAR2(255) NOT NULL, valor DOUBLE PRECISION NOT NULL, data_valor TIMESTAMP(0) NOT NULL, observacao VARCHAR2(255) DEFAULT NULL NULL, uso_especifico_processo NUMBER(1) DEFAULT NULL NULL, data_inicio_vigencia TIMESTAMP(0) NOT NULL, data_fim_vigencia TIMESTAMP(0) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_7453C5A2D17F50A6 ON gd_garantia (uuid)');
            $this->addSql('CREATE INDEX IDX_7453C5A2AAA822D2 ON gd_garantia (processo_id)');
            $this->addSql('CREATE INDEX IDX_7453C5A273533CF6 ON gd_garantia (mod_garantia_id)');
            $this->addSql('CREATE INDEX IDX_7453C5A2DF6FA0A5 ON gd_garantia (pessoa_id)');
            $this->addSql('CREATE INDEX IDX_7453C5A2F560C433 ON gd_garantia (bem_id)');
            $this->addSql('CREATE INDEX IDX_7453C5A2A654CBCD ON gd_garantia (origem_dados_id)');
            $this->addSql('CREATE INDEX IDX_7453C5A2F69C7D9B ON gd_garantia (criado_por)');
            $this->addSql('CREATE INDEX IDX_7453C5A2AF2B1A92 ON gd_garantia (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_7453C5A2A395BB94 ON gd_garantia (apagado_por)');
            $this->addSql('COMMENT ON COLUMN gd_garantia.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_indice_devedor (id NUMBER(10) NOT NULL, tipo_ind_devedor_id NUMBER(10) NOT NULL, pessoa_devedor_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, valor VARCHAR2(255) NOT NULL, data TIMESTAMP(0) NOT NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_95921F2DD17F50A6 ON gd_indice_devedor (uuid)');
            $this->addSql('CREATE INDEX IDX_95921F2DE69C2C24 ON gd_indice_devedor (tipo_ind_devedor_id)');
            $this->addSql('CREATE INDEX IDX_95921F2DB3260DAB ON gd_indice_devedor (pessoa_devedor_id)');
            $this->addSql('CREATE INDEX IDX_95921F2DF69C7D9B ON gd_indice_devedor (criado_por)');
            $this->addSql('CREATE INDEX IDX_95921F2DAF2B1A92 ON gd_indice_devedor (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_95921F2DA395BB94 ON gd_indice_devedor (apagado_por)');
            $this->addSql('COMMENT ON COLUMN gd_indice_devedor.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_medida_restritiva (id NUMBER(10) NOT NULL, bem_id NUMBER(10) NOT NULL, mod_medida_restritiva_id NUMBER(10) NOT NULL, origem_dados_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, observacao VARCHAR2(255) DEFAULT NULL NULL, data_inicio TIMESTAMP(0) NOT NULL, data_fim TIMESTAMP(0) DEFAULT NULL NULL, valor DOUBLE PRECISION DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_93A29DCDD17F50A6 ON gd_medida_restritiva (uuid)');
            $this->addSql('CREATE INDEX IDX_93A29DCDF560C433 ON gd_medida_restritiva (bem_id)');
            $this->addSql('CREATE INDEX IDX_93A29DCD30FD9868 ON gd_medida_restritiva (mod_medida_restritiva_id)');
            $this->addSql('CREATE INDEX IDX_93A29DCDA654CBCD ON gd_medida_restritiva (origem_dados_id)');
            $this->addSql('CREATE INDEX IDX_93A29DCDF69C7D9B ON gd_medida_restritiva (criado_por)');
            $this->addSql('CREATE INDEX IDX_93A29DCDAF2B1A92 ON gd_medida_restritiva (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_93A29DCDA395BB94 ON gd_medida_restritiva (apagado_por)');
            $this->addSql('COMMENT ON COLUMN gd_medida_restritiva.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_mod_bem (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, valor VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_D1FD8527D17F50A6 ON gd_mod_bem (uuid)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_D1FD85272E892728 ON gd_mod_bem (valor)');
            $this->addSql('CREATE INDEX IDX_D1FD8527F69C7D9B ON gd_mod_bem (criado_por)');
            $this->addSql('CREATE INDEX IDX_D1FD8527AF2B1A92 ON gd_mod_bem (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_D1FD8527A395BB94 ON gd_mod_bem (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_D1FD85272E892728FF6A170E ON gd_mod_bem (valor, apagado_em)');
            $this->addSql('COMMENT ON COLUMN gd_mod_bem.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_mod_diligencia (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, valor VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_95282E5AD17F50A6 ON gd_mod_diligencia (uuid)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_95282E5A2E892728 ON gd_mod_diligencia (valor)');
            $this->addSql('CREATE INDEX IDX_95282E5AF69C7D9B ON gd_mod_diligencia (criado_por)');
            $this->addSql('CREATE INDEX IDX_95282E5AAF2B1A92 ON gd_mod_diligencia (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_95282E5AA395BB94 ON gd_mod_diligencia (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_95282E5A2E892728FF6A170E ON gd_mod_diligencia (valor, apagado_em)');
            $this->addSql('COMMENT ON COLUMN gd_mod_diligencia.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_mod_garantia (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, valor VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_A68D5385D17F50A6 ON gd_mod_garantia (uuid)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_A68D53852E892728 ON gd_mod_garantia (valor)');
            $this->addSql('CREATE INDEX IDX_A68D5385F69C7D9B ON gd_mod_garantia (criado_por)');
            $this->addSql('CREATE INDEX IDX_A68D5385AF2B1A92 ON gd_mod_garantia (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_A68D5385A395BB94 ON gd_mod_garantia (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_A68D53852E892728FF6A170E ON gd_mod_garantia (valor, apagado_em)');
            $this->addSql('COMMENT ON COLUMN gd_mod_garantia.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_mod_med_restr (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, valor VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_B1D6C4E7D17F50A6 ON gd_mod_med_restr (uuid)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_B1D6C4E72E892728 ON gd_mod_med_restr (valor)');
            $this->addSql('CREATE INDEX IDX_B1D6C4E7F69C7D9B ON gd_mod_med_restr (criado_por)');
            $this->addSql('CREATE INDEX IDX_B1D6C4E7AF2B1A92 ON gd_mod_med_restr (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_B1D6C4E7A395BB94 ON gd_mod_med_restr (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_B1D6C4E72E892728FF6A170E ON gd_mod_med_restr (valor, apagado_em)');
            $this->addSql('COMMENT ON COLUMN gd_mod_med_restr.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_mod_qual_representante (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, valor VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_59F83836D17F50A6 ON gd_mod_qual_representante (uuid)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_59F838362E892728 ON gd_mod_qual_representante (valor)');
            $this->addSql('CREATE INDEX IDX_59F83836F69C7D9B ON gd_mod_qual_representante (criado_por)');
            $this->addSql('CREATE INDEX IDX_59F83836AF2B1A92 ON gd_mod_qual_representante (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_59F83836A395BB94 ON gd_mod_qual_representante (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_59F838362E892728FF6A170E ON gd_mod_qual_representante (valor, apagado_em)');
            $this->addSql('COMMENT ON COLUMN gd_mod_qual_representante.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_mod_qual_socio (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, valor VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_8FEB4265D17F50A6 ON gd_mod_qual_socio (uuid)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_8FEB42652E892728 ON gd_mod_qual_socio (valor)');
            $this->addSql('CREATE INDEX IDX_8FEB4265F69C7D9B ON gd_mod_qual_socio (criado_por)');
            $this->addSql('CREATE INDEX IDX_8FEB4265AF2B1A92 ON gd_mod_qual_socio (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_8FEB4265A395BB94 ON gd_mod_qual_socio (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_8FEB42652E892728FF6A170E ON gd_mod_qual_socio (valor, apagado_em)');
            $this->addSql('COMMENT ON COLUMN gd_mod_qual_socio.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_mod_situacao_esp (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, valor VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_CC117C0DD17F50A6 ON gd_mod_situacao_esp (uuid)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_CC117C0D2E892728 ON gd_mod_situacao_esp (valor)');
            $this->addSql('CREATE INDEX IDX_CC117C0DF69C7D9B ON gd_mod_situacao_esp (criado_por)');
            $this->addSql('CREATE INDEX IDX_CC117C0DAF2B1A92 ON gd_mod_situacao_esp (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_CC117C0DA395BB94 ON gd_mod_situacao_esp (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_CC117C0D2E892728FF6A170E ON gd_mod_situacao_esp (valor, apagado_em)');
            $this->addSql('COMMENT ON COLUMN gd_mod_situacao_esp.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_part_societaria (id NUMBER(10) NOT NULL, pessoa_socio_id NUMBER(10) NOT NULL, pessoa_representante_id NUMBER(10) DEFAULT NULL NULL, pessoa_sociedade_id NUMBER(10) NOT NULL, mod_qualificacao_socio_id NUMBER(10) NOT NULL, origem_dados_id NUMBER(10) DEFAULT NULL NULL, mod_qualificacao_representante_id NUMBER(10) DEFAULT NULL NULL, codigo_pais_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, data_entrada_sociedade TIMESTAMP(0) NOT NULL, data_saida_sociedade TIMESTAMP(0) DEFAULT NULL NULL, pct_part_societario DOUBLE PRECISION DEFAULT NULL NULL, nome_socio_estrangeiro VARCHAR2(255) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_F66C97ADD17F50A6 ON gd_part_societaria (uuid)');
            $this->addSql('CREATE INDEX IDX_F66C97AD62DFC196 ON gd_part_societaria (pessoa_socio_id)');
            $this->addSql('CREATE INDEX IDX_F66C97AD216BE4D2 ON gd_part_societaria (pessoa_representante_id)');
            $this->addSql('CREATE INDEX IDX_F66C97ADCE1130A9 ON gd_part_societaria (pessoa_sociedade_id)');
            $this->addSql('CREATE INDEX IDX_F66C97AD941C52DE ON gd_part_societaria (mod_qualificacao_socio_id)');
            $this->addSql('CREATE INDEX IDX_F66C97ADA654CBCD ON gd_part_societaria (origem_dados_id)');
            $this->addSql('CREATE INDEX IDX_F66C97AD553B98CC ON gd_part_societaria (mod_qualificacao_representante_id)');
            $this->addSql('CREATE INDEX IDX_F66C97AD6FC6993A ON gd_part_societaria (codigo_pais_id)');
            $this->addSql('CREATE INDEX IDX_F66C97ADF69C7D9B ON gd_part_societaria (criado_por)');
            $this->addSql('CREATE INDEX IDX_F66C97ADAF2B1A92 ON gd_part_societaria (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_F66C97ADA395BB94 ON gd_part_societaria (apagado_por)');
            $this->addSql('COMMENT ON COLUMN gd_part_societaria.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_pessoa_devedor (id NUMBER(10) NOT NULL, pessoa_id NUMBER(10) NOT NULL, origem_dados_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, valor_consolidado_divida DOUBLE PRECISION DEFAULT NULL NULL, data_valor_consolidado TIMESTAMP(0) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_C9B64A1CD17F50A6 ON gd_pessoa_devedor (uuid)');
            $this->addSql('CREATE INDEX IDX_C9B64A1CDF6FA0A5 ON gd_pessoa_devedor (pessoa_id)');
            $this->addSql('CREATE INDEX IDX_C9B64A1CA654CBCD ON gd_pessoa_devedor (origem_dados_id)');
            $this->addSql('CREATE INDEX IDX_C9B64A1CF69C7D9B ON gd_pessoa_devedor (criado_por)');
            $this->addSql('CREATE INDEX IDX_C9B64A1CAF2B1A92 ON gd_pessoa_devedor (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_C9B64A1CA395BB94 ON gd_pessoa_devedor (apagado_por)');
            $this->addSql('COMMENT ON COLUMN gd_pessoa_devedor.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_situacao_cadastral (id NUMBER(10) NOT NULL, pessoa_devedor_id NUMBER(10) NOT NULL, tipo_situacao_cad_id NUMBER(10) NOT NULL, origem_dados_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, data_inicio TIMESTAMP(0) NOT NULL, data_fim TIMESTAMP(0) DEFAULT NULL NULL, motivo_situacao_cadastral VARCHAR2(255) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_52AEF8B3D17F50A6 ON gd_situacao_cadastral (uuid)');
            $this->addSql('CREATE INDEX IDX_52AEF8B3B3260DAB ON gd_situacao_cadastral (pessoa_devedor_id)');
            $this->addSql('CREATE INDEX IDX_52AEF8B3C93544D0 ON gd_situacao_cadastral (tipo_situacao_cad_id)');
            $this->addSql('CREATE INDEX IDX_52AEF8B3A654CBCD ON gd_situacao_cadastral (origem_dados_id)');
            $this->addSql('CREATE INDEX IDX_52AEF8B3F69C7D9B ON gd_situacao_cadastral (criado_por)');
            $this->addSql('CREATE INDEX IDX_52AEF8B3AF2B1A92 ON gd_situacao_cadastral (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_52AEF8B3A395BB94 ON gd_situacao_cadastral (apagado_por)');
            $this->addSql('COMMENT ON COLUMN gd_situacao_cadastral.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_situacao_especial (id NUMBER(10) NOT NULL, pessoa_devedor_id NUMBER(10) NOT NULL, mod_situacao_especial_id NUMBER(10) NOT NULL, origem_dados_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, data_inicio TIMESTAMP(0) NOT NULL, data_fim TIMESTAMP(0) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_C920CD47D17F50A6 ON gd_situacao_especial (uuid)');
            $this->addSql('CREATE INDEX IDX_C920CD47B3260DAB ON gd_situacao_especial (pessoa_devedor_id)');
            $this->addSql('CREATE INDEX IDX_C920CD476552B93C ON gd_situacao_especial (mod_situacao_especial_id)');
            $this->addSql('CREATE INDEX IDX_C920CD47A654CBCD ON gd_situacao_especial (origem_dados_id)');
            $this->addSql('CREATE INDEX IDX_C920CD47F69C7D9B ON gd_situacao_especial (criado_por)');
            $this->addSql('CREATE INDEX IDX_C920CD47AF2B1A92 ON gd_situacao_especial (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_C920CD47A395BB94 ON gd_situacao_especial (apagado_por)');
            $this->addSql('COMMENT ON COLUMN gd_situacao_especial.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_tipo_indice_devedor (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, periodicidade VARCHAR2(255) NOT NULL, metodologia CLOB DEFAULT NULL NULL, data_type VARCHAR2(255) NOT NULL, fonte_dados VARCHAR2(255) DEFAULT NULL NULL, data_inicio_serie TIMESTAMP(0) NOT NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, nome VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, sigla VARCHAR2(25) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_E85B7556D17F50A6 ON gd_tipo_indice_devedor (uuid)');
            $this->addSql('CREATE INDEX IDX_E85B7556F69C7D9B ON gd_tipo_indice_devedor (criado_por)');
            $this->addSql('CREATE INDEX IDX_E85B7556AF2B1A92 ON gd_tipo_indice_devedor (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_E85B7556A395BB94 ON gd_tipo_indice_devedor (apagado_por)');
            $this->addSql('COMMENT ON COLUMN gd_tipo_indice_devedor.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_tipo_situacao_cad (id NUMBER(10) NOT NULL, mod_qual_pessoa_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, codigo VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_8C81BCCAD17F50A6 ON gd_tipo_situacao_cad (uuid)');
            $this->addSql('CREATE INDEX IDX_8C81BCCAB9869A70 ON gd_tipo_situacao_cad (mod_qual_pessoa_id)');
            $this->addSql('CREATE INDEX IDX_8C81BCCAF69C7D9B ON gd_tipo_situacao_cad (criado_por)');
            $this->addSql('CREATE INDEX IDX_8C81BCCAAF2B1A92 ON gd_tipo_situacao_cad (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_8C81BCCAA395BB94 ON gd_tipo_situacao_cad (apagado_por)');
            $this->addSql('COMMENT ON COLUMN gd_tipo_situacao_cad.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE gd_vinc_doc_gestao_devedor (id NUMBER(10) NOT NULL, documento_id NUMBER(10) DEFAULT NULL NULL, bem_id NUMBER(10) DEFAULT NULL NULL, diligencia_id NUMBER(10) DEFAULT NULL NULL, garantia_id NUMBER(10) DEFAULT NULL NULL, medida_restritiva_id NUMBER(10) DEFAULT NULL NULL, part_societaria_id NUMBER(10) DEFAULT NULL NULL, situacao_cadastral_id NUMBER(10) DEFAULT NULL NULL, situacao_especial_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_5713F2F3D17F50A6 ON gd_vinc_doc_gestao_devedor (uuid)');
            $this->addSql('CREATE INDEX IDX_5713F2F345C0CF75 ON gd_vinc_doc_gestao_devedor (documento_id)');
            $this->addSql('CREATE INDEX IDX_5713F2F3F560C433 ON gd_vinc_doc_gestao_devedor (bem_id)');
            $this->addSql('CREATE INDEX IDX_5713F2F378A11A10 ON gd_vinc_doc_gestao_devedor (diligencia_id)');
            $this->addSql('CREATE INDEX IDX_5713F2F32BDBF778 ON gd_vinc_doc_gestao_devedor (garantia_id)');
            $this->addSql('CREATE INDEX IDX_5713F2F3E28236DB ON gd_vinc_doc_gestao_devedor (medida_restritiva_id)');
            $this->addSql('CREATE INDEX IDX_5713F2F39D088444 ON gd_vinc_doc_gestao_devedor (part_societaria_id)');
            $this->addSql('CREATE INDEX IDX_5713F2F3FFD5999F ON gd_vinc_doc_gestao_devedor (situacao_cadastral_id)');
            $this->addSql('CREATE INDEX IDX_5713F2F3B72D178F ON gd_vinc_doc_gestao_devedor (situacao_especial_id)');
            $this->addSql('CREATE INDEX IDX_5713F2F3F69C7D9B ON gd_vinc_doc_gestao_devedor (criado_por)');
            $this->addSql('CREATE INDEX IDX_5713F2F3AF2B1A92 ON gd_vinc_doc_gestao_devedor (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_5713F2F3A395BB94 ON gd_vinc_doc_gestao_devedor (apagado_por)');
            $this->addSql('CREATE INDEX IDX_5713F2F3FF6A170EBF396750 ON gd_vinc_doc_gestao_devedor (apagado_em, id)');
            $this->addSql('COMMENT ON COLUMN gd_vinc_doc_gestao_devedor.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE jd_assunto_cnj (id NUMBER(10) NOT NULL, parent_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, codigo_nacional NUMBER(10) DEFAULT NULL NULL, codigo_nacional_pai NUMBER(10) DEFAULT NULL NULL, nome VARCHAR2(255) NOT NULL, situacao VARCHAR2(255) DEFAULT NULL NULL, lft NUMBER(10) NOT NULL, lvl NUMBER(10) NOT NULL, rgt NUMBER(10) NOT NULL, root NUMBER(10) DEFAULT NULL NULL, dispositivo_legal VARCHAR2(255) DEFAULT NULL NULL, artigo VARCHAR2(255) DEFAULT NULL NULL, glossario CLOB DEFAULT NULL NULL, sigiloso VARCHAR2(1) DEFAULT NULL NULL, assunto_secundario VARCHAR2(1) DEFAULT NULL NULL, crime_antecedente VARCHAR2(1) DEFAULT NULL NULL, just_es_1grau VARCHAR2(1) DEFAULT NULL NULL, just_es_2grau VARCHAR2(1) DEFAULT NULL NULL, just_es_juizado_es VARCHAR2(1) DEFAULT NULL NULL, just_es_turmas VARCHAR2(1) DEFAULT NULL NULL, just_es_1grau_mil VARCHAR2(1) DEFAULT NULL NULL, just_es_2grau_mil VARCHAR2(1) DEFAULT NULL NULL, just_es_juizado_es_fp VARCHAR2(1) DEFAULT NULL NULL, just_tu_es_un VARCHAR2(1) DEFAULT NULL NULL, just_fed_1grau VARCHAR2(1) DEFAULT NULL NULL, just_fed_2grau VARCHAR2(1) DEFAULT NULL NULL, just_fed_juizado_es VARCHAR2(1) DEFAULT NULL NULL, just_fed_turmas VARCHAR2(1) DEFAULT NULL NULL, just_fed_nacional VARCHAR2(1) DEFAULT NULL NULL, just_fed_regional VARCHAR2(1) DEFAULT NULL NULL, just_trab_1grau VARCHAR2(1) DEFAULT NULL NULL, just_trab_2grau VARCHAR2(1) DEFAULT NULL NULL, just_trab_tst VARCHAR2(1) DEFAULT NULL NULL, stf VARCHAR2(1) DEFAULT NULL NULL, stj VARCHAR2(1) DEFAULT NULL NULL, cfj VARCHAR2(1) DEFAULT NULL NULL, cnj VARCHAR2(1) DEFAULT NULL NULL, just_mil_uniao_1grau VARCHAR2(1) DEFAULT NULL NULL, just_mil_uniao_stm VARCHAR2(1) DEFAULT NULL NULL, just_mil_est_1grau VARCHAR2(1) DEFAULT NULL NULL, just_mil_est_tjm VARCHAR2(1) DEFAULT NULL NULL, just_elei_1grau VARCHAR2(1) DEFAULT NULL NULL, just_elei_2grau VARCHAR2(1) DEFAULT NULL NULL, just_elei_tse VARCHAR2(1) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_71F07256D17F50A6 ON jd_assunto_cnj (uuid)');
            $this->addSql('CREATE INDEX IDX_71F07256727ACA70 ON jd_assunto_cnj (parent_id)');
            $this->addSql('CREATE INDEX IDX_71F07256F69C7D9B ON jd_assunto_cnj (criado_por)');
            $this->addSql('CREATE INDEX IDX_71F07256AF2B1A92 ON jd_assunto_cnj (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_71F07256A395BB94 ON jd_assunto_cnj (apagado_por)');

            $this->addSql('ALTER TABLE gd_bem ADD CONSTRAINT FK_3B35CD71DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE gd_bem ADD CONSTRAINT FK_3B35CD7181D7FB4C FOREIGN KEY (mod_bem_id) REFERENCES gd_mod_bem (id)');
            $this->addSql('ALTER TABLE gd_bem ADD CONSTRAINT FK_3B35CD71A654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE gd_bem ADD CONSTRAINT FK_3B35CD71F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_bem ADD CONSTRAINT FK_3B35CD71AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_bem ADD CONSTRAINT FK_3B35CD71A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent ADD CONSTRAINT FK_4E8CE6FCB3260DAB FOREIGN KEY (pessoa_devedor_id) REFERENCES gd_pessoa_devedor (id)');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent ADD CONSTRAINT FK_4E8CE6FCAAA822D2 FOREIGN KEY (processo_id) REFERENCES ad_processo (id)');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent ADD CONSTRAINT FK_4E8CE6FCB7CB576B FOREIGN KEY (mod_orgao_central_id) REFERENCES ad_mod_orgao_central (id)');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent ADD CONSTRAINT FK_4E8CE6FCF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent ADD CONSTRAINT FK_4E8CE6FCAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent ADD CONSTRAINT FK_4E8CE6FCA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_diligencia ADD CONSTRAINT FK_56817890D117EC65 FOREIGN KEY (mod_diligencia_id) REFERENCES gd_mod_diligencia (id)');
            $this->addSql('ALTER TABLE gd_diligencia ADD CONSTRAINT FK_56817890AAA822D2 FOREIGN KEY (processo_id) REFERENCES ad_processo (id)');
            $this->addSql('ALTER TABLE gd_diligencia ADD CONSTRAINT FK_56817890DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE gd_diligencia ADD CONSTRAINT FK_5681789045C0CF75 FOREIGN KEY (documento_id) REFERENCES ad_documento (id)');
            $this->addSql('ALTER TABLE gd_diligencia ADD CONSTRAINT FK_56817890A654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE gd_diligencia ADD CONSTRAINT FK_56817890F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_diligencia ADD CONSTRAINT FK_56817890AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_diligencia ADD CONSTRAINT FK_56817890A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_garantia ADD CONSTRAINT FK_7453C5A2AAA822D2 FOREIGN KEY (processo_id) REFERENCES ad_processo (id)');
            $this->addSql('ALTER TABLE gd_garantia ADD CONSTRAINT FK_7453C5A273533CF6 FOREIGN KEY (mod_garantia_id) REFERENCES gd_mod_garantia (id)');
            $this->addSql('ALTER TABLE gd_garantia ADD CONSTRAINT FK_7453C5A2DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE gd_garantia ADD CONSTRAINT FK_7453C5A2F560C433 FOREIGN KEY (bem_id) REFERENCES gd_bem (id)');
            $this->addSql('ALTER TABLE gd_garantia ADD CONSTRAINT FK_7453C5A2A654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE gd_garantia ADD CONSTRAINT FK_7453C5A2F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_garantia ADD CONSTRAINT FK_7453C5A2AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_garantia ADD CONSTRAINT FK_7453C5A2A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_indice_devedor ADD CONSTRAINT FK_95921F2DE69C2C24 FOREIGN KEY (tipo_ind_devedor_id) REFERENCES gd_tipo_indice_devedor (id)');
            $this->addSql('ALTER TABLE gd_indice_devedor ADD CONSTRAINT FK_95921F2DB3260DAB FOREIGN KEY (pessoa_devedor_id) REFERENCES gd_pessoa_devedor (id)');
            $this->addSql('ALTER TABLE gd_indice_devedor ADD CONSTRAINT FK_95921F2DF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_indice_devedor ADD CONSTRAINT FK_95921F2DAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_indice_devedor ADD CONSTRAINT FK_95921F2DA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_medida_restritiva ADD CONSTRAINT FK_93A29DCDF560C433 FOREIGN KEY (bem_id) REFERENCES gd_bem (id)');
            $this->addSql('ALTER TABLE gd_medida_restritiva ADD CONSTRAINT FK_93A29DCD30FD9868 FOREIGN KEY (mod_medida_restritiva_id) REFERENCES gd_mod_med_restr (id)');
            $this->addSql('ALTER TABLE gd_medida_restritiva ADD CONSTRAINT FK_93A29DCDA654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE gd_medida_restritiva ADD CONSTRAINT FK_93A29DCDF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_medida_restritiva ADD CONSTRAINT FK_93A29DCDAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_medida_restritiva ADD CONSTRAINT FK_93A29DCDA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_mod_bem ADD CONSTRAINT FK_D1FD8527F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_bem ADD CONSTRAINT FK_D1FD8527AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_bem ADD CONSTRAINT FK_D1FD8527A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_mod_diligencia ADD CONSTRAINT FK_95282E5AF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_diligencia ADD CONSTRAINT FK_95282E5AAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_diligencia ADD CONSTRAINT FK_95282E5AA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_mod_garantia ADD CONSTRAINT FK_A68D5385F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_garantia ADD CONSTRAINT FK_A68D5385AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_garantia ADD CONSTRAINT FK_A68D5385A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_mod_med_restr ADD CONSTRAINT FK_B1D6C4E7F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_med_restr ADD CONSTRAINT FK_B1D6C4E7AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_med_restr ADD CONSTRAINT FK_B1D6C4E7A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_mod_qual_representante ADD CONSTRAINT FK_59F83836F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_qual_representante ADD CONSTRAINT FK_59F83836AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_qual_representante ADD CONSTRAINT FK_59F83836A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_mod_qual_socio ADD CONSTRAINT FK_8FEB4265F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_qual_socio ADD CONSTRAINT FK_8FEB4265AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_qual_socio ADD CONSTRAINT FK_8FEB4265A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_mod_situacao_esp ADD CONSTRAINT FK_CC117C0DF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_situacao_esp ADD CONSTRAINT FK_CC117C0DAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_situacao_esp ADD CONSTRAINT FK_CC117C0DA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97AD62DFC196 FOREIGN KEY (pessoa_socio_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97AD216BE4D2 FOREIGN KEY (pessoa_representante_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97ADCE1130A9 FOREIGN KEY (pessoa_sociedade_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97AD941C52DE FOREIGN KEY (mod_qualificacao_socio_id) REFERENCES gd_mod_qual_socio (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97ADA654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97AD553B98CC FOREIGN KEY (mod_qualificacao_representante_id) REFERENCES gd_mod_qual_representante (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97AD6FC6993A FOREIGN KEY (codigo_pais_id) REFERENCES ad_pais (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97ADF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97ADAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97ADA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_pessoa_devedor ADD CONSTRAINT FK_C9B64A1CDF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE gd_pessoa_devedor ADD CONSTRAINT FK_C9B64A1CA654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE gd_pessoa_devedor ADD CONSTRAINT FK_C9B64A1CF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_pessoa_devedor ADD CONSTRAINT FK_C9B64A1CAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_pessoa_devedor ADD CONSTRAINT FK_C9B64A1CA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_situacao_cadastral ADD CONSTRAINT FK_52AEF8B3B3260DAB FOREIGN KEY (pessoa_devedor_id) REFERENCES gd_pessoa_devedor (id)');
            $this->addSql('ALTER TABLE gd_situacao_cadastral ADD CONSTRAINT FK_52AEF8B3C93544D0 FOREIGN KEY (tipo_situacao_cad_id) REFERENCES gd_tipo_situacao_cad (id)');
            $this->addSql('ALTER TABLE gd_situacao_cadastral ADD CONSTRAINT FK_52AEF8B3A654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE gd_situacao_cadastral ADD CONSTRAINT FK_52AEF8B3F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_situacao_cadastral ADD CONSTRAINT FK_52AEF8B3AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_situacao_cadastral ADD CONSTRAINT FK_52AEF8B3A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_situacao_especial ADD CONSTRAINT FK_C920CD47B3260DAB FOREIGN KEY (pessoa_devedor_id) REFERENCES gd_pessoa_devedor (id)');
            $this->addSql('ALTER TABLE gd_situacao_especial ADD CONSTRAINT FK_C920CD476552B93C FOREIGN KEY (mod_situacao_especial_id) REFERENCES gd_mod_situacao_esp (id)');
            $this->addSql('ALTER TABLE gd_situacao_especial ADD CONSTRAINT FK_C920CD47A654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE gd_situacao_especial ADD CONSTRAINT FK_C920CD47F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_situacao_especial ADD CONSTRAINT FK_C920CD47AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_situacao_especial ADD CONSTRAINT FK_C920CD47A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_tipo_indice_devedor ADD CONSTRAINT FK_E85B7556F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_tipo_indice_devedor ADD CONSTRAINT FK_E85B7556AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_tipo_indice_devedor ADD CONSTRAINT FK_E85B7556A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_tipo_situacao_cad ADD CONSTRAINT FK_8C81BCCAB9869A70 FOREIGN KEY (mod_qual_pessoa_id) REFERENCES ad_mod_qual_pessoa (id)');
            $this->addSql('ALTER TABLE gd_tipo_situacao_cad ADD CONSTRAINT FK_8C81BCCAF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_tipo_situacao_cad ADD CONSTRAINT FK_8C81BCCAAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_tipo_situacao_cad ADD CONSTRAINT FK_8C81BCCAA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F345C0CF75 FOREIGN KEY (documento_id) REFERENCES ad_documento (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F3F560C433 FOREIGN KEY (bem_id) REFERENCES gd_bem (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F378A11A10 FOREIGN KEY (diligencia_id) REFERENCES gd_diligencia (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F32BDBF778 FOREIGN KEY (garantia_id) REFERENCES gd_garantia (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F3E28236DB FOREIGN KEY (medida_restritiva_id) REFERENCES gd_medida_restritiva (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F39D088444 FOREIGN KEY (part_societaria_id) REFERENCES gd_part_societaria (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F3FFD5999F FOREIGN KEY (situacao_cadastral_id) REFERENCES gd_situacao_cadastral (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F3B72D178F FOREIGN KEY (situacao_especial_id) REFERENCES gd_situacao_especial (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F3F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F3AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F3A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

        } else {

            $this->addSql('CREATE TABLE gd_bem (id INT AUTO_INCREMENT NOT NULL, pessoa_id INT NOT NULL, mod_bem_id INT NOT NULL, origem_dados_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, percentual_quota_parte DOUBLE PRECISION NOT NULL, fonte_informacao VARCHAR(255) DEFAULT NULL, indexador VARCHAR(255) DEFAULT NULL, valor DOUBLE PRECISION DEFAULT NULL, data_valor DATETIME DEFAULT NULL, status_localizacao VARCHAR(1) NOT NULL, antieconomico TINYINT(1) DEFAULT 0, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3B35CD71D17F50A6 (uuid), INDEX IDX_3B35CD71DF6FA0A5 (pessoa_id), INDEX IDX_3B35CD7181D7FB4C (mod_bem_id), INDEX IDX_3B35CD71A654CBCD (origem_dados_id), INDEX IDX_3B35CD71F69C7D9B (criado_por), INDEX IDX_3B35CD71AF2B1A92 (atualizado_por), INDEX IDX_3B35CD71A395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_cfg_pes_dev_org_cent (id INT AUTO_INCREMENT NOT NULL, pessoa_devedor_id INT NOT NULL, processo_id INT DEFAULT NULL, mod_orgao_central_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, grande_devedor TINYINT(1) DEFAULT NULL, acompanhamento_prioritario TINYINT(1) DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_4E8CE6FCD17F50A6 (uuid), INDEX IDX_4E8CE6FCB3260DAB (pessoa_devedor_id), INDEX IDX_4E8CE6FCAAA822D2 (processo_id), INDEX IDX_4E8CE6FCB7CB576B (mod_orgao_central_id), INDEX IDX_4E8CE6FCF69C7D9B (criado_por), INDEX IDX_4E8CE6FCAF2B1A92 (atualizado_por), INDEX IDX_4E8CE6FCA395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_diligencia (id INT AUTO_INCREMENT NOT NULL, mod_diligencia_id INT NOT NULL, processo_id INT DEFAULT NULL, pessoa_id INT NOT NULL, documento_id INT DEFAULT NULL, origem_dados_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, data DATETIME NOT NULL, observacao VARCHAR(255) DEFAULT NULL, status_diligencia VARCHAR(1) NOT NULL, fonte_informacao VARCHAR(255) DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_56817890D17F50A6 (uuid), INDEX IDX_56817890D117EC65 (mod_diligencia_id), INDEX IDX_56817890AAA822D2 (processo_id), INDEX IDX_56817890DF6FA0A5 (pessoa_id), INDEX IDX_5681789045C0CF75 (documento_id), INDEX IDX_56817890A654CBCD (origem_dados_id), INDEX IDX_56817890F69C7D9B (criado_por), INDEX IDX_56817890AF2B1A92 (atualizado_por), INDEX IDX_56817890A395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_garantia (id INT AUTO_INCREMENT NOT NULL, processo_id INT NOT NULL, mod_garantia_id INT NOT NULL, pessoa_id INT DEFAULT NULL, bem_id INT DEFAULT NULL, origem_dados_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, descricao VARCHAR(255) NOT NULL, valor DOUBLE PRECISION NOT NULL, data_valor DATETIME NOT NULL, observacao VARCHAR(255) DEFAULT NULL, uso_especifico_processo TINYINT(1) DEFAULT NULL, data_inicio_vigencia DATETIME NOT NULL, data_fim_vigencia DATETIME DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_7453C5A2D17F50A6 (uuid), INDEX IDX_7453C5A2AAA822D2 (processo_id), INDEX IDX_7453C5A273533CF6 (mod_garantia_id), INDEX IDX_7453C5A2DF6FA0A5 (pessoa_id), INDEX IDX_7453C5A2F560C433 (bem_id), INDEX IDX_7453C5A2A654CBCD (origem_dados_id), INDEX IDX_7453C5A2F69C7D9B (criado_por), INDEX IDX_7453C5A2AF2B1A92 (atualizado_por), INDEX IDX_7453C5A2A395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_indice_devedor (id INT AUTO_INCREMENT NOT NULL, tipo_ind_devedor_id INT NOT NULL, pessoa_devedor_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, valor VARCHAR(255) NOT NULL, data DATETIME NOT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_95921F2DD17F50A6 (uuid), INDEX IDX_95921F2DE69C2C24 (tipo_ind_devedor_id), INDEX IDX_95921F2DB3260DAB (pessoa_devedor_id), INDEX IDX_95921F2DF69C7D9B (criado_por), INDEX IDX_95921F2DAF2B1A92 (atualizado_por), INDEX IDX_95921F2DA395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_medida_restritiva (id INT AUTO_INCREMENT NOT NULL, bem_id INT NOT NULL, mod_medida_restritiva_id INT NOT NULL, origem_dados_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, observacao VARCHAR(255) DEFAULT NULL, data_inicio DATETIME NOT NULL, data_fim DATETIME DEFAULT NULL, valor DOUBLE PRECISION DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_93A29DCDD17F50A6 (uuid), INDEX IDX_93A29DCDF560C433 (bem_id), INDEX IDX_93A29DCD30FD9868 (mod_medida_restritiva_id), INDEX IDX_93A29DCDA654CBCD (origem_dados_id), INDEX IDX_93A29DCDF69C7D9B (criado_por), INDEX IDX_93A29DCDAF2B1A92 (atualizado_por), INDEX IDX_93A29DCDA395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_mod_bem (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', valor VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_D1FD8527D17F50A6 (uuid), UNIQUE INDEX UNIQ_D1FD85272E892728 (valor), INDEX IDX_D1FD8527F69C7D9B (criado_por), INDEX IDX_D1FD8527AF2B1A92 (atualizado_por), INDEX IDX_D1FD8527A395BB94 (apagado_por), UNIQUE INDEX UNIQ_D1FD85272E892728FF6A170E (valor, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_mod_diligencia (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', valor VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_95282E5AD17F50A6 (uuid), UNIQUE INDEX UNIQ_95282E5A2E892728 (valor), INDEX IDX_95282E5AF69C7D9B (criado_por), INDEX IDX_95282E5AAF2B1A92 (atualizado_por), INDEX IDX_95282E5AA395BB94 (apagado_por), UNIQUE INDEX UNIQ_95282E5A2E892728FF6A170E (valor, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_mod_garantia (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', valor VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_A68D5385D17F50A6 (uuid), UNIQUE INDEX UNIQ_A68D53852E892728 (valor), INDEX IDX_A68D5385F69C7D9B (criado_por), INDEX IDX_A68D5385AF2B1A92 (atualizado_por), INDEX IDX_A68D5385A395BB94 (apagado_por), UNIQUE INDEX UNIQ_A68D53852E892728FF6A170E (valor, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_mod_med_restr (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', valor VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_B1D6C4E7D17F50A6 (uuid), UNIQUE INDEX UNIQ_B1D6C4E72E892728 (valor), INDEX IDX_B1D6C4E7F69C7D9B (criado_por), INDEX IDX_B1D6C4E7AF2B1A92 (atualizado_por), INDEX IDX_B1D6C4E7A395BB94 (apagado_por), UNIQUE INDEX UNIQ_B1D6C4E72E892728FF6A170E (valor, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_mod_qual_representante (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', valor VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_59F83836D17F50A6 (uuid), UNIQUE INDEX UNIQ_59F838362E892728 (valor), INDEX IDX_59F83836F69C7D9B (criado_por), INDEX IDX_59F83836AF2B1A92 (atualizado_por), INDEX IDX_59F83836A395BB94 (apagado_por), UNIQUE INDEX UNIQ_59F838362E892728FF6A170E (valor, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_mod_qual_socio (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', valor VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8FEB4265D17F50A6 (uuid), UNIQUE INDEX UNIQ_8FEB42652E892728 (valor), INDEX IDX_8FEB4265F69C7D9B (criado_por), INDEX IDX_8FEB4265AF2B1A92 (atualizado_por), INDEX IDX_8FEB4265A395BB94 (apagado_por), UNIQUE INDEX UNIQ_8FEB42652E892728FF6A170E (valor, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_mod_situacao_esp (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', valor VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_CC117C0DD17F50A6 (uuid), UNIQUE INDEX UNIQ_CC117C0D2E892728 (valor), INDEX IDX_CC117C0DF69C7D9B (criado_por), INDEX IDX_CC117C0DAF2B1A92 (atualizado_por), INDEX IDX_CC117C0DA395BB94 (apagado_por), UNIQUE INDEX UNIQ_CC117C0D2E892728FF6A170E (valor, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_part_societaria (id INT AUTO_INCREMENT NOT NULL, pessoa_socio_id INT NOT NULL, pessoa_representante_id INT DEFAULT NULL, pessoa_sociedade_id INT NOT NULL, mod_qualificacao_socio_id INT NOT NULL, origem_dados_id INT DEFAULT NULL, mod_qualificacao_representante_id INT DEFAULT NULL, codigo_pais_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, data_entrada_sociedade DATETIME NOT NULL, data_saida_sociedade DATETIME DEFAULT NULL, pct_part_societario DOUBLE PRECISION DEFAULT NULL, nome_socio_estrangeiro VARCHAR(255) DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_F66C97ADD17F50A6 (uuid), INDEX IDX_F66C97AD62DFC196 (pessoa_socio_id), INDEX IDX_F66C97AD216BE4D2 (pessoa_representante_id), INDEX IDX_F66C97ADCE1130A9 (pessoa_sociedade_id), INDEX IDX_F66C97AD941C52DE (mod_qualificacao_socio_id), INDEX IDX_F66C97ADA654CBCD (origem_dados_id), INDEX IDX_F66C97AD553B98CC (mod_qualificacao_representante_id), INDEX IDX_F66C97AD6FC6993A (codigo_pais_id), INDEX IDX_F66C97ADF69C7D9B (criado_por), INDEX IDX_F66C97ADAF2B1A92 (atualizado_por), INDEX IDX_F66C97ADA395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_pessoa_devedor (id INT AUTO_INCREMENT NOT NULL, pessoa_id INT NOT NULL, origem_dados_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, valor_consolidado_divida DOUBLE PRECISION DEFAULT NULL, data_valor_consolidado DATETIME DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_C9B64A1CD17F50A6 (uuid), INDEX IDX_C9B64A1CDF6FA0A5 (pessoa_id), INDEX IDX_C9B64A1CA654CBCD (origem_dados_id), INDEX IDX_C9B64A1CF69C7D9B (criado_por), INDEX IDX_C9B64A1CAF2B1A92 (atualizado_por), INDEX IDX_C9B64A1CA395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_situacao_cadastral (id INT AUTO_INCREMENT NOT NULL, pessoa_devedor_id INT NOT NULL, tipo_situacao_cad_id INT NOT NULL, origem_dados_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, data_inicio DATETIME NOT NULL, data_fim DATETIME DEFAULT NULL, motivo_situacao_cadastral VARCHAR(255) DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_52AEF8B3D17F50A6 (uuid), INDEX IDX_52AEF8B3B3260DAB (pessoa_devedor_id), INDEX IDX_52AEF8B3C93544D0 (tipo_situacao_cad_id), INDEX IDX_52AEF8B3A654CBCD (origem_dados_id), INDEX IDX_52AEF8B3F69C7D9B (criado_por), INDEX IDX_52AEF8B3AF2B1A92 (atualizado_por), INDEX IDX_52AEF8B3A395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_situacao_especial (id INT AUTO_INCREMENT NOT NULL, pessoa_devedor_id INT NOT NULL, mod_situacao_especial_id INT NOT NULL, origem_dados_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, data_inicio DATETIME NOT NULL, data_fim DATETIME DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_C920CD47D17F50A6 (uuid), INDEX IDX_C920CD47B3260DAB (pessoa_devedor_id), INDEX IDX_C920CD476552B93C (mod_situacao_especial_id), INDEX IDX_C920CD47A654CBCD (origem_dados_id), INDEX IDX_C920CD47F69C7D9B (criado_por), INDEX IDX_C920CD47AF2B1A92 (atualizado_por), INDEX IDX_C920CD47A395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_tipo_indice_devedor (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, periodicidade VARCHAR(255) NOT NULL, metodologia LONGTEXT DEFAULT NULL, data_type VARCHAR(255) NOT NULL, fonte_dados VARCHAR(255) DEFAULT NULL, data_inicio_serie DATETIME NOT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, sigla VARCHAR(25) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_E85B7556D17F50A6 (uuid), INDEX IDX_E85B7556F69C7D9B (criado_por), INDEX IDX_E85B7556AF2B1A92 (atualizado_por), INDEX IDX_E85B7556A395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_tipo_situacao_cad (id INT AUTO_INCREMENT NOT NULL, mod_qual_pessoa_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, codigo VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8C81BCCAD17F50A6 (uuid), INDEX IDX_8C81BCCAB9869A70 (mod_qual_pessoa_id), INDEX IDX_8C81BCCAF69C7D9B (criado_por), INDEX IDX_8C81BCCAAF2B1A92 (atualizado_por), INDEX IDX_8C81BCCAA395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE gd_vinc_doc_gestao_devedor (id INT AUTO_INCREMENT NOT NULL, documento_id INT DEFAULT NULL, bem_id INT DEFAULT NULL, diligencia_id INT DEFAULT NULL, garantia_id INT DEFAULT NULL, medida_restritiva_id INT DEFAULT NULL, part_societaria_id INT DEFAULT NULL, situacao_cadastral_id INT DEFAULT NULL, situacao_especial_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_5713F2F3D17F50A6 (uuid), INDEX IDX_5713F2F345C0CF75 (documento_id), INDEX IDX_5713F2F3F560C433 (bem_id), INDEX IDX_5713F2F378A11A10 (diligencia_id), INDEX IDX_5713F2F32BDBF778 (garantia_id), INDEX IDX_5713F2F3E28236DB (medida_restritiva_id), INDEX IDX_5713F2F39D088444 (part_societaria_id), INDEX IDX_5713F2F3FFD5999F (situacao_cadastral_id), INDEX IDX_5713F2F3B72D178F (situacao_especial_id), INDEX IDX_5713F2F3F69C7D9B (criado_por), INDEX IDX_5713F2F3AF2B1A92 (atualizado_por), INDEX IDX_5713F2F3A395BB94 (apagado_por), INDEX IDX_5713F2F3FF6A170EBF396750 (apagado_em, id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');

            $this->addSql('ALTER TABLE gd_bem ADD CONSTRAINT FK_3B35CD71DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE gd_bem ADD CONSTRAINT FK_3B35CD7181D7FB4C FOREIGN KEY (mod_bem_id) REFERENCES gd_mod_bem (id)');
            $this->addSql('ALTER TABLE gd_bem ADD CONSTRAINT FK_3B35CD71A654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE gd_bem ADD CONSTRAINT FK_3B35CD71F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_bem ADD CONSTRAINT FK_3B35CD71AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_bem ADD CONSTRAINT FK_3B35CD71A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent ADD CONSTRAINT FK_4E8CE6FCB3260DAB FOREIGN KEY (pessoa_devedor_id) REFERENCES gd_pessoa_devedor (id)');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent ADD CONSTRAINT FK_4E8CE6FCAAA822D2 FOREIGN KEY (processo_id) REFERENCES ad_processo (id)');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent ADD CONSTRAINT FK_4E8CE6FCB7CB576B FOREIGN KEY (mod_orgao_central_id) REFERENCES ad_mod_orgao_central (id)');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent ADD CONSTRAINT FK_4E8CE6FCF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent ADD CONSTRAINT FK_4E8CE6FCAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent ADD CONSTRAINT FK_4E8CE6FCA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_diligencia ADD CONSTRAINT FK_56817890D117EC65 FOREIGN KEY (mod_diligencia_id) REFERENCES gd_mod_diligencia (id)');
            $this->addSql('ALTER TABLE gd_diligencia ADD CONSTRAINT FK_56817890AAA822D2 FOREIGN KEY (processo_id) REFERENCES ad_processo (id)');
            $this->addSql('ALTER TABLE gd_diligencia ADD CONSTRAINT FK_56817890DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE gd_diligencia ADD CONSTRAINT FK_5681789045C0CF75 FOREIGN KEY (documento_id) REFERENCES ad_documento (id)');
            $this->addSql('ALTER TABLE gd_diligencia ADD CONSTRAINT FK_56817890A654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE gd_diligencia ADD CONSTRAINT FK_56817890F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_diligencia ADD CONSTRAINT FK_56817890AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_diligencia ADD CONSTRAINT FK_56817890A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_garantia ADD CONSTRAINT FK_7453C5A2AAA822D2 FOREIGN KEY (processo_id) REFERENCES ad_processo (id)');
            $this->addSql('ALTER TABLE gd_garantia ADD CONSTRAINT FK_7453C5A273533CF6 FOREIGN KEY (mod_garantia_id) REFERENCES gd_mod_garantia (id)');
            $this->addSql('ALTER TABLE gd_garantia ADD CONSTRAINT FK_7453C5A2DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE gd_garantia ADD CONSTRAINT FK_7453C5A2F560C433 FOREIGN KEY (bem_id) REFERENCES gd_bem (id)');
            $this->addSql('ALTER TABLE gd_garantia ADD CONSTRAINT FK_7453C5A2A654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE gd_garantia ADD CONSTRAINT FK_7453C5A2F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_garantia ADD CONSTRAINT FK_7453C5A2AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_garantia ADD CONSTRAINT FK_7453C5A2A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_indice_devedor ADD CONSTRAINT FK_95921F2DE69C2C24 FOREIGN KEY (tipo_ind_devedor_id) REFERENCES gd_tipo_indice_devedor (id)');
            $this->addSql('ALTER TABLE gd_indice_devedor ADD CONSTRAINT FK_95921F2DB3260DAB FOREIGN KEY (pessoa_devedor_id) REFERENCES gd_pessoa_devedor (id)');
            $this->addSql('ALTER TABLE gd_indice_devedor ADD CONSTRAINT FK_95921F2DF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_indice_devedor ADD CONSTRAINT FK_95921F2DAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_indice_devedor ADD CONSTRAINT FK_95921F2DA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_medida_restritiva ADD CONSTRAINT FK_93A29DCDF560C433 FOREIGN KEY (bem_id) REFERENCES gd_bem (id)');
            $this->addSql('ALTER TABLE gd_medida_restritiva ADD CONSTRAINT FK_93A29DCD30FD9868 FOREIGN KEY (mod_medida_restritiva_id) REFERENCES gd_mod_med_restr (id)');
            $this->addSql('ALTER TABLE gd_medida_restritiva ADD CONSTRAINT FK_93A29DCDA654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE gd_medida_restritiva ADD CONSTRAINT FK_93A29DCDF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_medida_restritiva ADD CONSTRAINT FK_93A29DCDAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_medida_restritiva ADD CONSTRAINT FK_93A29DCDA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_bem ADD CONSTRAINT FK_D1FD8527F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_bem ADD CONSTRAINT FK_D1FD8527AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_bem ADD CONSTRAINT FK_D1FD8527A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_diligencia ADD CONSTRAINT FK_95282E5AF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_diligencia ADD CONSTRAINT FK_95282E5AAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_diligencia ADD CONSTRAINT FK_95282E5AA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_garantia ADD CONSTRAINT FK_A68D5385F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_garantia ADD CONSTRAINT FK_A68D5385AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_garantia ADD CONSTRAINT FK_A68D5385A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_med_restr ADD CONSTRAINT FK_B1D6C4E7F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_med_restr ADD CONSTRAINT FK_B1D6C4E7AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_med_restr ADD CONSTRAINT FK_B1D6C4E7A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_qual_representante ADD CONSTRAINT FK_59F83836F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_qual_representante ADD CONSTRAINT FK_59F83836AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_qual_representante ADD CONSTRAINT FK_59F83836A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_qual_socio ADD CONSTRAINT FK_8FEB4265F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_qual_socio ADD CONSTRAINT FK_8FEB4265AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_qual_socio ADD CONSTRAINT FK_8FEB4265A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_situacao_esp ADD CONSTRAINT FK_CC117C0DF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_situacao_esp ADD CONSTRAINT FK_CC117C0DAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_mod_situacao_esp ADD CONSTRAINT FK_CC117C0DA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97AD62DFC196 FOREIGN KEY (pessoa_socio_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97AD216BE4D2 FOREIGN KEY (pessoa_representante_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97ADCE1130A9 FOREIGN KEY (pessoa_sociedade_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97AD941C52DE FOREIGN KEY (mod_qualificacao_socio_id) REFERENCES gd_mod_qual_socio (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97ADA654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97AD553B98CC FOREIGN KEY (mod_qualificacao_representante_id) REFERENCES gd_mod_qual_representante (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97AD6FC6993A FOREIGN KEY (codigo_pais_id) REFERENCES ad_pais (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97ADF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97ADAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_part_societaria ADD CONSTRAINT FK_F66C97ADA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_pessoa_devedor ADD CONSTRAINT FK_C9B64A1CDF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE gd_pessoa_devedor ADD CONSTRAINT FK_C9B64A1CA654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE gd_pessoa_devedor ADD CONSTRAINT FK_C9B64A1CF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_pessoa_devedor ADD CONSTRAINT FK_C9B64A1CAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_pessoa_devedor ADD CONSTRAINT FK_C9B64A1CA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_situacao_cadastral ADD CONSTRAINT FK_52AEF8B3B3260DAB FOREIGN KEY (pessoa_devedor_id) REFERENCES gd_pessoa_devedor (id)');
            $this->addSql('ALTER TABLE gd_situacao_cadastral ADD CONSTRAINT FK_52AEF8B3C93544D0 FOREIGN KEY (tipo_situacao_cad_id) REFERENCES gd_tipo_situacao_cad (id)');
            $this->addSql('ALTER TABLE gd_situacao_cadastral ADD CONSTRAINT FK_52AEF8B3A654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE gd_situacao_cadastral ADD CONSTRAINT FK_52AEF8B3F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_situacao_cadastral ADD CONSTRAINT FK_52AEF8B3AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_situacao_cadastral ADD CONSTRAINT FK_52AEF8B3A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_situacao_especial ADD CONSTRAINT FK_C920CD47B3260DAB FOREIGN KEY (pessoa_devedor_id) REFERENCES gd_pessoa_devedor (id)');
            $this->addSql('ALTER TABLE gd_situacao_especial ADD CONSTRAINT FK_C920CD476552B93C FOREIGN KEY (mod_situacao_especial_id) REFERENCES gd_mod_situacao_esp (id)');
            $this->addSql('ALTER TABLE gd_situacao_especial ADD CONSTRAINT FK_C920CD47A654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE gd_situacao_especial ADD CONSTRAINT FK_C920CD47F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_situacao_especial ADD CONSTRAINT FK_C920CD47AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_situacao_especial ADD CONSTRAINT FK_C920CD47A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_tipo_indice_devedor ADD CONSTRAINT FK_E85B7556F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_tipo_indice_devedor ADD CONSTRAINT FK_E85B7556AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_tipo_indice_devedor ADD CONSTRAINT FK_E85B7556A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_tipo_situacao_cad ADD CONSTRAINT FK_8C81BCCAB9869A70 FOREIGN KEY (mod_qual_pessoa_id) REFERENCES ad_mod_qual_pessoa (id)');
            $this->addSql('ALTER TABLE gd_tipo_situacao_cad ADD CONSTRAINT FK_8C81BCCAF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_tipo_situacao_cad ADD CONSTRAINT FK_8C81BCCAAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_tipo_situacao_cad ADD CONSTRAINT FK_8C81BCCAA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F345C0CF75 FOREIGN KEY (documento_id) REFERENCES ad_documento (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F3F560C433 FOREIGN KEY (bem_id) REFERENCES gd_bem (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F378A11A10 FOREIGN KEY (diligencia_id) REFERENCES gd_diligencia (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F32BDBF778 FOREIGN KEY (garantia_id) REFERENCES gd_garantia (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F3E28236DB FOREIGN KEY (medida_restritiva_id) REFERENCES gd_medida_restritiva (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F39D088444 FOREIGN KEY (part_societaria_id) REFERENCES gd_part_societaria (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F3FFD5999F FOREIGN KEY (situacao_cadastral_id) REFERENCES gd_situacao_cadastral (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F3B72D178F FOREIGN KEY (situacao_especial_id) REFERENCES gd_situacao_especial (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F3F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F3AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor ADD CONSTRAINT FK_5713F2F3A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('DROP SEQUENCE gd_bem_id_seq');
            $this->addSql('DROP SEQUENCE gd_cfg_pes_dev_org_cent_id_seq');
            $this->addSql('DROP SEQUENCE gd_diligencia_id_seq');
            $this->addSql('DROP SEQUENCE gd_garantia_id_seq');
            $this->addSql('DROP SEQUENCE gd_indice_devedor_id_seq');
            $this->addSql('DROP SEQUENCE gd_medida_restritiva_id_seq');
            $this->addSql('DROP SEQUENCE gd_mod_bem_id_seq');
            $this->addSql('DROP SEQUENCE gd_mod_diligencia_id_seq');
            $this->addSql('DROP SEQUENCE gd_mod_garantia_id_seq');
            $this->addSql('DROP SEQUENCE gd_mod_med_restr_id_seq');
            $this->addSql('DROP SEQUENCE gd_mod_qual_representante_id_s');
            $this->addSql('DROP SEQUENCE gd_mod_qual_socio_id_seq');
            $this->addSql('DROP SEQUENCE gd_mod_situacao_esp_id_seq');
            $this->addSql('DROP SEQUENCE gd_part_societaria_id_seq');
            $this->addSql('DROP SEQUENCE gd_pessoa_devedor_id_seq');
            $this->addSql('DROP SEQUENCE gd_situacao_cadastral_id_seq');
            $this->addSql('DROP SEQUENCE gd_situacao_especial_id_seq');
            $this->addSql('DROP SEQUENCE gd_tipo_indice_devedor_id_seq');
            $this->addSql('DROP SEQUENCE gd_tipo_situacao_cad_id_seq');
            $this->addSql('DROP SEQUENCE gd_vinc_doc_gestao_devedor_id_');

            $this->addSql('ALTER TABLE gd_bem DROP CONSTRAINT FK_3B35CD71DF6FA0A5');
            $this->addSql('ALTER TABLE gd_bem DROP CONSTRAINT FK_3B35CD7181D7FB4C');
            $this->addSql('ALTER TABLE gd_bem DROP CONSTRAINT FK_3B35CD71A654CBCD');
            $this->addSql('ALTER TABLE gd_bem DROP CONSTRAINT FK_3B35CD71F69C7D9B');
            $this->addSql('ALTER TABLE gd_bem DROP CONSTRAINT FK_3B35CD71AF2B1A92');
            $this->addSql('ALTER TABLE gd_bem DROP CONSTRAINT FK_3B35CD71A395BB94');

            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent DROP CONSTRAINT FK_4E8CE6FCB3260DAB');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent DROP CONSTRAINT FK_4E8CE6FCAAA822D2');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent DROP CONSTRAINT FK_4E8CE6FCB7CB576B');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent DROP CONSTRAINT FK_4E8CE6FCF69C7D9B');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent DROP CONSTRAINT FK_4E8CE6FCAF2B1A92');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent DROP CONSTRAINT FK_4E8CE6FCA395BB94');

            $this->addSql('ALTER TABLE gd_diligencia DROP CONSTRAINT FK_56817890D117EC65');
            $this->addSql('ALTER TABLE gd_diligencia DROP CONSTRAINT FK_56817890AAA822D2');
            $this->addSql('ALTER TABLE gd_diligencia DROP CONSTRAINT FK_56817890DF6FA0A5');
            $this->addSql('ALTER TABLE gd_diligencia DROP CONSTRAINT FK_5681789045C0CF75');
            $this->addSql('ALTER TABLE gd_diligencia DROP CONSTRAINT FK_56817890A654CBCD');
            $this->addSql('ALTER TABLE gd_diligencia DROP CONSTRAINT FK_56817890F69C7D9B');
            $this->addSql('ALTER TABLE gd_diligencia DROP CONSTRAINT FK_56817890AF2B1A92');
            $this->addSql('ALTER TABLE gd_diligencia DROP CONSTRAINT FK_56817890A395BB94');

            $this->addSql('ALTER TABLE gd_garantia DROP CONSTRAINT FK_7453C5A2AAA822D2');
            $this->addSql('ALTER TABLE gd_garantia DROP CONSTRAINT FK_7453C5A273533CF6');
            $this->addSql('ALTER TABLE gd_garantia DROP CONSTRAINT FK_7453C5A2DF6FA0A5');
            $this->addSql('ALTER TABLE gd_garantia DROP CONSTRAINT FK_7453C5A2F560C433');
            $this->addSql('ALTER TABLE gd_garantia DROP CONSTRAINT FK_7453C5A2A654CBCD');
            $this->addSql('ALTER TABLE gd_garantia DROP CONSTRAINT FK_7453C5A2F69C7D9B');
            $this->addSql('ALTER TABLE gd_garantia DROP CONSTRAINT FK_7453C5A2AF2B1A92');
            $this->addSql('ALTER TABLE gd_garantia DROP CONSTRAINT FK_7453C5A2A395BB94');

            $this->addSql('ALTER TABLE gd_indice_devedor DROP CONSTRAINT FK_95921F2DE69C2C24');
            $this->addSql('ALTER TABLE gd_indice_devedor DROP CONSTRAINT FK_95921F2DB3260DAB');
            $this->addSql('ALTER TABLE gd_indice_devedor DROP CONSTRAINT FK_95921F2DF69C7D9B');
            $this->addSql('ALTER TABLE gd_indice_devedor DROP CONSTRAINT FK_95921F2DAF2B1A92');
            $this->addSql('ALTER TABLE gd_indice_devedor DROP CONSTRAINT FK_95921F2DA395BB94');

            $this->addSql('ALTER TABLE gd_medida_restritiva DROP CONSTRAINT FK_93A29DCDF560C433');
            $this->addSql('ALTER TABLE gd_medida_restritiva DROP CONSTRAINT FK_93A29DCD30FD9868');
            $this->addSql('ALTER TABLE gd_medida_restritiva DROP CONSTRAINT FK_93A29DCDA654CBCD');
            $this->addSql('ALTER TABLE gd_medida_restritiva DROP CONSTRAINT FK_93A29DCDF69C7D9B');
            $this->addSql('ALTER TABLE gd_medida_restritiva DROP CONSTRAINT FK_93A29DCDAF2B1A92');
            $this->addSql('ALTER TABLE gd_medida_restritiva DROP CONSTRAINT FK_93A29DCDA395BB94');

            $this->addSql('ALTER TABLE gd_mod_bem DROP CONSTRAINT FK_D1FD8527F69C7D9B');
            $this->addSql('ALTER TABLE gd_mod_bem DROP CONSTRAINT FK_D1FD8527AF2B1A92');
            $this->addSql('ALTER TABLE gd_mod_bem DROP CONSTRAINT FK_D1FD8527A395BB94');

            $this->addSql('ALTER TABLE gd_mod_diligencia DROP CONSTRAINT FK_95282E5AF69C7D9B');
            $this->addSql('ALTER TABLE gd_mod_diligencia DROP CONSTRAINT FK_95282E5AAF2B1A92');
            $this->addSql('ALTER TABLE gd_mod_diligencia DROP CONSTRAINT FK_95282E5AA395BB94');

            $this->addSql('ALTER TABLE gd_mod_garantia DROP CONSTRAINT FK_A68D5385F69C7D9B');
            $this->addSql('ALTER TABLE gd_mod_garantia DROP CONSTRAINT FK_A68D5385AF2B1A92');
            $this->addSql('ALTER TABLE gd_mod_garantia DROP CONSTRAINT FK_A68D5385A395BB94');

            $this->addSql('ALTER TABLE gd_mod_med_restr DROP CONSTRAINT FK_B1D6C4E7F69C7D9B');
            $this->addSql('ALTER TABLE gd_mod_med_restr DROP CONSTRAINT FK_B1D6C4E7AF2B1A92');
            $this->addSql('ALTER TABLE gd_mod_med_restr DROP CONSTRAINT FK_B1D6C4E7A395BB94');

            $this->addSql('ALTER TABLE gd_mod_qual_representante DROP CONSTRAINT FK_59F83836F69C7D9B');
            $this->addSql('ALTER TABLE gd_mod_qual_representante DROP CONSTRAINT FK_59F83836AF2B1A92');
            $this->addSql('ALTER TABLE gd_mod_qual_representante DROP CONSTRAINT FK_59F83836A395BB94');

            $this->addSql('ALTER TABLE gd_mod_qual_socio DROP CONSTRAINT FK_8FEB4265F69C7D9B');
            $this->addSql('ALTER TABLE gd_mod_qual_socio DROP CONSTRAINT FK_8FEB4265AF2B1A92');
            $this->addSql('ALTER TABLE gd_mod_qual_socio DROP CONSTRAINT FK_8FEB4265A395BB94');

            $this->addSql('ALTER TABLE gd_mod_situacao_esp DROP CONSTRAINT FK_CC117C0DF69C7D9B');
            $this->addSql('ALTER TABLE gd_mod_situacao_esp DROP CONSTRAINT FK_CC117C0DAF2B1A92');
            $this->addSql('ALTER TABLE gd_mod_situacao_esp DROP CONSTRAINT FK_CC117C0DA395BB94');

            $this->addSql('ALTER TABLE gd_part_societaria DROP CONSTRAINT FK_F66C97AD62DFC196');
            $this->addSql('ALTER TABLE gd_part_societaria DROP CONSTRAINT FK_F66C97AD216BE4D2');
            $this->addSql('ALTER TABLE gd_part_societaria DROP CONSTRAINT FK_F66C97ADCE1130A9');
            $this->addSql('ALTER TABLE gd_part_societaria DROP CONSTRAINT FK_F66C97AD941C52DE');
            $this->addSql('ALTER TABLE gd_part_societaria DROP CONSTRAINT FK_F66C97ADA654CBCD');
            $this->addSql('ALTER TABLE gd_part_societaria DROP CONSTRAINT FK_F66C97AD553B98CC');
            $this->addSql('ALTER TABLE gd_part_societaria DROP CONSTRAINT FK_F66C97AD6FC6993A');
            $this->addSql('ALTER TABLE gd_part_societaria DROP CONSTRAINT FK_F66C97ADF69C7D9B');
            $this->addSql('ALTER TABLE gd_part_societaria DROP CONSTRAINT FK_F66C97ADAF2B1A92');
            $this->addSql('ALTER TABLE gd_part_societaria DROP CONSTRAINT FK_F66C97ADA395BB94');

            $this->addSql('ALTER TABLE gd_pessoa_devedor DROP CONSTRAINT FK_C9B64A1CDF6FA0A5');
            $this->addSql('ALTER TABLE gd_pessoa_devedor DROP CONSTRAINT FK_C9B64A1CA654CBCD');
            $this->addSql('ALTER TABLE gd_pessoa_devedor DROP CONSTRAINT FK_C9B64A1CF69C7D9B');
            $this->addSql('ALTER TABLE gd_pessoa_devedor DROP CONSTRAINT FK_C9B64A1CAF2B1A92');
            $this->addSql('ALTER TABLE gd_pessoa_devedor DROP CONSTRAINT FK_C9B64A1CA395BB94');

            $this->addSql('ALTER TABLE gd_situacao_cadastral DROP CONSTRAINT FK_52AEF8B3B3260DAB');
            $this->addSql('ALTER TABLE gd_situacao_cadastral DROP CONSTRAINT FK_52AEF8B3C93544D0');
            $this->addSql('ALTER TABLE gd_situacao_cadastral DROP CONSTRAINT FK_52AEF8B3A654CBCD');
            $this->addSql('ALTER TABLE gd_situacao_cadastral DROP CONSTRAINT FK_52AEF8B3F69C7D9B');
            $this->addSql('ALTER TABLE gd_situacao_cadastral DROP CONSTRAINT FK_52AEF8B3AF2B1A92');
            $this->addSql('ALTER TABLE gd_situacao_cadastral DROP CONSTRAINT FK_52AEF8B3A395BB94');

            $this->addSql('ALTER TABLE gd_situacao_especial DROP CONSTRAINT FK_C920CD47B3260DAB');
            $this->addSql('ALTER TABLE gd_situacao_especial DROP CONSTRAINT FK_C920CD476552B93C');
            $this->addSql('ALTER TABLE gd_situacao_especial DROP CONSTRAINT FK_C920CD47A654CBCD');
            $this->addSql('ALTER TABLE gd_situacao_especial DROP CONSTRAINT FK_C920CD47F69C7D9B');
            $this->addSql('ALTER TABLE gd_situacao_especial DROP CONSTRAINT FK_C920CD47AF2B1A92');
            $this->addSql('ALTER TABLE gd_situacao_especial DROP CONSTRAINT FK_C920CD47A395BB94');

            $this->addSql('ALTER TABLE gd_tipo_indice_devedor DROP CONSTRAINT FK_E85B7556F69C7D9B');
            $this->addSql('ALTER TABLE gd_tipo_indice_devedor DROP CONSTRAINT FK_E85B7556AF2B1A92');
            $this->addSql('ALTER TABLE gd_tipo_indice_devedor DROP CONSTRAINT FK_E85B7556A395BB94');
            $this->addSql('ALTER TABLE gd_tipo_situacao_cad DROP CONSTRAINT FK_8C81BCCAB9869A70');
            $this->addSql('ALTER TABLE gd_tipo_situacao_cad DROP CONSTRAINT FK_8C81BCCAF69C7D9B');
            $this->addSql('ALTER TABLE gd_tipo_situacao_cad DROP CONSTRAINT FK_8C81BCCAAF2B1A92');
            $this->addSql('ALTER TABLE gd_tipo_situacao_cad DROP CONSTRAINT FK_8C81BCCAA395BB94');

            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP CONSTRAINT FK_5713F2F345C0CF75');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP CONSTRAINT FK_5713F2F3F560C433');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP CONSTRAINT FK_5713F2F378A11A10');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP CONSTRAINT FK_5713F2F32BDBF778');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP CONSTRAINT FK_5713F2F3E28236DB');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP CONSTRAINT FK_5713F2F39D088444');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP CONSTRAINT FK_5713F2F3FFD5999F');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP CONSTRAINT FK_5713F2F3B72D178F');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP CONSTRAINT FK_5713F2F3F69C7D9B');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP CONSTRAINT FK_5713F2F3AF2B1A92');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP CONSTRAINT FK_5713F2F3A395BB94');

            $this->addSql('DROP TABLE gd_bem');
            $this->addSql('DROP TABLE gd_cfg_pes_dev_org_cent');
            $this->addSql('DROP TABLE gd_diligencia');
            $this->addSql('DROP TABLE gd_garantia');
            $this->addSql('DROP TABLE gd_indice_devedor');
            $this->addSql('DROP TABLE gd_medida_restritiva');
            $this->addSql('DROP TABLE gd_mod_bem');
            $this->addSql('DROP TABLE gd_mod_diligencia');
            $this->addSql('DROP TABLE gd_mod_garantia');
            $this->addSql('DROP TABLE gd_mod_med_restr');
            $this->addSql('DROP TABLE gd_mod_qual_representante');
            $this->addSql('DROP TABLE gd_mod_qual_socio');
            $this->addSql('DROP TABLE gd_mod_situacao_esp');
            $this->addSql('DROP TABLE gd_part_societaria');
            $this->addSql('DROP TABLE gd_pessoa_devedor');
            $this->addSql('DROP TABLE gd_situacao_cadastral');
            $this->addSql('DROP TABLE gd_situacao_especial');
            $this->addSql('DROP TABLE gd_tipo_indice_devedor');
            $this->addSql('DROP TABLE gd_tipo_situacao_cad');
            $this->addSql('DROP TABLE gd_vinc_doc_gestao_devedor');

        } else {

            $this->addSql('ALTER TABLE gd_bem DROP FOREIGN KEY FK_3B35CD71DF6FA0A5');
            $this->addSql('ALTER TABLE gd_bem DROP FOREIGN KEY FK_3B35CD7181D7FB4C');
            $this->addSql('ALTER TABLE gd_bem DROP FOREIGN KEY FK_3B35CD71A654CBCD');
            $this->addSql('ALTER TABLE gd_bem DROP FOREIGN KEY FK_3B35CD71F69C7D9B');
            $this->addSql('ALTER TABLE gd_bem DROP FOREIGN KEY FK_3B35CD71AF2B1A92');
            $this->addSql('ALTER TABLE gd_bem DROP FOREIGN KEY FK_3B35CD71A395BB94');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent DROP FOREIGN KEY FK_4E8CE6FCB3260DAB');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent DROP FOREIGN KEY FK_4E8CE6FCAAA822D2');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent DROP FOREIGN KEY FK_4E8CE6FCB7CB576B');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent DROP FOREIGN KEY FK_4E8CE6FCF69C7D9B');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent DROP FOREIGN KEY FK_4E8CE6FCAF2B1A92');
            $this->addSql('ALTER TABLE gd_cfg_pes_dev_org_cent DROP FOREIGN KEY FK_4E8CE6FCA395BB94');
            $this->addSql('ALTER TABLE gd_diligencia DROP FOREIGN KEY FK_56817890D117EC65');
            $this->addSql('ALTER TABLE gd_diligencia DROP FOREIGN KEY FK_56817890AAA822D2');
            $this->addSql('ALTER TABLE gd_diligencia DROP FOREIGN KEY FK_56817890DF6FA0A5');
            $this->addSql('ALTER TABLE gd_diligencia DROP FOREIGN KEY FK_5681789045C0CF75');
            $this->addSql('ALTER TABLE gd_diligencia DROP FOREIGN KEY FK_56817890A654CBCD');
            $this->addSql('ALTER TABLE gd_diligencia DROP FOREIGN KEY FK_56817890F69C7D9B');
            $this->addSql('ALTER TABLE gd_diligencia DROP FOREIGN KEY FK_56817890AF2B1A92');
            $this->addSql('ALTER TABLE gd_diligencia DROP FOREIGN KEY FK_56817890A395BB94');
            $this->addSql('ALTER TABLE gd_garantia DROP FOREIGN KEY FK_7453C5A2AAA822D2');
            $this->addSql('ALTER TABLE gd_garantia DROP FOREIGN KEY FK_7453C5A273533CF6');
            $this->addSql('ALTER TABLE gd_garantia DROP FOREIGN KEY FK_7453C5A2DF6FA0A5');
            $this->addSql('ALTER TABLE gd_garantia DROP FOREIGN KEY FK_7453C5A2F560C433');
            $this->addSql('ALTER TABLE gd_garantia DROP FOREIGN KEY FK_7453C5A2A654CBCD');
            $this->addSql('ALTER TABLE gd_garantia DROP FOREIGN KEY FK_7453C5A2F69C7D9B');
            $this->addSql('ALTER TABLE gd_garantia DROP FOREIGN KEY FK_7453C5A2AF2B1A92');
            $this->addSql('ALTER TABLE gd_garantia DROP FOREIGN KEY FK_7453C5A2A395BB94');
            $this->addSql('ALTER TABLE gd_indice_devedor DROP FOREIGN KEY FK_95921F2DE69C2C24');
            $this->addSql('ALTER TABLE gd_indice_devedor DROP FOREIGN KEY FK_95921F2DB3260DAB');
            $this->addSql('ALTER TABLE gd_indice_devedor DROP FOREIGN KEY FK_95921F2DF69C7D9B');
            $this->addSql('ALTER TABLE gd_indice_devedor DROP FOREIGN KEY FK_95921F2DAF2B1A92');
            $this->addSql('ALTER TABLE gd_indice_devedor DROP FOREIGN KEY FK_95921F2DA395BB94');
            $this->addSql('ALTER TABLE gd_medida_restritiva DROP FOREIGN KEY FK_93A29DCDF560C433');
            $this->addSql('ALTER TABLE gd_medida_restritiva DROP FOREIGN KEY FK_93A29DCD30FD9868');
            $this->addSql('ALTER TABLE gd_medida_restritiva DROP FOREIGN KEY FK_93A29DCDA654CBCD');
            $this->addSql('ALTER TABLE gd_medida_restritiva DROP FOREIGN KEY FK_93A29DCDF69C7D9B');
            $this->addSql('ALTER TABLE gd_medida_restritiva DROP FOREIGN KEY FK_93A29DCDAF2B1A92');
            $this->addSql('ALTER TABLE gd_medida_restritiva DROP FOREIGN KEY FK_93A29DCDA395BB94');
            $this->addSql('ALTER TABLE gd_mod_bem DROP FOREIGN KEY FK_D1FD8527F69C7D9B');
            $this->addSql('ALTER TABLE gd_mod_bem DROP FOREIGN KEY FK_D1FD8527AF2B1A92');
            $this->addSql('ALTER TABLE gd_mod_bem DROP FOREIGN KEY FK_D1FD8527A395BB94');
            $this->addSql('ALTER TABLE gd_mod_diligencia DROP FOREIGN KEY FK_95282E5AF69C7D9B');
            $this->addSql('ALTER TABLE gd_mod_diligencia DROP FOREIGN KEY FK_95282E5AAF2B1A92');
            $this->addSql('ALTER TABLE gd_mod_diligencia DROP FOREIGN KEY FK_95282E5AA395BB94');
            $this->addSql('ALTER TABLE gd_mod_garantia DROP FOREIGN KEY FK_A68D5385F69C7D9B');
            $this->addSql('ALTER TABLE gd_mod_garantia DROP FOREIGN KEY FK_A68D5385AF2B1A92');
            $this->addSql('ALTER TABLE gd_mod_garantia DROP FOREIGN KEY FK_A68D5385A395BB94');
            $this->addSql('ALTER TABLE gd_mod_med_restr DROP FOREIGN KEY FK_B1D6C4E7F69C7D9B');
            $this->addSql('ALTER TABLE gd_mod_med_restr DROP FOREIGN KEY FK_B1D6C4E7AF2B1A92');
            $this->addSql('ALTER TABLE gd_mod_med_restr DROP FOREIGN KEY FK_B1D6C4E7A395BB94');
            $this->addSql('ALTER TABLE gd_mod_qual_representante DROP FOREIGN KEY FK_59F83836F69C7D9B');
            $this->addSql('ALTER TABLE gd_mod_qual_representante DROP FOREIGN KEY FK_59F83836AF2B1A92');
            $this->addSql('ALTER TABLE gd_mod_qual_representante DROP FOREIGN KEY FK_59F83836A395BB94');
            $this->addSql('ALTER TABLE gd_mod_qual_socio DROP FOREIGN KEY FK_8FEB4265F69C7D9B');
            $this->addSql('ALTER TABLE gd_mod_qual_socio DROP FOREIGN KEY FK_8FEB4265AF2B1A92');
            $this->addSql('ALTER TABLE gd_mod_qual_socio DROP FOREIGN KEY FK_8FEB4265A395BB94');
            $this->addSql('ALTER TABLE gd_mod_situacao_esp DROP FOREIGN KEY FK_CC117C0DF69C7D9B');
            $this->addSql('ALTER TABLE gd_mod_situacao_esp DROP FOREIGN KEY FK_CC117C0DAF2B1A92');
            $this->addSql('ALTER TABLE gd_mod_situacao_esp DROP FOREIGN KEY FK_CC117C0DA395BB94');
            $this->addSql('ALTER TABLE gd_part_societaria DROP FOREIGN KEY FK_F66C97AD62DFC196');
            $this->addSql('ALTER TABLE gd_part_societaria DROP FOREIGN KEY FK_F66C97AD216BE4D2');
            $this->addSql('ALTER TABLE gd_part_societaria DROP FOREIGN KEY FK_F66C97ADCE1130A9');
            $this->addSql('ALTER TABLE gd_part_societaria DROP FOREIGN KEY FK_F66C97AD941C52DE');
            $this->addSql('ALTER TABLE gd_part_societaria DROP FOREIGN KEY FK_F66C97ADA654CBCD');
            $this->addSql('ALTER TABLE gd_part_societaria DROP FOREIGN KEY FK_F66C97AD553B98CC');
            $this->addSql('ALTER TABLE gd_part_societaria DROP FOREIGN KEY FK_F66C97AD6FC6993A');
            $this->addSql('ALTER TABLE gd_part_societaria DROP FOREIGN KEY FK_F66C97ADF69C7D9B');
            $this->addSql('ALTER TABLE gd_part_societaria DROP FOREIGN KEY FK_F66C97ADAF2B1A92');
            $this->addSql('ALTER TABLE gd_part_societaria DROP FOREIGN KEY FK_F66C97ADA395BB94');
            $this->addSql('ALTER TABLE gd_pessoa_devedor DROP FOREIGN KEY FK_C9B64A1CDF6FA0A5');
            $this->addSql('ALTER TABLE gd_pessoa_devedor DROP FOREIGN KEY FK_C9B64A1CA654CBCD');
            $this->addSql('ALTER TABLE gd_pessoa_devedor DROP FOREIGN KEY FK_C9B64A1CF69C7D9B');
            $this->addSql('ALTER TABLE gd_pessoa_devedor DROP FOREIGN KEY FK_C9B64A1CAF2B1A92');
            $this->addSql('ALTER TABLE gd_pessoa_devedor DROP FOREIGN KEY FK_C9B64A1CA395BB94');
            $this->addSql('ALTER TABLE gd_situacao_cadastral DROP FOREIGN KEY FK_52AEF8B3B3260DAB');
            $this->addSql('ALTER TABLE gd_situacao_cadastral DROP FOREIGN KEY FK_52AEF8B3C93544D0');
            $this->addSql('ALTER TABLE gd_situacao_cadastral DROP FOREIGN KEY FK_52AEF8B3A654CBCD');
            $this->addSql('ALTER TABLE gd_situacao_cadastral DROP FOREIGN KEY FK_52AEF8B3F69C7D9B');
            $this->addSql('ALTER TABLE gd_situacao_cadastral DROP FOREIGN KEY FK_52AEF8B3AF2B1A92');
            $this->addSql('ALTER TABLE gd_situacao_cadastral DROP FOREIGN KEY FK_52AEF8B3A395BB94');
            $this->addSql('ALTER TABLE gd_situacao_especial DROP FOREIGN KEY FK_C920CD47B3260DAB');
            $this->addSql('ALTER TABLE gd_situacao_especial DROP FOREIGN KEY FK_C920CD476552B93C');
            $this->addSql('ALTER TABLE gd_situacao_especial DROP FOREIGN KEY FK_C920CD47A654CBCD');
            $this->addSql('ALTER TABLE gd_situacao_especial DROP FOREIGN KEY FK_C920CD47F69C7D9B');
            $this->addSql('ALTER TABLE gd_situacao_especial DROP FOREIGN KEY FK_C920CD47AF2B1A92');
            $this->addSql('ALTER TABLE gd_situacao_especial DROP FOREIGN KEY FK_C920CD47A395BB94');
            $this->addSql('ALTER TABLE gd_tipo_indice_devedor DROP FOREIGN KEY FK_E85B7556F69C7D9B');
            $this->addSql('ALTER TABLE gd_tipo_indice_devedor DROP FOREIGN KEY FK_E85B7556AF2B1A92');
            $this->addSql('ALTER TABLE gd_tipo_indice_devedor DROP FOREIGN KEY FK_E85B7556A395BB94');
            $this->addSql('ALTER TABLE gd_tipo_situacao_cad DROP FOREIGN KEY FK_8C81BCCAB9869A70');
            $this->addSql('ALTER TABLE gd_tipo_situacao_cad DROP FOREIGN KEY FK_8C81BCCAF69C7D9B');
            $this->addSql('ALTER TABLE gd_tipo_situacao_cad DROP FOREIGN KEY FK_8C81BCCAAF2B1A92');
            $this->addSql('ALTER TABLE gd_tipo_situacao_cad DROP FOREIGN KEY FK_8C81BCCAA395BB94');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP FOREIGN KEY FK_5713F2F345C0CF75');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP FOREIGN KEY FK_5713F2F3F560C433');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP FOREIGN KEY FK_5713F2F378A11A10');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP FOREIGN KEY FK_5713F2F32BDBF778');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP FOREIGN KEY FK_5713F2F3E28236DB');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP FOREIGN KEY FK_5713F2F39D088444');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP FOREIGN KEY FK_5713F2F3FFD5999F');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP FOREIGN KEY FK_5713F2F3B72D178F');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP FOREIGN KEY FK_5713F2F3F69C7D9B');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP FOREIGN KEY FK_5713F2F3AF2B1A92');
            $this->addSql('ALTER TABLE gd_vinc_doc_gestao_devedor DROP FOREIGN KEY FK_5713F2F3A395BB94');

            $this->addSql('DROP TABLE gd_bem');
            $this->addSql('DROP TABLE gd_cfg_pes_dev_org_cent');
            $this->addSql('DROP TABLE gd_diligencia');
            $this->addSql('DROP TABLE gd_garantia');
            $this->addSql('DROP TABLE gd_indice_devedor');
            $this->addSql('DROP TABLE gd_medida_restritiva');
            $this->addSql('DROP TABLE gd_mod_bem');
            $this->addSql('DROP TABLE gd_mod_diligencia');
            $this->addSql('DROP TABLE gd_mod_garantia');
            $this->addSql('DROP TABLE gd_mod_med_restr');
            $this->addSql('DROP TABLE gd_mod_qual_representante');
            $this->addSql('DROP TABLE gd_mod_qual_socio');
            $this->addSql('DROP TABLE gd_mod_situacao_esp');
            $this->addSql('DROP TABLE gd_part_societaria');
            $this->addSql('DROP TABLE gd_pessoa_devedor');
            $this->addSql('DROP TABLE gd_situacao_cadastral');
            $this->addSql('DROP TABLE gd_situacao_especial');
            $this->addSql('DROP TABLE gd_tipo_indice_devedor');
            $this->addSql('DROP TABLE gd_tipo_situacao_cad');
            $this->addSql('DROP TABLE gd_vinc_doc_gestao_devedor');
        }
    }
}
