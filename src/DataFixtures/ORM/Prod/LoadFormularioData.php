<?php

declare(strict_types=1);
/**
 * /src/DevDataFixtures/ORM/Dev/LoadFormularioDatamas o.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

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
        $formulario = (new Formulario())
            ->setNome('Formulário de IA de Gestão de Conhecimento da Petição Inicial')
            ->setSigla('ia_gestao_conhecimento_petini_1')
            ->setAtivo(true)
            ->setIa(true)
            ->setAceitaJsonInvalido(true)
            ->setDataSchema(
                json_encode(
                    [
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'https://supersapiensbackend.agu.gov.br/v1/administrativo/formulario/schema/ia_gestao_conhecimento_petini_1',
                        'description' => 'Schema de Formulário de IA de Gestão de Conhecimento da Petição Inicial',
                        'type' => 'object',
                        'properties' => [
                            'ramo_direito' => [
                                'title' => 'Ramo do direito',
                                'type' => ['string', 'null'],
                                'examples' => [
                                    'MILITARES',
                                    'SERVIDORES PÚBLICOS CIVIS',
                                    'DIREITO AMBIENTAL',
                                    'DIREITO PREVIDENCIÁRIO',
                                    'DIREITO ADMINISTRATIVO'
                                ]
                            ],
                            'tema' => [
                                'title' => 'Tema',
                                'type' => ['string', 'null'],
                                'examples' => [
                                    'LICENCIAMENTO AMBIENTAL',
                                    'PENSÃO POR MORTE',
                                    'SEGURO DEFESO',
                                    'ABONOS'
                                ]
                            ],
                            'tese_juridica_controvertida' => [
                                'title' => 'Tese Jurídica Controvertida',
                                'type' => ['string', 'null'],
                                'examples' => [
                                    'DIREITO DO MILITAR ANISTIADO A TODAS AS PROMOÇÕES A QUE TERIA DIREITO SE NA ATIVA ESTIVESSE, DESDE QUE DENTRO DA CARREIRA A QUE PERTENCIA À ÉPOCA DE SEU DESLIGAMENTO',
                                    'LEGITIMIDADE DO ATO DA ADMINISTRAÇÃO QUE PROMOVE O DESCONTO DOS DIAS NÃO TRABALHADOS DURANTE A GREVE'
                                ]
                            ],
                            'palavras_chave' => [
                                'title' => 'Palavras Chave',
                                'type' => ['string', 'null'],
                                'examples' => [
                                    'MILITAR. ANISTIADO. PROMOÇÃO',
                                    'SERVIDOR PÚBLICO. GREVE. DESCONTO'
                                ]
                            ],
                            'dispositivos_legais' => [
                                'title' => 'Dispositivos Legais',
                                'type' => ['array', 'null'],
                                'items' => [
                                    'type' => ['string', 'null'],
                                    'examples' => [
                                        'urn:lex:br:federal:lei:1998-02-12;9605',
                                        'urn:lex:br:federal:lei:2015-03-16;13105',
                                        'urn:lex:br:federal:constituicao:1988-10-05;1988'
                                    ]
                                ],
                                'examples' => [
                                    [
                                        'urn:lex:br:federal:lei:1998-02-12;9605',
                                        'urn:lex:br:federal:lei:2015-03-16;13105',
                                        'urn:lex:br:federal:constituicao:1988-10-05;1988'
                                    ]
                                ]
                            ],
                            'precedentes' => [
                                'title' => 'Precedentes',
                                'type' => ['array', 'null'],
                                'items' => [
                                    'type' => ['string', 'null'],
                                    'examples' => [
                                        'decisão judicial',
                                        'tema repetitivo',
                                        'urn:lex:br:federal:súmula.vinculante:2008-08-21;13'
                                    ]
                                ],
                                'examples' => [
                                    [
                                        'decisão judicial',
                                        'tema repetitivo',
                                        'urn:lex:br:federal:súmula.vinculante:2008-08-21;13'
                                    ]
                                ]
                            ],
                            'fatos_narrados' => [
                                'title' => 'Fatos Narrados',
                                'type' => ['array', 'null'],
                                'items' => [
                                    'type' => ['string', 'null'],
                                    'examples' => [
                                        'A autora, desde o início do contrato de trabalho, não recebeu os valores correspondentes às horas extras laboradas, apesar de frequentemente trabalhar além da jornada estipulada.',
                                        'Em 15 de março de 2020, durante a condução de um veículo automotor, o réu foi surpreendido em estado de embriaguez, colocando em risco a segurança pública.'
                                    ]
                                ],
                                'examples' => [
                                    [
                                        'A autora, desde o início do contrato de trabalho, não recebeu os valores correspondentes às horas extras laboradas, apesar de frequentemente trabalhar além da jornada estipulada.',
                                        'Em 15 de março de 2020, durante a condução de um veículo automotor, o réu foi surpreendido em estado de embriaguez, colocando em risco a segurança pública.'
                                    ]
                                ]
                            ],
                            'fundamentos_juridicos' => [
                                'title' => 'Fundamentos Jurídicos',
                                'type' => ['array', 'null'],
                                'items' => [
                                    'type' => ['string', 'null'],
                                    'examples' => [
                                        'A violação do artigo 5º, inciso X, da Constituição Federal, que garante a inviolabilidade da intimidade, vida privada, honra e imagem das pessoas.',
                                        'O descumprimento do artigo 7º, inciso XVI, da Consolidação das Leis do Trabalho (CLT), que assegura o pagamento de horas extras com adicional de 50%.'
                                    ]
                                ],
                                'examples' => [
                                    [
                                        'A violação do artigo 5º, inciso X, da Constituição Federal, que garante a inviolabilidade da intimidade, vida privada, honra e imagem das pessoas.',
                                        'O descumprimento do artigo 7º, inciso XVI, da Consolidação das Leis do Trabalho (CLT), que assegura o pagamento de horas extras com adicional de 50%.'
                                    ]
                                ]
                            ],
                            'pedidos_formulados' => [
                                'title' => 'Pedidos Formulados',
                                'type' => ['array', 'null'],
                                'items' => [
                                    'type' => ['string', 'null'],
                                    'examples' => [
                                        'Requer a condenação do réu ao pagamento de indenização por danos morais no valor de R$ 50.000,00.',
                                        'Solicita a reintegração do autor ao emprego, com o pagamento de todos os salários e benefícios retroativos, em razão da demissão sem justa causa.'
                                    ]
                                ],
                                'examples' => [
                                    [
                                        'Requer a condenação do réu ao pagamento de indenização por danos morais no valor de R$ 50.000,00.',
                                        'Solicita a reintegração do autor ao emprego, com o pagamento de todos os salários e benefícios retroativos, em razão da demissão sem justa causa.'
                                    ]
                                ]
                            ],
                            'tutela_solicitada' => [
                                'title' => 'Tutela Solicitada',
                                'type' => ['string', 'null'],
                                'examples' => [
                                    'TUTELA SOLICITADA',
                                    'TUTELA ANTECIPADA',
                                    'MEDIDA CAUTELAR',
                                    'TUTELA PROVISÓRIA',
                                    'TUTELA DE EVIDÊNCIA'
                                ]
                            ],
                            'tutela_coletiva' => [
                                'title' => 'Tutela Coletiva',
                                'type' => ['string', 'null'],
                                'examples' => [
                                    'AÇÃO COLETIVA',
                                    'AÇÃO CIVIL PÚBLICA',
                                    'MANDADO DE SEGURANÇA COLETIVO',
                                    'MANDADO DE INJUNÇÃO COLETIVO'
                                ]
                            ],
                            'valor_da_causa' => [
                                'title' => 'Valor da Causa',
                                'type' => ['number', 'null'],
                                'examples' => [
                                    100000.00
                                ]
                            ],
                            'somatorio_valores_diversos' => [
                                'title' => 'Somatório de Valores Diversos',
                                'type' => ['number', 'null'],
                                'examples' => [
                                    200000000.00
                                ]
                            ],
                            'resumo_peticao' => [
                                'title' => 'Resumo da Petição',
                                'type' => ['string', 'null'],
                                'examples' => [
                                    'Requer tutela coletiva para reparação de danos ambientais e sociais.'
                                ]
                            ],
                            'peticao_inicial_indicador' => [
                                'title' => 'Indicador de Petição Inicial',
                                'description' => 'Indica se o documento é do tipo Petição Inicial',
                                'type' => ['boolean', 'null'],
                                'examples' => [
                                    true,
                                    false
                                ]
                            ],
                            'tipo_documento_correto' => [
                                'title' => 'Classificação Do Documento',
                                'description' => 'Informa a sugestão da classificação do documento pela IA',
                                'type' => ['string', 'null'],
                                'examples' => [
                                    'AGRAVO DE INSTRUMENTO',
                                    'APELAÇÃO',
                                    'CONTESTAÇÃO',
                                    'CONTRARRAZÕES',
                                    'EMBARGOS DE DECLARAÇÃO',
                                    'LAUDO',
                                    'RECURSO INOMINADO',
                                    'SENTENÇA',
                                    'OUTROS DOCUMENTOS'
                                ]
                            ]
                        ],
                        'additionalProperties' => false
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            )
        ;

        $manager->persist($formulario);
        $this->addReference('ConfigModulo-'.$formulario->getNome(), $formulario);

        $formulario = (new Formulario())
            ->setNome('Formulário de IA de Gestão de Conhecimento de Sentença')
            ->setSigla('ia_gestao_conhecimento_sentenca_1')
            ->setAtivo(true)
            ->setIa(true)
            ->setAceitaJsonInvalido(true)
            ->setDataSchema(
                json_encode(
                    [
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'https://supersapiensbackend.agu.gov.br/v1/administrativo/formulario/schema/ia_gestao_conhecimento_sentenca_1',
                        'description' => 'Schema de Formulário de IA de Gestão de Conhecimento de Sentença',
                        'type' => 'object',
                        'properties' => [
                            'sentenca_indicador' => [
                                'title' => 'Indicador de Sentença',
                                'type' => ['boolean', 'null'],
                                'examples' => [true, false],
                            ],
                            'tipo_documento_correto' => [
                                'title' => 'Classificação Do Documento',
                                'description' => 'Informa a sugestão da classificação do documento pela IA',
                                'type' => ['string', 'null'],
                                'examples' => [
                                    'AGRAVO DE INSTRUMENTO',
                                    'APELAÇÃO',
                                    'CONTESTAÇÃO',
                                    'CONTRARRAZÕES',
                                    'EMBARGOS DE DECLARAÇÃO',
                                    'LAUDO',
                                    'RECURSO INOMINADO',
                                    'PETIÇÃO INICIAL',
                                    'OUTROS DOCUMENTOS'
                                ],
                            ],
                            'resumo_relatorio' => [
                                'title' => 'Resumo da sentença',
                                'type' => ['string', 'null'],
                                'examples' => ['sumarização da sentença'],
                            ],
                            'palavras_chave' => [
                                'title' => 'Palavras Chave',
                                'type' => ['string', 'null'],
                                'examples' => ['MILITAR. ANISTIADO. PROMOÇÃO', 'SERVIDOR PÚBLICO. GREVE. DESCONTO'],
                            ],
                            'dispositivos_legais' => [
                                'title' => 'Dispositivos Legais',
                                'type' => ['array', 'null'],
                                'items' => [
                                    'type' => ['string', 'null'],
                                    'examples' => [
                                        'urn:lex:br:federal:lei:1998-02-12;9605',
                                        'urn:lex:br:federal:lei:2015-03-16;13105',
                                        'urn:lex:br:federal:constituicao:1988-10-05;1988'
                                    ]
                                ],
                                'examples' => [
                                    [
                                        'urn:lex:br:federal:lei:1998-02-12;9605',
                                        'urn:lex:br:federal:lei:2015-03-16;13105',
                                        'urn:lex:br:federal:constituicao:1988-10-05;1988'
                                    ]
                                ]
                            ],
                            'precedentes' => [
                                'title' => 'Precedentes',
                                'type' => ['array', 'null'],
                                'items' => [
                                    'type' => ['string', 'null'],
                                    'examples' => [
                                        'decisão judicial',
                                        'tema repetitivo',
                                        'urn:lex:br:federal:súmula.vinculante:2008-08-21;13'
                                    ]
                                ],
                                'examples' => [
                                    [
                                        'decisão judicial',
                                        'tema repetitivo',
                                        'urn:lex:br:federal:súmula.vinculante:2008-08-21;13'
                                    ]
                                ]
                            ],
                            'julgador' => [
                                'title' => 'Nome do juiz',
                                'type' => ['string', 'null'],
                                'examples' => ['Nome do juiz'],
                            ],
                            'data_sentenca' => [
                                'title' => 'Data da sentença',
                                'type' => ['string', 'null'],
                                'format' => 'date',
                                'examples' => ['2020-02-14'],
                            ],
                            'municipio_orgao_julgador' => [
                                'title' => 'Municipio da sentença',
                                'type' => ['string', 'null'],
                                'examples' => ['JARAGUÁ', 'ARACAJU'],
                            ],
                            'estado' => [
                                'title' => 'Estado da sentença',
                                'type' => ['string', 'null'],
                                'examples' => ['GOIÁS', 'SERGIPE'],
                            ],
                            'preliminar' => [
                                'title' => 'Preliminar/Prejudicial decidida pelo juiz',
                                'type' => ['array', 'null'],
                                'items' => [
                                    'type' => ['string', 'null'],
                                    'examples' => ['preliminar 1', 'preliminar 2'],
                                ],
                            ],
                            'fundamentos_juridicos' => [
                                'title' => 'Fundamentos jurídicos',
                                'type' => ['array', 'null'],
                                'items' => [
                                    'type' => ['string', 'null'],
                                    'examples' => ['fundamento jurídico 1', 'fundamento jurídico 2'],
                                ],
                            ],
                            'dispositivo' => [
                                'title' => 'Dispositivos da sentença',
                                'type' => ['object', 'null'],
                                'properties' => [
                                    'dispositivo_da_sentenca' => [
                                        'title' => 'Dispositivo da sentença',
                                        'type' => ['string', 'null'],
                                        'examples' => ['dispositivo da sentença'],
                                    ],
                                    'valor_condenacao' => [
                                        'title' => 'Valores da condenação',
                                        'type' => ['array', 'null'],
                                        'items' => [
                                            'type' => ['object'],
                                            'properties' => [
                                                'condenacao' => [
                                                    'title' => 'Ordenador da condenação',
                                                    'type' => ['integer', 'null'],
                                                    'examples' => [1, 2],
                                                ],
                                                'valor_economico' => [
                                                    'title' => 'Valor da condenação',
                                                    'type' => ['number', 'integer', 'null'],
                                                    'examples' => [5000.00],
                                                ],
                                                'fundamento_juridico' => [
                                                    'title' => 'Fundamento jurídico da condenação',
                                                    'type' => ['string', 'null'],
                                                    'examples' => ['INDENIZAÇÃO POR DANOS MORAIS'],
                                                ],
                                                'data_calculo' => [
                                                    'title' => 'Data de cálculo da condenação',
                                                    'type' => ['string', 'null'],
                                                    'format' => 'date',
                                                    'examples' => ['2022-04-27'],
                                                ],
                                            ],
                                        ],
                                    ],
                                    'atualizacao' => [
                                        'title' => 'Critérios utilizados para a correção monetária (atualização) e incidência de juros',
                                        'type' => ['string', 'null'],
                                        'examples' => ['juros de acordo com a remuneração básica da caderneta de poupança (TR) a contar do fato danoso'],
                                    ],
                                    'honorarios' => [
                                        'title' => 'Valor da condenação em honorários advocatícios e seu fundamento jurídico',
                                        'type' => ['string', 'null'],
                                        'examples' => ['Condeno ao pagamento dos honorários sucumbenciais no valor correspondente a 5%'],
                                    ],
                                ],
                            ],
                            'resultado_sentenca' => [
                                'title' => 'Resultado do julgamento',
                                'type' => ['string', 'null'],
                                'examples' => ['PROCEDENTE', 'IMPROCEDENTE', 'PARCIALMENTE PROCEDENTE', 'HOMOLOGATÓRIA', 'SEM RESOLUÇÃO DE MÉRITO'],
                            ],
                            'multa' => [
                                'title' => 'Aplicação de multa, seu valor e seu fundamento',
                                'type' => ['string', 'null'],
                                'examples' => ['MULTA (ASTREINTE) NO VALOR DE R$ 150,00 POR DIA'],
                            ],
                            'tutela' => [
                                'title' => 'Tutela',
                                'type' => ['string', 'null'],
                                'examples' => ['TUTELA CONCEDIDA', 'TUTELA REVOGADA'],
                            ],
                        ],
                        'additionalProperties' => false
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            )
        ;

        $manager->persist($formulario);
        $this->addReference('ConfigModulo-'.$formulario->getNome(), $formulario);

        $formulario = (new Formulario())
            ->setNome('Formulário de IA de Sentença Previdenciaria')
            ->setSigla('ia_sentenca_previdenciaria_1')
            ->setAtivo(true)
            ->setIa(true)
            ->setAceitaJsonInvalido(true)
            ->setDataSchema(
                json_encode(
                    [
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'https://supersapiensbackend.agu.gov.br/v1/administrativo/formulario/schema/ia_sentenca_previdenciaria_1',
                        'description' => 'Schema de Formulário de IA de Sentença Previdenciaria',
                        'type' => 'object',
                        'properties' => [
                            'tipo_sentenca_previdenciario' => [
                                'title' => 'Tipo de sentença',
                                'type' => ['string', 'null'],
                                'examples' => ['CONCESSÃO', 'RESTABELECIMENTO', 'REVISÃO', 'OUTRO'],
                            ],
                            'nome' => [
                                'title' => 'Nome dos autores da sentença',
                                'type' => ['array', 'null'],
                                'items' => [
                                    'type' => ['string', 'null'],
                                    'examples' => ['Nome do autor 1', 'Nome do autor 2'],
                                ],
                            ],
                            'revisao_fundamento' => [
                                'title' => 'Revisão do valor da renda mensal inicial - RMI ou do valor do benefício previdenciário',
                                'type' => ['string', 'null'],
                                'examples' => ['TEMPO ESPECIAL', 'REVISÃO DA VIDA TODA'],
                            ],
                            'incapacidade_indicador' => [
                                'title' => 'Tipo de incapacidade ou de redução da capacidade para o trabalho',
                                'type' => ['string', 'null'],
                                'examples' => [
                                    'INCAPACIDADE TEMPORÁRIA',
                                    'INCAPACIDADE TOTAL E PERMANENTE',
                                    'INCAPACIDADE PARCIAL'
                                ],
                            ],
                            'acidente_indicador' => [
                                'title' => 'Data do acidente da incapacidade ou redução da capacidade laborativa',
                                'type' => ['string', 'null'],
                                'format' => 'date',
                                'examples' => ['2022-05-17'],
                            ],
                            'dependencia_economica_indicador' => [
                                'title' => 'Dependência econômica e seu fundamento',
                                'type' => ['string', 'null'],
                                'examples' => ['DEPENDÊNCIA RECONHECIDA: FILHO MENOR'],
                            ],
                            'dados_implantacao' => [
                                'title' => 'Dados da implantação do benefício determinada pela sentença',
                                'type' => ['array', 'null'],
                                'items' => [
                                    'type' => ['object', 'null'],
                                    'properties' => [
                                        'nome' => [
                                            'title' => 'Nome do segurado ou dependente',
                                            'type' => ['string', 'null'],
                                            'examples' => ['Nome do segurado 1', 'Nome do segurado 2'],
                                        ],
                                        'nb' => [
                                            'title' => 'Número de benefício (NB)',
                                            'type' => ['string', 'null'],
                                            'examples' => ['5081010592'],
                                        ],
                                        'especie_beneficio' => [
                                            'title' => 'Espécie de benefício a ser implantada',
                                            'type' => ['string', 'null'],
                                            'examples' => ['AUXÍLIO POR INCAPACIDADE TEMPORÁRIA'],
                                        ],
                                        'der' => [
                                            'title' => 'Data de entrada do requerimento (DER)',
                                            'type' => ['string', 'null'],
                                            'format' => 'date',
                                            'examples' => ['2018-01-20'],
                                        ],
                                        'dib' => [
                                            'title' => 'Data de início do benefício (DIB)',
                                            'type' => ['string', 'null'],
                                            'format' => 'date',
                                            'examples' => ['2018-01-20'],
                                        ],
                                        'data_restabelecimento' => [
                                            'title' => 'Data de restabelecimento do benefício',
                                            'type' => ['string', 'null'],
                                            'format' => 'date',
                                            'examples' => ['2023-04-11'],
                                        ],
                                        'dcb' => [
                                            'title' => 'Data de cessação do benefício (DCB)',
                                            'type' => ['string', 'null'],
                                            'format' => 'date',
                                            'examples' => ['2018-03-20'],
                                        ],
                                        'dip' => [
                                            'title' => 'Data de início do pagamento (DIP)',
                                            'type' => ['string', 'null'],
                                            'format' => 'date',
                                            'examples' => ['2022-12-10'],
                                        ],
                                    ],
                                ],
                            ],
                            'vinculos_reconhecidos' => [
                                'title' => 'Vínculos reconhecidos',
                                'type' => ['array'],
                                'items' => [
                                    'type' => ['object'],
                                    'properties' => [
                                        'vinculo' => [
                                            'title' => 'Ordenador do vínculo',
                                            'type' => ['integer', 'null'],
                                            'examples' => [1, 2],
                                        ],
                                        'categoria_segurado' => [
                                            'title' => 'Categoria de segurado (tipo de filiação)',
                                            'type' => ['string', 'null'],
                                            'examples' => ['EMPREGADO', 'EMPREGADO DOMÉSTICO'],
                                        ],
                                        'origem_vinculo' => [
                                            'title' => 'Origem do vínculo ou o nome do empregador constante do vínculo de trabalho',
                                            'type' => ['string', 'null'],
                                            'examples' => ['BOA PRAÇA LTDA', 'PERÍODO DE ATIVIDADE DE SEGURADO ESPECIAL'],
                                        ],
                                        'ocupacao' => [
                                            'title' => 'Nome da função, ocupação ou atividade desempenhada',
                                            'type' => ['string', 'null'],
                                            'examples' => ['MOTORISTA', 'LAVRADOR'],
                                        ],
                                        'periodo_inicial' => [
                                            'title' => 'Data em que o segurado começou a trabalhar',
                                            'type' => ['string', 'null'],
                                            'format' => 'date',
                                            'examples' => ['2000-01-01'],
                                        ],
                                        'periodo_final' => [
                                            'title' => 'Data em que o segurado parou de trabalhar',
                                            'type' => ['string', 'null'],
                                            'format' => 'date',
                                            'examples' => ['2019-03-05'],
                                        ],
                                        'agente_agressivo' => [
                                            'title' => 'Agente agressivo',
                                            'type' => ['string', 'null'],
                                            'examples' => ['RUÍDO', 'ELETRICIDADE', 'RADIAÇÃO IONIZANTE'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'additionalProperties' => false
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            )
        ;

        $manager->persist($formulario);
        $this->addReference('ConfigModulo-'.$formulario->getNome(), $formulario);

        $formulario = (new Formulario())
            ->setNome('Formulário de IA de Petição Inicial Previdenciaria')
            ->setSigla('ia_petini_previdenciaria_1')
            ->setAtivo(true)
            ->setIa(true)
            ->setAceitaJsonInvalido(true)
            ->setDataSchema(
                json_encode(
                    [
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'https://supersapiensbackend.agu.gov.br/v1/administrativo/formulario/schema/ia_petini_previdenciaria_1',
                        'description' => 'Schema de Formulário de IA de Petição Inicial Previdenciaria',
                        'type' => 'object',
                        'properties' => [
                            'tipo_pedido_previdenciario' => [
                                'title' => 'Tipo de pedido previdenciário',
                                'type' => ['string', 'null'],
                                'examples' => ['CONCESSÃO', 'RESTABELECIMENTO', 'REVISÃO', 'OUTRO'],
                            ],
                            'especie_beneficio' => [
                                'title' => 'Especie do benefício',
                                'type' => ['string', 'null'],
                                'examples' => [
                                    'APOSENTADORIA POR IDADE',
                                    'APOSENTADORIA POR IDADE RURAL',
                                    'PENSÃO POR MORTE',
                                ],
                            ],
                            'rural_urbano_indicador' => [
                                'title' => 'Indicador de rural ou urbano',
                                'type' => ['string', 'null'],
                                'examples' => ['RURAL', 'URBANO'],
                            ],
                            'revisao_fundamento' => [
                                'title' => 'Fundamento da revisão',
                                'type' => ['string', 'null'],
                                'examples' => [
                                    'TEMPO ESPECIAL',
                                    'TEMPO URBANO',
                                    'TEMPO RURAL',
                                ],
                            ],
                            'nb_dados' => [
                                'title' => 'Dados benefícios',
                                'type' => ['array', 'null'],
                                'items' => [
                                    'type' => ['object', 'null'],
                                    'properties' => [
                                        'processo_administrativo' => [
                                            'title' => 'Processo administrativo',
                                            'type' => ['number', 'null'],
                                            'examples' => [1, 2, 3],
                                        ],
                                        'nb' => [
                                            'title' => 'Número do benefício',
                                            'type' => ['string', 'null'],
                                            'examples' => ['5081010592'],
                                        ],
                                        'beneficio' => [
                                            'title' => 'Espécie de benefício',
                                            'type' => ['string', 'null'],
                                            'examples' => [
                                                'AUXILIO DOENCA PREVIDENCIARIO',
                                            ]
                                        ],
                                        'der' => [
                                            'title' => 'Data de entrada do requerimento',
                                            'type' => ['string', 'null'],
                                            'format' => 'date',
                                            'examples' => ['2018-01-20'],
                                        ],
                                        'dib' => [
                                            'title' => 'Data de inicio do benefício',
                                            'type' => ['string', 'null'],
                                            'format' => 'date',
                                            'examples' => ['2017-09-20'],
                                        ],
                                        'dcb' => [
                                            'title' => 'Data de cessação do benefício',
                                            'type' => ['string', 'null'],
                                            'format' => 'date',
                                            'examples' => ['2018-03-20'],
                                        ]
                                    ],
                                ]
                            ],
                            'vinculos_requeridos' => [
                                'title' => 'Vinculos requeridos',
                                'type' => ['array', 'null'],
                                'items' => [
                                    'type' => ['object', 'null'],
                                    'properties' => [
                                        'vinculo' => [
                                            'title' => 'Vinculo',
                                            'type' => ['number', 'null'],
                                            'examples' => [1, 2, 3],
                                        ],
                                        'categoria_segurado' => [
                                            'title' => 'Categoria do segurado',
                                            'type' => ['string', 'null'],
                                            'examples' => [
                                                'EMPREGADO',
                                                'EMPREGADO DOMÉSTICO',
                                                'TRABALHADOR AVULSO',
                                            ]
                                        ],
                                        'origem_vinculo' => [
                                            'title' => 'Origem do vinculo ou o nome do empregador',
                                            'type' => ['string', 'null'],
                                            'examples' => [
                                                'BOA PRAÇA LTDA',
                                            ]
                                        ],
                                        'ocupacao' => [
                                            'title' => 'Nome da função, ocupação ou atividade desempenhada',
                                            'type' => ['string', 'null'],
                                            'examples' => [
                                                'MOTORISTA',
                                                'AGRICULTOR',
                                            ]
                                        ],
                                        'periodo_inicial' => [
                                            'title' => 'Data em que o trabalhador começou a trabalhar na ocupação',
                                            'type' => ['string', 'null'],
                                            'format' => 'date',
                                            'examples' => ['2000-01-01'],
                                        ],
                                        'periodo_final' => [
                                            'title' => 'Data em que o trabalhador parou de trabalhar na ocupação',
                                            'type' => ['string', 'null'],
                                            'format' => 'date',
                                            'examples' => ['2019-03-05'],
                                        ],
                                        'agente_agressivo' => [
                                            'title' => 'Agente agressivo prejudicial à saúde',
                                            'type' => ['string', 'null'],
                                            'examples' => [
                                                'RUÍDO',
                                                'ELETRICIDADE',
                                                'RADIAÇÃO IONIZANTE',
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'additionalProperties' => false
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            )
        ;

        $manager->persist($formulario);
        $this->addReference('ConfigModulo-'.$formulario->getNome(), $formulario);

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
            )
        ;

        $manager->persist($formulario);
        $this->addReference('ConfigModulo-'.$formulario->getNome(), $formulario);

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
        $manager->persist($formulario);
        $this->addReference('Formulario-'.$formulario->getNome(), $formulario);

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
        return ['prod', 'dev', 'test'];
    }
}
