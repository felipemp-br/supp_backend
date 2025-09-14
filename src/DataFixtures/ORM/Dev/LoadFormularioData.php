<?php

declare(strict_types=1);
/**
 * /src/DevDataFixtures/ORM/Dev/LoadFormularioDatamas o.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Formulario;

/**
 * Class LoadFormularioDatamas o.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadFormularioData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        if (null === $manager
                ->createQuery(
                    "
                SELECT f 
                FROM SuppCore\AdministrativoBackend\Entity\Formulario f 
                WHERE f.nome = 'FORMULÁRIO DE REQUERIMENTO PGF/SCOB PARCELAMENTO'"
                )
                ->getOneOrNullResult()) {
            $formulario1 = new Formulario();
            $formulario1->setNome('FORMULÁRIO DE REQUERIMENTO PGF/SCOB PARCELAMENTO');
            $formulario1->setSigla('requerimento_pgf_cobranca_parcelamento');
            $formulario1->setTemplate(
                'Resources/Processo/Requerimentos/requerimento_pgf_cobranca_parcelamento.html.twig'
            );
            $formulario1->setDataSchema(
                json_encode(
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
                )
            );
            $manager->persist($formulario1);
            $this->addReference('Formulario-' . $formulario1->getNome(), $formulario1);
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT f 
                FROM SuppCore\AdministrativoBackend\Entity\Formulario f 
                WHERE f.nome = 'FORMULÁRIO DE REQUERIMENTO PGF/SCOB ATENDIMENTO'"
                )
                ->getOneOrNullResult()) {
            $formulario2 = new Formulario();
            $formulario2->setNome('FORMULÁRIO DE REQUERIMENTO PGF/SCOB ATENDIMENTO');
            $formulario2->setSigla('requerimento_pgf_cobranca_atendimento');
            $formulario2->setTemplate(
                'Resources/Processo/Requerimentos/requerimento_pgf_cobranca_atendimento.html.twig'
            );
            $formulario2->setDataSchema(
                json_encode(
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
                )
            );
            $manager->persist($formulario2);
            $this->addReference('Formulario-' . $formulario2->getNome(), $formulario2);
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT f 
                FROM SuppCore\AdministrativoBackend\Entity\Formulario f 
                WHERE f.nome = 'FORMULÁRIO DE REQUERIMENTO PAC PREV SALÁRIO MATERNIDADE ESPECIAL'"
                )
                ->getOneOrNullResult()) {
            $formulario3 = new Formulario();
            $formulario3->setNome('FORMULÁRIO DE REQUERIMENTO PAC PREV SALÁRIO MATERNIDADE ESPECIAL');
            $formulario3->setSigla('requerimento_pgf_previdencario_conciliacao_salario_maternidade_especial');
            $formulario3->setTemplate(
                'Resources/Processo/Requerimentos/requerimento_pgf_previdencario_conciliacao_salario_maternidade_especial.html.twig'
            );
            $formulario3->setDataSchema(
                json_encode(
                    [
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'supp_core.administrativo_backend.requerimento_pgf_previdencario_conciliacao_salario_maternidade_especial',
                        'title' => 'FORMULÁRIO DE REQUERIMENTO PAC PREV SALÁRIO MATERNIDADE ESPECIAL',
                        'type' => 'object',
                        'required' => [
                            'tipoRequerimento',
                            'nomeRequerente',
                            'cpfRequerente',
                            'rua',
                            'numero',
                            'estado',
                            'municipio',
                            'email',
                            'dataNascimento',
                            'genero',
                            'numeroBeneficio',
                            'dataEntradaRequerimento',
                            'dataNascimentoCrianca',
                            'teveAjuda',
                            'entrouContatoJustica',
                            'atividadesRurais',
                            'membroFamiliaRecebeuBeneficio',
                            'tiposDocumentos',
                        ],
                        'properties' => [
                            'tipoRequerimento' => [
                                'title' => 'Tipo Requerimento',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'nomeRequerente' => [
                                'title' => 'Nome Requerente',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'cpfRequerente' => [
                                'title' => 'CPF Requerente',
                                'type' => 'string',
                                'pattern' => '^[0-9]{3}.?[0-9]{3}.?[0-9]{3}-?[0-9]{2}$',
                            ],
                            'nomeConjuge' => [
                                'title' => 'Nome Conjuge',
                                'type' => [
                                    'string',
                                    'null',
                                ],
                                'minLength' => 1,
                            ],
                            'cpfConjuge' => [
                                'title' => 'CPF Conjuge',
                                'type' => [
                                    'string',
                                    'null',
                                ],
                                'pattern' => '^[0-9]{3}.?[0-9]{3}.?[0-9]{3}-?[0-9]{2}$',
                            ],
                            'rua' => [
                                'title' => 'Rua',
                                'type' => 'string',
                                'minLength' => 1,
                            ],
                            'numero' => [
                                'title' => 'Numero',
                                'type' => 'string',
                                'minLength' => 1,
                                'pattern' => "^\d+$",
                            ],
                            'complemento' => [
                                'title' => 'Complemento',
                                'type' => [
                                    'string',
                                    'null',
                                ],
                                'minLength' => 1,
                            ],
                            'estado' => [
                                'title' => 'Estado',
                                'type' => 'integer',
                                'minValue' => 0,
                            ],
                            'municipio' => [
                                'title' => 'Municipio',
                                'type' => 'integer',
                                'minValue' => 0,
                            ],
                            'email' => [
                                'title' => 'E-mail',
                                'type' => 'string',
                                'minLength' => 1,
                                'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                                'format' => 'email',
                            ],
                            'dataNascimento' => [
                                'title' => 'Data Nascimento',
                                'type' => 'string',
                                'format' => 'date-time',
                                'examples' => [
                                    '2023-01-01T00:00:00-00:00',
                                ],
                            ],
                            'genero' => [
                                'title' => 'Genero',
                                'type' => 'string',
                                'enum' => [
                                    'feminino',
                                    'masculino',
                                    'outro',
                                ],
                            ],
                            'numeroBeneficio' => [
                                'title' => 'Numero Beneficio',
                                'type' => [
                                    'string',
                                    'null',
                                ],
                                'minLength' => 1,
                                'pattern' => '^([0-9]{1}.)?([0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{1})$',
                            ],
                            'dataEntradaRequerimento' => [
                                'title' => 'Data Entrada Requerimento',
                                'type' => 'string',
                                'format' => 'date-time',
                            ],
                            'dataNascimentoCrianca' => [
                                'title' => 'Data Nascimento Crianca',
                                'type' => 'string',
                                'format' => 'date-time',
                            ],
                            'teveAjuda' => [
                                'title' => 'Teve ajuda',
                                'type' => 'boolean',
                                'default' => false,
                            ],
                            'relacao' => [
                                'title' => 'Relacao',
                                'type' => ['string', 'null'],
                                'enum' => [
                                    null,
                                    'defensoria_publica',
                                    'advogado_privado',
                                    'outro_profissional',
                                    'amigo',
                                    'parente',
                                ],
                            ],
                            'entrouContatoJustica' => [
                                'title' => 'Entrou em Contato com a Justica',
                                'type' => 'boolean',
                                'default' => false,
                                'enum' => [
                                    false,
                                ],
                            ],
                            'atividadesRurais' => [
                                'title' => 'Periodos Atividade Rural',
                                'type' => 'array',
                                'minItems' => 1,
                                'items' => [
                                    'title' => 'Items',
                                    'type' => 'object',
                                    'properties' => [
                                        'dataInicio' => [
                                            'type' => 'string',
                                            'format' => 'date-time',
                                        ],
                                        'dataFim' => [
                                            'type' => 'string',
                                            'format' => 'date-time',
                                        ],
                                        'estado' => [
                                            'type' => 'integer',
                                            'minValue' => 0,
                                        ],
                                        'municipio' => [
                                            'type' => 'integer',
                                            'minValue' => 0,
                                        ],
                                    ],
                                ],
                            ],
                            'membroFamiliaRecebeuBeneficio' => [
                                'title' => 'Membro da Familia Recebeu Beneficio',
                                'type' => 'boolean',
                                'default' => false,
                            ],
                            'tiposDocumentos' => [
                                'title' => 'Tipos Documentos',
                                'type' => 'array',
                                'minItems' => 1,
                                'maxItems' => 3,
                                'items' => [
                                    'title' => 'Items',
                                    'type' => 'string',
                                    'enum' => [
                                        'carteira_vacinacao_gestante',
                                        'ficha_registro_livros',
                                        'ficha_atendimento_medico',
                                        'certidao_casamento_uniao_estavel',
                                        'certidao_nascimento_batismo',
                                        'titulo_certidao_eleitoral',
                                        'comprovante_escolar',
                                        'contrato_arrendamento',
                                        'declaracao_programa_agricultura_familiar',
                                        'licenca_incra',
                                        'comprovante_pagamento_itr',
                                        'certidao_funai',
                                        'escritura_imovel_rural',
                                        'outros',
                                    ],
                                ],
                            ],
                        ],
                        'allOf' => [
                            [
                                'if' => [
                                    'properties' => [
                                        'teveAjuda' => [
                                            'const' => true,
                                        ],
                                    ],
                                ],
                                'then' => [
                                    'required' => [
                                        'relacao',
                                    ],
                                    'properties' => [
                                        'nomeRepresentante' => [
                                            'type' => ['string', 'null'],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'if' => [
                                    'properties' => [
                                        'relacao' => [
                                            'const' => 'advogado_privado',
                                        ],
                                    ],
                                ],
                                'then' => [
                                    'properties' => [
                                        'oab' => [
                                            'title' => 'OAB',
                                            'type' => [
                                                'string',
                                                'null',
                                            ],
                                            'pattern' => '^[-.0-9]*$',
                                        ],
                                        'uf' => [
                                            'title' => 'UF',
                                            'type' => [
                                                'string',
                                                'null',
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
                                                null,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'if' => [
                                    'properties' => [
                                        'membroFamiliaRecebeuBeneficio' => [
                                            'const' => true,
                                        ],
                                    ],
                                ],
                                'then' => [
                                    'required' => [
                                        'beneficiarios',
                                    ],
                                    'properties' => [
                                        'beneficiarios' => [
                                            'title' => 'Beneficiarios',
                                            'type' => 'array',
                                            'items' => [
                                                'allOf' => [
                                                    [
                                                        'title' => 'Items',
                                                        'type' => 'object',
                                                        'required' => [
                                                            'nomeBeneficiario',
                                                            'parentescoBeneficiario',
                                                        ],
                                                        'properties' => [
                                                            'nomeBeneficiario' => [
                                                                'title' => 'Nome Beneficiario',
                                                                'type' => 'string',
                                                                'minLength' => 1,
                                                            ],
                                                            'cpfBeneficiario' => [
                                                                'title' => 'CPF Beneficiario',
                                                                'type' => ['string', 'null'],
                                                                'pattern' => '^[0-9]{3}.?[0-9]{3}.?[0-9]{3}-?[0-9]{2}$',
                                                            ],
                                                            'parentescoBeneficiario' => [
                                                                'title' => 'Parentesco Beneficiario',
                                                                'type' => 'string',
                                                                'enum' => [
                                                                    'pai',
                                                                    'mae',
                                                                    'filho',
                                                                    'filha',
                                                                    'conjuge',
                                                                    'irmao',
                                                                    'irma',
                                                                    'primo',
                                                                    'prima',
                                                                    'cunhado',
                                                                    'cunhada',
                                                                    'sobrinho',
                                                                    'sobrinha',
                                                                    'tio',
                                                                    'tia',
                                                                    'outro',
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                    [
                                                        'if' => [
                                                            'properties' => [
                                                                'parentescoBeneficiario' => [
                                                                    'const' => 'outro',
                                                                ],
                                                            ],
                                                        ],
                                                        'then' => [
                                                            'required' => [
                                                                'especificarParentescoBeneficiario',
                                                            ],
                                                            'properties' => [
                                                                'especificarParentescoBeneficiario' => [
                                                                    'title' => 'Especificar Parentesco Beneficiario',
                                                                    'type' => 'string',
                                                                    'minLength' => 1,
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                            'minItems' => 1,
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'if' => [
                                    'properties' => [
                                        'tiposDocumentos' => [
                                            'contains' => [
                                                'pattern' => 'outros',
                                            ],
                                        ],
                                    ],
                                ],
                                'then' => [
                                    'required' => [
                                        'descricaoOutros',
                                    ],
                                    'properties' => [
                                        'descricaoOutros' => [
                                            'type' => 'string',
                                            'minLength' => 3,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );
            $manager->persist($formulario3);
            $this->addReference('Formulario-' . $formulario3->getNome(), $formulario3);
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT f 
                FROM SuppCore\AdministrativoBackend\Entity\Formulario f 
                WHERE f.nome = 'FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO EXTRAORDINÁRIA PGF'"
                )
                ->getOneOrNullResult()) {
            $formulario3 = new Formulario();
            $formulario3->setNome('FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO EXTRAORDINÁRIA PGF');
            $formulario3->setSigla('requerimento_transacao_adesao_pgf');
            $formulario3->setTemplate(
                'Resources/Processo/Requerimentos/requerimento_transacao_adesao_pgf.html.twig'
            );
            $formulario3->setDataSchema(
                json_encode(
                    [
                        "\$schema" => "http://json-schema.org/draft-07/schema#",
                        "\$id" => "supp_core.administrativo_backend.requerimento_transacao_adesao_pgf",
                        "title" => "FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO EXTRAORDINÁRIA PGF",
                        "type" => "object",
                        "required" => [
                            "setorAtual",
                            "tipoRequerimento",
                            "tipoPessoa",
                            "cpfCnpj",
                            "nomeRazaoSocial",
                            "email",
                            "confirmeEmail",
                            "tipoTelefone",
                            "telefone",
                            "cep",
                            "endereco",
                            "complemento",
                            "estado",
                            "municipio",
                            "tipoSolicitacao",
                            "credor",
                            "ciente"
                        ],
                        "properties" => [
                            "setorAtual" => [
                                "title" => "Setor atual",
                                "type" => "integer",
                                "minValue" => 0
                            ],
                            "tipoRequerimento" => [
                                "title" => "Tipo requerimento",
                                "type" => "string",
                                "enum" => [
                                    "requerimento_transacao_adesao_pgf",
                                    "requerimento_transacao_informacoes_pgf"
                                ]
                            ],
                            "tipoPessoa" => [
                                "title" => "Tipo pessoa",
                                "type" => "string",
                                "enum" => [
                                    "fisica",
                                    "juridica"
                                ]
                            ],
                            "nomeRazaoSocial" => [
                                "title" => "Nome razão social",
                                "type" => "string",
                                "minLength" => 1
                            ],
                            "email" => [
                                "title" => "E-mail",
                                "type" => "string",
                                "pattern" => "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$",
                                "format" => "email"
                            ],
                            "confirmeEmail" => [
                                "title" => "Confirme e-mail",
                                "type" => "string",
                                "pattern" => "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$",
                                "format" => "email"
                            ],
                            "tipoTelefone" => [
                                "title" => "Tipo telefone",
                                "type" => "string",
                                "enum" => [
                                    "fixo",
                                    "celular",
                                    "nao_informado"
                                ]
                            ],
                            "cep" => [
                                "title" => "CEP",
                                "type" => "string",
                                "pattern" => "^[0-9]{2}\.?[0-9]{3}[-]?[0-9]{3}$"
                            ],
                            "endereco" => [
                                "title" => "Endereço",
                                "type" => "string",
                                "minLength" => 1
                            ],
                            "complemento" => [
                                "title" => "Complemento",
                                "type" => [
                                    "string",
                                    "null"
                                ]
                            ],
                            "estado" => [
                                "title" => "Estado",
                                "type" => "integer",
                                "minValue" => 0
                            ],
                            "municipio" => [
                                "title" => "Município",
                                "type" => "integer",
                                "minValue" => 0
                            ],
                            "credor" => [
                                "title" => "Credor",
                                "type" => "string",
                                "minLength" => 1
                            ],
                            "tipoSolicitacao" => [
                                "title" => "Tipo solicitação",
                                "type" => "string",
                                "enum" => [
                                    "transacao_adesao_pgf",
                                    "transacao_informacoes_gerais"
                                ]
                            ],
                            "cnpjsFiliais" => [
                                "title" => "CNPJs Filiais",
                                "type" => [
                                    "string",
                                    "null"
                                ]
                            ],
                            "ciente" => [
                                "title" => "Ciente",
                                "type" => "boolean"
                            ]
                        ],
                        "allOf" => [
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoRequerimento" => [
                                            "const" => "requerimento_transacao_adesao_pgf"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "devedorPessoaFisicaEDemais" => [
                                        "title" => "Devedor pessoa fisica e MEI ou demais organizações sociedade civil",
                                        "type" => "boolean"
                                    ],
                                    "incluirCreditosInscritosDividaAtiva" => [
                                        "title" => "Incluir créditos inscritos na dívida ativa",
                                        "type" => "boolean"
                                    ],
                                    "cienciaDebitoExecucaoFiscal" => [
                                        "title" => "Ciêmcia débito execução fiscal",
                                        "type" => "boolean"
                                    ],
                                    "existemEmbargosExecucaoFiscal" => [
                                        "title" => "Existem embargos execução fiscal",
                                        "type" => "boolean"
                                    ],
                                    "incluirCreditosConstituicaoAutarquias" => [
                                        "title" => "Incluir créditos constituição autarquias",
                                        "type" => "boolean"
                                    ],
                                    "existemDepositosJudiciaisVinculadosCreditos" => [
                                        "title" => "Existem depósitos judiciais vinculados créditos",
                                        "type" => "boolean"
                                    ],
                                    "cpfSolicitante" => [
                                        "title" => "CPF solicitante",
                                        "type" => "string",
                                        "pattern" => "^[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}-?[0-9]{2}$"
                                    ],
                                    "nomeSolicitante" => [
                                        "title" => "Nome solicitante",
                                        "type" => "string",
                                        "minLength" => 1
                                    ],
                                    "emailSolicitante" => [
                                        "title" => "E-mail solicitante",
                                        "type" => "string",
                                        "pattern" => "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$",
                                        "format" => "email"
                                    ],
                                    "confirmeEmailSolicitante" => [
                                        "title" => "Confirme e-mail solicitante",
                                        "type" => "string",
                                        "pattern" => "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$",
                                        "format" => "email"
                                    ],
                                    "tipoTelefoneSolicitante" => [
                                        "title" => "Tipo telefone solicitante",
                                        "type" => "string",
                                        "enum" => [
                                            "fixo",
                                            "celular",
                                            "nao_informado"
                                        ]
                                    ],
                                    "opcaoPagamentoParcelado" => [
                                        "title" => "Opção pagamento parcelamento",
                                        "type" => [
                                            "string"
                                        ],
                                        "enum" => [
                                            "pessoa_fisica_integral_avista",
                                            "pessoa_fisica_integral_12_meses",
                                            "pessoa_fisica_integral_48_meses",
                                            "pessoa_fisica_integral_96_meses",
                                            "pessoa_fisica_integral_145_meses",
                                            "pessoa_fisica_parcial_avista",
                                            "pessoa_fisica_parcial_12_meses",
                                            "pessoa_fisica_parcial_48_meses",
                                            "pessoa_fisica_parcial_96_meses",
                                            "pessoa_fisica_parcial_145_meses",
                                            "pessoa_juridica_integral_avista",
                                            "pessoa_juridica_integral_12_meses",
                                            "pessoa_juridica_integral_48_meses",
                                            "pessoa_juridica_integral_96_meses",
                                            "pessoa_juridica_integral_120_meses",
                                            "pessoa_juridica_parcial_avista",
                                            "pessoa_juridica_parcial_12_meses",
                                            "pessoa_juridica_parcial_48_meses",
                                            "pessoa_juridica_parcial_96_meses",
                                            "pessoa_juridica_parcial_120_meses"
                                        ]
                                    ],
                                    "quantidadeParcelas" => [
                                        "type" => [
                                            "integer",
                                            "null"
                                        ]
                                    ]
                                ],
                                "else" => [
                                    "properties" => [
                                        "representante" => [
                                            "type" => "boolean"
                                        ],
                                        "processos" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processo" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processo"
                                                ]
                                            ]
                                        ],
                                        "processosJudicial" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoJudicial" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoJudicial"
                                                ]
                                            ]
                                        ],
                                        "infoAdicionais" => [
                                            "type" => [
                                                "string",
                                                "null"
                                            ]
                                        ],
                                        "protestado" => [
                                            "type" => "string",
                                            "enum" => [
                                                "sim",
                                                "nao",
                                                "nao_sabe"
                                            ]
                                        ],
                                        "protocolos" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "protocolo" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ],
                                        "embargo" => [
                                            "type" => [
                                                "string",
                                                "null"
                                            ],
                                            "enum" => [
                                                "sim",
                                                "nao",
                                                "nao_sabe"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "incluirCreditosInscritosDividaAtiva" => [
                                            "const" => false
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "certidoesCreditosInscritosDividaAtiva" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "certidaoCreditoInscricaoDividaAtiva" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "certidaoCreditoInscricaoDividaAtiva"
                                                ]
                                            ]
                                        ],
                                        "processosAdministrativosCreditosInscritosDividaAtiva" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoAdministrativoCreditoInscritoDividaAtiva" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoAdministrativoCreditoInscritoDividaAtiva"
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "cienciaDebitoExecucaoFiscal" => [
                                            "const" => true
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "certidoesInscricaoDividaAtivaExecucaoFiscal" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "certidaoInscricaoDividaAtivaExecucaoFiscal" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "certidaoInscricaoDividaAtivaExecucaoFiscal"
                                                ]
                                            ]
                                        ],
                                        "processosAdministrativosConstituicaoExecucaoFiscal" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoAdministrativoConstituicaoExecucaoFiscal" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoAdministrativoConstituicaoExecucaoFiscal"
                                                ]
                                            ]
                                        ],
                                        "processosAdministrativosExecucaoFiscal" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoAdministrativoExecucaoFiscal" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoAdministrativoExecucaoFiscal"
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "existemEmbargosExecucaoFiscal" => [
                                            "const" => true
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "certidoesInscricaoDividaAtivaEmbargos" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "certidaoInscricaoDividaAtivaEmbargo" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "certidaoInscricaoDividaAtivaEmbargo"
                                                ]
                                            ]
                                        ],
                                        "processosAdministrativosConstituicaoEmbargos" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoAdministrativoConstituicaoEmbargo" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoAdministrativoConstituicaoEmbargo"
                                                ]
                                            ]
                                        ],
                                        "processosJudiciaisEmbargos" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoJudicialEmbargo" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoJudicialEmbargo"
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "incluirCreditosConstituicaoAutarquias" => [
                                            "const" => true
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "documentosOrigemDividasCreditosConstituicao" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "documentoOrigemDividaCreditoConstituicao" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "documentoOrigemDividaCreditoConstituicao"
                                                ]
                                            ]
                                        ],
                                        "processosAdministrativosCreditosConstituicao" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoAdministrativoCreditoConstituicao" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoAdministrativoCreditoConstituicao"
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "existemDepositosJudiciaisVinculadosCreditos" => [
                                            "const" => true
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "certidoesInscricaoDividaAtivaDepositosJudiciais" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "certidaoInscricaoDividaAtivaDepositoJudicial" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "certidaoInscricaoDividaAtivaDepositoJudicial"
                                                ]
                                            ]
                                        ],
                                        "processosAdministrativosDepositosJudiciais" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoAdministrativoDepositoJudicial" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoAdministrativoDepositoJudicial"
                                                ]
                                            ]
                                        ],
                                        "processosJudiciaisDepositosJudiciais" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoJudicialDepositoJudicial" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoJudicialDepositoJudicial"
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "embargo" => [
                                            "const" => "sim"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "embargosProcesso" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "embargoProcesso" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "embargoProcesso"
                                                ]
                                            ],
                                            "minItens" => 1
                                        ]
                                    ]
                                ],
                                "else" => [
                                    "properties" => [
                                        "embargosProcesso" => [
                                            "type" => [
                                                "array",
                                                "null"
                                            ],
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "embargoProcesso" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "embargoProcesso"
                                                ]
                                            ],
                                            "minItens" => 0
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "representante" => [
                                            "const" => true
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "telefoneComercialRepresentante" => [
                                            "type" => [
                                                "string",
                                                "null"
                                            ],
                                            "pattern" => "^$|^\([0-9]{2}\) [0-9]{4}-[0-9]{4}$",
                                            "minLength" => 0,
                                            "maxLength" => 14
                                        ],
                                        "celularRepresentante" => [
                                            "type" => [
                                                "string",
                                                "null"
                                            ],
                                            "pattern" => "^$|^\([0-9]{2}\) [89][0-9]{4}-[0-9]{4}$",
                                            "minLength" => 0,
                                            "maxLength" => 15
                                        ],
                                        "nomeRepresentante" => [
                                            "type" => "string",
                                            "minLength" => 1
                                        ],
                                        "cpfRepresentante" => [
                                            "pattern" => "^[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}-?[0-9]{2}$"
                                        ],
                                        "emailRepresentante" => [
                                            "pattern" => "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$",
                                            "format" => "email"
                                        ],
                                        "oab" => [
                                            "type" => [
                                                "string",
                                                "null"
                                            ],
                                            "pattern" => "^[-.0-9]*$"
                                        ],
                                        "ufRepresentante" => [
                                            "type" => [
                                                "string",
                                                "null"
                                            ],
                                            "enum" => [
                                                "RO",
                                                "AC",
                                                "AM",
                                                "RR",
                                                "PA",
                                                "AP",
                                                "TO",
                                                "MA",
                                                "PI",
                                                "CE",
                                                "RN",
                                                "PB",
                                                "PE",
                                                "AL",
                                                "SE",
                                                "BA",
                                                "MG",
                                                "ES",
                                                "RJ",
                                                "SP",
                                                "PR",
                                                "SC",
                                                "RS",
                                                "MS",
                                                "MT",
                                                "GO",
                                                "DF",
                                                null
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoPessoa" => [
                                            "const" => "fisica"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "cpfCnpj" => [
                                            "pattern" => "^[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}-?[0-9]{2}$"
                                        ]
                                    ]
                                ],
                                "else" => [
                                    "properties" => [
                                        "cpfCnpj" => [
                                            "pattern" => "^[0-9]{2}\.?[0-9]{3}\.?[0-9]{3}/?[0-9]{4}-?[0-9]{2}$"
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoTelefone" => [
                                            "const" => "celular"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "telefone" => [
                                            "type" => "string",
                                            "pattern" => "^\([0-9]{2}\) [89][0-9]{4}-?[0-9]{4}$"
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoTelefone" => [
                                            "const" => "fixo"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "telefone" => [
                                            "type" => "string",
                                            "pattern" => "^\([0-9]{2}\) [0-9]{4}-?[0-9]{4}$"
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoTelefone" => [
                                            "const" => "nao_informado"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "telefone" => [
                                            "type" => "null"
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoSolicitacao" => [
                                            "const" => "outros"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "tipoSolicitacaoOutros" => [
                                            "type" => "string",
                                            "minLength" => 1
                                        ]
                                    ]
                                ],
                                "else" => [
                                    "properties" => [
                                        "tipoSolicitacaoOutros" => [
                                            "type" => "null"
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoTelefoneSolicitante" => [
                                            "const" => "celular"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "telefoneSolicitante" => [
                                            "type" => "string",
                                            "pattern" => "^\([0-9]{2}\) [89][0-9]{4}-?[0-9]{4}$"
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoTelefoneSolicitante" => [
                                            "const" => "fixo"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "telefoneSolicitante" => [
                                            "type" => "string",
                                            "pattern" => "^\([0-9]{2}\) [0-9]{4}-?[0-9]{4}$"
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoTelefoneSolicitante" => [
                                            "const" => "nao_informado"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "telefoneSolicitante" => [
                                            "type" => "null"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );
            $manager->persist($formulario3);
            $this->addReference('Formulario-' . $formulario3->getNome(), $formulario3);
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT f 
                FROM SuppCore\AdministrativoBackend\Entity\Formulario f 
                WHERE f.nome = 'FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO INFORMAÇÕES GERAIS'"
                )
                ->getOneOrNullResult()) {
            $formulario2 = new Formulario();
            $formulario2->setNome('FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO INFORMAÇÕES GERAIS');
            $formulario2->setSigla('requerimento_transacao_informacoes_pgf');
            $formulario2->setTemplate(
                'Resources/Processo/Requerimentos/requerimento_transacao_informacoes_pgf.html.twig'
            );
            $formulario2->setDataSchema(
                json_encode(
                    [
                        "\$schema" => "http://json-schema.org/draft-07/schema#",
                        "\$id" => "supp_core.administrativo_backend.requerimento_transacao_informacoes_pgf",
                        "title" => "FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO INFORMAÇÕES GERAIS",
                        "type" => "object",
                        "required" => [
                            "setorAtual",
                            "tipoRequerimento",
                            "tipoPessoa",
                            "cpfCnpj",
                            "nomeRazaoSocial",
                            "email",
                            "confirmeEmail",
                            "tipoTelefone",
                            "telefone",
                            "cep",
                            "endereco",
                            "complemento",
                            "estado",
                            "municipio",
                            "tipoSolicitacao",
                            "credor",
                            "ciente"
                        ],
                        "properties" => [
                            "setorAtual" => [
                                "title" => "Setor atual",
                                "type" => "integer",
                                "minValue" => 0
                            ],
                            "tipoRequerimento" => [
                                "title" => "Tipo requerimento",
                                "type" => "string",
                                "enum" => [
                                    "requerimento_transacao_adesao_pgf",
                                    "requerimento_transacao_informacoes_pgf"
                                ]
                            ],
                            "tipoPessoa" => [
                                "title" => "Tipo pessoa",
                                "type" => "string",
                                "enum" => [
                                    "fisica",
                                    "juridica"
                                ]
                            ],
                            "nomeRazaoSocial" => [
                                "title" => "Nome razão social",
                                "type" => "string",
                                "minLength" => 1
                            ],
                            "email" => [
                                "title" => "E-mail",
                                "type" => "string",
                                "pattern" => "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$",
                                "format" => "email"
                            ],
                            "confirmeEmail" => [
                                "title" => "Confirme e-mail",
                                "type" => "string",
                                "pattern" => "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$",
                                "format" => "email"
                            ],
                            "tipoTelefone" => [
                                "title" => "Tipo telefone",
                                "type" => "string",
                                "enum" => [
                                    "fixo",
                                    "celular",
                                    "nao_informado"
                                ]
                            ],
                            "cep" => [
                                "title" => "CEP",
                                "type" => "string",
                                "pattern" => "^[0-9]{2}\.?[0-9]{3}[-]?[0-9]{3}$"
                            ],
                            "endereco" => [
                                "title" => "Endereço",
                                "type" => "string",
                                "minLength" => 1
                            ],
                            "complemento" => [
                                "title" => "Complemento",
                                "type" => [
                                    "string",
                                    "null"
                                ]
                            ],
                            "estado" => [
                                "title" => "Estado",
                                "type" => "integer",
                                "minValue" => 0
                            ],
                            "municipio" => [
                                "title" => "Município",
                                "type" => "integer",
                                "minValue" => 0
                            ],
                            "credor" => [
                                "title" => "Credor",
                                "type" => "string",
                                "minLength" => 1
                            ],
                            "tipoSolicitacao" => [
                                "title" => "Tipo solicitação",
                                "type" => "string",
                                "enum" => [
                                    "transacao_adesao_pgf",
                                    "transacao_informacoes_gerais"
                                ]
                            ],
                            "cnpjsFiliais" => [
                                "title" => "CNPJs Filiais",
                                "type" => [
                                    "string",
                                    "null"
                                ]
                            ],
                            "ciente" => [
                                "title" => "Ciente",
                                "type" => "boolean"
                            ]
                        ],
                        "allOf" => [
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoRequerimento" => [
                                            "const" => "requerimento_transacao_adesao_pgf"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "devedorPessoaFisicaEDemais" => [
                                        "title" => "Devedor pessoa fisica e MEI ou demais organizações sociedade civil",
                                        "type" => "boolean"
                                    ],
                                    "incluirCreditosInscritosDividaAtiva" => [
                                        "title" => "Incluir créditos inscritos na dívida ativa",
                                        "type" => "boolean"
                                    ],
                                    "cienciaDebitoExecucaoFiscal" => [
                                        "title" => "Ciêmcia débito execução fiscal",
                                        "type" => "boolean"
                                    ],
                                    "existemEmbargosExecucaoFiscal" => [
                                        "title" => "Existem embargos execução fiscal",
                                        "type" => "boolean"
                                    ],
                                    "incluirCreditosConstituicaoAutarquias" => [
                                        "title" => "Incluir créditos constituição autarquias",
                                        "type" => "boolean"
                                    ],
                                    "existemDepositosJudiciaisVinculadosCreditos" => [
                                        "title" => "Existem depósitos judiciais vinculados créditos",
                                        "type" => "boolean"
                                    ],
                                    "cpfSolicitante" => [
                                        "title" => "CPF solicitante",
                                        "type" => "string",
                                        "pattern" => "^[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}-?[0-9]{2}$"
                                    ],
                                    "nomeSolicitante" => [
                                        "title" => "Nome solicitante",
                                        "type" => "string",
                                        "minLength" => 1
                                    ],
                                    "emailSolicitante" => [
                                        "title" => "E-mail solicitante",
                                        "type" => "string",
                                        "pattern" => "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$",
                                        "format" => "email"
                                    ],
                                    "confirmeEmailSolicitante" => [
                                        "title" => "Confirme e-mail solicitante",
                                        "type" => "string",
                                        "pattern" => "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$",
                                        "format" => "email"
                                    ],
                                    "tipoTelefoneSolicitante" => [
                                        "title" => "Tipo telefone solicitante",
                                        "type" => "string",
                                        "enum" => [
                                            "fixo",
                                            "celular",
                                            "nao_informado"
                                        ]
                                    ],
                                    "opcaoPagamentoParcelado" => [
                                        "title" => "Opção pagamento parcelamento",
                                        "type" => [
                                            "string"
                                        ],
                                        "enum" => [
                                            "pessoa_fisica_integral_avista",
                                            "pessoa_fisica_integral_12_meses",
                                            "pessoa_fisica_integral_48_meses",
                                            "pessoa_fisica_integral_96_meses",
                                            "pessoa_fisica_integral_145_meses",
                                            "pessoa_fisica_parcial_avista",
                                            "pessoa_fisica_parcial_12_meses",
                                            "pessoa_fisica_parcial_48_meses",
                                            "pessoa_fisica_parcial_96_meses",
                                            "pessoa_fisica_parcial_145_meses",
                                            "pessoa_juridica_integral_avista",
                                            "pessoa_juridica_integral_12_meses",
                                            "pessoa_juridica_integral_48_meses",
                                            "pessoa_juridica_integral_96_meses",
                                            "pessoa_juridica_integral_120_meses",
                                            "pessoa_juridica_parcial_avista",
                                            "pessoa_juridica_parcial_12_meses",
                                            "pessoa_juridica_parcial_48_meses",
                                            "pessoa_juridica_parcial_96_meses",
                                            "pessoa_juridica_parcial_120_meses"
                                        ]
                                    ],
                                    "quantidadeParcelas" => [
                                        "type" => [
                                            "integer",
                                            "null"
                                        ]
                                    ]
                                ],
                                "else" => [
                                    "properties" => [
                                        "representante" => [
                                            "type" => "boolean"
                                        ],
                                        "processos" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processo" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processo"
                                                ]
                                            ]
                                        ],
                                        "processosJudicial" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoJudicial" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoJudicial"
                                                ]
                                            ]
                                        ],
                                        "infoAdicionais" => [
                                            "type" => [
                                                "string",
                                                "null"
                                            ]
                                        ],
                                        "protestado" => [
                                            "type" => "string",
                                            "enum" => [
                                                "sim",
                                                "nao",
                                                "nao_sabe"
                                            ]
                                        ],
                                        "protocolos" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "protocolo" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ],
                                        "embargo" => [
                                            "type" => [
                                                "string",
                                                "null"
                                            ],
                                            "enum" => [
                                                "sim",
                                                "nao",
                                                "nao_sabe"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "incluirCreditosInscritosDividaAtiva" => [
                                            "const" => false
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "certidoesCreditosInscritosDividaAtiva" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "certidaoCreditoInscricaoDividaAtiva" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "certidaoCreditoInscricaoDividaAtiva"
                                                ]
                                            ]
                                        ],
                                        "processosAdministrativosCreditosInscritosDividaAtiva" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoAdministrativoCreditoInscritoDividaAtiva" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoAdministrativoCreditoInscritoDividaAtiva"
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "cienciaDebitoExecucaoFiscal" => [
                                            "const" => true
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "certidoesInscricaoDividaAtivaExecucaoFiscal" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "certidaoInscricaoDividaAtivaExecucaoFiscal" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "certidaoInscricaoDividaAtivaExecucaoFiscal"
                                                ]
                                            ]
                                        ],
                                        "processosAdministrativosConstituicaoExecucaoFiscal" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoAdministrativoConstituicaoExecucaoFiscal" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoAdministrativoConstituicaoExecucaoFiscal"
                                                ]
                                            ]
                                        ],
                                        "processosAdministrativosExecucaoFiscal" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoAdministrativoExecucaoFiscal" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoAdministrativoExecucaoFiscal"
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "existemEmbargosExecucaoFiscal" => [
                                            "const" => true
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "certidoesInscricaoDividaAtivaEmbargos" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "certidaoInscricaoDividaAtivaEmbargo" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "certidaoInscricaoDividaAtivaEmbargo"
                                                ]
                                            ]
                                        ],
                                        "processosAdministrativosConstituicaoEmbargos" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoAdministrativoConstituicaoEmbargo" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoAdministrativoConstituicaoEmbargo"
                                                ]
                                            ]
                                        ],
                                        "processosJudiciaisEmbargos" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoJudicialEmbargo" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoJudicialEmbargo"
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "incluirCreditosConstituicaoAutarquias" => [
                                            "const" => true
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "documentosOrigemDividasCreditosConstituicao" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "documentoOrigemDividaCreditoConstituicao" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "documentoOrigemDividaCreditoConstituicao"
                                                ]
                                            ]
                                        ],
                                        "processosAdministrativosCreditosConstituicao" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoAdministrativoCreditoConstituicao" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoAdministrativoCreditoConstituicao"
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "existemDepositosJudiciaisVinculadosCreditos" => [
                                            "const" => true
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "certidoesInscricaoDividaAtivaDepositosJudiciais" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "certidaoInscricaoDividaAtivaDepositoJudicial" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "certidaoInscricaoDividaAtivaDepositoJudicial"
                                                ]
                                            ]
                                        ],
                                        "processosAdministrativosDepositosJudiciais" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoAdministrativoDepositoJudicial" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoAdministrativoDepositoJudicial"
                                                ]
                                            ]
                                        ],
                                        "processosJudiciaisDepositosJudiciais" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "processoJudicialDepositoJudicial" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "processoJudicialDepositoJudicial"
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "embargo" => [
                                            "const" => "sim"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "embargosProcesso" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "embargoProcesso" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "embargoProcesso"
                                                ]
                                            ],
                                            "minItens" => 1
                                        ]
                                    ]
                                ],
                                "else" => [
                                    "properties" => [
                                        "embargosProcesso" => [
                                            "type" => [
                                                "array",
                                                "null"
                                            ],
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "embargoProcesso" => [
                                                        "type" => [
                                                            "string",
                                                            "null"
                                                        ]
                                                    ]
                                                ],
                                                "required" => [
                                                    "embargoProcesso"
                                                ]
                                            ],
                                            "minItens" => 0
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "representante" => [
                                            "const" => true
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "telefoneComercialRepresentante" => [
                                            "type" => [
                                                "string",
                                                "null"
                                            ],
                                            "pattern" => "^$|^\([0-9]{2}\) [0-9]{4}-[0-9]{4}$",
                                            "minLength" => 0,
                                            "maxLength" => 14
                                        ],
                                        "celularRepresentante" => [
                                            "type" => [
                                                "string",
                                                "null"
                                            ],
                                            "pattern" => "^$|^\([0-9]{2}\) [89][0-9]{4}-[0-9]{4}$",
                                            "minLength" => 0,
                                            "maxLength" => 15
                                        ],
                                        "nomeRepresentante" => [
                                            "type" => "string",
                                            "minLength" => 1
                                        ],
                                        "cpfRepresentante" => [
                                            "pattern" => "^[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}-?[0-9]{2}$"
                                        ],
                                        "emailRepresentante" => [
                                            "pattern" => "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$",
                                            "format" => "email"
                                        ],
                                        "oab" => [
                                            "type" => [
                                                "string",
                                                "null"
                                            ],
                                            "pattern" => "^[-.0-9]*$"
                                        ],
                                        "ufRepresentante" => [
                                            "type" => [
                                                "string",
                                                "null"
                                            ],
                                            "enum" => [
                                                "RO",
                                                "AC",
                                                "AM",
                                                "RR",
                                                "PA",
                                                "AP",
                                                "TO",
                                                "MA",
                                                "PI",
                                                "CE",
                                                "RN",
                                                "PB",
                                                "PE",
                                                "AL",
                                                "SE",
                                                "BA",
                                                "MG",
                                                "ES",
                                                "RJ",
                                                "SP",
                                                "PR",
                                                "SC",
                                                "RS",
                                                "MS",
                                                "MT",
                                                "GO",
                                                "DF",
                                                null
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoPessoa" => [
                                            "const" => "fisica"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "cpfCnpj" => [
                                            "pattern" => "^[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}-?[0-9]{2}$"
                                        ]
                                    ]
                                ],
                                "else" => [
                                    "properties" => [
                                        "cpfCnpj" => [
                                            "pattern" => "^[0-9]{2}\.?[0-9]{3}\.?[0-9]{3}/?[0-9]{4}-?[0-9]{2}$"
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoTelefone" => [
                                            "const" => "celular"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "telefone" => [
                                            "type" => "string",
                                            "pattern" => "^\([0-9]{2}\) [89][0-9]{4}-?[0-9]{4}$"
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoTelefone" => [
                                            "const" => "fixo"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "telefone" => [
                                            "type" => "string",
                                            "pattern" => "^\([0-9]{2}\) [0-9]{4}-?[0-9]{4}$"
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoTelefone" => [
                                            "const" => "nao_informado"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "telefone" => [
                                            "type" => "null"
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoSolicitacao" => [
                                            "const" => "outros"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "tipoSolicitacaoOutros" => [
                                            "type" => "string",
                                            "minLength" => 1
                                        ]
                                    ]
                                ],
                                "else" => [
                                    "properties" => [
                                        "tipoSolicitacaoOutros" => [
                                            "type" => "null"
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoTelefoneSolicitante" => [
                                            "const" => "celular"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "telefoneSolicitante" => [
                                            "type" => "string",
                                            "pattern" => "^\([0-9]{2}\) [89][0-9]{4}-?[0-9]{4}$"
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoTelefoneSolicitante" => [
                                            "const" => "fixo"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "telefoneSolicitante" => [
                                            "type" => "string",
                                            "pattern" => "^\([0-9]{2}\) [0-9]{4}-?[0-9]{4}$"
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "if" => [
                                    "properties" => [
                                        "tipoTelefoneSolicitante" => [
                                            "const" => "nao_informado"
                                        ]
                                    ]
                                ],
                                "then" => [
                                    "properties" => [
                                        "telefoneSolicitante" => [
                                            "type" => "null"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );
            $manager->persist($formulario2);
            $this->addReference('Formulario-' . $formulario2->getNome(), $formulario2);
        }

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 4;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['dev'];
    }
}
