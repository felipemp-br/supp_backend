<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use SuppCore\AdministrativoBackend\Entity\Formulario;
use SuppCore\AdministrativoBackend\Migrations\Helpers\MigrationHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241217181801 extends AbstractMigration
{
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

    public function getDescription(): string
    {
        return 'Alterações de dataSchemas de formulários de requerimento de transação';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->formularioUp();
    }

    private function formularioUp(): void {
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                Formulario::class,
                [
                    'dataSchema' => json_encode(
                        [
                            '$schema' => 'http://json-schema.org/draft-07/schema#',
                            '$id' => 'supp_core.administrativo_backend.requerimento_transacao_adesao_pgf',
                            'title' => 'FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO EXTRAORDINÁRIA PGF',
                            'type' => 'object',
                            'required' => [
                                'setorAtual',
                                'tipoRequerimento',
                                'tipoPessoa',
                                'cpfCnpj',
                                'nomeRazaoSocial',
                                'email',
                                'confirmeEmail',
                                'tipoTelefone',
                                'telefone',
                                'cep',
                                'endereco',
                                'complemento',
                                'estado',
                                'municipio',
                                'tipoSolicitacao',
                                'credor',
                                'ciente'
                            ],
                            'properties' => [
                                'setorAtual' => [
                                    'title' => 'Setor atual',
                                    'type' => 'integer',
                                    'minValue' => 0
                                ],
                                'tipoRequerimento' => [
                                    'title' => 'Tipo requerimento',
                                    'type' => 'string',
                                    'enum' => [
                                        'requerimento_transacao_adesao_pgf',
                                        'requerimento_transacao_informacoes_pgf'
                                    ]
                                ],
                                'tipoPessoa' => [
                                    'title' => 'Tipo pessoa',
                                    'type' => 'string',
                                    'enum' => [
                                        'fisica',
                                        'juridica'
                                    ]
                                ],
                                'nomeRazaoSocial' => [
                                    'title' => 'Nome razão social',
                                    'type' => 'string',
                                    'minLength' => 1
                                ],
                                'email' => [
                                    'title' => 'E-mail',
                                    'type' => 'string',
                                    'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                    'format' => 'email'
                                ],
                                'confirmeEmail' => [
                                    'title' => 'Confirme e-mail',
                                    'type' => 'string',
                                    'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                    'format' => 'email'
                                ],
                                'tipoTelefone' => [
                                    'title' => 'Tipo telefone',
                                    'type' => 'string',
                                    'enum' => [
                                        'fixo',
                                        'celular',
                                        'nao_informado'
                                    ]
                                ],
                                'cep' => [
                                    'title' => 'CEP',
                                    'type' => 'string',
                                    'pattern' => '^[0-9]{2}\\.?[0-9]{3}[-]?[0-9]{3}$'
                                ],
                                'endereco' => [
                                    'title' => 'Endereço',
                                    'type' => 'string',
                                    'minLength' => 1
                                ],
                                'complemento' => [
                                    'title' => 'Complemento',
                                    'type' => [
                                        'string',
                                        'null'
                                    ]
                                ],
                                'estado' => [
                                    'title' => 'Estado',
                                    'type' => 'integer',
                                    'minValue' => 0
                                ],
                                'municipio' => [
                                    'title' => 'Município',
                                    'type' => 'integer',
                                    'minValue' => 0
                                ],
                                'credor' => [
                                    'title' => 'Credor',
                                    'type' => 'string',
                                    'minLength' => 1
                                ],
                                'tipoSolicitacao' => [
                                    'title' => 'Tipo solicitação',
                                    'type' => 'string',
                                    'enum' => [
                                        'transacao_adesao_pgf',
                                        'transacao_informacoes_gerais'
                                    ]
                                ],
                                'cnpjsFiliais' => [
                                    'title' => 'CNPJs Filiais',
                                    'type' => [
                                        'string',
                                        'null'
                                    ]
                                ],
                                'ciente' => [
                                    'title' => 'Ciente',
                                    'type' => 'boolean'
                                ]
                            ],
                            'allOf' => [
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoRequerimento' => [
                                                'const' => 'requerimento_transacao_adesao_pgf'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'devedorPessoaFisicaEDemais' => [
                                            'title' => 'Devedor pessoa fisica e MEI ou demais organizações sociedade civil',
                                            'type' => 'boolean'
                                        ],
                                        'incluirCreditosInscritosDividaAtiva' => [
                                            'title' => 'Incluir créditos inscritos na dívida ativa',
                                            'type' => 'boolean'
                                        ],
                                        'cienciaDebitoExecucaoFiscal' => [
                                            'title' => 'Ciêmcia débito execução fiscal',
                                            'type' => 'boolean'
                                        ],
                                        'existemEmbargosExecucaoFiscal' => [
                                            'title' => 'Existem embargos execução fiscal',
                                            'type' => 'boolean'
                                        ],
                                        'incluirCreditosConstituicaoAutarquias' => [
                                            'title' => 'Incluir créditos constituição autarquias',
                                            'type' => 'boolean'
                                        ],
                                        'existemDepositosJudiciaisVinculadosCreditos' => [
                                            'title' => 'Existem depósitos judiciais vinculados créditos',
                                            'type' => 'boolean'
                                        ],
                                        'cpfSolicitante' => [
                                            'title' => 'CPF solicitante',
                                            'type' => 'string',
                                            'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$'
                                        ],
                                        'nomeSolicitante' => [
                                            'title' => 'Nome solicitante',
                                            'type' => 'string',
                                            'minLength' => 1
                                        ],
                                        'emailSolicitante' => [
                                            'title' => 'E-mail solicitante',
                                            'type' => 'string',
                                            'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                            'format' => 'email'
                                        ],
                                        'confirmeEmailSolicitante' => [
                                            'title' => 'Confirme e-mail solicitante',
                                            'type' => 'string',
                                            'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                            'format' => 'email'
                                        ],
                                        'tipoTelefoneSolicitante' => [
                                            'title' => 'Tipo telefone solicitante',
                                            'type' => 'string',
                                            'enum' => [
                                                'fixo',
                                                'celular',
                                                'nao_informado'
                                            ]
                                        ],
                                        'opcaoPagamentoParcelado' => [
                                            'title' => 'Opção pagamento parcelamento',
                                            'type' => [
                                                'string'
                                            ],
                                            'enum' => [
                                                'pessoa_fisica_integral_avista',
                                                'pessoa_fisica_integral_12_meses',
                                                'pessoa_fisica_integral_48_meses',
                                                'pessoa_fisica_integral_96_meses',
                                                'pessoa_fisica_integral_145_meses',
                                                'pessoa_fisica_parcial_avista',
                                                'pessoa_fisica_parcial_12_meses',
                                                'pessoa_fisica_parcial_48_meses',
                                                'pessoa_fisica_parcial_96_meses',
                                                'pessoa_fisica_parcial_145_meses',
                                                'pessoa_juridica_integral_avista',
                                                'pessoa_juridica_integral_12_meses',
                                                'pessoa_juridica_integral_48_meses',
                                                'pessoa_juridica_integral_96_meses',
                                                'pessoa_juridica_integral_120_meses',
                                                'pessoa_juridica_parcial_avista',
                                                'pessoa_juridica_parcial_12_meses',
                                                'pessoa_juridica_parcial_48_meses',
                                                'pessoa_juridica_parcial_96_meses',
                                                'pessoa_juridica_parcial_120_meses'
                                            ]
                                        ],
                                        'quantidadeParcelas' => [
                                            'type' => [
                                                'integer',
                                                'null'
                                            ]
                                        ]
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'representante' => [
                                                'type' => 'boolean'
                                            ],
                                            'processos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processo' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processo'
                                                    ]
                                                ]
                                            ],
                                            'processosJudicial' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoJudicial' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoJudicial'
                                                    ]
                                                ]
                                            ],
                                            'infoAdicionais' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ]
                                            ],
                                            'protestado' => [
                                                'type' => 'string',
                                                'enum' => [
                                                    'sim',
                                                    'nao',
                                                    'nao_sabe'
                                                ]
                                            ],
                                            'protocolos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'protocolo' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ]
                                                ]
                                            ],
                                            'embargo' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'enum' => [
                                                    'sim',
                                                    'nao',
                                                    'nao_sabe'
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'incluirCreditosInscritosDividaAtiva' => [
                                                'const' => false
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'certidoesCreditosInscritosDividaAtiva' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'certidaoCreditoInscricaoDividaAtiva' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'certidaoCreditoInscricaoDividaAtiva'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosCreditosInscritosDividaAtiva' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoCreditoInscritoDividaAtiva' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoCreditoInscritoDividaAtiva'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'cienciaDebitoExecucaoFiscal' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'certidoesInscricaoDividaAtivaExecucaoFiscal' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'certidaoInscricaoDividaAtivaExecucaoFiscal' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'certidaoInscricaoDividaAtivaExecucaoFiscal'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosConstituicaoExecucaoFiscal' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoConstituicaoExecucaoFiscal' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoConstituicaoExecucaoFiscal'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosExecucaoFiscal' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoExecucaoFiscal' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoExecucaoFiscal'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'existemEmbargosExecucaoFiscal' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'certidoesInscricaoDividaAtivaEmbargos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'certidaoInscricaoDividaAtivaEmbargo' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'certidaoInscricaoDividaAtivaEmbargo'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosConstituicaoEmbargos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoConstituicaoEmbargo' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoConstituicaoEmbargo'
                                                    ]
                                                ]
                                            ],
                                            'processosJudiciaisEmbargos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoJudicialEmbargo' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoJudicialEmbargo'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'incluirCreditosConstituicaoAutarquias' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'documentosOrigemDividasCreditosConstituicao' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'documentoOrigemDividaCreditoConstituicao' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'documentoOrigemDividaCreditoConstituicao'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosCreditosConstituicao' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoCreditoConstituicao' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoCreditoConstituicao'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'existemDepositosJudiciaisVinculadosCreditos' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'certidoesInscricaoDividaAtivaDepositosJudiciais' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'certidaoInscricaoDividaAtivaDepositoJudicial' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'certidaoInscricaoDividaAtivaDepositoJudicial'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosDepositosJudiciais' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoDepositoJudicial' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoDepositoJudicial'
                                                    ]
                                                ]
                                            ],
                                            'processosJudiciaisDepositosJudiciais' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoJudicialDepositoJudicial' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoJudicialDepositoJudicial'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'embargo' => [
                                                'const' => 'sim'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'embargosProcesso' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'embargoProcesso' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'embargoProcesso'
                                                    ]
                                                ],
                                                'minItens' => 1
                                            ]
                                        ]
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'embargosProcesso' => [
                                                'type' => [
                                                    'array',
                                                    'null'
                                                ],
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'embargoProcesso' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'embargoProcesso'
                                                    ]
                                                ],
                                                'minItens' => 0
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'representante' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefoneComercialRepresentante' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'pattern' => '^$|^\\([0-9]{2}\\) [0-9]{4}-[0-9]{4}$',
                                                'minLength' => 0,
                                                'maxLength' => 14
                                            ],
                                            'celularRepresentante' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'pattern' => '^$|^\\([0-9]{2}\\) [89][0-9]{4}-[0-9]{4}$',
                                                'minLength' => 0,
                                                'maxLength' => 15
                                            ],
                                            'nomeRepresentante' => [
                                                'type' => 'string',
                                                'minLength' => 1
                                            ],
                                            'cpfRepresentante' => [
                                                'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$'
                                            ],
                                            'emailRepresentante' => [
                                                'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                                'format' => 'email'
                                            ],
                                            'oab' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'pattern' => '^[-.0-9]*$'
                                            ],
                                            'ufRepresentante' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'enum' => [
                                                    'RO',
                                                    'AC',
                                                    'AM',
                                                    'RR',
                                                    'PA',
                                                    'AP',
                                                    'TO',
                                                    'MA',
                                                    'PI',
                                                    'CE',
                                                    'RN',
                                                    'PB',
                                                    'PE',
                                                    'AL',
                                                    'SE',
                                                    'BA',
                                                    'MG',
                                                    'ES',
                                                    'RJ',
                                                    'SP',
                                                    'PR',
                                                    'SC',
                                                    'RS',
                                                    'MS',
                                                    'MT',
                                                    'GO',
                                                    'DF',
                                                    null
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoPessoa' => [
                                                'const' => 'fisica'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'cpfCnpj' => [
                                                'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$'
                                            ]
                                        ]
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'cpfCnpj' => [
                                                'pattern' => '^[0-9]{2}\\.?[0-9]{3}\\.?[0-9]{3}/?[0-9]{4}-?[0-9]{2}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefone' => [
                                                'const' => 'celular'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [89][0-9]{4}-?[0-9]{4}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefone' => [
                                                'const' => 'fixo'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [0-9]{4}-?[0-9]{4}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefone' => [
                                                'const' => 'nao_informado'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'null'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoSolicitacao' => [
                                                'const' => 'outros'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'tipoSolicitacaoOutros' => [
                                                'type' => 'string',
                                                'minLength' => 1
                                            ]
                                        ]
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'tipoSolicitacaoOutros' => [
                                                'type' => 'null'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefoneSolicitante' => [
                                                'const' => 'celular'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefoneSolicitante' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [89][0-9]{4}-?[0-9]{4}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefoneSolicitante' => [
                                                'const' => 'fixo'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefoneSolicitante' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [0-9]{4}-?[0-9]{4}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefoneSolicitante' => [
                                                'const' => 'nao_informado'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefoneSolicitante' => [
                                                'type' => 'null'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                    ),
                ],
                [
                    'sigla' => 'requerimento_transacao_adesao_pgf'
                ]
            )
        );

        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                Formulario::class,
                [
                    'dataSchema' => json_encode(
                        [
                            '$schema' => 'http://json-schema.org/draft-07/schema#',
                            '$id' => 'supp_core.administrativo_backend.requerimento_transacao_informacoes_pgf',
                            'title' => 'FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO INFORMAÇÕES GERAIS',
                            'type' => 'object',
                            'required' => [
                                'setorAtual',
                                'tipoRequerimento',
                                'tipoPessoa',
                                'cpfCnpj',
                                'nomeRazaoSocial',
                                'email',
                                'confirmeEmail',
                                'tipoTelefone',
                                'telefone',
                                'cep',
                                'endereco',
                                'complemento',
                                'estado',
                                'municipio',
                                'tipoSolicitacao',
                                'credor',
                                'ciente'
                            ],
                            'properties' => [
                                'setorAtual' => [
                                    'title' => 'Setor atual',
                                    'type' => 'integer',
                                    'minValue' => 0
                                ],
                                'tipoRequerimento' => [
                                    'title' => 'Tipo requerimento',
                                    'type' => 'string',
                                    'enum' => [
                                        'requerimento_transacao_adesao_pgf',
                                        'requerimento_transacao_informacoes_pgf'
                                    ]
                                ],
                                'tipoPessoa' => [
                                    'title' => 'Tipo pessoa',
                                    'type' => 'string',
                                    'enum' => [
                                        'fisica',
                                        'juridica'
                                    ]
                                ],
                                'nomeRazaoSocial' => [
                                    'title' => 'Nome razão social',
                                    'type' => 'string',
                                    'minLength' => 1
                                ],
                                'email' => [
                                    'title' => 'E-mail',
                                    'type' => 'string',
                                    'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                    'format' => 'email'
                                ],
                                'confirmeEmail' => [
                                    'title' => 'Confirme e-mail',
                                    'type' => 'string',
                                    'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                    'format' => 'email'
                                ],
                                'tipoTelefone' => [
                                    'title' => 'Tipo telefone',
                                    'type' => 'string',
                                    'enum' => [
                                        'fixo',
                                        'celular',
                                        'nao_informado'
                                    ]
                                ],
                                'cep' => [
                                    'title' => 'CEP',
                                    'type' => 'string',
                                    'pattern' => '^[0-9]{2}\\.?[0-9]{3}[-]?[0-9]{3}$'
                                ],
                                'endereco' => [
                                    'title' => 'Endereço',
                                    'type' => 'string',
                                    'minLength' => 1
                                ],
                                'complemento' => [
                                    'title' => 'Complemento',
                                    'type' => [
                                        'string',
                                        'null'
                                    ]
                                ],
                                'estado' => [
                                    'title' => 'Estado',
                                    'type' => 'integer',
                                    'minValue' => 0
                                ],
                                'municipio' => [
                                    'title' => 'Município',
                                    'type' => 'integer',
                                    'minValue' => 0
                                ],
                                'credor' => [
                                    'title' => 'Credor',
                                    'type' => 'string',
                                    'minLength' => 1
                                ],
                                'tipoSolicitacao' => [
                                    'title' => 'Tipo solicitação',
                                    'type' => 'string',
                                    'enum' => [
                                        'transacao_adesao_pgf',
                                        'transacao_informacoes_gerais'
                                    ]
                                ],
                                'cnpjsFiliais' => [
                                    'title' => 'CNPJs Filiais',
                                    'type' => [
                                        'string',
                                        'null'
                                    ]
                                ],
                                'ciente' => [
                                    'title' => 'Ciente',
                                    'type' => 'boolean'
                                ]
                            ],
                            'allOf' => [
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoRequerimento' => [
                                                'const' => 'requerimento_transacao_adesao_pgf'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'devedorPessoaFisicaEDemais' => [
                                            'title' => 'Devedor pessoa fisica e MEI ou demais organizações sociedade civil',
                                            'type' => 'boolean'
                                        ],
                                        'incluirCreditosInscritosDividaAtiva' => [
                                            'title' => 'Incluir créditos inscritos na dívida ativa',
                                            'type' => 'boolean'
                                        ],
                                        'cienciaDebitoExecucaoFiscal' => [
                                            'title' => 'Ciêmcia débito execução fiscal',
                                            'type' => 'boolean'
                                        ],
                                        'existemEmbargosExecucaoFiscal' => [
                                            'title' => 'Existem embargos execução fiscal',
                                            'type' => 'boolean'
                                        ],
                                        'incluirCreditosConstituicaoAutarquias' => [
                                            'title' => 'Incluir créditos constituição autarquias',
                                            'type' => 'boolean'
                                        ],
                                        'existemDepositosJudiciaisVinculadosCreditos' => [
                                            'title' => 'Existem depósitos judiciais vinculados créditos',
                                            'type' => 'boolean'
                                        ],
                                        'cpfSolicitante' => [
                                            'title' => 'CPF solicitante',
                                            'type' => 'string',
                                            'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$'
                                        ],
                                        'nomeSolicitante' => [
                                            'title' => 'Nome solicitante',
                                            'type' => 'string',
                                            'minLength' => 1
                                        ],
                                        'emailSolicitante' => [
                                            'title' => 'E-mail solicitante',
                                            'type' => 'string',
                                            'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                            'format' => 'email'
                                        ],
                                        'confirmeEmailSolicitante' => [
                                            'title' => 'Confirme e-mail solicitante',
                                            'type' => 'string',
                                            'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                            'format' => 'email'
                                        ],
                                        'tipoTelefoneSolicitante' => [
                                            'title' => 'Tipo telefone solicitante',
                                            'type' => 'string',
                                            'enum' => [
                                                'fixo',
                                                'celular',
                                                'nao_informado'
                                            ]
                                        ],
                                        'opcaoPagamentoParcelado' => [
                                            'title' => 'Opção pagamento parcelamento',
                                            'type' => [
                                                'string'
                                            ],
                                            'enum' => [
                                                'pessoa_fisica_integral_avista',
                                                'pessoa_fisica_integral_12_meses',
                                                'pessoa_fisica_integral_48_meses',
                                                'pessoa_fisica_integral_96_meses',
                                                'pessoa_fisica_integral_145_meses',
                                                'pessoa_fisica_parcial_avista',
                                                'pessoa_fisica_parcial_12_meses',
                                                'pessoa_fisica_parcial_48_meses',
                                                'pessoa_fisica_parcial_96_meses',
                                                'pessoa_fisica_parcial_145_meses',
                                                'pessoa_juridica_integral_avista',
                                                'pessoa_juridica_integral_12_meses',
                                                'pessoa_juridica_integral_48_meses',
                                                'pessoa_juridica_integral_96_meses',
                                                'pessoa_juridica_integral_120_meses',
                                                'pessoa_juridica_parcial_avista',
                                                'pessoa_juridica_parcial_12_meses',
                                                'pessoa_juridica_parcial_48_meses',
                                                'pessoa_juridica_parcial_96_meses',
                                                'pessoa_juridica_parcial_120_meses'
                                            ]
                                        ],
                                        'quantidadeParcelas' => [
                                            'type' => [
                                                'integer',
                                                'null'
                                            ]
                                        ]
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'representante' => [
                                                'type' => 'boolean'
                                            ],
                                            'processos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processo' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processo'
                                                    ]
                                                ]
                                            ],
                                            'processosJudicial' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoJudicial' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoJudicial'
                                                    ]
                                                ]
                                            ],
                                            'infoAdicionais' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ]
                                            ],
                                            'protestado' => [
                                                'type' => 'string',
                                                'enum' => [
                                                    'sim',
                                                    'nao',
                                                    'nao_sabe'
                                                ]
                                            ],
                                            'protocolos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'protocolo' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ]
                                                ]
                                            ],
                                            'embargo' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'enum' => [
                                                    'sim',
                                                    'nao',
                                                    'nao_sabe'
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'incluirCreditosInscritosDividaAtiva' => [
                                                'const' => false
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'certidoesCreditosInscritosDividaAtiva' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'certidaoCreditoInscricaoDividaAtiva' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'certidaoCreditoInscricaoDividaAtiva'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosCreditosInscritosDividaAtiva' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoCreditoInscritoDividaAtiva' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoCreditoInscritoDividaAtiva'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'cienciaDebitoExecucaoFiscal' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'certidoesInscricaoDividaAtivaExecucaoFiscal' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'certidaoInscricaoDividaAtivaExecucaoFiscal' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'certidaoInscricaoDividaAtivaExecucaoFiscal'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosConstituicaoExecucaoFiscal' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoConstituicaoExecucaoFiscal' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoConstituicaoExecucaoFiscal'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosExecucaoFiscal' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoExecucaoFiscal' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoExecucaoFiscal'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'existemEmbargosExecucaoFiscal' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'certidoesInscricaoDividaAtivaEmbargos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'certidaoInscricaoDividaAtivaEmbargo' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'certidaoInscricaoDividaAtivaEmbargo'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosConstituicaoEmbargos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoConstituicaoEmbargo' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoConstituicaoEmbargo'
                                                    ]
                                                ]
                                            ],
                                            'processosJudiciaisEmbargos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoJudicialEmbargo' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoJudicialEmbargo'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'incluirCreditosConstituicaoAutarquias' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'documentosOrigemDividasCreditosConstituicao' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'documentoOrigemDividaCreditoConstituicao' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'documentoOrigemDividaCreditoConstituicao'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosCreditosConstituicao' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoCreditoConstituicao' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoCreditoConstituicao'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'existemDepositosJudiciaisVinculadosCreditos' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'certidoesInscricaoDividaAtivaDepositosJudiciais' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'certidaoInscricaoDividaAtivaDepositoJudicial' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'certidaoInscricaoDividaAtivaDepositoJudicial'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosDepositosJudiciais' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoDepositoJudicial' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoDepositoJudicial'
                                                    ]
                                                ]
                                            ],
                                            'processosJudiciaisDepositosJudiciais' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoJudicialDepositoJudicial' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoJudicialDepositoJudicial'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'embargo' => [
                                                'const' => 'sim'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'embargosProcesso' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'embargoProcesso' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'embargoProcesso'
                                                    ]
                                                ],
                                                'minItens' => 1
                                            ]
                                        ]
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'embargosProcesso' => [
                                                'type' => [
                                                    'array',
                                                    'null'
                                                ],
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'embargoProcesso' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'embargoProcesso'
                                                    ]
                                                ],
                                                'minItens' => 0
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'representante' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefoneComercialRepresentante' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'pattern' => '^$|^\\([0-9]{2}\\) [0-9]{4}-[0-9]{4}$',
                                                'minLength' => 0,
                                                'maxLength' => 14
                                            ],
                                            'celularRepresentante' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'pattern' => '^$|^\\([0-9]{2}\\) [89][0-9]{4}-[0-9]{4}$',
                                                'minLength' => 0,
                                                'maxLength' => 15
                                            ],
                                            'nomeRepresentante' => [
                                                'type' => 'string',
                                                'minLength' => 1
                                            ],
                                            'cpfRepresentante' => [
                                                'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$'
                                            ],
                                            'emailRepresentante' => [
                                                'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                                'format' => 'email'
                                            ],
                                            'oab' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'pattern' => '^[-.0-9]*$'
                                            ],
                                            'ufRepresentante' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'enum' => [
                                                    'RO',
                                                    'AC',
                                                    'AM',
                                                    'RR',
                                                    'PA',
                                                    'AP',
                                                    'TO',
                                                    'MA',
                                                    'PI',
                                                    'CE',
                                                    'RN',
                                                    'PB',
                                                    'PE',
                                                    'AL',
                                                    'SE',
                                                    'BA',
                                                    'MG',
                                                    'ES',
                                                    'RJ',
                                                    'SP',
                                                    'PR',
                                                    'SC',
                                                    'RS',
                                                    'MS',
                                                    'MT',
                                                    'GO',
                                                    'DF',
                                                    null
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoPessoa' => [
                                                'const' => 'fisica'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'cpfCnpj' => [
                                                'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$'
                                            ]
                                        ]
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'cpfCnpj' => [
                                                'pattern' => '^[0-9]{2}\\.?[0-9]{3}\\.?[0-9]{3}/?[0-9]{4}-?[0-9]{2}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefone' => [
                                                'const' => 'celular'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [89][0-9]{4}-?[0-9]{4}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefone' => [
                                                'const' => 'fixo'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [0-9]{4}-?[0-9]{4}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefone' => [
                                                'const' => 'nao_informado'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'null'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoSolicitacao' => [
                                                'const' => 'outros'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'tipoSolicitacaoOutros' => [
                                                'type' => 'string',
                                                'minLength' => 1
                                            ]
                                        ]
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'tipoSolicitacaoOutros' => [
                                                'type' => 'null'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefoneSolicitante' => [
                                                'const' => 'celular'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefoneSolicitante' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [89][0-9]{4}-?[0-9]{4}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefoneSolicitante' => [
                                                'const' => 'fixo'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefoneSolicitante' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [0-9]{4}-?[0-9]{4}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefoneSolicitante' => [
                                                'const' => 'nao_informado'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefoneSolicitante' => [
                                                'type' => 'null'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                    ),
                ],
                [
                    'sigla' => 'requerimento_transacao_informacoes_pgf'
                ]
            )
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->formularioDown();
    }

    private function formularioDown()
    {
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                Formulario::class,
                [
                    'dataSchema' => json_encode(
                        [
                            '$schema' => 'http://json-schema.org/draft-07/schema#',
                            '$id' => 'supp_core.administrativo_backend.requerimento_transacao_adesao_pgf',
                            'title' => 'FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO EXTRAORDINÁRIA PGF',
                            'type' => 'object',
                            'required' => [
                                'setorAtual',
                                'tipoRequerimento',
                                'tipoPessoa',
                                'cpfCnpj',
                                'nomeRazaoSocial',
                                'email',
                                'confirmeEmail',
                                'tipoTelefone',
                                'telefone',
                                'cep',
                                'endereco',
                                'complemento',
                                'estado',
                                'municipio',
                                'tipoSolicitacao',
                                'credor',
                                'ciente'
                            ],
                            'properties' => [
                                'setorAtual' => [
                                    'title' => 'Setor atual',
                                    'type' => 'integer',
                                    'minValue' => 0
                                ],
                                'tipoRequerimento' => [
                                    'title' => 'Tipo requerimento',
                                    'type' => 'string',
                                    'enum' => [
                                        'requerimento_transacao_adesao_pgf',
                                        'requerimento_transacao_informacoes_pgf'
                                    ]
                                ],
                                'tipoPessoa' => [
                                    'title' => 'Tipo pessoa',
                                    'type' => 'string',
                                    'enum' => [
                                        'fisica',
                                        'juridica'
                                    ]
                                ],
                                'nomeRazaoSocial' => [
                                    'title' => 'Nome razão social',
                                    'type' => 'string',
                                    'minLength' => 1
                                ],
                                'email' => [
                                    'title' => 'E-mail',
                                    'type' => 'string',
                                    'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                    'format' => 'email'
                                ],
                                'confirmeEmail' => [
                                    'title' => 'Confirme e-mail',
                                    'type' => 'string',
                                    'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                    'format' => 'email'
                                ],
                                'tipoTelefone' => [
                                    'title' => 'Tipo telefone',
                                    'type' => 'string',
                                    'enum' => [
                                        'fixo',
                                        'celular',
                                        'nao_informado'
                                    ]
                                ],
                                'cep' => [
                                    'title' => 'CEP',
                                    'type' => 'string',
                                    'pattern' => '^[0-9]{2}\\.?[0-9]{3}[-]?[0-9]{3}$'
                                ],
                                'endereco' => [
                                    'title' => 'Endereço',
                                    'type' => 'string',
                                    'minLength' => 1
                                ],
                                'complemento' => [
                                    'title' => 'Complemento',
                                    'type' => [
                                        'string',
                                        'null'
                                    ]
                                ],
                                'estado' => [
                                    'title' => 'Estado',
                                    'type' => 'integer',
                                    'minValue' => 0
                                ],
                                'municipio' => [
                                    'title' => 'Município',
                                    'type' => 'integer',
                                    'minValue' => 0
                                ],
                                'credor' => [
                                    'title' => 'Credor',
                                    'type' => 'string',
                                    'minLength' => 1
                                ],
                                'tipoSolicitacao' => [
                                    'title' => 'Tipo solicitação',
                                    'type' => 'string',
                                    'enum' => [
                                        'transacao_adesao_pgf',
                                        'transacao_informacoes_gerais'
                                    ]
                                ],
                                'cnpjsFiliais' => [
                                    'title' => 'CNPJs Filiais',
                                    'type' => [
                                        'string',
                                        'null'
                                    ]
                                ],
                                'ciente' => [
                                    'title' => 'Ciente',
                                    'type' => 'boolean'
                                ]
                            ],
                            'allOf' => [
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoRequerimento' => [
                                                'const' => 'requerimento_transacao_adesao_pgf'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'devedorPessoaFisicaEDemais' => [
                                            'title' => 'Devedor pessoa fisica e MEI ou demais organizações sociedade civil',
                                            'type' => 'boolean'
                                        ],
                                        'incluirCreditosInscritosDividaAtiva' => [
                                            'title' => 'Incluir créditos inscritos na dívida ativa',
                                            'type' => 'boolean'
                                        ],
                                        'cienciaDebitoExecucaoFiscal' => [
                                            'title' => 'Ciêmcia débito execução fiscal',
                                            'type' => 'boolean'
                                        ],
                                        'existemEmbargosExecucaoFiscal' => [
                                            'title' => 'Existem embargos execução fiscal',
                                            'type' => 'boolean'
                                        ],
                                        'incluirCreditosConstituicaoAutarquias' => [
                                            'title' => 'Incluir créditos constituição autarquias',
                                            'type' => 'boolean'
                                        ],
                                        'existemDepositosJudiciaisVinculadosCreditos' => [
                                            'title' => 'Existem depósitos judiciais vinculados créditos',
                                            'type' => 'boolean'
                                        ],
                                        'cpfSolicitante' => [
                                            'title' => 'CPF solicitante',
                                            'type' => 'string',
                                            'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$'
                                        ],
                                        'nomeSolicitante' => [
                                            'title' => 'Nome solicitante',
                                            'type' => 'string',
                                            'minLength' => 1
                                        ],
                                        'emailSolicitante' => [
                                            'title' => 'E-mail solicitante',
                                            'type' => 'string',
                                            'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                            'format' => 'email'
                                        ],
                                        'confirmeEmailSolicitante' => [
                                            'title' => 'Confirme e-mail solicitante',
                                            'type' => 'string',
                                            'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                            'format' => 'email'
                                        ],
                                        'tipoTelefoneSolicitante' => [
                                            'title' => 'Tipo telefone solicitante',
                                            'type' => 'string',
                                            'enum' => [
                                                'fixo',
                                                'celular',
                                                'nao_informado'
                                            ]
                                        ],
                                        'opcaoPagamentoParcelado' => [
                                            'title' => 'Opção pagamento parcelamento',
                                            'type' => [
                                                'string'
                                            ],
                                            'enum' => [
                                                'pessoa_fisica_integral_avista',
                                                'pessoa_fisica_integral_12_meses',
                                                'pessoa_fisica_integral_48_meses',
                                                'pessoa_fisica_integral_96_meses',
                                                'pessoa_fisica_integral_145_meses',
                                                'pessoa_fisica_parcial_avista',
                                                'pessoa_fisica_parcial_12_meses',
                                                'pessoa_fisica_parcial_48_meses',
                                                'pessoa_fisica_parcial_96_meses',
                                                'pessoa_fisica_parcial_145_meses',
                                                'pessoa_juridica_integral_avista',
                                                'pessoa_juridica_integral_12_meses',
                                                'pessoa_juridica_integral_48_meses',
                                                'pessoa_juridica_integral_96_meses',
                                                'pessoa_juridica_integral_120_meses',
                                                'pessoa_juridica_parcial_avista',
                                                'pessoa_juridica_parcial_12_meses',
                                                'pessoa_juridica_parcial_48_meses',
                                                'pessoa_juridica_parcial_96_meses',
                                                'pessoa_juridica_parcial_120_meses'
                                            ]
                                        ],
                                        'quantidadeParcelas' => [
                                            'type' => [
                                                'integer',
                                                'null'
                                            ]
                                        ]
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'representante' => [
                                                'type' => 'boolean'
                                            ],
                                            'processos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processo' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ],
                                                            'pattern' => '^[0-9]{5}\\.[0-9]{6}\\-[0-9]{4}/[0-9]{2}$'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processo'
                                                    ]
                                                ]
                                            ],
                                            'processosJudicial' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoJudicial' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ],
                                                            'pattern' => '^[0-9]{7}\\-[0-9]{2}\\.[0-9]{4}\\.[0-9]\\.[0-9]{2}\\.[0-9]{4}([.-][0-9]{2})?$'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoJudicial'
                                                    ]
                                                ]
                                            ],
                                            'infoAdicionais' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ]
                                            ],
                                            'protestado' => [
                                                'type' => 'string',
                                                'enum' => [
                                                    'sim',
                                                    'nao',
                                                    'nao_sabe'
                                                ]
                                            ],
                                            'protocolos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'protocolo' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ]
                                                ]
                                            ],
                                            'embargo' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'enum' => [
                                                    'sim',
                                                    'nao',
                                                    'nao_sabe'
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'incluirCreditosInscritosDividaAtiva' => [
                                                'const' => false
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'certidoesCreditosInscritosDividaAtiva' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'certidaoCreditoInscricaoDividaAtiva' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'certidaoCreditoInscricaoDividaAtiva'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosCreditosInscritosDividaAtiva' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoCreditoInscritoDividaAtiva' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoCreditoInscritoDividaAtiva'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'cienciaDebitoExecucaoFiscal' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'certidoesInscricaoDividaAtivaExecucaoFiscal' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'certidaoInscricaoDividaAtivaExecucaoFiscal' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'certidaoInscricaoDividaAtivaExecucaoFiscal'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosConstituicaoExecucaoFiscal' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoConstituicaoExecucaoFiscal' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoConstituicaoExecucaoFiscal'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosExecucaoFiscal' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoExecucaoFiscal' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoExecucaoFiscal'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'existemEmbargosExecucaoFiscal' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'certidoesInscricaoDividaAtivaEmbargos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'certidaoInscricaoDividaAtivaEmbargo' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'certidaoInscricaoDividaAtivaEmbargo'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosConstituicaoEmbargos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoConstituicaoEmbargo' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoConstituicaoEmbargo'
                                                    ]
                                                ]
                                            ],
                                            'processosJudiciaisEmbargos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoJudicialEmbargo' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoJudicialEmbargo'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'incluirCreditosConstituicaoAutarquias' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'documentosOrigemDividasCreditosConstituicao' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'documentoOrigemDividaCreditoConstituicao' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'documentoOrigemDividaCreditoConstituicao'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosCreditosConstituicao' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoCreditoConstituicao' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoCreditoConstituicao'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'existemDepositosJudiciaisVinculadosCreditos' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'certidoesInscricaoDividaAtivaDepositosJudiciais' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'certidaoInscricaoDividaAtivaDepositoJudicial' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'certidaoInscricaoDividaAtivaDepositoJudicial'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosDepositosJudiciais' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoDepositoJudicial' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoDepositoJudicial'
                                                    ]
                                                ]
                                            ],
                                            'processosJudiciaisDepositosJudiciais' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoJudicialDepositoJudicial' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoJudicialDepositoJudicial'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'embargo' => [
                                                'const' => 'sim'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'embargosProcesso' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'embargoProcesso' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ],
                                                            'pattern' => '^[0-9]{7}\\-[0-9]{2}\\.[0-9]{4}\\.[0-9]\\.[0-9]{2}\\.[0-9]{4}([.-][0-9]{2})?$'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'embargoProcesso'
                                                    ]
                                                ],
                                                'minItens' => 1
                                            ]
                                        ]
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'embargosProcesso' => [
                                                'type' => [
                                                    'array',
                                                    'null'
                                                ],
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'embargoProcesso' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ],
                                                            'pattern' => '^[0-9]{7}\\-[0-9]{2}\\.[0-9]{4}\\.[0-9]\\.[0-9]{2}\\.[0-9]{4}([.-][0-9]{2})?$'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'embargoProcesso'
                                                    ]
                                                ],
                                                'minItens' => 0
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'representante' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefoneComercialRepresentante' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'pattern' => '^$|^\\([0-9]{2}\\) [0-9]{4}-[0-9]{4}$',
                                                'minLength' => 0,
                                                'maxLength' => 14
                                            ],
                                            'celularRepresentante' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'pattern' => '^$|^\\([0-9]{2}\\) [89][0-9]{4}-[0-9]{4}$',
                                                'minLength' => 0,
                                                'maxLength' => 15
                                            ],
                                            'nomeRepresentante' => [
                                                'type' => 'string',
                                                'minLength' => 1
                                            ],
                                            'cpfRepresentante' => [
                                                'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$'
                                            ],
                                            'emailRepresentante' => [
                                                'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                                'format' => 'email'
                                            ],
                                            'oab' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'pattern' => '^[-.0-9]*$'
                                            ],
                                            'ufRepresentante' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'enum' => [
                                                    'RO',
                                                    'AC',
                                                    'AM',
                                                    'RR',
                                                    'PA',
                                                    'AP',
                                                    'TO',
                                                    'MA',
                                                    'PI',
                                                    'CE',
                                                    'RN',
                                                    'PB',
                                                    'PE',
                                                    'AL',
                                                    'SE',
                                                    'BA',
                                                    'MG',
                                                    'ES',
                                                    'RJ',
                                                    'SP',
                                                    'PR',
                                                    'SC',
                                                    'RS',
                                                    'MS',
                                                    'MT',
                                                    'GO',
                                                    'DF',
                                                    null
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoPessoa' => [
                                                'const' => 'fisica'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'cpfCnpj' => [
                                                'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$'
                                            ]
                                        ]
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'cpfCnpj' => [
                                                'pattern' => '^[0-9]{2}\\.?[0-9]{3}\\.?[0-9]{3}/?[0-9]{4}-?[0-9]{2}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefone' => [
                                                'const' => 'celular'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [89][0-9]{4}-?[0-9]{4}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefone' => [
                                                'const' => 'fixo'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [0-9]{4}-?[0-9]{4}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefone' => [
                                                'const' => 'nao_informado'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'null'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoSolicitacao' => [
                                                'const' => 'outros'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'tipoSolicitacaoOutros' => [
                                                'type' => 'string',
                                                'minLength' => 1
                                            ]
                                        ]
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'tipoSolicitacaoOutros' => [
                                                'type' => 'null'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefoneSolicitante' => [
                                                'const' => 'celular'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefoneSolicitante' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [89][0-9]{4}-?[0-9]{4}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefoneSolicitante' => [
                                                'const' => 'fixo'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefoneSolicitante' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [0-9]{4}-?[0-9]{4}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefoneSolicitante' => [
                                                'const' => 'nao_informado'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefoneSolicitante' => [
                                                'type' => 'null'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                    ),
                ],
                [
                    'sigla' => 'requerimento_transacao_adesao_pgf'
                ]
            )
        );

        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                Formulario::class,
                [
                    'dataSchema' => json_encode(
                        [
                            '$schema' => 'http://json-schema.org/draft-07/schema#',
                            '$id' => 'supp_core.administrativo_backend.requerimento_transacao_informacoes_pgf',
                            'title' => 'FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO INFORMAÇÕES GERAIS',
                            'type' => 'object',
                            'required' => [
                                'setorAtual',
                                'tipoRequerimento',
                                'tipoPessoa',
                                'cpfCnpj',
                                'nomeRazaoSocial',
                                'email',
                                'confirmeEmail',
                                'tipoTelefone',
                                'telefone',
                                'cep',
                                'endereco',
                                'complemento',
                                'estado',
                                'municipio',
                                'tipoSolicitacao',
                                'credor',
                                'ciente'
                            ],
                            'properties' => [
                                'setorAtual' => [
                                    'title' => 'Setor atual',
                                    'type' => 'integer',
                                    'minValue' => 0
                                ],
                                'tipoRequerimento' => [
                                    'title' => 'Tipo requerimento',
                                    'type' => 'string',
                                    'enum' => [
                                        'requerimento_transacao_adesao_pgf',
                                        'requerimento_transacao_informacoes_pgf'
                                    ]
                                ],
                                'tipoPessoa' => [
                                    'title' => 'Tipo pessoa',
                                    'type' => 'string',
                                    'enum' => [
                                        'fisica',
                                        'juridica'
                                    ]
                                ],
                                'nomeRazaoSocial' => [
                                    'title' => 'Nome razão social',
                                    'type' => 'string',
                                    'minLength' => 1
                                ],
                                'email' => [
                                    'title' => 'E-mail',
                                    'type' => 'string',
                                    'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                    'format' => 'email'
                                ],
                                'confirmeEmail' => [
                                    'title' => 'Confirme e-mail',
                                    'type' => 'string',
                                    'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                    'format' => 'email'
                                ],
                                'tipoTelefone' => [
                                    'title' => 'Tipo telefone',
                                    'type' => 'string',
                                    'enum' => [
                                        'fixo',
                                        'celular',
                                        'nao_informado'
                                    ]
                                ],
                                'cep' => [
                                    'title' => 'CEP',
                                    'type' => 'string',
                                    'pattern' => '^[0-9]{2}\\.?[0-9]{3}[-]?[0-9]{3}$'
                                ],
                                'endereco' => [
                                    'title' => 'Endereço',
                                    'type' => 'string',
                                    'minLength' => 1
                                ],
                                'complemento' => [
                                    'title' => 'Complemento',
                                    'type' => [
                                        'string',
                                        'null'
                                    ]
                                ],
                                'estado' => [
                                    'title' => 'Estado',
                                    'type' => 'integer',
                                    'minValue' => 0
                                ],
                                'municipio' => [
                                    'title' => 'Município',
                                    'type' => 'integer',
                                    'minValue' => 0
                                ],
                                'credor' => [
                                    'title' => 'Credor',
                                    'type' => 'string',
                                    'minLength' => 1
                                ],
                                'tipoSolicitacao' => [
                                    'title' => 'Tipo solicitação',
                                    'type' => 'string',
                                    'enum' => [
                                        'transacao_adesao_pgf',
                                        'transacao_informacoes_gerais'
                                    ]
                                ],
                                'cnpjsFiliais' => [
                                    'title' => 'CNPJs Filiais',
                                    'type' => [
                                        'string',
                                        'null'
                                    ]
                                ],
                                'ciente' => [
                                    'title' => 'Ciente',
                                    'type' => 'boolean'
                                ]
                            ],
                            'allOf' => [
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoRequerimento' => [
                                                'const' => 'requerimento_transacao_adesao_pgf'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'devedorPessoaFisicaEDemais' => [
                                            'title' => 'Devedor pessoa fisica e MEI ou demais organizações sociedade civil',
                                            'type' => 'boolean'
                                        ],
                                        'incluirCreditosInscritosDividaAtiva' => [
                                            'title' => 'Incluir créditos inscritos na dívida ativa',
                                            'type' => 'boolean'
                                        ],
                                        'cienciaDebitoExecucaoFiscal' => [
                                            'title' => 'Ciêmcia débito execução fiscal',
                                            'type' => 'boolean'
                                        ],
                                        'existemEmbargosExecucaoFiscal' => [
                                            'title' => 'Existem embargos execução fiscal',
                                            'type' => 'boolean'
                                        ],
                                        'incluirCreditosConstituicaoAutarquias' => [
                                            'title' => 'Incluir créditos constituição autarquias',
                                            'type' => 'boolean'
                                        ],
                                        'existemDepositosJudiciaisVinculadosCreditos' => [
                                            'title' => 'Existem depósitos judiciais vinculados créditos',
                                            'type' => 'boolean'
                                        ],
                                        'cpfSolicitante' => [
                                            'title' => 'CPF solicitante',
                                            'type' => 'string',
                                            'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$'
                                        ],
                                        'nomeSolicitante' => [
                                            'title' => 'Nome solicitante',
                                            'type' => 'string',
                                            'minLength' => 1
                                        ],
                                        'emailSolicitante' => [
                                            'title' => 'E-mail solicitante',
                                            'type' => 'string',
                                            'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                            'format' => 'email'
                                        ],
                                        'confirmeEmailSolicitante' => [
                                            'title' => 'Confirme e-mail solicitante',
                                            'type' => 'string',
                                            'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                            'format' => 'email'
                                        ],
                                        'tipoTelefoneSolicitante' => [
                                            'title' => 'Tipo telefone solicitante',
                                            'type' => 'string',
                                            'enum' => [
                                                'fixo',
                                                'celular',
                                                'nao_informado'
                                            ]
                                        ],
                                        'opcaoPagamentoParcelado' => [
                                            'title' => 'Opção pagamento parcelamento',
                                            'type' => [
                                                'string'
                                            ],
                                            'enum' => [
                                                'pessoa_fisica_integral_avista',
                                                'pessoa_fisica_integral_12_meses',
                                                'pessoa_fisica_integral_48_meses',
                                                'pessoa_fisica_integral_96_meses',
                                                'pessoa_fisica_integral_145_meses',
                                                'pessoa_fisica_parcial_avista',
                                                'pessoa_fisica_parcial_12_meses',
                                                'pessoa_fisica_parcial_48_meses',
                                                'pessoa_fisica_parcial_96_meses',
                                                'pessoa_fisica_parcial_145_meses',
                                                'pessoa_juridica_integral_avista',
                                                'pessoa_juridica_integral_12_meses',
                                                'pessoa_juridica_integral_48_meses',
                                                'pessoa_juridica_integral_96_meses',
                                                'pessoa_juridica_integral_120_meses',
                                                'pessoa_juridica_parcial_avista',
                                                'pessoa_juridica_parcial_12_meses',
                                                'pessoa_juridica_parcial_48_meses',
                                                'pessoa_juridica_parcial_96_meses',
                                                'pessoa_juridica_parcial_120_meses'
                                            ]
                                        ],
                                        'quantidadeParcelas' => [
                                            'type' => [
                                                'integer',
                                                'null'
                                            ]
                                        ]
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'representante' => [
                                                'type' => 'boolean'
                                            ],
                                            'processos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processo' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ],
                                                            'pattern' => '^[0-9]{5}\\.[0-9]{6}\\-[0-9]{4}/[0-9]{2}$'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processo'
                                                    ]
                                                ]
                                            ],
                                            'processosJudicial' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoJudicial' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ],
                                                            'pattern' => '^[0-9]{7}\\-[0-9]{2}\\.[0-9]{4}\\.[0-9]\\.[0-9]{2}\\.[0-9]{4}([.-][0-9]{2})?$'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoJudicial'
                                                    ]
                                                ]
                                            ],
                                            'infoAdicionais' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ]
                                            ],
                                            'protestado' => [
                                                'type' => 'string',
                                                'enum' => [
                                                    'sim',
                                                    'nao',
                                                    'nao_sabe'
                                                ]
                                            ],
                                            'protocolos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'protocolo' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ]
                                                        ]
                                                    ]
                                                ]
                                            ],
                                            'embargo' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'enum' => [
                                                    'sim',
                                                    'nao',
                                                    'nao_sabe'
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'incluirCreditosInscritosDividaAtiva' => [
                                                'const' => false
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'certidoesCreditosInscritosDividaAtiva' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'certidaoCreditoInscricaoDividaAtiva' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'certidaoCreditoInscricaoDividaAtiva'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosCreditosInscritosDividaAtiva' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoCreditoInscritoDividaAtiva' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoCreditoInscritoDividaAtiva'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'cienciaDebitoExecucaoFiscal' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'certidoesInscricaoDividaAtivaExecucaoFiscal' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'certidaoInscricaoDividaAtivaExecucaoFiscal' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'certidaoInscricaoDividaAtivaExecucaoFiscal'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosConstituicaoExecucaoFiscal' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoConstituicaoExecucaoFiscal' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoConstituicaoExecucaoFiscal'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosExecucaoFiscal' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoExecucaoFiscal' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoExecucaoFiscal'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'existemEmbargosExecucaoFiscal' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'certidoesInscricaoDividaAtivaEmbargos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'certidaoInscricaoDividaAtivaEmbargo' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'certidaoInscricaoDividaAtivaEmbargo'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosConstituicaoEmbargos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoConstituicaoEmbargo' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoConstituicaoEmbargo'
                                                    ]
                                                ]
                                            ],
                                            'processosJudiciaisEmbargos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoJudicialEmbargo' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoJudicialEmbargo'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'incluirCreditosConstituicaoAutarquias' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'documentosOrigemDividasCreditosConstituicao' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'documentoOrigemDividaCreditoConstituicao' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'documentoOrigemDividaCreditoConstituicao'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosCreditosConstituicao' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoCreditoConstituicao' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoCreditoConstituicao'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'existemDepositosJudiciaisVinculadosCreditos' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'certidoesInscricaoDividaAtivaDepositosJudiciais' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'certidaoInscricaoDividaAtivaDepositoJudicial' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'certidaoInscricaoDividaAtivaDepositoJudicial'
                                                    ]
                                                ]
                                            ],
                                            'processosAdministrativosDepositosJudiciais' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoAdministrativoDepositoJudicial' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoAdministrativoDepositoJudicial'
                                                    ]
                                                ]
                                            ],
                                            'processosJudiciaisDepositosJudiciais' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoJudicialDepositoJudicial' => [
                                                            'type' => 'string'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'processoJudicialDepositoJudicial'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'embargo' => [
                                                'const' => 'sim'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'embargosProcesso' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'embargoProcesso' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ],
                                                            'pattern' => '^[0-9]{7}\\-[0-9]{2}\\.[0-9]{4}\\.[0-9]\\.[0-9]{2}\\.[0-9]{4}([.-][0-9]{2})?$'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'embargoProcesso'
                                                    ]
                                                ],
                                                'minItens' => 1
                                            ]
                                        ]
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'embargosProcesso' => [
                                                'type' => [
                                                    'array',
                                                    'null'
                                                ],
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'embargoProcesso' => [
                                                            'type' => [
                                                                'string',
                                                                'null'
                                                            ],
                                                            'pattern' => '^[0-9]{7}\\-[0-9]{2}\\.[0-9]{4}\\.[0-9]\\.[0-9]{2}\\.[0-9]{4}([.-][0-9]{2})?$'
                                                        ]
                                                    ],
                                                    'required' => [
                                                        'embargoProcesso'
                                                    ]
                                                ],
                                                'minItens' => 0
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'representante' => [
                                                'const' => true
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefoneComercialRepresentante' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'pattern' => '^$|^\\([0-9]{2}\\) [0-9]{4}-[0-9]{4}$',
                                                'minLength' => 0,
                                                'maxLength' => 14
                                            ],
                                            'celularRepresentante' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'pattern' => '^$|^\\([0-9]{2}\\) [89][0-9]{4}-[0-9]{4}$',
                                                'minLength' => 0,
                                                'maxLength' => 15
                                            ],
                                            'nomeRepresentante' => [
                                                'type' => 'string',
                                                'minLength' => 1
                                            ],
                                            'cpfRepresentante' => [
                                                'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$'
                                            ],
                                            'emailRepresentante' => [
                                                'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                                'format' => 'email'
                                            ],
                                            'oab' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'pattern' => '^[-.0-9]*$'
                                            ],
                                            'ufRepresentante' => [
                                                'type' => [
                                                    'string',
                                                    'null'
                                                ],
                                                'enum' => [
                                                    'RO',
                                                    'AC',
                                                    'AM',
                                                    'RR',
                                                    'PA',
                                                    'AP',
                                                    'TO',
                                                    'MA',
                                                    'PI',
                                                    'CE',
                                                    'RN',
                                                    'PB',
                                                    'PE',
                                                    'AL',
                                                    'SE',
                                                    'BA',
                                                    'MG',
                                                    'ES',
                                                    'RJ',
                                                    'SP',
                                                    'PR',
                                                    'SC',
                                                    'RS',
                                                    'MS',
                                                    'MT',
                                                    'GO',
                                                    'DF',
                                                    null
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoPessoa' => [
                                                'const' => 'fisica'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'cpfCnpj' => [
                                                'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$'
                                            ]
                                        ]
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'cpfCnpj' => [
                                                'pattern' => '^[0-9]{2}\\.?[0-9]{3}\\.?[0-9]{3}/?[0-9]{4}-?[0-9]{2}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefone' => [
                                                'const' => 'celular'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [89][0-9]{4}-?[0-9]{4}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefone' => [
                                                'const' => 'fixo'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [0-9]{4}-?[0-9]{4}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefone' => [
                                                'const' => 'nao_informado'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'null'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoSolicitacao' => [
                                                'const' => 'outros'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'tipoSolicitacaoOutros' => [
                                                'type' => 'string',
                                                'minLength' => 1
                                            ]
                                        ]
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'tipoSolicitacaoOutros' => [
                                                'type' => 'null'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefoneSolicitante' => [
                                                'const' => 'celular'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefoneSolicitante' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [89][0-9]{4}-?[0-9]{4}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefoneSolicitante' => [
                                                'const' => 'fixo'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefoneSolicitante' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [0-9]{4}-?[0-9]{4}$'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoTelefoneSolicitante' => [
                                                'const' => 'nao_informado'
                                            ]
                                        ]
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefoneSolicitante' => [
                                                'type' => 'null'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                    ),
                ],
                [
                    'sigla' => 'requerimento_transacao_informacoes_pgf'
                ]
            )
        );
    }
}
