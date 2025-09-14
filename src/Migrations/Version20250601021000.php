<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration para criar a tabela ad_tarefa_mensagem e suas dependências.
 */
final class Version20250601021000 extends AbstractMigration // Use o timestamp que você escolheu
{
    public function getDescription(): string
    {
        return 'Cria manualmente a tabela ad_tarefa_mensagem para o chat de tarefas.';
    }

    public function up(Schema $schema): void
    {
        // Validação para não executar se a tabela já existir (opcional, mas boa prática)
        if ($schema->hasTable('ad_tarefa_mensagem')) {
            $this->write('Tabela ad_tarefa_mensagem já existe, pulando criação.');
            return;
        }

        $this->addSql('CREATE TABLE ad_tarefa_mensagem (
            id SERIAL NOT NULL,
            tarefa_id INT NOT NULL,
            usuario_id INT NOT NULL,
            uuid UUID NOT NULL,
            criado_em TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            atualizado_em TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            usuario_nome VARCHAR(255) NOT NULL,
            conteudo TEXT NOT NULL,
            data_hora_envio TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            PRIMARY KEY(id)
        )');
        // Adiciona comentários para tipos do Doctrine (ajuda na introspecção)
        $this->addSql('COMMENT ON COLUMN ad_tarefa_mensagem.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ad_tarefa_mensagem.criado_em IS \'(DC2Type:datetime)\''); // Ou datetime se for o tipo do seu Timestampable
        $this->addSql('COMMENT ON COLUMN ad_tarefa_mensagem.atualizado_em IS \'(DC2Type:datetime)\''); // Ou datetime

        // Cria índices
        $this->addSql('CREATE UNIQUE INDEX idx_tarefa_mensagem_uuid ON ad_tarefa_mensagem (uuid)');
        $this->addSql('CREATE INDEX idx_tarefa_mensagem_tarefa ON ad_tarefa_mensagem (tarefa_id)');
        $this->addSql('CREATE INDEX idx_tarefa_mensagem_usuario ON ad_tarefa_mensagem (usuario_id)');
        $this->addSql('CREATE INDEX idx_tarefa_mensagem_data_envio ON ad_tarefa_mensagem (data_hora_envio)');

        // Adiciona Chaves Estrangeiras
        $this->addSql('ALTER TABLE ad_tarefa_mensagem ADD CONSTRAINT FK_AD_TAREFA_MENSAGEM_TAREFA_ID
            FOREIGN KEY (tarefa_id) REFERENCES ad_tarefa (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ad_tarefa_mensagem ADD CONSTRAINT FK_AD_TAREFA_MENSAGEM_USUARIO_ID
            FOREIGN KEY (usuario_id) REFERENCES ad_usuario (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');

         // Se você adicionou a coleção `mensagensChat` na entidade Tarefa e quer que o Doctrine saiba disso
         // (geralmente o diff faria isso, mas como é manual, você pode adicionar se sua ferramenta de schema diff precisar)
         // Isso não cria colunas, apenas informa o schema sobre a relação, o que é mais para o `doctrine:schema:validate`.
         // Não é estritamente necessário para o funcionamento da tabela ad_tarefa_mensagem em si.
         /*
         if ($schema->hasTable('ad_tarefa') && !$schema->getTable('ad_tarefa')->hasForeignKey('fk_algum_nome_para_tarefa_mensagem')) {
             // Este passo é mais complexo de fazer manualmente e geralmente é deixado para o diff
             // A relação OneToMany em Tarefa não cria uma FK na tabela ad_tarefa.
             // A FK está em ad_tarefa_mensagem (tarefa_id) apontando para ad_tarefa.
             // O que o diff faria, se você tivesse adicionado a coleção à Tarefa *antes* de gerar um diff,
             // seria garantir que a FK em ad_tarefa_mensagem está correta.
             // Como já estamos criando a FK em ad_tarefa_mensagem, essa parte é coberta.
         }
         */
    }

    public function down(Schema $schema): void
    {
        // Opcional: verificar se as FKs existem antes de tentar dropar
        // $table = $schema->getTable('ad_tarefa_mensagem');
        // if ($table->hasForeignKey('FK_AD_TAREFA_MENSAGEM_TAREFA_ID')) {
        //    $this->addSql('ALTER TABLE ad_tarefa_mensagem DROP CONSTRAINT FK_AD_TAREFA_MENSAGEM_TAREFA_ID');
        // }
        // if ($table->hasForeignKey('FK_AD_TAREFA_MENSAGEM_USUARIO_ID')) {
        //    $this->addSql('ALTER TABLE ad_tarefa_mensagem DROP CONSTRAINT FK_AD_TAREFA_MENSAGEM_USUARIO_ID');
        // }

        // A ordem de drop das FKs não importa tanto quanto a ordem de criação de tabelas
        $this->addSql('ALTER TABLE ad_tarefa_mensagem DROP CONSTRAINT IF EXISTS FK_AD_TAREFA_MENSAGEM_TAREFA_ID');
        $this->addSql('ALTER TABLE ad_tarefa_mensagem DROP CONSTRAINT IF EXISTS FK_AD_TAREFA_MENSAGEM_USUARIO_ID');

        $this->addSql('DROP TABLE IF EXISTS ad_tarefa_mensagem');
    }
}