<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Mapping\MappingException;
use SuppCore\AdministrativoBackend\Entity\ConfigModulo;
use SuppCore\AdministrativoBackend\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407124555 extends AbstractMigration
{
    /**
     * Summary of getDescription
     * @return string
     */
    public function getDescription(): string
    {
        return 'Modifica config modulo para incluir parâmetros para exclusão de sentenças de empréstimos consignado e servidores público';
    }

    /**
     * @param Schema $schema
     * 
     * @return void
     * 
     * @throws Exception
     * @throws MappingException
     * @throws SchemaException
     */

    public function up(Schema $schema): void
    {
        $this->configModuloUp();
        // $this->debug();
    }

    /**
     * Summary of confgModuloUp
     * @return void
     * 
     * @throws Exception
     * @throws MappingException
     */
    protected function configModuloUp(): void
    {
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ConfigModulo::class,
                [
                    'dataSchema' => json_encode( [
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'supp_core.administrativo_backend.ia.trilha.sentenca_previdenciaria',
                        '$comment' => 'CONFIGURAÇÕES DA TRILHA DE SENTENÇA PREVIDENCIARIA',
                        'type' => 'object',
                        'required' => [
                            'ativo',
                            'tipos_documentos_suportados',
                            'nome_especie_setor',
                            'nome_modalidade_interessado',
                            'nome_interessado',
                            'contem_string_dispositivos_legais',
                            'nao_deve_constar_palavras_chave',
                        ],
                        'properties' => [
                            'ativo' => [
                                'type' => 'boolean',
                                'title' => 'Trilha Habilitada',
                                'description' => 'Indica se a execução da trilha esta habilitada',
                                'examples' => [
                                    true,
                                    false
                                ],
                            ],
                            'tipos_documentos_suportados' => [
                                'type' => 'array',
                                'title' => 'Sigla dos Tipos de Documentos Suportados',
                                'description' => 'Lista dos tipos de documentos suportados pela trilha',
                                'items' => [
                                    'type' => 'string',
                                    'examples' => [
                                        'IMPUGSENT'
                                    ]
                                ]
                            ],
                            'nome_especie_setor' => [
                                'type' => 'string',
                                'title' => 'Nome da Especie de Setor',
                                'description' => 'Nome da especie de setor que analisa processos previdenciários',
                                'examples' => [
                                    'Matéria Previdenciária'
                                ]
                            ],
                            'nome_modalidade_interessado' => [
                                'type' => 'string',
                                'title' => 'Nome da Modalidade do Interessado',
                                'description' => 'Nome da modalidade do interessado no processo',
                                'examples' => [
                                    'Requerido (Pólo Passivo)'
                                ]
                            ],
                            'nome_interessado' => [
                                'type' => 'string',
                                'title' => 'Nome do Interessado',
                                'description' => 'Nome do interessado no processo',
                                'examples' => [
                                    'INSTITUTO NACIONAL DO SEGURO SOCIAL - INSS'
                                ]
                            ],
                            'contem_string_dispositivos_legais' => [
                                'type' => 'array',
                                'title' => 'Parte de strings que contem nos dispositivos_legais',
                                'description' => 'Parte de strings que contem nos dispositivos_legais',
                                'items' => [
                                    'type' => 'string',
                                    'examples' => [
                                        '8.213',
                                        '8213'
                                    ]
                                ]
                            ],
                            'nao_deve_constar_palavras_chave' => [
                                'type' => 'array',
                                'title' => 'Parte de strings que contem nas palavras_chave para não executar trilha',
                                'description' => 'Parte de strings que contem nas palavras_chaves para não executar trilha',
                                'items' => [
                                    'type' => 'string',
                                    'examples' => [
                                        'SERVIDOR PÚBLICO',
                                        'DESCONTO'
                                    ]
                                ]
                            ]
                        ],
                    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                    'invalid' => true,
                ],
                [
                    'nome' => 'supp_core.administrativo_backend.ia.trilha.sentenca_previdenciaria'
                ]
            )
        );
    }

    public function down(Schema $schema): void
    {
        $this->configModuloDown();
        // $this->debug();

    }

    protected function configModuloDown() : void 
    {
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ConfigModulo::class,
                [
                   'dataSchema' => json_encode( [
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'supp_core.administrativo_backend.ia.trilha.sentenca_previdenciaria',
                        '$comment' => 'CONFIGURAÇÕES DA TRILHA DE SENTENÇA PREVIDENCIARIA',
                        'type' => 'object',
                        'required' => [
                            'ativo',
                            'tipos_documentos_suportados',
                            'nome_especie_setor',
                            'nome_modalidade_interessado',
                            'nome_interessado',
                        ],
                        'properties' => [
                            'ativo' => [
                                'type' => 'boolean',
                                'title' => 'Trilha Habilitada',
                                'description' => 'Indica se a execução da trilha esta habilitada',
                                'examples' => [
                                    true,
                                    false
                                ],
                            ],
                            'tipos_documentos_suportados' => [
                                'type' => 'array',
                                'title' => 'Sigla dos Tipos de Documentos Suportados',
                                'description' => 'Lista dos tipos de documentos suportados pela trilha',
                                'items' => [
                                    'type' => 'string',
                                    'examples' => [
                                        'IMPUGSENT'
                                    ]
                                ]
                            ],
                            'nome_especie_setor' => [
                                'type' => 'string',
                                'title' => 'Nome da Especie de Setor',
                                'description' => 'Nome da especie de setor que analisa processos previdenciários',
                                'examples' => [
                                    'Matéria Previdenciária'
                                ]
                            ],
                            'nome_modalidade_interessado' => [
                                'type' => 'string',
                                'title' => 'Nome da Modalidade do Interessado',
                                'description' => 'Nome da modalidade do interessado no processo',
                                'examples' => [
                                    'Requerido (Pólo Passivo)'
                                ]
                            ],
                            'nome_interessado' => [
                                'type' => 'string',
                                'title' => 'Nome do Interessado',
                                'description' => 'Nome do interessado no processo',
                                'examples' => [
                                    'INSTITUTO NACIONAL DO SEGURO SOCIAL - INSS'
                                ]
                            ],
                        ],
                    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                    'invalid' => true,
                ],
                [
                    'nome' => 'supp_core.administrativo_backend.ia.trilha.sentenca_previdenciaria'
                ]
            )
        ); 
    }
}
