<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping\MappingException;
use SuppCore\AdministrativoBackend\Entity\ConfigModulo;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250113181728 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Atualiza as configurações de solicitação automatizada para fluxo de aguardando cumprimento.';
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
        $this->solicitacaoAutomatizadaUp($schema);
//        $this->debug();
    }

    /**
     * @return void
     *
     * @throws Exception
     * @throws MappingException
     */
    private function configModuloUp(): void
    {
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ConfigModulo::class,
                [
                    'dataSchema' => json_encode([
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'supp_core.administrativo_backend.solicitacao_automatizada',
                        '$comment' => 'Configurações para a SolicitacaoAutomatizada.',
                        'type' => 'object',
                        'additionalProperties' => false,
                        'required' => [
                            'especie_tarefa_analise',
                            'especie_tarefa_dados_cumprimento',
                            'especie_tarefa_erro_solicitacao',
                            'especie_tarefa_acompanhamento_cumprimento',
                            'especie_atividade_deferimento',
                            'especie_atividade_indeferimento',
                            'especie_atividade_erro_solicitacao',
                            'especie_atividade_acompanhamento_cumprimento',
                            'tipo_documento',
                            'prazo_timeout_verificacao_dossies',
                            'analise_positiva',
                            'analise_negativa',
                            'setor_erro_solicitacao',
                        ],
                        'properties' => [
                            'especie_tarefa_analise' => [
                                '$comment' => 'Nome da espécie da tarefa que será aberta para analise da solicitação automatizada pelo procurador',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'especie_tarefa_dados_cumprimento' => [
                                '$comment' => 'Nome da espécie da tarefa que será aberta para preenchimento dos dados de cumprimento.',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'especie_tarefa_erro_solicitacao' => [
                                '$comment' => 'Nome da espécie da tarefa que será aberta para fluxo de erro.',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'especie_tarefa_acompanhamento_cumprimento' => [
                                '$comment' => 'Nome da espécie da tarefa que será aberta para acompanhamento do cumprimento.',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'especie_atividade_deferimento' => [
                                '$comment' => 'Espécie da atividade que será monitorada para deferimento',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'especie_atividade_indeferimento' => [
                                '$comment' => 'Espécie da atividade que será monitorada para indeferimento',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'especie_atividade_erro_solicitacao' => [
                                '$comment' => 'Espécie da atividade que será monitorada finalização do fluxo de erro.',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'especie_atividade_acompanhamento_cumprimento' => [
                                '$comment' => 'Espécie da atividade que será monitorada para acompanhamento do cumprimento.',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'tipo_documento' => [
                                '$comment' => 'Tipo de Documento que será utilizado para juntado na solicitação',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'prazo_timeout_verificacao_dossies' => [
                                '$comment' => 'Prazo em dias para considerar os dossies com status em geração como timeout (ERRO)',
                                'type' => 'number',
                                'examples' => [
                                    12,
                                    15,
                                    20,
                                ],
                            ],
                            'analise_positiva' => [
                                '$comment' => 'Configurações para análise possitiva dos requisitos dos dossies',
                                'type' => 'object',
                                'properties' => [
                                    'etiquetas_processo' => [
                                        '$comment' => 'Nome das etiquetas de sistema que serão adicionadas ao processo.',
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'string',
                                            'examples' => [
                                                'SEM RESTRIÇÕES',
                                                'LIMPO',
                                            ],
                                        ],
                                    ],
                                ],
                                'required' => [
                                    'etiquetas_processo',
                                ],
                            ],
                            'analise_negativa' => [
                                '$comment' => 'Configurações para análise negativa dos requisitos dos dossies',
                                'type' => 'object',
                                'properties' => [
                                    'etiquetas_processo' => [
                                        '$comment' => 'Nome das etiquetas de sistema que serão adicionadas ao processo.',
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'string',
                                            'examples' => [
                                                'SEM RESTRIÇÕES',
                                                'LIMPO',
                                            ],
                                        ],
                                    ],
                                ],
                                'required' => [
                                    'etiquetas_processo',
                                ],
                            ],
                            'setor_erro_solicitacao' => [
                                '$comment' => 'Id do setor onde será aberta a tarefa para tratamento de erro da solicitação automatizada.',
                                'type' => 'number',
                                'examples' => [
                                    1,
                                    2,
                                ],
                            ],
                        ],
                    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                    'invalid' => true,
                ],
                [
                    'nome' => 'supp_core.administrativo_backend.solicitacao_automatizada'
                ]
            )
        );
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ConfigModulo::class,
                [
                    'dataSchema' => json_encode([
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'supp_core.administrativo_backend.solicitacao_automatizada.salario_maternidade_rural',
                        '$comment' => 'Configurações para a SolicitacaoAutomatizada para Salário Maternidade Rural.',
                        'type' => 'object',
                        'additionalProperties' => false,
                        'required' => [
                            'setor_analise_procurador',
                            'setor_dados_cumprimento',
                            'setor_tarefa_acompanhamento_cumprimento',
                            'especie_documento_avulso_analise',
                            'especie_documento_avulso_cumprimento',
                            'modelo_documento_avulso_analise',
                            'modelo_documento_avulso_cumprimento',
                            'dossies_beneficiario',
                            'dossies_conjuge',
                            'extracoes_conjuge',
                            'extracoes_dados_cumprimento',
                            'analise_negativa_pelo_procurador',
                            'analise_positiva_pelo_procurador',
                            'dados_cumprimento_sumario',
                            'id_pessoa_cumprimento_destino',
                            'termos_documento',
                            'dias_prazo_verificacao_cumprimento',
                            'dias_final_prazo_tarefa_acompanhamento_cumprimento',
                        ],
                        'properties' => [
                            'setor_analise_procurador' => [
                                '$comment' => 'Id do setor onde será aberta a tarefa de análise pelo procurador.',
                                'type' => 'number',
                                'examples' => [
                                    1,
                                    2,
                                ],
                            ],
                            'setor_dados_cumprimento' => [
                                '$comment' => 'Id do setor onde será aberta a tarefa para preenchimento dos dados de cumprimento.',
                                'type' => 'number',
                                'examples' => [
                                    1,
                                    2,
                                ],
                            ],
                            'setor_tarefa_acompanhamento_cumprimento' => [
                                '$comment' => 'Id do setor onde será aberta a tarefa para acompanhamento do cumprimento.',
                                'type' => 'number',
                                'examples' => [
                                    1,
                                    2,
                                ],
                            ],
                            'especie_documento_avulso_analise' => [
                                '$comment' => 'Nome da espécie do documento avulso do ofício de análise.',
                                'type' => 'string',
                                'examples' => [
                                    'SOLICITACAO CUMPRIMENTO DO INSS',
                                ],
                            ],
                            'especie_documento_avulso_cumprimento' => [
                                '$comment' => 'Nome da espécie do documento avulso do ofício de cumprimento.',
                                'type' => 'string',
                                'examples' => [
                                    'SOLICITACAO CUMPRIMENTO DO INSS',
                                ],
                            ],
                            'modelo_documento_avulso_analise' => [
                                '$comment' => 'Nome do modelo do ofício de análise.',
                                'type' => 'string',
                                'examples' => [
                                    'ANALISE SOLICITAÇÃO AUTOMATICA',
                                ],
                            ],
                            'modelo_documento_avulso_cumprimento' => [
                                '$comment' => 'Nome do modelo do ofício de cumprimento.',
                                'type' => 'string',
                                'examples' => [
                                    'modelo_documento_avulso_cumprimento',
                                ],
                            ],
                            'dossies_beneficiario' => [
                                '$comment' => 'Define a lista de dossiês que serão solicitados para o beneficiário.',
                                'type' => 'array',
                                'items' => [
                                    'type' => 'string',
                                    'examples' => [
                                        'PAPGET',
                                        'DOSPREV',
                                        'DOSLABRA',
                                        'DOSOC',
                                    ]
                                ]
                            ],
                            'dossies_conjuge' => [
                                '$comment' => 'Define a lista de dossiês que serão solicitados para o conjuge.',
                                'type' => 'array',
                                'items' => [
                                    'type' => 'string',
                                    'examples' => [
                                        'PAPGET',
                                        'DOSPREV',
                                        'DOSLABRA',
                                        'DOSOC',
                                    ]
                                ]
                            ],
                            'analise_inicial' => [
                                '$comment' => 'Configurações de análise inicial de requisitos.',
                                'type' => 'object',
                                'required' => [
                                    'analises_beneficiario',
                                    'analises_conjuge',
                                ],
                                'properties' => [
                                    'analises_beneficiario' => [
                                        '$comment' => 'Define a lista de análises realizadas para os dados do beneficiário.',
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'required' => [
                                                'analise',
                                                'etiquetas_tarefa_analise_procurador',
                                            ],
                                            'properties' => [
                                                'analise' => [
                                                    '$comment' => 'Nome da classe de análise de requisitos.',
                                                    'type' => 'string',
                                                    'examples' => Version20241014115244::ANALISE_INICIAL_EXAMPLES,
                                                ],
                                                'etiquetas_tarefa_analise_procurador' => [
                                                    '$comment' => 'Lista de configurações de etiquetas da tarefa de análise do procurador.',
                                                    'type' => 'array',
                                                    'items' => [
                                                        '$comment' => 'Configuração de etiquetas da análise',
                                                        'type' => 'object',
                                                        'required' => [
                                                            'passou_analise',
                                                            'etiquetas'
                                                        ],
                                                        'properties' => [
                                                            'passou_analise' => [
                                                                '$comment' => 'Passou na análise?',
                                                                'type' => 'boolean',
                                                                'examples' => [
                                                                    true,
                                                                    false
                                                                ],
                                                            ],
                                                            'etiquetas' => [
                                                                '$comment' => 'Id das etiquetas a serem vinculadas a tarefa.',
                                                                'type' => 'array',
                                                                'items' => [
                                                                    'type' => 'number',
                                                                    'examples' => [
                                                                        1,
                                                                        2,
                                                                        3,
                                                                    ]
                                                                ]
                                                            ]
                                                        ]
                                                    ]
                                                ],
                                            ],
                                        ],
                                    ],
                                    'analises_conjuge' => [
                                        '$comment' => 'Define a lista de análises realizadas para os dados do conjuge.',
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'required' => [
                                                'analise',
                                                'etiquetas_tarefa_analise_procurador',
                                            ],
                                            'properties' => [
                                                'analise' => [
                                                    '$comment' => 'Nome da classe de análise de requisitos.',
                                                    'type' => 'string',
                                                    'examples' => Version20241014115244::ANALISE_INICIAL_EXAMPLES,
                                                ],
                                                'etiquetas_tarefa_analise_procurador' => [
                                                    '$comment' => 'Lista de configurações de etiquetas da tarefa de análise do procurador.',
                                                    'type' => 'array',
                                                    'items' => [
                                                        '$comment' => 'Configuração de etiquetas da análise',
                                                        'type' => 'object',
                                                        'required' => [
                                                            'passou_analise',
                                                            'etiquetas'
                                                        ],
                                                        'properties' => [
                                                            'passou_analise' => [
                                                                '$comment' => 'Passou na análise?',
                                                                'type' => 'boolean',
                                                                'examples' => [
                                                                    true,
                                                                    false
                                                                ],
                                                            ],
                                                            'etiquetas' => [
                                                                '$comment' => 'Id das etiquetas a serem vinculadas a tarefa.',
                                                                'type' => 'array',
                                                                'items' => [
                                                                    'type' => 'number',
                                                                    'examples' => [
                                                                        1,
                                                                        2,
                                                                        3,
                                                                    ]
                                                                ]
                                                            ]
                                                        ]
                                                    ]
                                                ],
                                            ],
                                        ],
                                    ],
                                ]
                            ],
                            'analise_prova_material' => [
                                '$comment' => 'Configurações de análise de prova material.',
                                'type' => 'object',
                                'required' => [
                                    'analises_beneficiario',
                                    'analises_conjuge',
                                ],
                                'properties' => [
                                    'analises_beneficiario' => [
                                        '$comment' => 'Define a lista de análises realizadas para os dados do beneficiário.',
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'required' => [
                                                'analise',
                                                'etiquetas_tarefa_analise_procurador',
                                            ],
                                            'properties' => [
                                                'analise' => [
                                                    '$comment' => 'Nome da classe de análise de requisitos.',
                                                    'type' => 'string',
                                                    'examples' => Version20241014115244::ANALISE_PROVA_MATERIAL_EXAMPLES,
                                                ],
                                                'etiquetas_tarefa_analise_procurador' => [
                                                    '$comment' => 'Lista de configurações de etiquetas da tarefa de análise do procurador.',
                                                    'type' => 'array',
                                                    'items' => [
                                                        '$comment' => 'Configuração de etiquetas da análise',
                                                        'type' => 'object',
                                                        'required' => [
                                                            'passou_analise',
                                                            'etiquetas'
                                                        ],
                                                        'properties' => [
                                                            'passou_analise' => [
                                                                '$comment' => 'Passou na análise?',
                                                                'type' => 'boolean',
                                                                'examples' => [
                                                                    true,
                                                                    false
                                                                ],
                                                            ],
                                                            'etiquetas' => [
                                                                '$comment' => 'Id das etiquetas a serem vinculadas a tarefa.',
                                                                'type' => 'array',
                                                                'items' => [
                                                                    'type' => 'number',
                                                                    'examples' => [
                                                                        1,
                                                                        2,
                                                                        3,
                                                                    ]
                                                                ]
                                                            ]
                                                        ]
                                                    ]
                                                ],
                                            ],
                                        ],
                                    ],
                                    'analises_conjuge' => [
                                        '$comment' => 'Define a lista de análises realizadas para os dados do conjuge.',
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'required' => [
                                                'analise',
                                                'etiquetas_tarefa_analise_procurador',
                                            ],
                                            'properties' => [
                                                'analise' => [
                                                    '$comment' => 'Nome da classe de análise de requisitos.',
                                                    'type' => 'string',
                                                    'examples' => Version20241014115244::ANALISE_PROVA_MATERIAL_EXAMPLES,
                                                ],
                                                'etiquetas_tarefa_analise_procurador' => [
                                                    '$comment' => 'Lista de configurações de etiquetas da tarefa de análise do procurador.',
                                                    'type' => 'array',
                                                    'items' => [
                                                        '$comment' => 'Configuração de etiquetas da análise',
                                                        'type' => 'object',
                                                        'required' => [
                                                            'passou_analise',
                                                            'etiquetas'
                                                        ],
                                                        'properties' => [
                                                            'passou_analise' => [
                                                                '$comment' => 'Passou na análise?',
                                                                'type' => 'boolean',
                                                                'examples' => [
                                                                    true,
                                                                    false
                                                                ],
                                                            ],
                                                            'etiquetas' => [
                                                                '$comment' => 'Id das etiquetas a serem vinculadas a tarefa.',
                                                                'type' => 'array',
                                                                'items' => [
                                                                    'type' => 'number',
                                                                    'examples' => [
                                                                        1,
                                                                        2,
                                                                        3,
                                                                    ]
                                                                ]
                                                            ]
                                                        ]
                                                    ]
                                                ],
                                            ],
                                        ],
                                    ],
                                ]
                            ],
                            'extracoes_conjuge' => [
                                '$comment' => 'Siglas dos dossies do benefíciario para extração de dados do conjuge.',
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'extrator' => [
                                            '$comment' => 'Nome da classe de extração de dados',
                                            'type' => 'string',
                                            'examples' => Version20241014115244::EXTRATORES_CONJUGE_EXAMPLES
                                        ],
                                    ],
                                ],
                            ],
                            'extracoes_dados_cumprimento' => [
                                '$comment' => 'Siglas dos dossies para extração de dados de cumprimento.',
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'extrator' => [
                                            '$comment' => 'Nome da classe de extração de dados',
                                            'type' => 'string',
                                            'examples' => Version20241014115244::EXTRATORES_DADOS_CUMPRIMENTO_EXAMPLES
                                        ],
                                    ],
                                ],
                            ],
                            'analise_negativa_pelo_procurador' => [
                                '$comment' => 'Em caso de análise negativa o Procurador deverá atuar?',
                                'type' => 'boolean',
                                'examples' => [
                                    true,
                                    false,
                                ]
                            ],
                            'analise_positiva_pelo_procurador' => [
                                '$comment' => 'Em caso de análise positiva o Procurador deverá atuar?',
                                'type' => 'boolean',
                                'examples' => [
                                    true,
                                    false,
                                ]
                            ],
                            'dados_cumprimento_sumario' => [
                                '$comment' => 'O preenchimento dos dados de cumprimento será feita automaticamente?',
                                'type' => 'boolean',
                                'examples' => [
                                    true,
                                    false,
                                ]
                            ],
                            'id_pessoa_cumprimento_destino' => [
                                '$comment' => 'Id da pessoa que ira receber o ofício de cumprimento.',
                                'type' => 'number',
                                'examples' => [
                                    1,
                                    2,
                                    3
                                ]
                            ],
                            'termos_documento' => [
                                '$comment' => 'Textos de acordos que serão utilizados nesta solicitação.',
                                'type' => 'object',
                                'required' => [
                                    'requerimento',
                                    'cumprimento'
                                ],
                                'properties' => [
                                    'requerimento' => [
                                        '$comment' => 'Documento de Requerimento',
                                        'type' => 'string'
                                    ],
                                    'cumprimento' => [
                                        '$comment' => 'Documento de Cumprimento',
                                        'type' => 'string'
                                    ]
                                ],
                            ],
                            'dias_prazo_verificacao_cumprimento' => [
                                '$comment' => 'Quantidade de dias após a criação do ofício sem resposta que deve ser criada a tarefa de acompanhamento de cumprimento.',
                                'type' => 'number',
                                'default' => 30,
                                'examples' => [
                                    30,
                                    45,
                                ]
                            ],
                            'dias_final_prazo_tarefa_acompanhamento_cumprimento' => [
                                '$comment' => 'Quantidade de dias para determinação do prazo final da tarefa de acompanhamento de cumprimento.',
                                'type' => 'number',
                                'default' => 10,
                                'examples' => [
                                    10,
                                    15,
                                ]
                            ],
                        ],
                    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                    'invalid' => true,
                ],
                [
                    'nome' => 'supp_core.administrativo_backend.solicitacao_automatizada.salario_maternidade_rural'
                ]
            )
        );
    }

    /**
     * @param Schema $schema
     *
     * @return void
     *
     * @throws Exception
     * @throws SchemaException
     */
    protected function solicitacaoAutomatizadaUp(Schema $schema): void
    {
        $schemaTo = $this->sm->introspectSchema();
        $table = $schemaTo->getTable('ad_sol_automat');
        $table->addColumn(
            'tarefa_acomp_cump_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false
            ]
        );
        $table->addForeignKeyConstraint(
            'ad_tarefa',
            ['tarefa_acomp_cump_id'],
            ['id'],
        );
        $table->addIndex(
            [
                'tarefa_acomp_cump_id'
            ]
        );
        $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaTo);
        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($this->platform->getAlterSchemaSQL($schemaDiff) as $sql) {
            $this->addSql($sql);
        }
    }

    /**
     * @param Schema $schema
     *
     * @return void
     *
     * @throws Exception
     * @throws SchemaException
     */
    public function down(Schema $schema): void
    {
        $this->configModuloDown();
        $this->solicitacaoAutomatizadaDown($schema);
//        $this->debug();
    }

    protected function configModuloDown(): void
    {
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ConfigModulo::class,
                [
                    'dataSchema' => json_encode([
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'supp_core.administrativo_backend.solicitacao_automatizada',
                        '$comment' => 'Configurações para a SolicitacaoAutomatizada.',
                        'type' => 'object',
                        'additionalProperties' => false,
                        'required' => [
                            'especie_tarefa_analise',
                            'especie_tarefa_dados_cumprimento',
                            'especie_tarefa_erro_solicitacao',
                            'especie_atividade_deferimento',
                            'especie_atividade_indeferimento',
                            'especie_atividade_erro_solicitacao',
                            'tipo_documento',
                            'prazo_timeout_verificacao_dossies',
                            'analise_positiva',
                            'analise_negativa',
                            'setor_erro_solicitacao',
                        ],
                        'properties' => [
                            'especie_tarefa_analise' => [
                                '$comment' => 'Nome da espécie da tarefa que será aberta para analise da solicitação automatizada pelo procurador',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'especie_tarefa_dados_cumprimento' => [
                                '$comment' => 'Nome da espécie da tarefa que será aberta para preenchimento dos dados de cumprimento.',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'especie_tarefa_erro_solicitacao' => [
                                '$comment' => 'Nome da espécie da tarefa que será aberta para fluxo de erro.',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'especie_atividade_deferimento' => [
                                '$comment' => 'Espécie da atividade que será monitorada para deferimento',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'especie_atividade_indeferimento' => [
                                '$comment' => 'Espécie da atividade que será monitorada para deferimento',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'especie_atividade_erro_solicitacao' => [
                                '$comment' => 'Espécie da atividade que será monitorada finalização do fluxo de erro.',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'tipo_documento' => [
                                '$comment' => 'Tipo de Documento que será utilizado para juntado na solicitação',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'prazo_timeout_verificacao_dossies' => [
                                '$comment' => 'Prazo em dias para considerar os dossies com status em geração como timeout (ERRO)',
                                'type' => 'number',
                                'examples' => [
                                    12,
                                    15,
                                    20,
                                ],
                            ],
                            'analise_positiva' => [
                                '$comment' => 'Configurações para análise possitiva dos requisitos dos dossies',
                                'type' => 'object',
                                'properties' => [
                                    'etiquetas_processo' => [
                                        '$comment' => 'Nome das etiquetas de sistema que serão adicionadas ao processo.',
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'string',
                                            'examples' => [
                                                'SEM RESTRIÇÕES',
                                                'LIMPO',
                                            ],
                                        ],
                                    ],
                                ],
                                'required' => [
                                    'etiquetas_processo',
                                ],
                            ],
                            'analise_negativa' => [
                                '$comment' => 'Configurações para análise negativa dos requisitos dos dossies',
                                'type' => 'object',
                                'properties' => [
                                    'etiquetas_processo' => [
                                        '$comment' => 'Nome das etiquetas de sistema que serão adicionadas ao processo.',
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'string',
                                            'examples' => [
                                                'SEM RESTRIÇÕES',
                                                'LIMPO',
                                            ],
                                        ],
                                    ],
                                ],
                                'required' => [
                                    'etiquetas_processo',
                                ],
                            ],
                            'setor_erro_solicitacao' => [
                                '$comment' => 'Id do setor onde será aberta a tarefa para tratamento de erro da solicitação automatizada.',
                                'type' => 'number',
                                'examples' => [
                                    1,
                                    2,
                                ],
                            ],
                        ],
                    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                    'invalid' => true,
                ],
                [
                    'nome' => 'supp_core.administrativo_backend.solicitacao_automatizada'
                ]
            )
        );
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ConfigModulo::class,
                [
                    'dataSchema' => json_encode([
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'supp_core.administrativo_backend.solicitacao_automatizada.salario_maternidade_rural',
                        '$comment' => 'Configurações para a SolicitacaoAutomatizada para Salário Maternidade Rural.',
                        'type' => 'object',
                        'additionalProperties' => false,
                        'required' => [
                            'setor_analise_procurador',
                            'setor_dados_cumprimento',
                            'especie_documento_avulso_analise',
                            'especie_documento_avulso_cumprimento',
                            'modelo_documento_avulso_analise',
                            'modelo_documento_avulso_cumprimento',
                            'dossies_beneficiario',
                            'dossies_conjuge',
                            'extracoes_conjuge',
                            'extracoes_dados_cumprimento',
                            'analise_negativa_pelo_procurador',
                            'analise_positiva_pelo_procurador',
                            'dados_cumprimento_sumario',
                            'id_pessoa_cumprimento_destino',
                            'termos_documento',
                        ],
                        'properties' => [
                            'setor_analise_procurador' => [
                                '$comment' => 'Id do setor onde será aberta a tarefa de análise pelo procurador.',
                                'type' => 'number',
                                'examples' => [
                                    1,
                                    2,
                                ],
                            ],
                            'setor_dados_cumprimento' => [
                                '$comment' => 'Id do setor onde será aberta a tarefa para preenchimento dos dados de cumprimento.',
                                'type' => 'number',
                                'examples' => [
                                    1,
                                    2,
                                ],
                            ],
                            'especie_documento_avulso_analise' => [
                                '$comment' => 'Nome da espécie do documento avulso do ofício de análise.',
                                'type' => 'string',
                                'examples' => [
                                    'SOLICITACAO CUMPRIMENTO DO INSS',
                                ],
                            ],
                            'especie_documento_avulso_cumprimento' => [
                                '$comment' => 'Nome da espécie do documento avulso do ofício de cumprimento.',
                                'type' => 'string',
                                'examples' => [
                                    'SOLICITACAO CUMPRIMENTO DO INSS',
                                ],
                            ],
                            'modelo_documento_avulso_analise' => [
                                '$comment' => 'Nome do modelo do ofício de análise.',
                                'type' => 'string',
                                'examples' => [
                                    'ANALISE SOLICITAÇÃO AUTOMATICA',
                                ],
                            ],
                            'modelo_documento_avulso_cumprimento' => [
                                '$comment' => 'Nome do modelo do ofício de cumprimento.',
                                'type' => 'string',
                                'examples' => [
                                    'modelo_documento_avulso_cumprimento',
                                ],
                            ],
                            'dossies_beneficiario' => [
                                '$comment' => 'Define a lista de dossiês que serão solicitados para o beneficiário.',
                                'type' => 'array',
                                'items' => [
                                    'type' => 'string',
                                    'examples' => [
                                        'PAPGET',
                                        'DOSPREV',
                                        'DOSLABRA',
                                        'DOSOC',
                                    ]
                                ]
                            ],
                            'dossies_conjuge' => [
                                '$comment' => 'Define a lista de dossiês que serão solicitados para o conjuge.',
                                'type' => 'array',
                                'items' => [
                                    'type' => 'string',
                                    'examples' => [
                                        'PAPGET',
                                        'DOSPREV',
                                        'DOSLABRA',
                                        'DOSOC',
                                    ]
                                ]
                            ],
                            'analise_inicial' => [
                                '$comment' => 'Configurações de análise inicial de requisitos.',
                                'type' => 'object',
                                'required' => [
                                    'analises_beneficiario',
                                    'analises_conjuge',
                                ],
                                'properties' => [
                                    'analises_beneficiario' => [
                                        '$comment' => 'Define a lista de análises realizadas para os dados do beneficiário.',
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'required' => [
                                                'analise',
                                                'etiquetas_tarefa_analise_procurador',
                                            ],
                                            'properties' => [
                                                'analise' => [
                                                    '$comment' => 'Nome da classe de análise de requisitos.',
                                                    'type' => 'string',
                                                    'examples' => Version20241014115244::ANALISE_INICIAL_EXAMPLES,
                                                ],
                                                'etiquetas_tarefa_analise_procurador' => [
                                                    '$comment' => 'Lista de configurações de etiquetas da tarefa de análise do procurador.',
                                                    'type' => 'array',
                                                    'items' => [
                                                        '$comment' => 'Configuração de etiquetas da análise',
                                                        'type' => 'object',
                                                        'required' => [
                                                            'passou_analise',
                                                            'etiquetas'
                                                        ],
                                                        'properties' => [
                                                            'passou_analise' => [
                                                                '$comment' => 'Passou na análise?',
                                                                'type' => 'boolean',
                                                                'examples' => [
                                                                    true,
                                                                    false
                                                                ],
                                                            ],
                                                            'etiquetas' => [
                                                                '$comment' => 'Id das etiquetas a serem vinculadas a tarefa.',
                                                                'type' => 'array',
                                                                'items' => [
                                                                    'type' => 'number',
                                                                    'examples' => [
                                                                        1,
                                                                        2,
                                                                        3,
                                                                    ]
                                                                ]
                                                            ]
                                                        ]
                                                    ]
                                                ],
                                            ],
                                        ],
                                    ],
                                    'analises_conjuge' => [
                                        '$comment' => 'Define a lista de análises realizadas para os dados do conjuge.',
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'required' => [
                                                'analise',
                                                'etiquetas_tarefa_analise_procurador',
                                            ],
                                            'properties' => [
                                                'analise' => [
                                                    '$comment' => 'Nome da classe de análise de requisitos.',
                                                    'type' => 'string',
                                                    'examples' => Version20241014115244::ANALISE_INICIAL_EXAMPLES,
                                                ],
                                                'etiquetas_tarefa_analise_procurador' => [
                                                    '$comment' => 'Lista de configurações de etiquetas da tarefa de análise do procurador.',
                                                    'type' => 'array',
                                                    'items' => [
                                                        '$comment' => 'Configuração de etiquetas da análise',
                                                        'type' => 'object',
                                                        'required' => [
                                                            'passou_analise',
                                                            'etiquetas'
                                                        ],
                                                        'properties' => [
                                                            'passou_analise' => [
                                                                '$comment' => 'Passou na análise?',
                                                                'type' => 'boolean',
                                                                'examples' => [
                                                                    true,
                                                                    false
                                                                ],
                                                            ],
                                                            'etiquetas' => [
                                                                '$comment' => 'Id das etiquetas a serem vinculadas a tarefa.',
                                                                'type' => 'array',
                                                                'items' => [
                                                                    'type' => 'number',
                                                                    'examples' => [
                                                                        1,
                                                                        2,
                                                                        3,
                                                                    ]
                                                                ]
                                                            ]
                                                        ]
                                                    ]
                                                ],
                                            ],
                                        ],
                                    ],
                                ]
                            ],
                            'analise_prova_material' => [
                                '$comment' => 'Configurações de análise de prova material.',
                                'type' => 'object',
                                'required' => [
                                    'analises_beneficiario',
                                    'analises_conjuge',
                                ],
                                'properties' => [
                                    'analises_beneficiario' => [
                                        '$comment' => 'Define a lista de análises realizadas para os dados do beneficiário.',
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'required' => [
                                                'analise',
                                                'etiquetas_tarefa_analise_procurador',
                                            ],
                                            'properties' => [
                                                'analise' => [
                                                    '$comment' => 'Nome da classe de análise de requisitos.',
                                                    'type' => 'string',
                                                    'examples' => Version20241014115244::ANALISE_PROVA_MATERIAL_EXAMPLES,
                                                ],
                                                'etiquetas_tarefa_analise_procurador' => [
                                                    '$comment' => 'Lista de configurações de etiquetas da tarefa de análise do procurador.',
                                                    'type' => 'array',
                                                    'items' => [
                                                        '$comment' => 'Configuração de etiquetas da análise',
                                                        'type' => 'object',
                                                        'required' => [
                                                            'passou_analise',
                                                            'etiquetas'
                                                        ],
                                                        'properties' => [
                                                            'passou_analise' => [
                                                                '$comment' => 'Passou na análise?',
                                                                'type' => 'boolean',
                                                                'examples' => [
                                                                    true,
                                                                    false
                                                                ],
                                                            ],
                                                            'etiquetas' => [
                                                                '$comment' => 'Id das etiquetas a serem vinculadas a tarefa.',
                                                                'type' => 'array',
                                                                'items' => [
                                                                    'type' => 'number',
                                                                    'examples' => [
                                                                        1,
                                                                        2,
                                                                        3,
                                                                    ]
                                                                ]
                                                            ]
                                                        ]
                                                    ]
                                                ],
                                            ],
                                        ],
                                    ],
                                    'analises_conjuge' => [
                                        '$comment' => 'Define a lista de análises realizadas para os dados do conjuge.',
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'required' => [
                                                'analise',
                                                'etiquetas_tarefa_analise_procurador',
                                            ],
                                            'properties' => [
                                                'analise' => [
                                                    '$comment' => 'Nome da classe de análise de requisitos.',
                                                    'type' => 'string',
                                                    'examples' => Version20241014115244::ANALISE_PROVA_MATERIAL_EXAMPLES,
                                                ],
                                                'etiquetas_tarefa_analise_procurador' => [
                                                    '$comment' => 'Lista de configurações de etiquetas da tarefa de análise do procurador.',
                                                    'type' => 'array',
                                                    'items' => [
                                                        '$comment' => 'Configuração de etiquetas da análise',
                                                        'type' => 'object',
                                                        'required' => [
                                                            'passou_analise',
                                                            'etiquetas'
                                                        ],
                                                        'properties' => [
                                                            'passou_analise' => [
                                                                '$comment' => 'Passou na análise?',
                                                                'type' => 'boolean',
                                                                'examples' => [
                                                                    true,
                                                                    false
                                                                ],
                                                            ],
                                                            'etiquetas' => [
                                                                '$comment' => 'Id das etiquetas a serem vinculadas a tarefa.',
                                                                'type' => 'array',
                                                                'items' => [
                                                                    'type' => 'number',
                                                                    'examples' => [
                                                                        1,
                                                                        2,
                                                                        3,
                                                                    ]
                                                                ]
                                                            ]
                                                        ]
                                                    ]
                                                ],
                                            ],
                                        ],
                                    ],
                                ]
                            ],
                            'extracoes_conjuge' => [
                                '$comment' => 'Siglas dos dossies do benefíciario para extração de dados do conjuge.',
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'extrator' => [
                                            '$comment' => 'Nome da classe de extração de dados',
                                            'type' => 'string',
                                            'examples' => Version20241014115244::EXTRATORES_CONJUGE_EXAMPLES
                                        ],
                                    ],
                                ],
                            ],
                            'extracoes_dados_cumprimento' => [
                                '$comment' => 'Siglas dos dossies para extração de dados de cumprimento.',
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'extrator' => [
                                            '$comment' => 'Nome da classe de extração de dados',
                                            'type' => 'string',
                                            'examples' => Version20241014115244::EXTRATORES_DADOS_CUMPRIMENTO_EXAMPLES
                                        ],
                                    ],
                                ],
                            ],
                            'analise_negativa_pelo_procurador' => [
                                '$comment' => 'Em caso de análise negativa o Procurador deverá atuar?',
                                'type' => 'boolean',
                                'examples' => [
                                    true,
                                    false,
                                ]
                            ],
                            'analise_positiva_pelo_procurador' => [
                                '$comment' => 'Em caso de análise positiva o Procurador deverá atuar?',
                                'type' => 'boolean',
                                'examples' => [
                                    true,
                                    false,
                                ]
                            ],
                            'dados_cumprimento_sumario' => [
                                '$comment' => 'O preenchimento dos dados de cumprimento será feita automaticamente?',
                                'type' => 'boolean',
                                'examples' => [
                                    true,
                                    false,
                                ]
                            ],
                            'id_pessoa_cumprimento_destino' => [
                                '$comment' => 'Id da pessoa que ira receber o ofício de cumprimento.',
                                'type' => 'number',
                                'examples' => [
                                    1,
                                    2,
                                    3
                                ]
                            ],
                            'termos_documento' => [
                                '$comment' => 'Textos de acordos que serão utilizados nesta solicitação.',
                                'type' => 'object',
                                'required' => [
                                    'requerimento',
                                    'cumprimento'
                                ],
                                'properties' => [
                                    'requerimento' => [
                                        '$comment' => 'Documento de Requerimento',
                                        'type' => 'string'
                                    ],
                                    'cumprimento' => [
                                        '$comment' => 'Documento de Cumprimento',
                                        'type' => 'string'
                                    ]
                                ],
                            ],
                        ],
                    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                    'invalid' => true,
                ],
                [
                    'nome' => 'supp_core.administrativo_backend.solicitacao_automatizada.salario_maternidade_rural'
                ]
            )
        );
    }

    /**
     * @param Schema $schema
     *
     * @return void
     *
     * @throws Exception
     * @throws SchemaException
     */
    protected function solicitacaoAutomatizadaDown(Schema $schema): void
    {
        $schemaTo = $this->sm->introspectSchema();
        $table = $schemaTo->getTable('ad_sol_automat');
        foreach ($table->getForeignKeys() as $fk) {
            if ($fk->getForeignTableName() === 'ad_tarefa'
                && !array_diff(['tarefa_acomp_cump_id'], $fk->getLocalColumns())) {
                $table->removeForeignKey($fk->getName());
            }
        }
        $table->dropColumn('tarefa_acomp_cump_id');
        $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaTo);
        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($this->platform->getAlterSchemaSQL($schemaDiff) as $sql) {
            $this->addSql($sql);
        }
    }
}
