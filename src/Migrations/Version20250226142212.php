<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use SuppCore\AdministrativoBackend\Entity\Modulo;
use SuppCore\AdministrativoBackend\Entity\ConfigModulo;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250226142212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Inclusão de dados no config modulo para formulário de QRCode';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->formularioUp();
    }

    private function formularioUp()
    {
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $qb = $em->createQueryBuilder();
        $qb
            ->select('m')
            ->from(Modulo::class, 'm')
            ->where($qb->expr()->eq('m.nome', ':nome'))
            ->setParameter('nome', 'CONSULTIVO');

        $moduloConsultivo = $qb->getQuery()->getOneOrNullResult();

        $configModulo = (new ConfigModulo())
            ->setModulo($moduloConsultivo)
            ->setNome('supp_core.consultivo_backend.qrcode.avaliacao')
            ->setDescricao('CONFIGURAÇÕES RELATIVAS AO QRCODE E TEXTO DESCRITIVO NA CHANCELA DO COMPONENTE DIGITAL')
            ->setInvalid(false)
            ->setMandatory(false)
            ->setDataType('json')
            ->setParadigma(null)
            ->setDataSchema(
                json_encode([
                    '$schema' => 'http://json-schema.org/draft-07/schema#',
                    '$id' => 'https://supersapiensbackend.agu.gov.br/v1/administrativo/config_module/schema/supp_core.consultivo_backend.qrcode.avaliacao',
                    'description' => 'PARÂMETROS DE CONFIGURAÇÃO DO QRCODE E TEXTO DESCRITIVO',
                    'type' => 'object',
                    'required' => [],
                    'additionalProperties' => false,
                    'properties' => [
                        'conteudo' => [
                            '$comment' => 'Conteúdo do QRCode',
                            'type' => 'string',
                            'examples' => ['https://supersapiens.agu.gov.br', 'Texto livre']
                        ],
                        'descricao' => [
                            '$comment' => 'Texto descritivo do QRCode',
                            'type' => 'string',
                            'examples' => ['Avalie esta manifestação de forma anônima em menos de 30 segundos!']
                        ],
                        'tiposDocumento' => [
                            '$comment' => 'Tipos de documentos aceitos para inserir o QRCode',
                            'type' => 'array',
                            'examples' => ['DESPACHO']
                        ],
                        'quantidadeMaximaRespostas' => [
                            '$comment' => 'Quantidade máxima de respostas para mostrar QRCode da Avaliação',
                            'type' => 'integer',
                            'examples' => [5]
                        ],
                        'idFormularioAvaliacao' => [
                            '$comment' => 'ID do formulário de avaliação',
                            'type' => 'integer',
                            'examples' => [1]
                        ],
                        'maximoDiasJuntada' => [
                            '$comment' => 'Prazo máximo de dias corridos após a criação da juntada',
                            'type' => 'integer',
                            'examples' => [90]
                        ]
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            )
            ->setDataValue(
                json_encode([
                    'conteudo' => 'https://supersapiens.agu.gov.br',
                    'descricao' => 'Avalie esta manifestação de forma anônima em menos de 30 segundos!',
                    'tiposDocumento' => ['DESPACHO'],
                    'quantidadeMaximaRespostas' => 5,
                    'idFormularioAvaliacao' => 0,
                    'maximoDiasJuntada' => 90
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );

        $this->addSql($this->migrationHelper->generateInsertSQL($configModulo));
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
                ConfigModulo::class,
                [
                    'nome' => 'supp_core.consultivo_backend.qrcode.avaliacao',
                ]
            )
        );
    }
}
