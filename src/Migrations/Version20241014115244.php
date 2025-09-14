<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use SuppCore\AdministrativoBackend\Entity\ConfigModulo;
use SuppCore\AdministrativoBackend\Entity\Formulario;
use SuppCore\AdministrativoBackend\Entity\Modulo;
use SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Entity\TipoSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Migrations\Helpers\MigrationHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241014115244 extends AbstractMigration
{
    const ANALISE_INICIAL_EXAMPLES = [
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseInicialAeronave',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseInicialAusenciaRequerimentoAdministrativo',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseInicialBeneficioIncompativelBeneficiario',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseInicialBensTSE',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseInicialEmbarcacao',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseInicialEmpregoBeneficiario',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseInicialEmpregoConjuge',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseInicialEmpregoPublicoBeneficiario',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseInicialEmpregoPublicoConjuge',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseInicialEmpresa',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseInicialImovelRural',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseInicialImovelSP',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseInicialLitispendenciaBeneficiario',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseInicialPagamentoAnteriorBeneficiario',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseInicialPrescricaoBeneficiario',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseInicialVeiculo',
    ];
    const ANALISE_PROVA_MATERIAL_EXAMPLES = [
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseProvaMaterialDossieLabraBeneficiario',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseProvaMaterialDossieLabraConjuge',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseProvaMaterialDossieSocialBeneficiario',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseProvaMaterialPrevidenciarioEnderecoRuralBeneficiario',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\AnaliseProvaMaterialPrevidenciarioEnderecoRuralConjuge',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\SuppCoreAnaliseProvaMaterialPrevidenciarioSeguradoEspecialBeneficiario',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Analises\\\\SuppCoreAnaliseProvaMaterialPrevidenciarioSeguradoEspecialConjuge'
    ];

    const EXTRATORES_CONJUGE_EXAMPLES = [
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Extratores\\\\ExtratorDadosConjugeDossieSocial',
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Extratores\\\\ExtratorDadosConjugeDossieLabra',
    ];

    const EXTRATORES_DADOS_CUMPRIMENTO_EXAMPLES = [
        'SuppCore\\\\AdministrativoBackend\\\\Helpers\\\\SolicitacaoAutomatizada\\\\Drivers\\\\SalarioMaternidadeRural\\\\Extratores\\\\ExtratorDadosCumprimento',
    ];

    private ContainerInterface $container;
    private MigrationHelper $migrationHelper;

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
        return 'Solicitação automatizada auxilio maternidade rural';
    }

    /**
     * @param Schema $schema
     *
     * @return void
     * @throws Exception
     */
    public function up(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $this->formularioUp();
        $this->configModuloUp();
        $this->tipoSolicitacaoAutomatizadaUp($schemaTo);
        $this->solicitacaoAutomatizadaUp($schemaTo);
        $this->dossieUp($schemaTo);

        $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaTo);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schemaDiff->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }

        $this->tipoSolicitacaoAutomatizadaPostUp();

