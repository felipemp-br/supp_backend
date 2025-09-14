<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use SuppCore\AdministrativoBackend\Entity\Formulario;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213132235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Alterações de dataSchemas de formulários de requerimento de parcelamento e atendimento PGF';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->formularioUp();
    }

    private function formularioUp()
    {
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                Formulario::class,
                [
                    'dataSchema' => json_encode(
                        [
                            '$schema' => 'http://json-schema.org/draft-07/schema#',
                            '$id' => 'supp_core.administrativo_backend.requerimento_pgf_cobranca_parcelamento',
                            'title' => 'FORMULÁRIO DE REQUERIMENTO PGF/SCOB PARCELAMENTO',
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
                                'representante',
                                'nomeRepresentante',
                                'cpfRepresentante',
                                'uf',
                                'emailRepresentante',
                                'tipoSolicitacao',
                                'tipoSolicitacaoOutros',
                                'credor',
                                'motivoSolicitacao',
                                'motivoSolicitacaoOutros',
                                'processos',
                                'processosJudicial',
                                'nupParcelamentos',
                                'infoAdicionais',
                                'protestado',
                                'protocolos',
                                'embargo',
                                'embargosProcesso',
                                'creditosInscricoesProcessos',
                                'ajuizado',
                                'numeroParcelas',
                                'numeroAcao',
                                'ciente',
                            ],
                            'properties' => [
                                'tipoRequerimento' => [
                                    'type' => 'string',
                                    'enum' => [
                                        'requerimento_pgf_cobranca_atendimento',
                                        'requerimento_pgf_cobranca_parcelamento',
                                    ],
                                ],
                                'setorAtual' => ['type' => 'integer', 'minValue' => 0],
                                'tipoPessoa' => ['type' => 'string', 'enum' => ['fisica', 'juridica']],
                                'nomeRazaoSocial' => ['type' => 'string', 'minLength' => 1],
                                'email' => [
                                    'type' => 'string',
                                    'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                    'format' => 'email',
                                ],
                                'confirmeEmail' => [
                                    'type' => 'string',
                                    'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                    'format' => 'email',
                                ],
                                'tipoTelefone' => ['type' => 'string', 'enum' => ['fixo', 'celular', 'nao_informado']],
                                'cep' => [
                                    'type' => 'string',
                                    'pattern' => '^[0-9]{2}\\.?[0-9]{3}[-]?[0-9]{3}$',
                                ],
                                'endereco' => ['type' => 'string', 'minLength' => 1],
                                'complemento' => ['type' => ['string', 'null']],
                                'estado' => ['type' => 'integer', 'minValue' => 0],
                                'municipio' => ['type' => 'integer', 'minValue' => 0],
                                'credor' => ['type' => 'string', 'minLength' => 1],
                                'representante' => ['type' => 'boolean'],
                                'tipoSolicitacao' => [
                                    'type' => 'string',
                                    'enum' => [
                                        'boleto_credito',
                                        'parcelamento_credito',
                                        'informacoes_gerais',
                                        'boleto_parcelamento',
                                        'carta_anuencia',
                                        'outros',
                                    ],
                                ],
                                'ciente' => ['type' => 'boolean'],
                                'motivoSolicitacao' => [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'string',
                                        'enum' => [
                                            'protesto',
                                            'negativacao',
                                            'leilao_judicial',
                                            'penhora',
                                            'execucao_fiscal',
                                            'outros',
                                        ],
                                    ],
                                    'minItens' => 1,
                                ],
                            ],
                            'allOf' => [
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoRequerimento' => [
                                                'const' => 'requerimento_pgf_cobranca_parcelamento',
                                            ],
                                        ],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'processos' => ['type' => 'array', 'maxItens' => 0],
                                            'processosJudicial' => ['type' => 'array', 'maxItens' => 0],
                                            'nupParcelamentos' => ['type' => ['array', 'null'], 'maxItens' => 0],
                                            'protestado' => [
                                                'type' => 'string',
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'protocolos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'protocolo' => [
                                                            'type' => ['string', 'null'],
                                                        ],
                                                    ]
                                                ],
                                            ],
                                            'embargo' => [
                                                'type' => ['string', 'null'],
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'embargosProcesso' => ['type' => ['array', 'null'], 'maxItens' => 0],
                                            'creditosInscricoesProcessos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'creditoInscricaoProcesso' => [
                                                            'type' => ['string', 'null'],
                                                        ],
                                                    ]
                                                ],
                                                'minItens' => 1,
                                            ],
                                            'ajuizado' => [
                                                'type' => 'string',
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'numeroParcelas' => ['type' => 'integer', 'minimum' => 2, 'maximum' => 60],
                                            'infoAdicionais' => ['type' => ['string', 'null']],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'processos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processo' => [
                                                            'type' => ['string', 'null'],
                                                        ],
                                                    ]
                                                ],
                                            ],
                                            'processosJudicial' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoJudicial' => [
                                                            'type' => ['string', 'null'],
                                                        ],
                                                    ]
                                                ],
                                            ],
                                            'nupParcelamentos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'nupParcelamento' => [
                                                            'type' => ['string', 'null'],
                                                        ],
                                                    ]
                                                ],
                                            ],
                                            'infoAdicionais' => ['type' => ['string', 'null']],
                                            'protestado' => [
                                                'type' => 'string',
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'protocolos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'protocolo' => [
                                                            'type' => ['string', 'null'],
                                                        ],
                                                    ]
                                                ],
                                            ],
                                            'embargo' => [
                                                'type' => ['string', 'null'],
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'creditosInscricoesProcessos' => [
                                                'type' => 'array',
                                                'maxItens' => 0,
                                            ],
                                            'numeroParcelas' => ['type' => 'null'],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => ['properties' => ['tipoPessoa' => ['const' => 'fisica']]],
                                    'then' => [
                                        'properties' => [
                                            'cpfCnpj' => [
                                                'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$',
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'cpfCnpj' => [
                                                'pattern' => '^[0-9]{2}\\.?[0-9]{3}\\.?[0-9]{3}/?[0-9]{4}-?[0-9]{2}$',
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => ['tipoTelefone' => ['const' => 'celular']],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => ['string', 'null'],
                                                'pattern' => '^(\\([0-9]{2}\\) [89][0-9]{4}-?[0-9]{4})?$',
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => ['tipoTelefone' => ['const' => 'fixo']],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [0-9]{4}-?[0-9]{4}$',
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => ['tipoTelefone' => ['const' => 'nao_informado']],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'null',
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => ['properties' => ['ajuizado' => ['const' => 'sim']]],
                                    'then' => [
                                        'properties' => [
                                            'numeroAcao' => [
                                                'type' => 'string'
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'numeroAcao' => [
                                                'type' => 'null'
                                            ]
                                        ]
                                    ],
                                ],
                                [
                                    'if' => ['properties' => ['embargo' => ['const' => 'sim']]],
                                    'then' => [
                                        'properties' => [
                                            'embargosProcesso' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'embargoProcesso' => [
                                                            'type' => ['string', 'null'],
                                                        ],
                                                    ]
                                                ],
                                                'minItens' => 0,
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'embargosProcesso' => [
                                                'type' => ['array', 'null'],
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'embargoProcesso' => [
                                                            'type' => ['string', 'null'],
                                                        ],
                                                    ],
                                                ],
                                                'minItens' => 0,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => ['properties' => ['representante' => ['const' => true]]],
                                    'then' => [
                                        'properties' => [
                                            'telefoneComercial' => [
                                                'type' => ['string', 'null'],
                                                'pattern' => '^(\\([0-9]{2}\\) [0-9]{4}-[0-9]{4})?$',
                                            ],
                                            'celular' => [
                                                'type' => ['string', 'null'],
                                                'pattern' => '^(\\([0-9]{2}\\) [89][0-9]{4}-?[0-9]{4})?$',
                                            ],
                                            'nomeRepresentante' => [
                                                'type' => 'string',
                                                'minLength' => 1,
                                            ],
                                            'cpfRepresentante' => [
                                                'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$',
                                            ],
                                            'emailRepresentante' => [
                                                'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                                'format' => 'email',
                                            ],
                                            'oab' => ['type' => ['string', 'null'], 'pattern' => '^[-.0-9]*$'],
                                            'uf' => [
                                                'type' => ['string', 'null'],
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
                                                    null,
                                                ],
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'telefoneComercial' => ['type' => 'null'],
                                            'celular' => ['type' => 'null'],
                                            'nomeRepresentante' => ['type' => 'null'],
                                            'cpfRepresentante' => ['type' => 'null'],
                                            'emailRepresentante' => ['type' => 'null'],
                                            'oab' => ['type' => 'null'],
                                            'uf' => ['type' => 'null'],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => ['tipoSolicitacao' => ['const' => 'outros']],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'tipoSolicitacaoOutros' => [
                                                'type' => 'string',
                                                'minLength' => 1,
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => ['tipoSolicitacaoOutros' => ['type' => 'null']],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'motivoSolicitacao' => [
                                                'type' => 'array',
                                                'items' => ['const' => 'outros'],
                                                'minItens' => 1,
                                                'maxItens' => 1,
                                            ],
                                        ],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'motivoSolicitacaoOutros' => [
                                                'type' => 'string',
                                                'minLength' => 1,
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'motivoSolicitacaoOutros' => ['type' => 'null'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                    ),
                ],
                [
                    'sigla' => 'requerimento_pgf_cobranca_parcelamento'
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
                            '$id' => 'supp_core.administrativo_backend.requerimento_pgf_cobranca_atendimento',
                            'title' => 'FORMULÁRIO DE REQUERIMENTO PGF/SCOB ATENDIMENTO',
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
                                'representante',
                                'nomeRepresentante',
                                'cpfRepresentante',
                                'uf',
                                'emailRepresentante',
                                'tipoSolicitacao',
                                'tipoSolicitacaoOutros',
                                'credor',
                                'motivoSolicitacao',
                                'motivoSolicitacaoOutros',
                                'processos',
                                'processosJudicial',
                                'nupParcelamentos',
                                'infoAdicionais',
                                'protestado',
                                'protocolos',
                                'embargo',
                                'embargosProcesso',
                                'creditosInscricoesProcessos',
                                'ajuizado',
                                'numeroParcelas',
                                'numeroAcao',
                                'ciente',
                            ],
                            'properties' => [
                                'tipoRequerimento' => [
                                    'type' => 'string',
                                    'enum' => [
                                        'requerimento_pgf_cobranca_atendimento',
                                        'requerimento_pgf_cobranca_parcelamento',
                                    ],
                                ],
                                'setorAtual' => ['type' => 'integer', 'minValue' => 0],
                                'tipoPessoa' => ['type' => 'string', 'enum' => ['fisica', 'juridica']],
                                'nomeRazaoSocial' => ['type' => 'string', 'minLength' => 1],
                                'email' => [
                                    'type' => 'string',
                                    'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                    'format' => 'email',
                                ],
                                'confirmeEmail' => [
                                    'type' => 'string',
                                    'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                    'format' => 'email',
                                ],
                                'tipoTelefone' => ['type' => 'string', 'enum' => ['fixo', 'celular', 'nao_informado']],
                                'cep' => [
                                    'type' => 'string',
                                    'pattern' => '^[0-9]{2}\\.?[0-9]{3}[-]?[0-9]{3}$',
                                ],
                                'endereco' => ['type' => 'string', 'minLength' => 1],
                                'complemento' => ['type' => ['string', 'null']],
                                'estado' => ['type' => 'integer', 'minValue' => 0],
                                'municipio' => ['type' => 'integer', 'minValue' => 0],
                                'credor' => ['type' => 'string', 'minLength' => 1],
                                'representante' => ['type' => 'boolean'],
                                'tipoSolicitacao' => [
                                    'type' => 'string',
                                    'enum' => [
                                        'boleto_credito',
                                        'parcelamento_credito',
                                        'informacoes_gerais',
                                        'boleto_parcelamento',
                                        'carta_anuencia',
                                        'outros',
                                    ],
                                ],
                                'ciente' => ['type' => 'boolean'],
                                'motivoSolicitacao' => [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'string',
                                        'enum' => [
                                            'protesto',
                                            'negativacao',
                                            'leilao_judicial',
                                            'penhora',
                                            'execucao_fiscal',
                                            'outros',
                                        ],
                                    ],
                                    'minItens' => 1,
                                ],
                            ],
                            'allOf' => [
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoRequerimento' => [
                                                'const' => 'requerimento_pgf_cobranca_parcelamento',
                                            ],
                                        ],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'processos' => ['type' => 'array', 'maxItens' => 0],
                                            'processosJudicial' => ['type' => 'array', 'maxItens' => 0],
                                            'nupParcelamentos' => ['type' => ['array', 'null'], 'maxItens' => 0],
                                            'protestado' => [
                                                'type' => 'string',
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'protocolos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'protocolo' => [
                                                            'type' => 'string',
                                                        ],
                                                    ],
                                                    'required' => ['protocolo'],
                                                ],
                                            ],
                                            'embargo' => [
                                                'type' => ['string', 'null'],
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'embargosProcesso' => ['type' => ['array', 'null'], 'maxItens' => 0],
                                            'creditosInscricoesProcessos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'creditoInscricaoProcesso' => [
                                                            'type' => ['string', 'null'],
                                                        ],
                                                    ]
                                                ],
                                                'minItens' => 1,
                                            ],
                                            'ajuizado' => [
                                                'type' => 'string',
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'numeroParcelas' => ['type' => 'integer', 'minimum' => 2, 'maximum' => 60],
                                            'infoAdicionais' => ['type' => ['string', 'null']],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'processos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processo' => [
                                                            'type' => ['string', 'null'],
                                                        ],
                                                    ]
                                                ],
                                            ],
                                            'processosJudicial' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoJudicial' => [
                                                            'type' => ['string', 'null'],
                                                        ],
                                                    ]
                                                ],
                                            ],
                                            'nupParcelamentos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'nupParcelamento' => [
                                                            'type' => ['string', 'null'],
                                                        ],
                                                    ]
                                                ],
                                            ],
                                            'infoAdicionais' => ['type' => ['string', 'null']],
                                            'protestado' => [
                                                'type' => 'string',
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'protocolos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'protocolo' => [
                                                            'type' => ['string', 'null'],
                                                        ],
                                                    ]
                                                ],
                                            ],
                                            'embargo' => [
                                                'type' => ['string', 'null'],
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'creditosInscricoesProcessos' => [
                                                'type' => 'array',
                                                'maxItens' => 0,
                                            ],
                                            'numeroParcelas' => ['type' => 'null'],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => ['properties' => ['tipoPessoa' => ['const' => 'fisica']]],
                                    'then' => [
                                        'properties' => [
                                            'cpfCnpj' => [
                                                'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$',
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'cpfCnpj' => [
                                                'pattern' => '^[0-9]{2}\\.?[0-9]{3}\\.?[0-9]{3}/?[0-9]{4}-?[0-9]{2}$',
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => ['tipoTelefone' => ['const' => 'celular']],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => ['string', 'null'],
                                                'pattern' => '^(\\([0-9]{2}\\) [89][0-9]{4}-?[0-9]{4})?$',
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => ['tipoTelefone' => ['const' => 'fixo']],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [0-9]{4}-?[0-9]{4}$',
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => ['tipoTelefone' => ['const' => 'nao_informado']],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'null',
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => ['properties' => ['ajuizado' => ['const' => 'sim']]],
                                    'then' => [
                                        'properties' => [
                                            'numeroAcao' => [
                                                'type' => 'string'
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'numeroAcao' => [
                                                'type' => 'null'
                                            ]
                                        ]
                                    ],
                                ],
                                [
                                    'if' => ['properties' => ['embargo' => ['const' => 'sim']]],
                                    'then' => [
                                        'properties' => [
                                            'embargosProcesso' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'embargoProcesso' => [
                                                            'type' => ['string', 'null'],
                                                        ],
                                                    ]
                                                ],
                                                'minItens' => 0,
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'embargosProcesso' => [
                                                'type' => ['array', 'null'],
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'embargoProcesso' => [
                                                            'type' => ['string', 'null'],
                                                        ],
                                                    ],
                                                ],
                                                'minItens' => 0,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => ['properties' => ['representante' => ['const' => true]]],
                                    'then' => [
                                        'properties' => [
                                            'telefoneComercial' => [
                                                'type' => ['string', 'null'],
                                                'pattern' => '^(\\([0-9]{2}\\) [0-9]{4}-[0-9]{4})?$',
                                            ],
                                            'celular' => [
                                                'type' => ['string', 'null'],
                                                'pattern' => '^(\\([0-9]{2}\\) [89][0-9]{4}-?[0-9]{4})?$',
                                            ],
                                            'nomeRepresentante' => [
                                                'type' => 'string',
                                                'minLength' => 1,
                                            ],
                                            'cpfRepresentante' => [
                                                'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$',
                                            ],
                                            'emailRepresentante' => [
                                                'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                                'format' => 'email',
                                            ],
                                            'oab' => ['type' => ['string', 'null'], 'pattern' => '^[-.0-9]*$'],
                                            'uf' => [
                                                'type' => ['string', 'null'],
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
                                                    null,
                                                ],
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'telefoneComercial' => ['type' => 'null'],
                                            'celular' => ['type' => 'null'],
                                            'nomeRepresentante' => ['type' => 'null'],
                                            'cpfRepresentante' => ['type' => 'null'],
                                            'emailRepresentante' => ['type' => 'null'],
                                            'oab' => ['type' => 'null'],
                                            'uf' => ['type' => 'null'],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => ['tipoSolicitacao' => ['const' => 'outros']],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'tipoSolicitacaoOutros' => [
                                                'type' => 'string',
                                                'minLength' => 1,
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => ['tipoSolicitacaoOutros' => ['type' => 'null']],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'motivoSolicitacao' => [
                                                'type' => 'array',
                                                'items' => ['const' => 'outros'],
                                                'minItens' => 1,
                                                'maxItens' => 1,
                                            ],
                                        ],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'motivoSolicitacaoOutros' => [
                                                'type' => 'string',
                                                'minLength' => 1,
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'motivoSolicitacaoOutros' => ['type' => 'null'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                    ),
                ],
                [
                    'sigla' => 'requerimento_pgf_cobranca_atendimento'
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
                            '$id' => 'supp_core.administrativo_backend.requerimento_pgf_cobranca_parcelamento',
                            'title' => 'FORMULÁRIO DE REQUERIMENTO PGF/SCOB PARCELAMENTO',
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
                                'representante',
                                'nomeRepresentante',
                                'cpfRepresentante',
                                'uf',
                                'emailRepresentante',
                                'tipoSolicitacao',
                                'tipoSolicitacaoOutros',
                                'credor',
                                'motivoSolicitacao',
                                'motivoSolicitacaoOutros',
                                'processos',
                                'processosJudicial',
                                'nupParcelamentos',
                                'infoAdicionais',
                                'protestado',
                                'protocolos',
                                'embargo',
                                'embargosProcesso',
                                'creditosInscricoesProcessos',
                                'ajuizado',
                                'numeroParcelas',
                                'numeroAcao',
                                'ciente',
                            ],
                            'properties' => [
                                'tipoRequerimento' => [
                                    'type' => 'string',
                                    'enum' => [
                                        'requerimento_pgf_cobranca_atendimento',
                                        'requerimento_pgf_cobranca_parcelamento',
                                    ],
                                ],
                                'setorAtual' => ['type' => 'integer', 'minValue' => 0],
                                'tipoPessoa' => ['type' => 'string', 'enum' => ['fisica', 'juridica']],
                                'nomeRazaoSocial' => ['type' => 'string', 'minLength' => 1],
                                'email' => [
                                    'type' => 'string',
                                    'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                    'format' => 'email',
                                ],
                                'confirmeEmail' => [
                                    'type' => 'string',
                                    'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                    'format' => 'email',
                                ],
                                'tipoTelefone' => ['type' => 'string', 'enum' => ['fixo', 'celular', 'nao_informado']],
                                'cep' => [
                                    'type' => 'string',
                                    'pattern' => '^[0-9]{2}\\.?[0-9]{3}[-]?[0-9]{3}$',
                                ],
                                'endereco' => ['type' => 'string', 'minLength' => 1],
                                'complemento' => ['type' => ['string', 'null']],
                                'estado' => ['type' => 'integer', 'minValue' => 0],
                                'municipio' => ['type' => 'integer', 'minValue' => 0],
                                'credor' => ['type' => 'string', 'minLength' => 1],
                                'representante' => ['type' => 'boolean'],
                                'tipoSolicitacao' => [
                                    'type' => 'string',
                                    'enum' => [
                                        'boleto_credito',
                                        'parcelamento_credito',
                                        'informacoes_gerais',
                                        'boleto_parcelamento',
                                        'carta_anuencia',
                                        'outros',
                                    ],
                                ],
                                'ciente' => ['type' => 'boolean'],
                                'motivoSolicitacao' => [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'string',
                                        'enum' => [
                                            'protesto',
                                            'negativacao',
                                            'leilao_judicial',
                                            'penhora',
                                            'execucao_fiscal',
                                            'outros',
                                        ],
                                    ],
                                    'minItens' => 1,
                                ],
                            ],
                            'allOf' => [
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoRequerimento' => [
                                                'const' => 'requerimento_pgf_cobranca_parcelamento',
                                            ],
                                        ],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'processos' => ['type' => 'array', 'maxItens' => 0],
                                            'processosJudicial' => ['type' => 'array', 'maxItens' => 0],
                                            'nupParcelamentos' => ['type' => ['array', 'null'], 'maxItens' => 0],
                                            'protestado' => [
                                                'type' => 'string',
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'protocolos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'protocolo' => [
                                                            'type' => 'string',
                                                        ],
                                                    ],
                                                    'required' => ['protocolo'],
                                                ],
                                            ],
                                            'embargo' => [
                                                'type' => ['string', 'null'],
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'embargosProcesso' => ['type' => ['array', 'null'], 'maxItens' => 0],
                                            'creditosInscricoesProcessos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'creditoInscricaoProcesso' => [
                                                            'pattern' => "^[\\/\-.0-9]+$",
                                                        ],
                                                    ],
                                                    'required' => ['creditoInscricaoProcesso'],
                                                ],
                                                'minItens' => 1,
                                            ],
                                            'ajuizado' => [
                                                'type' => 'string',
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'numeroParcelas' => ['type' => 'integer', 'minimum' => 2, 'maximum' => 60],
                                            'infoAdicionais' => ['type' => ['string', 'null']],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'processos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processo' => [
                                                            'type' => 'string',
                                                            'pattern' => '^[0-9]{5}\\.[0-9]{6}\\-[0-9]{4}/[0-9]{2}$',
                                                        ],
                                                    ],
                                                    'required' => ['processo'],
                                                ],
                                            ],
                                            'processosJudicial' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoJudicial' => [
                                                            'type' => 'string',
                                                            'pattern' => '^[0-9]{7}\\-[0-9]{2}\\.[0-9]{4}\\.[0-9]\\.[0-9]{2}\\.[0-9]{4}([.-][0-9]{2})?$',
                                                        ],
                                                    ],
                                                    'required' => ['processoJudicial'],
                                                ],
                                            ],
                                            'nupParcelamentos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'nupParcelamento' => [
                                                            'type' => 'string',
                                                            'pattern' => '^[0-9]{5}\\.[0-9]{6}\\-[0-9]{4}/[0-9]{2}$',
                                                        ],
                                                    ],
                                                    'required' => ['nupParcelamento'],
                                                ],
                                            ],
                                            'infoAdicionais' => ['type' => ['string', 'null']],
                                            'protestado' => [
                                                'type' => 'string',
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'protocolos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'protocolo' => [
                                                            'type' => 'string',
                                                        ],
                                                    ],
                                                    'required' => ['protocolo'],
                                                ],
                                            ],
                                            'embargo' => [
                                                'type' => ['string', 'null'],
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'creditosInscricoesProcessos' => [
                                                'type' => 'array',
                                                'maxItens' => 0,
                                            ],
                                            'numeroParcelas' => ['type' => 'null'],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => ['properties' => ['tipoPessoa' => ['const' => 'fisica']]],
                                    'then' => [
                                        'properties' => [
                                            'cpfCnpj' => [
                                                'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$',
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'cpfCnpj' => [
                                                'pattern' => '^[0-9]{2}\\.?[0-9]{3}\\.?[0-9]{3}/?[0-9]{4}-?[0-9]{2}$',
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => ['tipoTelefone' => ['const' => 'celular']],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [89][0-9]{4}-?[0-9]{4}$',
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => ['tipoTelefone' => ['const' => 'fixo']],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [0-9]{4}-?[0-9]{4}$',
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => ['tipoTelefone' => ['const' => 'nao_informado']],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'null',
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => ['properties' => ['ajuizado' => ['const' => 'sim']]],
                                    'then' => [
                                        'properties' => [
                                            'numeroAcao' => [
                                                'pattern' => '^[0-9]{7}\\-[0-9]{2}\\.[0-9]{4}\\.[0-9]\\.[0-9]{2}\\.[0-9]{4}([.-][0-9]{2})?$',
                                            ],
                                        ],
                                    ],
                                    'else' => ['properties' => ['numeroAcao' => ['type' => 'null']]],
                                ],
                                [
                                    'if' => ['properties' => ['embargo' => ['const' => 'sim']]],
                                    'then' => [
                                        'properties' => [
                                            'embargosProcesso' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'embargoProcesso' => [
                                                            'type' => 'string',
                                                            'pattern' => '^[0-9]{7}\\-[0-9]{2}\\.[0-9]{4}\\.[0-9]\\.[0-9]{2}\\.[0-9]{4}([.-][0-9]{2})?$',
                                                        ],
                                                    ],
                                                    'required' => ['embargoProcesso'],
                                                ],
                                                'minItens' => 1,
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'embargosProcesso' => [
                                                'type' => ['array', 'null'],
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'embargoProcesso' => [
                                                            'type' => 'string',
                                                            'pattern' => '^[0-9]{7}\\-[0-9]{2}\\.[0-9]{4}\\.[0-9]\\.[0-9]{2}\\.[0-9]{4}([.-][0-9]{2})?$',
                                                        ],
                                                    ],
                                                    'required' => ['embargoProcesso'],
                                                ],
                                                'minItens' => 0,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => ['properties' => ['representante' => ['const' => true]]],
                                    'then' => [
                                        'properties' => [
                                            'telefoneComercial' => [
                                                'type' => ['string', 'null'],
                                                'pattern' => '^\\([0-9]{2}\\) [0-9]{4}-[0-9]{4}$',
                                            ],
                                            'celular' => [
                                                'type' => ['string', 'null'],
                                                'pattern' => '^\\([0-9]{2}\\) [89][0-9]{4}-[0-9]{4}$',
                                            ],
                                            'nomeRepresentante' => [
                                                'type' => 'string',
                                                'minLength' => 1,
                                            ],
                                            'cpfRepresentante' => [
                                                'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$',
                                            ],
                                            'emailRepresentante' => [
                                                'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                                'format' => 'email',
                                            ],
                                            'oab' => ['type' => ['string', 'null'], 'pattern' => '^[-.0-9]*$'],
                                            'uf' => [
                                                'type' => ['string', 'null'],
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
                                                    null,
                                                ],
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'telefoneComercial' => ['type' => 'null'],
                                            'celular' => ['type' => 'null'],
                                            'nomeRepresentante' => ['type' => 'null'],
                                            'cpfRepresentante' => ['type' => 'null'],
                                            'emailRepresentante' => ['type' => 'null'],
                                            'oab' => ['type' => 'null'],
                                            'uf' => ['type' => 'null'],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => ['tipoSolicitacao' => ['const' => 'outros']],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'tipoSolicitacaoOutros' => [
                                                'type' => 'string',
                                                'minLength' => 1,
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => ['tipoSolicitacaoOutros' => ['type' => 'null']],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'motivoSolicitacao' => [
                                                'type' => 'array',
                                                'items' => ['const' => 'outros'],
                                                'minItens' => 1,
                                                'maxItens' => 1,
                                            ],
                                        ],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'motivoSolicitacaoOutros' => [
                                                'type' => 'string',
                                                'minLength' => 1,
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'motivoSolicitacaoOutros' => ['type' => 'null'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                    ),
                ],
                [
                    'sigla' => 'requerimento_pgf_cobranca_parcelamento'
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
                            '$id' => 'supp_core.administrativo_backend.requerimento_pgf_cobranca_atendimento',
                            'title' => 'FORMULÁRIO DE REQUERIMENTO PGF/SCOB ATENDIMENTO',
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
                                'representante',
                                'nomeRepresentante',
                                'cpfRepresentante',
                                'uf',
                                'emailRepresentante',
                                'tipoSolicitacao',
                                'tipoSolicitacaoOutros',
                                'credor',
                                'motivoSolicitacao',
                                'motivoSolicitacaoOutros',
                                'processos',
                                'processosJudicial',
                                'nupParcelamentos',
                                'infoAdicionais',
                                'protestado',
                                'protocolos',
                                'embargo',
                                'embargosProcesso',
                                'creditosInscricoesProcessos',
                                'ajuizado',
                                'numeroParcelas',
                                'numeroAcao',
                                'ciente',
                            ],
                            'properties' => [
                                'tipoRequerimento' => [
                                    'type' => 'string',
                                    'enum' => [
                                        'requerimento_pgf_cobranca_atendimento',
                                        'requerimento_pgf_cobranca_parcelamento',
                                    ],
                                ],
                                'setorAtual' => ['type' => 'integer', 'minValue' => 0],
                                'tipoPessoa' => ['type' => 'string', 'enum' => ['fisica', 'juridica']],
                                'nomeRazaoSocial' => ['type' => 'string', 'minLength' => 1],
                                'email' => [
                                    'type' => 'string',
                                    'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                    'format' => 'email',
                                ],
                                'confirmeEmail' => [
                                    'type' => 'string',
                                    'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                    'format' => 'email',
                                ],
                                'tipoTelefone' => ['type' => 'string', 'enum' => ['fixo', 'celular', 'nao_informado']],
                                'cep' => [
                                    'type' => 'string',
                                    'pattern' => '^[0-9]{2}\\.?[0-9]{3}[-]?[0-9]{3}$',
                                ],
                                'endereco' => ['type' => 'string', 'minLength' => 1],
                                'complemento' => ['type' => ['string', 'null']],
                                'estado' => ['type' => 'integer', 'minValue' => 0],
                                'municipio' => ['type' => 'integer', 'minValue' => 0],
                                'credor' => ['type' => 'string', 'minLength' => 1],
                                'representante' => ['type' => 'boolean'],
                                'tipoSolicitacao' => [
                                    'type' => 'string',
                                    'enum' => [
                                        'boleto_credito',
                                        'parcelamento_credito',
                                        'informacoes_gerais',
                                        'boleto_parcelamento',
                                        'carta_anuencia',
                                        'outros',
                                    ],
                                ],
                                'ciente' => ['type' => 'boolean'],
                                'motivoSolicitacao' => [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'string',
                                        'enum' => [
                                            'protesto',
                                            'negativacao',
                                            'leilao_judicial',
                                            'penhora',
                                            'execucao_fiscal',
                                            'outros',
                                        ],
                                    ],
                                    'minItens' => 1,
                                ],
                            ],
                            'allOf' => [
                                [
                                    'if' => [
                                        'properties' => [
                                            'tipoRequerimento' => [
                                                'const' => 'requerimento_pgf_cobranca_parcelamento',
                                            ],
                                        ],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'processos' => ['type' => 'array', 'maxItens' => 0],
                                            'processosJudicial' => ['type' => 'array', 'maxItens' => 0],
                                            'nupParcelamentos' => ['type' => ['array', 'null'], 'maxItens' => 0],
                                            'protestado' => [
                                                'type' => 'string',
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'protocolos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'protocolo' => [
                                                            'type' => 'string',
                                                        ],
                                                    ],
                                                    'required' => ['protocolo'],
                                                ],
                                            ],
                                            'embargo' => [
                                                'type' => ['string', 'null'],
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'embargosProcesso' => ['type' => ['array', 'null'], 'maxItens' => 0],
                                            'creditosInscricoesProcessos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'creditoInscricaoProcesso' => [
                                                            'pattern' => "^[\\/\-.0-9]+$",
                                                        ],
                                                    ],
                                                    'required' => ['creditoInscricaoProcesso'],
                                                ],
                                                'minItens' => 1,
                                            ],
                                            'ajuizado' => [
                                                'type' => 'string',
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'numeroParcelas' => ['type' => 'integer', 'minimum' => 2, 'maximum' => 60],
                                            'infoAdicionais' => ['type' => ['string', 'null']],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'processos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processo' => [
                                                            'type' => 'string',
                                                            'pattern' => '^[0-9]{5}\\.[0-9]{6}\\-[0-9]{4}/[0-9]{2}$',
                                                        ],
                                                    ],
                                                    'required' => ['processo'],
                                                ],
                                            ],
                                            'processosJudicial' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'processoJudicial' => [
                                                            'type' => 'string',
                                                            'pattern' => '^[0-9]{7}\\-[0-9]{2}\\.[0-9]{4}\\.[0-9]\\.[0-9]{2}\\.[0-9]{4}([.-][0-9]{2})?$',
                                                        ],
                                                    ],
                                                    'required' => ['processoJudicial'],
                                                ],
                                            ],
                                            'nupParcelamentos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'nupParcelamento' => [
                                                            'type' => 'string',
                                                            'pattern' => '^[0-9]{5}\\.[0-9]{6}\\-[0-9]{4}/[0-9]{2}$',
                                                        ],
                                                    ],
                                                    'required' => ['nupParcelamento'],
                                                ],
                                            ],
                                            'infoAdicionais' => ['type' => ['string', 'null']],
                                            'protestado' => [
                                                'type' => 'string',
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'protocolos' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'protocolo' => [
                                                            'type' => 'string',
                                                        ],
                                                    ],
                                                    'required' => ['protocolo'],
                                                ],
                                            ],
                                            'embargo' => [
                                                'type' => ['string', 'null'],
                                                'enum' => ['sim', 'nao', 'nao_sabe'],
                                            ],
                                            'creditosInscricoesProcessos' => [
                                                'type' => 'array',
                                                'maxItens' => 0,
                                            ],
                                            'numeroParcelas' => ['type' => 'null'],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => ['properties' => ['tipoPessoa' => ['const' => 'fisica']]],
                                    'then' => [
                                        'properties' => [
                                            'cpfCnpj' => [
                                                'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$',
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'cpfCnpj' => [
                                                'pattern' => '^[0-9]{2}\\.?[0-9]{3}\\.?[0-9]{3}/?[0-9]{4}-?[0-9]{2}$',
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => ['tipoTelefone' => ['const' => 'celular']],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [89][0-9]{4}-?[0-9]{4}$',
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => ['tipoTelefone' => ['const' => 'fixo']],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'string',
                                                'pattern' => '^\\([0-9]{2}\\) [0-9]{4}-?[0-9]{4}$',
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => ['tipoTelefone' => ['const' => 'nao_informado']],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'telefone' => [
                                                'type' => 'null',
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => ['properties' => ['ajuizado' => ['const' => 'sim']]],
                                    'then' => [
                                        'properties' => [
                                            'numeroAcao' => [
                                                'pattern' => '^[0-9]{7}\\-[0-9]{2}\\.[0-9]{4}\\.[0-9]\\.[0-9]{2}\\.[0-9]{4}([.-][0-9]{2})?$',
                                            ],
                                        ],
                                    ],
                                    'else' => ['properties' => ['numeroAcao' => ['type' => 'null']]],
                                ],
                                [
                                    'if' => ['properties' => ['embargo' => ['const' => 'sim']]],
                                    'then' => [
                                        'properties' => [
                                            'embargosProcesso' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'embargoProcesso' => [
                                                            'type' => 'string',
                                                            'pattern' => '^[0-9]{7}\\-[0-9]{2}\\.[0-9]{4}\\.[0-9]\\.[0-9]{2}\\.[0-9]{4}([.-][0-9]{2})?$',
                                                        ],
                                                    ],
                                                    'required' => ['embargoProcesso'],
                                                ],
                                                'minItens' => 1,
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'embargosProcesso' => [
                                                'type' => ['array', 'null'],
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'embargoProcesso' => [
                                                            'type' => 'string',
                                                            'pattern' => '^[0-9]{7}\\-[0-9]{2}\\.[0-9]{4}\\.[0-9]\\.[0-9]{2}\\.[0-9]{4}([.-][0-9]{2})?$',
                                                        ],
                                                    ],
                                                    'required' => ['embargoProcesso'],
                                                ],
                                                'minItens' => 0,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => ['properties' => ['representante' => ['const' => true]]],
                                    'then' => [
                                        'properties' => [
                                            'telefoneComercial' => [
                                                'type' => ['string', 'null'],
                                                'pattern' => '^\\([0-9]{2}\\) [0-9]{4}-[0-9]{4}$',
                                            ],
                                            'celular' => [
                                                'type' => ['string', 'null'],
                                                'pattern' => '^\\([0-9]{2}\\) [89][0-9]{4}-[0-9]{4}$',
                                            ],
                                            'nomeRepresentante' => [
                                                'type' => 'string',
                                                'minLength' => 1,
                                            ],
                                            'cpfRepresentante' => [
                                                'pattern' => '^[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}$',
                                            ],
                                            'emailRepresentante' => [
                                                'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                                'format' => 'email',
                                            ],
                                            'oab' => ['type' => ['string', 'null'], 'pattern' => '^[-.0-9]*$'],
                                            'uf' => [
                                                'type' => ['string', 'null'],
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
                                                    null,
                                                ],
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'telefoneComercial' => ['type' => 'null'],
                                            'celular' => ['type' => 'null'],
                                            'nomeRepresentante' => ['type' => 'null'],
                                            'cpfRepresentante' => ['type' => 'null'],
                                            'emailRepresentante' => ['type' => 'null'],
                                            'oab' => ['type' => 'null'],
                                            'uf' => ['type' => 'null'],
                                        ],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => ['tipoSolicitacao' => ['const' => 'outros']],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'tipoSolicitacaoOutros' => [
                                                'type' => 'string',
                                                'minLength' => 1,
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => ['tipoSolicitacaoOutros' => ['type' => 'null']],
                                    ],
                                ],
                                [
                                    'if' => [
                                        'properties' => [
                                            'motivoSolicitacao' => [
                                                'type' => 'array',
                                                'items' => ['const' => 'outros'],
                                                'minItens' => 1,
                                                'maxItens' => 1,
                                            ],
                                        ],
                                    ],
                                    'then' => [
                                        'properties' => [
                                            'motivoSolicitacaoOutros' => [
                                                'type' => 'string',
                                                'minLength' => 1,
                                            ],
                                        ],
                                    ],
                                    'else' => [
                                        'properties' => [
                                            'motivoSolicitacaoOutros' => ['type' => 'null'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                    ),
                ],
                [
                    'sigla' => 'requerimento_pgf_cobranca_atendimento'
                ]
            )
        );
    }
}
