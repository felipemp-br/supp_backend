<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use SuppCore\AdministrativoBackend\Entity\Formulario;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250226153042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Inclusão de dados do formulário de QRCode manifestação consultiva';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->formularioUp();
    }

    private function formularioUp()
    {
        $formularioQRCode = (new Formulario())
            ->setNome('FORMULARIO QRCODE')
            ->setSigla('formulario_questionario_qrcode')
            ->setDataSchema(json_encode(
                [
                    '$schema' => 'http://json-schema.org/draft-07/schema#',
                    '$id' => 'https://supersapiensbackend.agu.gov.br/v1/administrativo/config_module/schema/supp_core.consultivo_backend.qrcode.avaliacao',
                    'title' => 'AVALIAÇÃO DA MANIFESTAÇÃO CONSULTIVA DA AGU',
                    'type' => 'object',
                    'required' => [
                        'pergunta_1',
                        'pergunta_2',
                        'pergunta_3',
                        'pergunta_4',
                    ],
                    'properties' => [
                        'pergunta_1' => [
                            'type' => 'rating',
                            'title' => '1. TEMPESTIVIDADE: o tempo de resposta da AGU à consulta foi adequado?',
                        ],
                        'pergunta_2' => [
                            'type' => 'rating',
                            'title' => '2. CLAREZA: a linguagem utilizada na manifestação consultiva foi de fácil compreensão?',
                        ],
                        'pergunta_3' => [
                            'type' => 'rating',
                            'title' => '3. ABRANGÊNCIA: a manifestação consultiva examinou todos os questionamentos?',
                        ],
                        'pergunta_4' => [
                            'type' => 'rating',
                            'title' => '4. RESOLUTIVIDADE: a sua demanda foi solucionada ou foi oferecida uma alternativa jurídica?',
                        ],
                        'pergunta_5' => [
                            'type' => 'text',
                            'title' => '5. CRÍTICAS, SUGESTÕES E COMENTÁRIOS',
                        ],
                    ]
                ],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            ))
            ->setIa(false)
            ->setAceitaJsonInvalido(true)
            ->setAtivo(true);

        $this->addSql($this->migrationHelper->generateInsertSQL($formularioQRCode));
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->formularioDown();
    }

    private function formularioDown()
    {
        $this->addSql(
            $this->migrationHelper->generateDeleteSQL(
                Formulario::class,
                [
                    'sigla' => 'formulario_questionario_qrcode',
                ]
            )
        );
    }
}