//        dd(implode('; ', array_map(fn(Query $q) => $q->getStatement(), $this->getSql())).';');
    }

    /**
     * @return void
     */
    private function formularioUp(): void
    {
        $formulario = (new Formulario())
            ->setNome('Formulário de Solicitação Automatizada')
            ->setSigla('formulario_solicitacao_automatizada')
            ->setAtivo(true)
            ->setIa(false)
            ->setAceitaJsonInvalido(false)
            ->setDataSchema(
                json_encode(
                    [
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'https://supersapiensbackend.agu.gov.br/v1/administrativo/formulario/schema/solicitacao_automatizada',
                        'description' => 'Schema de Formulário de Solicitação Automatizada',
                        'type' => 'object',
                        'additionalProperties' => true
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );

        $this->addSql($this->migrationHelper->generateInsertSQL($formulario));

        $formulario = (new Formulario())
            ->setNome('FORMULÁRIO DE REQUERIMENTO PACIFICA SALÁRIO MATERNIDADE RURAL')
            ->setSigla('requerimento.pacifica.salario_maternidade_rural')
            ->setTemplate(
            'Resources/SolicitacaoAutomatizada/SalarioMaternidadeRural/requerimento.html.twig'
            )
            ->setDataSchema(
            json_encode(
                [
                    '$schema' => 'http://json-schema.org/draft-07/schema#',
                    '$id' => 'supp_core.administrativo_backend.formulario.pacifica.salario_maternidade_rural',
                    'title' => 'FORMULARIO SALARIO MATERINIDADE RURAL PACIFICA',
                    'type' => 'object',
                    'required' => [
                        'cpfCrianca',
                        'cpfBeneficiario',
                        'numeroBeneficioNegado',
                        'dataRequerimentoAdministrativo',
                    ],
                    'properties' => [
                        'cpfCrianca' => [
                            'title' => 'CPF Criança',
                            'type' => ['string', 'null'],
                            'pattern' => '^[0-9]{3}.?[0-9]{3}.?[0-9]{3}-?[0-9]{2}$',
                        ],
                        'cpfBeneficiario' => [
                            'title' => 'CPF Beneficiario',
                            'type' => 'string',
                            'pattern' => '^[0-9]{3}.?[0-9]{3}.?[0-9]{3}-?[0-9]{2}$',
                        ],
                        'numeroBeneficioNegado' => [
                            'title' => 'Numero Beneficio',
                            'type' => 'string',
                            'minLength' => 1,
                            'pattern' => '^([0-9]{1}.)?([0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{1})$',
                        ],
                        'dataRequerimentoAdministrativo' => [
                            'title' => 'Data Requerimento Administrativo',
                            'type' => 'string',
                            'format' => 'date',
                        ],
                        'nomeConjuge' => [
                            'title' => 'Nome Conjuge',
                            'type' => ['string', 'null'],
                            'minLength' => 1,
                        ],
                        'cpfConjuge' => [
                            'title' => 'CPF Conjuge',
                            'type' => ['string', 'null'],
                            'pattern' => '^[0-9]{3}.?[0-9]{3}.?[0-9]{3}-?[0-9]{2}$',
                        ],
                    ],
                ],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );

        $this->addSql($this->migrationHelper->generateInsertSQL($formulario));
    }

    /**
     * @return void
     */
    private function configModuloUp(): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $moduloAdministrativo = $em
            ->getRepository(Modulo::class)
            ->findOneBy(['sigla' => 'AD']);

        $configModulo = new ConfigModulo();
        $configModulo->setModulo($moduloAdministrativo);
        $configModulo->setNome('supp_core.administrativo_backend.solicitacao_automatizada');
        $configModulo->setDescricao('CONFIGURAÇÕES RELATIVAS À SOLICITACAO AUTOMATIZADA');
        $configModulo->setDataType('json');
        $configModulo->setDataSchema(
            json_encode([
                '$schema' => 'http://json-schema.org/draft-07/schema#',
                '$id' => 'supp_core.administrativo_backend.solicitacao_automatizada',
                '$comment' => 'Configurações para a SolicitacaoAutomatizada.',
                'type' => 'object',
                'additionalProperties' => false,
                'required' => [
                    'especie_tarefa_analise',
                    'especie_tarefa_dados_cumprimento',
                    'especie_atividade_deferimento',
                    'especie_atividade_indeferimento',
                    'tipo_documento',
                    'prazo_timeout_verificacao_dossies',
                    'analise_positiva',
                    'analise_negativa',
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
                            20
                        ]
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
                                    ]
                                ]
                            ]
                        ],
                        'required' => [
                            'etiquetas_processo',
                        ]
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
                                    ]
                                ]
                            ],
                        ],
                        'required' => [
                            'etiquetas_processo',
                        ]
                    ]
                ],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        $this->addSql($this->migrationHelper->generateInsertSQL($configModulo));

        $configModulo = new ConfigModulo();
        $configModulo->setModulo($moduloAdministrativo);
        $configModulo->setNome('supp_core.administrativo_backend.solicitacao_automatizada.advogados_dpu');
        $configModulo->setDescricao(
            'CONFIGURAÇÃO RELATIVA À SOLICITAÇÃO AUTOMATIZADA COM LISTA DE ADVOGADOS DA DPU'
        );
        $configModulo->setDataType('json');
        $configModulo->setDataSchema(
            json_encode([
                '$schema' => 'http://json-schema.org/draft-07/schema#',
                '$id' => 'supp_core.administrativo_backend.solicitacao_automatizada.advogados_dpu',
                '$comment' => 'Configuração relativa à solicitação automatizada',
                'type' => 'array',
                'minItems' => 1,
                'items' => [
                    '$comment' => 'Lista do CPF de advogados da DPU para validação durante cadastramento.',
                    'type' => 'string',
                    'minLength' => 1,
                ],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        $this->addSql($this->migrationHelper->generateInsertSQL($configModulo));

        $configModulo = new ConfigModulo();
        $configModulo->setModulo($moduloAdministrativo);
        $configModulo->setNome('supp_core.administrativo_backend.solicitacao_automatizada.salario_maternidade_rural');
        $configModulo->setDescricao(
            'CONFIGURAÇÕES RELATIVAS À SOLICITACAO AUTOMATIZADA PARA SALÁRIO MATERNIDADE RURAL'
        );
        $configModulo->setDataType('json');
        $configModulo->setDataSchema(
            json_encode([
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
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        $this->addSql($this->migrationHelper->generateInsertSQL($configModulo));
    }

    /**
     * @param Schema $schemaTo
     *
     * @return void
     */
    private function tipoSolicitacaoAutomatizadaUp(Schema $schemaTo): void
    {
         // Adicionando a criação da sequência antes de criar a tabela - 28042025 - Marco 
        $this->addSql('CREATE SEQUENCE ad_tipo_sol_automat_id_seq START 1 INCREMENT 1');

        $em = $this->container->get('doctrine.orm.entity_manager');
        /** @var ClassMetadataInfo $metadata */
        $metadata = $em->getClassMetadata(TipoSolicitacaoAutomatizada::class);
        $table = $schemaTo->createTable('ad_tipo_sol_automat');
        $table->addColumn(
            'id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
                'autoincrement' => $metadata->generatorType === ClassMetadataInfo::GENERATOR_TYPE_IDENTITY,
            ]
        );

        $table->addColumn(
            'criado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'atualizado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'apagado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'criado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'atualizado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'apagado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'uuid',
            Type::getType('guid')->getName(),
            [
                'notnull' => true,
            ]
        );

        $table->addColumn(
            'sigla',
            Type::getType('string')->getName(),
            [
                'notnull' => true,
                'length' => 25,
            ]
        );

        $table->addColumn(
            'descricao',
            Type::getType('string')->getName(),
            [
                'notnull' => true,
                'length' => 255,
            ]
        );

        $table->addColumn(
            'ativo',
            Type::getType('boolean')->getName(),
            [
                'notnull' => true,
            ]
        );

        $table->addColumn(
            'formulario_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
            ]
        );

        $table
            ->addUniqueIndex(
                [
                    'uuid',
                ]
            )
            ->setPrimaryKey(['id'])
            ->addForeignKeyConstraint(
                'ad_usuario',
                ['criado_por'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_usuario',
                ['atualizado_por'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_usuario',
                ['apagado_por'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_formulario',
                ['formulario_id'],
                ['id']
            )
        ;
    }

    /**
     * @return void
     */
    private function tipoSolicitacaoAutomatizadaPostUp(): void
    {
        $tipoSolicitacaoAutomatizada = (new TipoSolicitacaoAutomatizada())
            ->setSigla('PACIFICA_SAL_MAT_RURAL')
            ->setDescricao('PACIFICA - SALARIO MATERNIDADE RURAL');
        $this->addSql(
            $this->migrationHelper->generateInsertSQL(
                $tipoSolicitacaoAutomatizada,
                [
                    [
                        'column' => 'formulario_id',
                        'value' => sprintf(
                            '(SELECT id from ad_formulario where sigla = \'%s\')',
                            'requerimento.pacifica.salario_maternidade_rural'
                        )
                    ]
                ]
            )
        );
    }

    /**
     * @param Schema $schemaTo
     *
     * @return void
     */
    private function solicitacaoAutomatizadaUp(Schema $schemaTo): void
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        /** @var ClassMetadataInfo $metadata */
        $metadata = $em->getClassMetadata(SolicitacaoAutomatizada::class);
        $table = $schemaTo->createTable('ad_sol_automat');

        $table->addColumn(
            'id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
                'autoincrement' => $metadata->generatorType === ClassMetadataInfo::GENERATOR_TYPE_IDENTITY,
            ]
        );

        $table->addColumn(
            'criado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'atualizado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'apagado_por',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'criado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'atualizado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'apagado_em',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'uuid',
            Type::getType('guid')->getName(),
            [
                'notnull' => true,
            ]
        );

        $table->addColumn(
            'processo_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
            ]
        );

        $table->addColumn(
            'tarefa_analise_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'tarefa_dados_cump_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'dados_formulario_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'beneficiario_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
            ]
        );

        $table->addColumn(
            'tipo_sol_automat_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
            ]
        );

        $table->addColumn(
            'usuario_responsavel_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'setor_responsavel_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'resultado_solicitacao_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'observacao',
            Type::getType('string')->getName(),
            [
                'notnull' => false,
                'length' => 255,
            ]
        );

        $table->addColumn(
            'status',
            Type::getType('string')->getName(),
            [
                'notnull' => true,
                'length' => 255,
            ]
        );

        $table->addColumn(
            'status_exibicao',
            Type::getType('string')->getName(),
            [
                'notnull' => true,
                'length' => 255,
            ]
        );

        $table->addColumn(
            'urgente',
            Type::getType('boolean')->getName(),
            [
                'notnull' => true,
            ]
        );

        $table->addColumn(
            'sugestao_deferimento',
            Type::getType('boolean')->getName(),
            [
                'notnull' => true,
            ]
        );

        $table->addColumn(
            'erro_analise_sumaria',
            Type::getType('boolean')->getName(),
            [
                'notnull' => true,
            ]
        );

        $table->addColumn(
            'protocolo_externo',
            Type::getType('boolean')->getName(),
            [
                'notnull' => true,
            ]
        );

        $table->addColumn(
            'dossies_necessarios',
            Type::getType('text')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'analises_dossies',
            Type::getType('text')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'dados_tipo_solicitacao',
            Type::getType('text')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'dados_cumprimento',
            Type::getType('text')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table
            ->addUniqueIndex(
                [
                    'uuid',
                ]
            )
            ->setPrimaryKey(['id'])
            ->addForeignKeyConstraint(
                'ad_usuario',
                ['criado_por'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_usuario',
                ['atualizado_por'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_usuario',
                ['apagado_por'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_processo',
                ['processo_id'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_tarefa',
                ['tarefa_analise_id'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_tarefa',
                ['tarefa_dados_cump_id'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_dados_formulario',
                ['dados_formulario_id'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_pessoa',
                ['beneficiario_id'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_tipo_sol_automat',
                ['tipo_sol_automat_id'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_usuario',
                ['usuario_responsavel_id'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_setor',
                ['setor_responsavel_id'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_documento',
                ['resultado_solicitacao_id'],
                ['id']
            )
        ;
    }

    /**
     * @param Schema $schemaTo
     *
     * @return void
     */
    private function dossieUp(Schema $schemaTo): void
    {
        $table = $schemaTo->getTable('ad_dossie');

        $table->addColumn(
            'solicitacao_automatizada_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table
            ->addIndex(
                [
                    'solicitacao_automatizada_id',
                ]
            );

        $table
            ->addForeignKeyConstraint(
                'ad_sol_automat',
                ['solicitacao_automatizada_id'],
                ['id']
            );
    }

    /**
     * @param Schema $schema
     *
     * @return void
     */
    public function down(Schema $schema): void
    {
        $schemaTo = $this->sm->createSchema();
        $this->solicitacaoAutomatizadaDown($schemaTo);
        $this->tipoSolicitacaoAutomatizadaDown($schemaTo);
        $this->dossieDown($schemaTo);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schema->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }

        $this->dadosFormularioDown();
        $this->formularioDown();
        $this->configModuloDown();

//        dd(implode('; ', array_map(fn(Query $q) => $q->getStatement(), $this->getSql())).';');
    }

    /**
     * @param Schema $schemaTo
     *
     * @return void
     */
    private function dossieDown(Schema $schemaTo): void
    {
        $table = $schemaTo->getTable('ad_dossie');
        foreach ($table->getIndexes() as $index) {
            if ($index->getColumns() === ['solicitacao_automatizada_id']) {
                $table->dropIndex($index->getName());
            }
        }
        $table->dropColumn('solicitacao_automatizada_id');
    }

    /**
     * @param Schema $schemaTo
     *
     * @return void
     */
    private function tipoSolicitacaoAutomatizadaDown(Schema $schemaTo): void
    {

        $table = $schemaTo->getTable('ad_tipo_sol_automat');
        foreach ($table->getIndexes() as $index) {
            $table->dropIndex($index->getName());
        }
        foreach ($table->getForeignKeys() as $fk) {
            $table->removeForeignKey($fk->getName());
        }
        foreach ($table->getUniqueConstraints() as $uc) {
            $table->removeUniqueConstraint($uc->getName());
        }

        $seqName = sprintf(
            '%s_SEQ',
            mb_strtoupper($table->getName())
        );
        foreach ($schemaTo->getSequences() as $sequence) {
            if (mb_strtoupper($sequence->getName()) === $seqName) {
                $schemaTo->dropSequence($sequence->getName());
            }
        }
        $schemaTo->dropTable($table->getName());
    }

    /**
     * @param Schema $schemaTo
     *
     * @return void
     */
    private function solicitacaoAutomatizadaDown(Schema $schemaTo): void
    {
        $table = $schemaTo->getTable('ad_sol_automat');
        foreach ($table->getIndexes() as $index) {
            $table->dropIndex($index->getName());
        }
        foreach ($table->getForeignKeys() as $fk) {
            $table->removeForeignKey($fk->getName());
        }
        foreach ($table->getUniqueConstraints() as $uc) {
            $table->removeUniqueConstraint($uc->getName());
        }
        $seqName = sprintf(
            '%s_SEQ',
            mb_strtoupper($table->getName())
        );
        foreach ($schemaTo->getSequences() as $sequence) {
            if (mb_strtoupper($sequence->getName()) === $seqName) {
                $schemaTo->dropSequence($sequence->getName());
            }
        }
        $schemaTo->dropTable($table->getName());
    }

    /**
     * @return void
     */
    private function dadosFormularioDown(): void
    {
        $this->addSql(
            sprintf(
                'DELETE FROM ad_dados_formulario WHERE formulario_id = %s',
                '(SELECT id FROM ad_formulario WHERE sigla = \'formulario_solicitacao_automatizada\')',
            )
        );
    }

    /**
     * @return void
     */
    private function formularioDown(): void
    {
        $this->addSql(
            sprintf(
                'DELETE FROM ad_formulario WHERE sigla in (%s)',
                implode(
                    ', ',
                    [
                        '\'formulario_solicitacao_automatizada\'',
                        '\'requerimento.pacifica.salario_maternidade_rural\'',
                    ]
                )
            )
        );
    }

    /**
     * @return void
     */
    private function configModuloDown(): void
    {
        $this->addSql(
            sprintf(
                'DELETE FROM ad_config_modulo WHERE sigla IN (%s)',
                implode(
                    ', ',
                    [
                        '\'supp_core.administrativo_backend.solicitacao_automatizada\'',
                        '\'supp_core.administrativo_backend.solicitacao_automatizada.advogados_dpu\'',
                        '\'supp_core.administrativo_backend.solicitacao_automatizada.advogados_dpu\'',
                        '\'supp_core.administrativo_backend.solicitacao_automatizada.salario_maternidade_rural\'',
                    ]
                )
            )
        );
    }

    public function isTransactional(): bool
    {
        return false;
    }


}
