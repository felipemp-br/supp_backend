<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\Mapping\MappingException;
use SuppCore\AdministrativoBackend\Entity\ConfigModulo;
use SuppCore\AdministrativoBackend\Migrations\Helpers\MigrationHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Version20241121141127.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
final class Version20241121141127 extends AbstractMigration
{
    private ContainerInterface $container;
    private MigrationHelper $migrationHelper;

    /**
     * @return bool
     */
    public function isTransactional(): bool
    {
        return false;
    }

    /**
     * @param ContainerInterface $container
     *
     * @return void
     */
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
        $this->migrationHelper = $this->container->get(MigrationHelper::class);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Atualiza as configurações do módulo de Salário Maternidade Rural';
    }

    /**
     * @param Schema $schema
     *
     * @return void
     */
    public function up(Schema $schema): void
    {
        $this->configModuloUp();
//        dd(implode('; ', array_map(fn($q) => $q->getStatement(), $this->getSql())).';');

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
     */
    public function down(Schema $schema): void
    {
        $this->configModuloDown();
//        dd(implode('; ', array_map(fn($q) => $q->getStatement(), $this->getSql())).';');

    }

    private function configModuloDown(): void
    {
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
                            'documento_acordo',
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
                            'documento_acordo' => [
                                '$comment' => 'Texto do acordo que será utilizado nesta solicitação.',
                                'type' => 'string',
                                'examples' => [
                                    'Dados do acordo.'
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
}
