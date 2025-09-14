<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Dev/LoadConfigModuloData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Migrations\Version20241014115244;
use SuppCore\AdministrativoBackend\Entity\ConfigModulo;
use SuppCore\AdministrativoBackend\Entity\Modulo;
use SuppCore\AdministrativoBackend\Entity\Processo;

/**
 * Class LoadConfigModuloData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadConfigModuloData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $moduloAdministrativo = $manager
            ->createQuery(
                "
                SELECT m 
                FROM SuppCore\AdministrativoBackend\Entity\Modulo m 
                WHERE m.nome = 'ADMINISTRATIVO'"
            )
            ->getOneOrNullResult() ?: $this->getReference('Modulo-ADMINISTRATIVO', Modulo::class);

        $configModulo = $manager
            ->createQuery(
                "
                SELECT c 
                FROM SuppCore\AdministrativoBackend\Entity\ConfigModulo c 
                WHERE c.nome = 'supp_core.administrativo_backend.gerador_dossie.template'"
            )
            ->getOneOrNullResult() ?: new ConfigModulo();
        $configModulo->setModulo($moduloAdministrativo);
        $configModulo->setNome('supp_core.administrativo_backend.gerador_dossie.template');
        $configModulo->setDescricao('CONFIGURAÇÕES RELATIVAS À GERAÇÃO DE DOSSIÊS');
        $configModulo->setInvalid(false);
        $configModulo->setMandatory(false);
        $configModulo->setDataType('json');
        $configModulo->setDataSchema(
            json_encode([
                '$schema' => 'http://json-schema.org/draft-07/schema#',
                '$id' => 'supp_core.administrativo_backend.gerador_dossie.template',
                '$comment' => 'Template para geração de um dossie.',
                'title' => 'Template para geração de um dossie.',
                'type' => 'array',
                'minItems' => 1,
                'items' => [
                    '$comment' => 'Parâmetros de configuração de utilização de um dossiê da defesa digital.',
                    'type' => 'object',
                    'additionalProperties' => false,
                    'required' => [
                        0 => 'nome_tipo_dossie',
                        1 => 'ativo',
                        2 => 'assuntos_suportados',
                        3 => 'siglas_unidades_suportadas',
                        4 => 'tarefas_suportadas',
                        5 => 'pesquisa_assunto_pai',
                        6 => 'template',
                    ],
                    'properties' => [
                        'nome_tipo_dossie' => [
                            '$comment' => '',
                            'type' => 'string',
                            'minLength' => 1,
                        ],
                        'ativo' => [
                            '$comment' => '',
                            'type' => 'boolean',
                            'default' => true,
                        ],
                        'num_max_interessados' => [
                            '$comment' => '',
                            'type' => 'integer',
                        ],
                        'assuntos_suportados' => [
                            '$comment' => '',
                            'type' => 'array',
                            'items' => [
                                'type' => 'string',
                            ],
                            'default' => [
                                0 => '*',
                            ],
                            'examples' => [
                                0 => [
                                    0 => 'MILITAR',
                                    1 => 'SERVIDOR PÚBLICO CIVIL',
                                ],
                            ],
                        ],
                        'siglas_unidades_suportadas' => [
                            '$comment' => '',
                            'type' => 'array',
                            'items' => [
                                'type' => 'string',
                            ],
                            'default' => [
                                0 => '*',
                            ],
                            'examples' => [
                                0 => [
                                    0 => 'AGU-SEDE',
                                    1 => 'PGF-SEDE',
                                ],
                            ],
                        ],
                        'tarefas_suportadas' => [
                            '$comment' => '',
                            'type' => 'array',
                            'items' => [
                                'type' => 'string',
                            ],
                            'default' => [
                                0 => '*',
                            ],
                            'examples' => [
                                0 => [
                                    0 => 'ADOTAR PROVIDÊNCIAS ADMINISTRATIVAS',
                                    1 => 'ANALISAR DEMANDAS',
                                ],
                            ],
                        ],
                        'pesquisa_assunto_pai' => [
                            '$comment' => '',
                            'type' => 'boolean',
                            'default' => true,
                        ],
                        'template' => [
                            '$comment' => '',
                            'type' => 'string',
                            'minLength' => 1,
                        ],
                    ],
                ],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
        $manager->persist($configModulo);
        $this->addReference(
            'ConfigModulo-'.$configModulo->getNome(),
            $configModulo
        );

        $configModulo = $manager
            ->createQuery(
                "
                SELECT c 
                FROM SuppCore\AdministrativoBackend\Entity\ConfigModulo c 
                WHERE c.nome = 'supp_core.administrativo_backend.processo.template'"
            )
            ->getOneOrNullResult() ?: new ConfigModulo();
        $configModulo->setModulo($moduloAdministrativo);
        $configModulo->setNome('supp_core.administrativo_backend.processo.template');
        $configModulo->setDescricao('TEMPLATE PARA CRIAÇÃO DE PROCESSO');
        $configModulo->setInvalid(false);
        $configModulo->setMandatory(false);
        $configModulo->setDataType('json');
        $configModulo->setDataSchema(
            json_encode([
                '$schema' => 'http://json-schema.org/draft-07/schema#',
                '$id' => 'supp_core.administrativo_backend.processo.template',
                '$comment' => 'Template para criação de um processo.',
                'title' => 'Template para criação de um processo.',
                'type' => 'object',
                'additionalProperties' => false,
                'properties' => [
                    'nup' => [
                        '$comment' => 'Caso não seja informado, será cadastrado como novo tipo de processo',
                        'type' => 'string',
                        'pattern' => '$([0-9]{17})|([0-9]{21})^',
                        'example' => [
                            '00400000020201813',
                        ],
                    ],
                    'unidade_arquivistica' => [
                        '$comment' => 'Indica se o processo surgirá como processo ou como documento avulso.',
                        'type' => 'integer',
                        'example' => [
                            Processo::UA_PROCESSO,
                        ],
                        'default' => Processo::UA_PROCESSO,
                    ],
                    'cpf_cnpj_procedencia' => [
                        '$comment' => 'CPF ou CNPJ da pessoa de procedência.',
                        'type' => 'string',
                        'pattern' => '$([0-9]{11})|([0-9]{14})^',
                        'example' => [
                            '23298001856',
                            '33415411000105',
                        ],
                    ],
                    'nome_especie_processo' => [
                        '$comment' => 'Nome da espécie de processo',
                        'type' => 'string',
                        'minLength' => 1,
                        'example' => 'ADMINISTRATIVO COMUM',
                    ],
                    'nome_genero_processo' => [
                        '$comment' => 'Nome do gênero de processo',
                        'type' => 'string',
                        'minLength' => 1,
                        'example' => 'ADMINISTRATIVO',
                    ],
                    'codigo_classificacao' => [
                        '$comment' => 'Código da classificação',
                        'type' => 'string',
                        'minLength' => 1,
                        'example' => '060.1',
                    ],
                    'valor_modalidade_interessado_ativo' => [
                        '$comment' => '',
                        'type' => 'string',
                        'minLength' => 1,
                        'example' => [
                            'REQUERENTE (PÓLO ATIVO)',
                        ],
                    ],
                    'valor_modalidade_interessado_passivo' => [
                        '$comment' => '',
                        'type' => 'string',
                        'minLength' => 1,
                        'example' => [
                            'REQUERIDO (PÓLO PASSIVO)',
                        ],
                    ],
                    'assunto_administrativo' => [
                        '$comment' => '',
                        'type' => 'object',
                        'additionalProperties' => false,
                        'properties' => [
                            'nome' => [
                                '$comment' => '',
                                'type' => 'string',
                                'minLength' => 1,
                                'example' => ['DOSSIÊ DE GESTÃO DEVEDOR'],
                            ],
                            'codigo_cnj' => [
                                '$comment' => '',
                                'type' => 'string',
                                'minLength' => 1,
                                'example' => ['5989'],
                            ],
                        ],
                    ],
                    'valor_modalidade_meio' => [
                        '$comment' => '',
                        'type' => 'string',
                        'minLength' => 1,
                        'example' => [
                            'HÍBRIDO',
                            'ELETRÔNICO',
                        ],
                    ],
                    'prefixo_titulo' => [
                        '$comment' => '',
                        'type' => 'string',
                        'minLength' => 1,
                        'example' => [
                            'DOSSIÊ DO PROCESSO JUDICIAL',
                        ],
                    ],
                    'id_setor_atual' => [
                        '$comment' => '',
                        'type' => 'integer',
                        'example' => [
                            10,
                        ],
                    ],
                ],
                'required' => [],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
        $manager->persist($configModulo);
        $this->addReference(
            'ConfigModulo-'.$configModulo->getNome(),
            $configModulo
        );

        $configModulo = $manager
            ->createQuery(
                "
                SELECT c 
                FROM SuppCore\AdministrativoBackend\Entity\ConfigModulo c 
                WHERE c.nome = 'supp_core.administrativo_backend.parametros.template'"
            )
            ->getOneOrNullResult() ?: new ConfigModulo();
        $configModulo->setModulo($moduloAdministrativo);
        $configModulo->setNome('supp_core.administrativo_backend.parametros.template');
        $configModulo->setDescricao('TEMPLATE PARA CRIAÇÃO DE CONFIGURAÇÕES');
        $configModulo->setInvalid(false);
        $configModulo->setMandatory(false);
        $configModulo->setDataType('json');
        $configModulo->setDataSchema(
            json_encode(
                [
                    '$schema' => 'http://json-schema.org/draft-07/schema#',
                    '$id' => 'supp_core.administrativo_backend.parametros.template',
                    '$comment' => 'Template para criação de configurações ',
                    'title' => 'Template para criação de configurações',
                    'type' => 'array',
                    'items' => [
                        '$comment' => '',
                        'type' => 'object',
                        'additionalProperties' => false,
                        'required' => [
                            'chave',
                            'valor',
                        ],
                        'properties' => [
                            'chave' => [
                                '$comment' => 'Chave',
                                'type' => 'string',
                                'example' => 'tamanho_lote_execucao',
                            ],
                            'valor' => [
                                '$comment' => 'Valor',
                                'type' => 'string',
                                'example' => '100',
                            ],
                            'valor_modalidade_orgao_central' => [
                                '$comment' => 'Valor modalidade órgão central',
                                'type' => 'string',
                                'example' => 'PGF',
                            ],
                        ],
                    ],
                ],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
        $manager->persist($configModulo);
        $this->addReference('ConfigModulo-'.$configModulo->getNome(), $configModulo);

        $configModulo = $manager
            ->createQuery(
                "
                SELECT c 
                FROM SuppCore\AdministrativoBackend\Entity\ConfigModulo c 
                WHERE c.nome = 'supp_core.administrativo_backend.datalake.kafka.enabled'"
            )
            ->getOneOrNullResult() ?: new ConfigModulo();
        $configModulo->setModulo($moduloAdministrativo);
        $configModulo->setNome('supp_core.administrativo_backend.datalake.kafka.enabled');
        $configModulo->setDescricao('SERVIÇO DE TÓPICOS KAFKA');
        $configModulo->setInvalid(false);
        $configModulo->setMandatory(true);
        $configModulo->setDataType('bool');
        $configModulo->setDataSchema(null);
        $configModulo->setDataValue('false');
        $manager->persist($configModulo);
        $this->addReference('ConfigModulo-'.$configModulo->getNome(), $configModulo);

        $configModulo = $manager
            ->createQuery(
                "
                SELECT c 
                FROM SuppCore\AdministrativoBackend\Entity\ConfigModulo c 
                WHERE c.nome = 'supp_core.administrativo_backend.datalake.kafka.config'"
            )
            ->getOneOrNullResult() ?: new ConfigModulo();
        $configModulo->setModulo($moduloAdministrativo);
        $configModulo->setNome('supp_core.administrativo_backend.datalake.kafka.config');
        $configModulo->setDescricao('CONFIGURAÇÃO DO SERVIÇO DE TÓPICOS KAFKA, SE EXISTENTE');
        $configModulo->setInvalid(false);
        $configModulo->setMandatory(false);
        $configModulo->setDataType('json');
        $configModulo->setDataSchema(
            json_encode(
                [
                    '$schema' => 'http://json-schema.org/draft-07/schema#',
                    '$id' => 'supp_core.administrativo_backend.datalake.kafka',
                    '$comment' => 'Configuração do Datalake, se existente na infraestrutura',
                    'title' => 'Configuração do Datalake',
                    'type' => 'object',
                    'required' => [
                        'server',
                        'username',
                        'password',
                    ],
                    'properties' => [
                        'server' => [
                            '$comment' => 'Nome do servidor (DNS)  que hospeda o datalake',
                            'type' => 'string',
                            'examples' => ['datalake.org'],
                        ],
                        'username' => [
                            '$comment' => 'Nome do usuário de acesso ao datalake',
                            'type' => 'string',
                            'examples' => ['username'],
                        ],
                        'password' => [
                            '$comment' => 'Senha do usuário de acesso ao datalake',
                            'type' => 'string',
                            'examples' => ['mypassword'],
                        ],
                        'group' => [
                            '$comment' => 'Senha do usuário de acesso ao datalake',
                            'type' => 'string',
                            'examples' => ['mypassword'],
                        ],
                        'url' => [
                            '$comment' => 'URL de acesso ao datalake',
                            'type' => 'string',
                            'examples' => ['/topics'],
                        ],
                        'ativo' => [
                            '$comment' => 'Configuração está ativa',
                            'type' => 'boolean',
                            'examples' => [true],
                        ],
                    ],
                ],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
        $configModulo->setDataValue(
            json_encode(
                [
                    'server' => $_ENV['DATALAKE_SERVER'] ?? '',
                    'username' => $_ENV['DATALAKE_USER'] ?? '',
                    'password' => $_ENV['DATALAKE_PASS'] ?? '',
                    'url' => '/topics',
                    'ativo' => boolval($_ENV['DATALAKE_SERVER'] ?? false),
                ],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
        $manager->persist($configModulo);
        $this->addReference('ConfigModulo-'.$configModulo->getNome(), $configModulo);

        $configModulo = $manager
            ->createQuery(
                "
                SELECT c 
                FROM SuppCore\AdministrativoBackend\Entity\ConfigModulo c 
                WHERE c.nome = 'supp_core.administrativo_backend.formularios'"
            )
            ->getOneOrNullResult() ?: new ConfigModulo();
        $configModulo->setModulo($moduloAdministrativo);
        $configModulo->setNome('supp_core.administrativo_backend.formularios');
        $configModulo->setDescricao('CONFIGURAÇÃO DE DADOS DO PROCESSO PARA FORMULÁRIO REQUERIMENTO COBRANÇA PGF');
        $configModulo->setInvalid(false);
        $configModulo->setMandatory(false);
        $configModulo->setDataType('json');
        $configModulo->setDataSchema(
            json_encode(
                [
                    '$schema' => 'http://json-schema.org/draft-07/schema#',
                    '$id' => 'supp_core.administrativo_backend.formularios',
                    '$comment' => 'Configuração de dados do processo para formulário requerimento cobrança PGF',
                    'title' => 'Configuração de dados do processo para formulário requerimento cobrança PGF',
                    'type' => 'array',
                    'items' => [
                        '$id' => '#root/formularios/items',
                        'title' => 'Configurações por sigla de formulários',
                        'type' => 'object',
                        'required' => [
                            'sigla_formulario',
                            'codigo_classificacao_processo',
                            'titulo_processo',
                            'descricao_processo',
                            'assuntos_processo',
                            'etiquetas_processo',
                            'lembretes_processo',
                            'nome_especie_processo',
                            'nome_especie_tarefa',
                            'etiquetas_tarefa',
                            'sigla_unidade',
                        ],
                        'properties' => [
                            'sigla_formulario' => [
                                '$id' => '#root/formularios/items/sigla_formulario',
                                'title' => 'Sigla Formulário',
                                'type' => 'string',
                                'minLength' => 3,
                                'examples' => [
                                    'requerimento_pgf_cobranca_parcelamento',
                                ],
                            ],
                            'codigo_classificacao_processo' => [
                                '$id' => '#root/formularios/items/codigo_classificacao_processo',
                                'title' => 'Código Classificação Processo',
                                'type' => 'string',
                                'minLength' => 1,
                                'examples' => [
                                    '091',
                                ],
                            ],
                            'titulo_processo' => [
                                '$id' => '#root/formularios/items/titulo_processo',
                                'title' => 'Título do Processo',
                                'type' => 'string',
                                'minLength' => 3,
                                'examples' => [
                                    'REQUERIMENTO COBRANÇA PARCELAMENTO PGF',
                                ],
                            ],
                            'descricao_processo' => [
                                '$id' => '#root/formularios/items/descricao_processo',
                                'title' => 'Descrição do Processo',
                                'type' => 'string',
                                'minLength' => 3,
                                'examples' => [
                                    'REQUERIMENTO COBRANÇA PARCELAMENTO PGF',
                                ],
                            ],
                            'assuntos_processo' => [
                                '$id' => '#root/formularios/items/assuntos_processo',
                                'title' => 'Assuntos do Processo',
                                'type' => 'array',
                                'minItems' => 1,
                                'items' => [
                                    'type' => 'object',
                                    'required' => [
                                        'nome_assunto',
                                    ],
                                    'properties' => [
                                        'nome_assunto' => [
                                            '$id' => '#root/formularios/items/assuntos_processo/nome_assunto',
                                            'title' => 'Nome Assunto',
                                            'type' => 'string',
                                            'minLength' => 3,
                                            'examples' => [
                                                'ATIVIDADE MEIO',
                                            ],
                                        ],
                                        'principal' => [
                                            '$id' => '#root/formularios/items/assuntos_processo/principal',
                                            'title' => 'Principal',
                                            'type' => [
                                                'boolean',
                                                'null',
                                            ],
                                            'default' => false,
                                            'examples' => [
                                                false,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'etiquetas_processo' => [
                                '$id' => '#root/formularios/items/etiquetas_processo',
                                'title' => 'Etiquetas do Processo',
                                'type' => 'array',
                                'items' => [
                                    '$id' => '#root/formularios/items/etiquetas_processo/items',
                                    'title' => 'Items',
                                    'type' => 'string',
                                    'examples' => [
                                        'REQUERIMENTO COBRANÇA PARCELAMENTO PGF',
                                    ],
                                ],
                            ],
                            'lembretes_processo' => [
                                '$id' => '#root/formularios/items/lembretes_processo',
                                'title' => 'Lembrete do Processo',
                                'type' => 'array',
                                'items' => [
                                    '$id' => '#root/formularios/items/lembretes_processo/items',
                                    'title' => 'Items',
                                    'type' => 'string',
                                    'examples' => [
                                        'REQUERIMENTO COBRANÇA PARCELAMENTO PGF',
                                    ],
                                ],
                            ],
                            'nome_especie_processo' => [
                                '$id' => '#root/formularios/items/nome_especie_processo',
                                'title' => 'Nome Espécie Processo',
                                'type' => 'string',
                                'minLength' => 3,
                                'examples' => [
                                    'ADMINISTRATIVO COMUM',
                                ],
                            ],
                            'nome_especie_tarefa' => [
                                '$id' => '#root/formularios/items/nome_especie_tarefa',
                                'title' => 'Nome Espécie Tarefa',
                                'type' => ['string', 'null'],
                                'minLength' => 3,
                                'examples' => [
                                    'ADOTAR PROVIDÊNCIAS ADMINISTRATIVAS',
                                    null,
                                ],
                            ],
                            'etiquetas_tarefa' => [
                                '$id' => '#root/formularios/items/etiquetas_tarefa',
                                'title' => 'Etiquetas Tarefa',
                                'type' => 'array',
                                'items' => [
                                    '$id' => '#root/formularios/items/etiquetas_tarefa/items',
                                    'title' => 'Items',
                                    'type' => 'string',
                                    'examples' => [
                                        'REQUERIMENTO COBRANÇA PARCELAMENTO PGF',
                                    ],
                                ],
                            ],
                            'sigla_unidade' => [
                                '$id' => '#root/formularios/items/sigla_unidade',
                                'title' => 'Sigla Unidade',
                                'type' => 'string',
                                'minLength' => 3,
                                'examples' => [
                                    'PRF1',
                                ],
                            ],
                            'modalidade_meio' => [
                                '$id' => '#root/formularios/items/modalidade_meio',
                                'title' => 'Modalidade Meio',
                                'type' => [
                                    'string',
                                    'null',
                                ],
                                'default' => 'ELETRÔNICO',
                                'examples' => [
                                    'ELETRÔNICO',
                                ],
                            ],
                            'post_it_tarefa' => [
                                '$id' => '#root/formularios/items/post_it_tarefa',
                                'title' => 'Post It Tarefa',
                                'type' => [
                                    'string',
                                    'null',
                                ],
                                'default' => '',
                                'examples' => [
                                    'REQUERIMENTO COBRANÇA PARCELAMENTO PGF',
                                ],
                            ],
                            'observacao_tarefa' => [
                                '$id' => '#root/formularios/items/observacao_tarefa',
                                'title' => 'Observação Tarefa',
                                'type' => [
                                    'string',
                                    'null',
                                ],
                                'default' => '',
                                'examples' => [
                                    'REQUERIMENTO COBRANÇA PARCELAMENTO PGF',
                                ],
                            ],
                            'nome_setor_unidade' => [
                                '$id' => '#root/formularios/items/nome_setor_unidade',
                                'title' => 'Sigla Unidade',
                                'type' => [
                                    'string',
                                    'null',
                                ],
                                'minLength' => 3,
                                'default' => 'PROTOCOLO',
                                'examples' => [
                                    'PROTOCOLO',
                                ],
                            ],
                        ],
                    ],
                ],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
        $configModulo->setDataValue(
            json_encode(
                [
                    [
                        'sigla_formulario' => 'requerimento_pgf_cobranca_parcelamento',
                        'codigo_classificacao_processo' => '211.2',
                        'titulo_processo' => 'REQUERIMENTO COBRANÇA PARCELAMENTO PGF',
                        'descricao_processo' => "ROTINA: GCPARC;\nSISTEMA: SICAFI;\nESTADO: RR;\nTOTAL_PARCELAS: NN;\nENCARGOS: 10 OU 20;\nVENCIMENTO_PRIMEIRA: DD/MM/AAAA;\nENVIAR_BOLETO: S/N;\nPROTESTO: S/N;\n",
                        'assuntos_processo' => [
                            [
                                'nome_assunto' => 'ATIVIDADE MEIO',
                                'principal' => true,
                            ],
                        ],
                        'etiquetas_processo' => [
                            'PARCELAMENTO PGF',
                        ],
                        'lembretes_processo' => [
                            'REQUERIMENTO COBRANÇA PARCELAMENTO PGF',
                        ],
                        'nome_especie_processo' => 'COMUM',
                        'nome_especie_tarefa' => 'ADOTAR PROVIDÊNCIAS ADMINISTRATIVAS',
                        'etiquetas_tarefa' => [
                            'PARCELAMENTO FORM',
                        ],
                        'sigla_unidade' => 'PGF-SEDE',
                        'modalidade_meio' => 'ELETRÔNICO',
                        'post_it_tarefa' => 'REQUERIMENTO COBRANÇA PARCELAMENTO PGF',
                        'observacao_tarefa' => 'REQUERIMENTO COBRANÇA PARCELAMENTO PGF',
                        'nome_setor_unidade' => 'PROTOCOLO',
                    ],
                    [
                        'sigla_formulario' => 'requerimento_pgf_cobranca_atendimento',
                        'codigo_classificacao_processo' => '211.2',
                        'titulo_processo' => 'REQUERIMENTO COBRANÇA ATENDIMENTO PGF',
                        'descricao_processo' => "ROTINA: GCPARC;\nSISTEMA: SICAFI;\nESTADO: RR;\nTOTAL_PARCELAS: NN;\nENCARGOS: 10 OU 20;\nVENCIMENTO_PRIMEIRA: DD/MM/AAAA;\nENVIAR_BOLETO: S/N;\nPROTESTO: S/N;\n",
                        'assuntos_processo' => [
                            [
                                'nome_assunto' => 'ATIVIDADE MEIO',
                                'principal' => true,
                            ],
                        ],
                        'etiquetas_processo' => [
                            'ATENDIMENTO PGF',
                        ],
                        'lembretes_processo' => [
                            'REQUERIMENTO COBRANÇA ATENDIMENTO PGF',
                        ],
                        'nome_especie_processo' => 'COMUM',
                        'nome_especie_tarefa' => 'ADOTAR PROVIDÊNCIAS ADMINISTRATIVAS',
                        'etiquetas_tarefa' => [
                            'ATENDIMENTO FORM',
                        ],
                        'sigla_unidade' => 'PGF-SEDE',
                        'modalidade_meio' => 'ELETRÔNICO',
                        'post_it_tarefa' => 'REQUERIMENTO COBRANÇA ATENDIMENTO PGF',
                        'observacao_tarefa' => 'REQUERIMENTO COBRANÇA ATENDIMENTO PGF',
                        'nome_setor_unidade' => 'PROTOCOLO',
                    ],
                    [
                        'sigla_formulario' => 'requerimento_pgf_previdencario_conciliacao_salario_maternidade_especial',
                        'codigo_classificacao_processo' => '211.2',
                        'titulo_processo' => 'FORMULÁRIO DE REQUERIMENTO PAC PREV SALÁRIO MATERNIDADE ESPECIAL',
                        'descricao_processo' => "FORMULÁRIO;\nSSALÁRIO - MATERNIDADE (SEGURADA ESPECIAL)\n",
                        'assuntos_processo' => [
                            [
                                'nome_assunto' => 'ATIVIDADE MEIO',
                                'principal' => true,
                            ],
                        ],
                        'etiquetas_processo' => [
                            'MATERNIDADE RURAL',
                        ],
                        'lembretes_processo' => [
                            'REQUERIMENTO PAC PREV SALÁRIO MATERNIDADE ESPECIAL',
                        ],
                        'nome_especie_processo' => 'COMUM',
                        'nome_especie_tarefa' => 'ADOTAR PROVIDÊNCIAS ADMINISTRATIVAS',
                        'etiquetas_tarefa' => [
                            'PACPREV FORM',
                            'FORMULÁRIO SALARIO MATERNIDADE RURAL DOSSIÊ PREVIDENCIÁRIO',
                        ],
                        'sigla_unidade' => 'PGF-SEDE',
                        'modalidade_meio' => 'ELETRÔNICO',
                        'post_it_tarefa' => 'REQUERIMENTO PAC PREV SALÁRIO MATERNIDADE ESPECIAL',
                        'observacao_tarefa' => 'REQUERIMENTO PAC PREV SALÁRIO MATERNIDADE ESPECIAL',
                        'nome_setor_unidade' => 'PROTOCOLO',
                    ],
                    [
                        'sigla_formulario' => 'requerimento.pacifica.salario_maternidade_rural',
                        'codigo_classificacao_processo' => '211.2',
                        'titulo_processo' => 'FORMULÁRIO DE REQUERIMENTO PACIFICA SALÁRIO MATERNIDADE ESPECIAL',
                        'descricao_processo' => "FORMULÁRIO;\nSSALÁRIO - MATERNIDADE (SEGURADA ESPECIAL)\n",
                        'assuntos_processo' => [
                            [
                                'nome_assunto' => 'ATIVIDADE MEIO',
                                'principal' => true,
                            ],
                        ],
                        'etiquetas_processo' => [
                            'MATERNIDADE RURAL',
                        ],
                        'lembretes_processo' => [
                            'REQUERIMENTO PACIFICA SALÁRIO MATERNIDADE ESPECIAL',
                        ],
                        'nome_especie_processo' => 'COMUM',
                        'nome_especie_tarefa' => null,
                        'etiquetas_tarefa' => [],
                        'sigla_unidade' => 'PGF-SEDE',
                        'modalidade_meio' => 'ELETRÔNICO',
                        'post_it_tarefa' => null,
                        'observacao_tarefa' => null,
                        'nome_setor_unidade' => 'PROTOCOLO',
                    ],
                    [
                        'sigla_formulario' => 'requerimento_transacao_adesao_pgf',
                        'codigo_classificacao_processo' => '211.2',
                        'titulo_processo' => 'FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO EXTRAORDINÁRIA PGF',
                        'descricao_processo' => "ROTINA: GCPARC;\nSISTEMA: SICAFI;\nESTADO: RR;\nTOTAL_PARCELAS: NN;\nENCARGOS: 10 OU 20;\nVENCIMENTO_PRIMEIRA: DD/MM/AAAA;\nENVIAR_BOLETO: S/N;\nPROTESTO: S/N;\n",
                        'assuntos_processo' => [
                            [
                                'nome_assunto' => 'ATIVIDADE MEIO',
                                'principal' => true,
                            ],
                        ],
                        'etiquetas_processo' => [
                            'TRANSAÇÃO2024 ADESÃO',
                        ],
                        'lembretes_processo' => [
                            'FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO EXTRAORDINÁRIA PGF',
                        ],
                        'nome_especie_processo' => 'COMUM',
                        'nome_especie_tarefa' => 'ADOTAR PROVIDÊNCIAS ADMINISTRATIVAS',
                        'etiquetas_tarefa' => [
                            'TRANSAÇÃO ADESÃO',
                        ],
                        'sigla_unidade' => 'PGF-SEDE',
                        'modalidade_meio' => 'ELETRÔNICO',
                        'post_it_tarefa' => 'FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO EXTRAORDINÁRIA PGF',
                        'observacao_tarefa' => 'FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO EXTRAORDINÁRIA PGF',
                        'nome_setor_unidade' => 'PROTOCOLO',
                    ],
                    [
                        'sigla_formulario' => 'requerimento_transacao_informacoes_pgf',
                        'codigo_classificacao_processo' => '211.2',
                        'titulo_processo' => 'FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO INFORMAÇÕES GERAIS',
                        'descricao_processo' => "ROTINA: GCPARC;\nSISTEMA: SICAFI;\nESTADO: RR;\nTOTAL_PARCELAS: NN;\nENCARGOS: 10 OU 20;\nVENCIMENTO_PRIMEIRA: DD/MM/AAAA;\nENVIAR_BOLETO: S/N;\nPROTESTO: S/N;\n",
                        'assuntos_processo' => [
                            [
                                'nome_assunto' => 'ATIVIDADE MEIO',
                                'principal' => true,
                            ],
                        ],
                        'etiquetas_processo' => [
                            'TRANSAÇÃO2024 INFO',
                        ],
                        'lembretes_processo' => [
                            'FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO INFORMAÇÕES GERAIS',
                        ],
                        'nome_especie_processo' => 'COMUM',
                        'nome_especie_tarefa' => 'ADOTAR PROVIDÊNCIAS ADMINISTRATIVAS',
                        'etiquetas_tarefa' => [
                            'TRANSAÇÃO INFO',
                        ],
                        'sigla_unidade' => 'PGF-SEDE',
                        'modalidade_meio' => 'ELETRÔNICO',
                        'post_it_tarefa' => 'FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO INFORMAÇÕES GERAIS',
                        'observacao_tarefa' => 'FORMULÁRIO DE REQUERIMENTO TRANSAÇÃO INFORMAÇÕES GERAIS',
                        'nome_setor_unidade' => 'PROTOCOLO',
                    ],
                ],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
        $manager->persist($configModulo);
        $this->addReference('ConfigModulo-'.$configModulo->getNome(), $configModulo);

        $configModulo = $manager
            ->createQuery(
                "
                SELECT c 
                FROM SuppCore\AdministrativoBackend\Entity\ConfigModulo c 
                WHERE c.nome = 'supp_core.administrativo_backend.formularios.pgf'"
            )
            ->getOneOrNullResult() ?: new ConfigModulo();
        $configModulo->setModulo($moduloAdministrativo);
        $configModulo->setNome('supp_core.administrativo_backend.formularios.pgf');
        $configModulo->setDescricao('CONFIGURAÇÃO DE DADOS ESPECIFICOS DO PROCESSO PARA FORMULÁRIO DE REQUERIMENTO DE ATENDIMENTO PGF');
        $configModulo->setInvalid(false);
        $configModulo->setMandatory(false);
        $configModulo->setDataType('json');
        $configModulo->setDataSchema(
            json_encode(
                [
                    '$schema' => 'http://json-schema.org/draft-07/schema#',
                    '$id' => 'supp_core.administrativo_backend.formularios.pgf',
                    '$comment' => 'Configuração de dados do processo para formulário de requerimento de atendimento PGF',
                    'title' => 'Configuração de dados do processo para formulário de requerimento de atendimento PGF',
                    'type' => 'object',
                    'required' => [
                        'distribuicao_atendimento_geral',
                        'distribuicao_parcelamento',
                    ],
                    'properties' => [
                        'distribuicao_atendimento_geral' => [
                            '$id' => '#root/distribuicao_atendimento_geral',
                            'title' => 'Mapeamento Setores Atendimento',
                            'type' => 'array',
                            'default' => [
                            ],
                            'items' => [
                                '$id' => '#root/distribuicao_atendimento_geral/items',
                                'title' => 'Items',
                                'type' => 'object',
                                'required' => [
                                    'sigla_unidade',
                                    'nome_setor',
                                    'uf_domicilio_devedor',
                                ],
                                'properties' => [
                                    'sigla_unidade' => [
                                        '$id' => '#root/distribuicao_atendimento_geral/items/sigla_unidade',
                                        'title' => 'Sigla Unidade',
                                        'type' => 'string',
                                        'examples' => [
                                            'PRF1',
                                        ],
                                    ],
                                    'nome_setor' => [
                                        '$id' => '#root/distribuicao_atendimento_geral/items/nome_setor',
                                        'title' => 'Nome Setor',
                                        'type' => 'string',
                                        'examples' => [
                                            'PROTOCOLO',
                                        ],
                                    ],
                                    'uf_domicilio_devedor' => [
                                        '$id' => '#root/distribuicao_atendimento_geral/items/uf_domicilio_devedor',
                                        'title' => 'UF Domicílio Devedor',
                                        'type' => 'array',
                                        'items' => [
                                            '$id' => '#root/formularios/items/etiquetas_tarefa/items',
                                            'title' => 'Items',
                                            'type' => 'string',
                                            'examples' => [
                                                'DF',
                                                'GO',
                                                'TO',
                                                'BA',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'distribuicao_parcelamento' => [
                            '$id' => '#root/distribuicao_parcelamento',
                            'title' => 'Mapeamento Setores Parcelamento',
                            'type' => 'array',
                            'default' => [
                            ],
                            'items' => [
                                '$id' => '#root/distribuicao_parcelamento/items',
                                'title' => 'Items',
                                'type' => 'object',
                                'required' => [
                                    'sigla_unidade',
                                    'nome_setor',
                                    'uf_domicilio_devedor',
                                ],
                                'properties' => [
                                    'sigla_unidade' => [
                                        '$id' => '#root/distribuicao_parcelamento/items/sigla_unidade',
                                        'title' => 'Sigla Unidade',
                                        'type' => 'string',
                                        'examples' => [
                                            'ENAC',
                                        ],
                                    ],
                                    'nome_setor' => [
                                        '$id' => '#root/distribuicao_parcelamento/items/nome_setor',
                                        'title' => 'Nome Setor',
                                        'type' => 'string',
                                        'examples' => [
                                            'PROTOCOLO',
                                        ],
                                    ],
                                    'uf_domicilio_devedor' => [
                                        '$id' => '#root/distribuicao_atendimento_geral/items/uf_domicilio_devedor',
                                        'title' => 'UF Domicílio Devedor',
                                        'type' => 'array',
                                        'items' => [
                                            '$id' => '#root/formularios/items/etiquetas_tarefa/items',
                                            'title' => 'Items',
                                            'type' => 'string',
                                            'examples' => [
                                                'DF',
                                                'GO',
                                                'TO',
                                                'BA',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'polo_passivo' => [
                            '$id' => '#root/polo_passivo',
                            'title' => 'Mapeamento de Polos Passívos',
                            'type' => 'array',
                            'default' => [
                            ],
                            'items' => [
                                '$id' => '#root/polo_passivo/items',
                                'title' => 'Items',
                                'type' => 'object',
                                'required' => [
                                    'id',
                                    'nome',
                                ],
                                'properties' => [
                                    'id' => [
                                        '$id' => '#root/polo_passivo/items/id',
                                        'title' => 'ID',
                                        'type' => 'integer',
                                        'examples' => [
                                            10,
                                        ],
                                    ],
                                    'nome' => [
                                        '$id' => '#root/polo_passivo/items/nome',
                                        'title' => 'Nome',
                                        'type' => 'string',
                                        'examples' => [
                                            'Agência Nacional de Telecomunicações - ANATEL',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
        $configModulo->setDataValue(
            json_encode(
                [
                    'distribuicao_atendimento_geral' => [
                        [
                            'sigla_unidade' => 'PGF-SEDE',
                            'nome_setor' => 'PROTOCOLO',
                            'uf_domicilio_devedor' => [
                                'DF',
                                'GO',
                                'TO',
                                'BA',
                                'PI',
                                'MA',
                                'PA',
                                'MT',
                                'RO',
                                'AC',
                                'AM',
                                'RR',
                                'AP',
                                'MG',
                            ],
                        ],
                        [
                            'sigla_unidade' => 'PGF-SEDE',
                            'nome_setor' => 'PROTOCOLO',
                            'uf_domicilio_devedor' => [
                                'RJ',
                                'ES',
                            ],
                        ],
                        [
                            'sigla_unidade' => 'PGF-SEDE',
                            'nome_setor' => 'PROTOCOLO',
                            'uf_domicilio_devedor' => [
                                'SP',
                                'MS',
                            ],
                        ],
                        [
                            'sigla_unidade' => 'ENAC',
                            'nome_setor' => 'PROTOCOLO',
                            'uf_domicilio_devedor' => [
                                'RS',
                                'SC',
                                'PR',
                            ],
                        ],
                        [
                            'sigla_unidade' => 'PGF-SEDE',
                            'nome_setor' => 'PROTOCOLO',
                            'uf_domicilio_devedor' => [
                                'AL',
                                'CE',
                                'PB',
                                'PE',
                                'RN',
                                'SE',
                            ],
                        ],
                    ],
                    'distribuicao_parcelamento' => [
                        [
                            'sigla_unidade' => 'PGF-SEDE',
                            'nome_setor' => 'PROTOCOLO',
                            'uf_domicilio_devedor' => [
                                'DF',
                                'GO',
                                'TO',
                                'BA',
                                'PI',
                                'MA',
                                'PA',
                                'MT',
                                'RO',
                                'AC',
                                'AM',
                                'RR',
                                'AP',
                                'MG',
                                'RJ',
                                'ES',
                                'SP',
                                'MS',
                                'RS',
                                'SC',
                                'PR',
                                'AL',
                                'CE',
                                'PB',
                                'PE',
                                'RN',
                                'SE',
                            ],
                        ],
                    ],
                    'polo_passivo' => [
                        [
                            'id' => 2,
                            'nome' => '*',
                        ],
                        [
                            'id' => 10,
                            'nome' => 'AGÊNCIA NACIONAL DE TRANSPORTES TERRESTRES - ANTT',
                        ],
                        [
                            'id' => 12,
                            'nome' => 'INSTITUTO FEDERAL DE EDUCAÇÃO, CIÊNCIA E TECNOLOGIA CATARINENSE - IFC',
                        ],
                        [
                            'id' => 13,
                            'nome' => 'INSTITUTO FEDERAL DE EDUCAÇÃO, CIÊNCIA E TECNOLOGIA DA PARAÍBA - IFPB',
                        ],
                    ],
                ],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
        $manager->persist($configModulo);
        $this->addReference('ConfigModulo-'.$configModulo->getNome(), $configModulo);

        $configModulo = $manager
            ->createQuery(
                "
                SELECT c 
                FROM SuppCore\AdministrativoBackend\Entity\ConfigModulo c 
                WHERE c.nome = 'supp_core.administrativo_backend.formularios.transacao_adesao_pgf'"
            )
            ->getOneOrNullResult() ?: new ConfigModulo();
        $configModulo->setModulo($moduloAdministrativo);
        $configModulo->setNome('supp_core.administrativo_backend.formularios.transacao_adesao_pgf');
        $configModulo->setDescricao('CONFIGURAÇÃO DE DADOS ESPECIFICOS DO PROCESSO PARA FORMULÁRIO DE REQUERIMENTO DE TRANSAÇÃO EXTRAORDINÁRIA PGF');
        $configModulo->setInvalid(false);
        $configModulo->setMandatory(false);
        $configModulo->setDataType('json');
        $configModulo->setDataSchema(
            json_encode(
                [
                    '$schema' => 'http://json-schema.org/draft-07/schema#',
                    '$id' => 'supp_core.administrativo_backend.formularios.transacao_adesao_pgf',
                    '$comment' => 'Configuração de dados do processo para formulário de requerimento de transação extraordinária PGF',
                    'title' => 'Configuração de dados do processo para formulário de requerimento de atendimento PGF',
                    'type' => 'object',
                    'required' => [
                        'distribuicao_transacao_informacoes',
                        'distribuicao_transacao',
                    ],
                    'properties' => [
                        'distribuicao_transacao_informacoes' => [
                            '$id' => '#root/distribuicao_transacao_informacoes',
                            'title' => 'Mapeamento Setores Atendimento',
                            'type' => 'array',
                            'default' => [
                            ],
                            'items' => [
                                '$id' => '#root/distribuicao_transacao_informacoes/items',
                                'title' => 'Items',
                                'type' => 'object',
                                'required' => [
                                    'sigla_unidade',
                                    'nome_setor',
                                    'uf_domicilio_devedor',
                                ],
                                'properties' => [
                                    'sigla_unidade' => [
                                        '$id' => '#root/distribuicao_transacao_informacoes/items/sigla_unidade',
                                        'title' => 'Sigla Unidade',
                                        'type' => 'string',
                                        'examples' => [
                                            'PRF1',
                                        ],
                                    ],
                                    'nome_setor' => [
                                        '$id' => '#root/distribuicao_transacao_informacoes/items/nome_setor',
                                        'title' => 'Nome Setor',
                                        'type' => 'string',
                                        'examples' => [
                                            'PROTOCOLO',
                                        ],
                                    ],
                                    'uf_domicilio_devedor' => [
                                        '$id' => '#root/distribuicao_transacao_informacoes/items/uf_domicilio_devedor',
                                        'title' => 'UF Domicílio Devedor',
                                        'type' => 'array',
                                        'items' => [
                                            '$id' => '#root/distribuicao_transacao_informacoes/items/uf_domicilio_devedor/items',
                                            'title' => 'Items',
                                            'type' => 'string',
                                            'examples' => [
                                                'DF',
                                                'GO',
                                                'TO',
                                                'BA',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'distribuicao_transacao' => [
                            '$id' => '#root/distribuicao_transacao',
                            'title' => 'Mapeamento Setores Transação Extraordinária PGF',
                            'type' => 'array',
                            'default' => [
                            ],
                            'items' => [
                                '$id' => '#root/distribuicao_transacao/items',
                                'title' => 'Items',
                                'type' => 'object',
                                'required' => [
                                    'sigla_unidade',
                                    'nome_setor',
                                    'uf_domicilio_devedor',
                                ],
                                'properties' => [
                                    'sigla_unidade' => [
                                        '$id' => '#root/distribuicao_transacao/items/sigla_unidade',
                                        'title' => 'Sigla Unidade',
                                        'type' => 'string',
                                        'examples' => [
                                            'ENAC',
                                        ],
                                    ],
                                    'nome_setor' => [
                                        '$id' => '#root/distribuicao_transacao/items/nome_setor',
                                        'title' => 'Nome Setor',
                                        'type' => 'string',
                                        'examples' => [
                                            'PROTOCOLO',
                                        ],
                                    ],
                                    'uf_domicilio_devedor' => [
                                        '$id' => '#root/distribuicao_transacao/items/uf_domicilio_devedor',
                                        'title' => 'UF Domicílio Devedor',
                                        'type' => 'array',
                                        'items' => [
                                            '$id' => '#root/distribuicao_transacao/items/uf_domicilio_devedor/items',
                                            'title' => 'Items',
                                            'type' => 'string',
                                            'examples' => [
                                                'DF',
                                                'GO',
                                                'TO',
                                                'BA',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'polo_passivo' => [
                            '$id' => '#root/polo_passivo',
                            'title' => 'Mapeamento de Polos Passívos',
                            'type' => 'array',
                            'default' => [
                            ],
                            'items' => [
                                '$id' => '#root/polo_passivo/items',
                                'title' => 'Items',
                                'type' => 'object',
                                'required' => [
                                    'id',
                                    'nome',
                                ],
                                'properties' => [
                                    'id' => [
                                        '$id' => '#root/polo_passivo/items/id',
                                        'title' => 'ID',
                                        'type' => 'integer',
                                        'examples' => [
                                            10,
                                        ],
                                    ],
                                    'nome' => [
                                        '$id' => '#root/polo_passivo/items/nome',
                                        'title' => 'Nome',
                                        'type' => 'string',
                                        'examples' => [
                                            'Agência Nacional de Telecomunicações - ANATEL',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
        $configModulo->setDataValue(
            json_encode(
                [
                    'distribuicao_transacao_informacoes' => [
                        [
                            'sigla_unidade' => 'PGF-SEDE',
                            'nome_setor' => 'PROTOCOLO',
                            'uf_domicilio_devedor' => [
                                'DF',
                                'GO',
                                'TO',
                                'BA',
                                'PI',
                                'MA',
                                'PA',
                                'MT',
                                'RO',
                                'AC',
                                'AM',
                                'RR',
                                'AP',
                                'MG',
                            ],
                        ],
                        [
                            'sigla_unidade' => 'PGF-SEDE',
                            'nome_setor' => 'PROTOCOLO',
                            'uf_domicilio_devedor' => [
                                'RJ',
                                'ES',
                            ],
                        ],
                        [
                            'sigla_unidade' => 'PGF-SEDE',
                            'nome_setor' => 'PROTOCOLO',
                            'uf_domicilio_devedor' => [
                                'SP',
                                'MS',
                            ],
                        ],
                        [
                            'sigla_unidade' => 'ENAC',
                            'nome_setor' => 'PROTOCOLO',
                            'uf_domicilio_devedor' => [
                                'RS',
                                'SC',
                                'PR',
                            ],
                        ],
                        [
                            'sigla_unidade' => 'PGF-SEDE',
                            'nome_setor' => 'PROTOCOLO',
                            'uf_domicilio_devedor' => [
                                'AL',
                                'CE',
                                'PB',
                                'PE',
                                'RN',
                                'SE',
                            ],
                        ],
                    ],
                    'distribuicao_transacao' => [
                        [
                            'sigla_unidade' => 'PGF-SEDE',
                            'nome_setor' => 'PROTOCOLO',
                            'uf_domicilio_devedor' => [
                                'DF',
                                'GO',
                                'TO',
                                'BA',
                                'PI',
                                'MA',
                                'PA',
                                'MT',
                                'RO',
                                'AC',
                                'AM',
                                'RR',
                                'AP',
                                'MG',
                                'RJ',
                                'ES',
                                'SP',
                                'MS',
                                'RS',
                                'SC',
                                'PR',
                                'AL',
                                'CE',
                                'PB',
                                'PE',
                                'RN',
                                'SE',
                            ],
                        ],
                    ],
                    'polo_passivo' => [
                        [
                            'id' => 2,
                            'nome' => '*',
                        ],
                        [
                            'id' => 10,
                            'nome' => 'Agência Nacional de Transportes Terrestres - ANTT',
                        ],
                        [
                            'id' => 12,
                            'nome' => 'Instituto Federal de Educação, Ciência e Tecnologia Catarinense - IFC',
                        ],
                        [
                            'id' => 13,
                            'nome' => 'Instituto Federal de Educação, Ciência e Tecnologia da Paraíba - IFPB',
                        ],
                    ],
                ],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
        $manager->persist($configModulo);
        $this->addReference('ConfigModulo-'.$configModulo->getNome(), $configModulo);

        $configModulo = (new ConfigModulo())
            ->setModulo($moduloAdministrativo)
            ->setMandatory(true)
            ->setInvalid(false)
            ->setNome('supp_core.administrativo_backend.ia.trilha.gestao_conhecimento_petinicao_inicial')
            ->setDescricao('CONFIGURAÇÕES DA TRILHA DE GESTÃO DE CONHECIMENTO DE PETIÇÃO INICIAL')
            ->setSigla('ADMINISTRATIVO_TRILHA_GESTAO_CONHECIMENTO_PETICAO_INICIAL')
            ->setDataType('json')
            ->setDataSchema(
                json_encode(
                    [
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'supp_core.administrativo_backend.ia.trilha.gestao_conhecimento_petinicao_inicial',
                        'title' => 'CONFIGURAÇÕES DA TRILHA DE GESTÃO DE CONHECIMENTO DE PETIÇÃO INICIAL',
                        'type' => 'object',
                        'required' => [
                            'ativo',
                            'tipos_documentos_suportados'
                        ],
                        'properties' => [
                            'ativo' => [
                                'type' => 'boolean',
                                'title' => 'Trilha Habilitada',
                                'description' => 'Indica se a execução da trilha esta habilitada',
                                'examples' => [
                                    true,
                                    false
                                ]
                            ],
                            'tipos_documentos_suportados' => [
                                'type' => 'array',
                                'title' => 'Sigla dos Tipos de Documentos Suportados',
                                'description' => 'Lista dos tipos de documentos suportados pela trilha',
                                'items' => [
                                    'type' => 'string',
                                    'examples' => [
                                        'PETINI'
                                    ]
                                ]
                            ]
                        ],
                        'additionalProperties' => false
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            )
            ->setDataValue(
                json_encode(
                    [
                        'ativo' => true,
                        'tipos_documentos_suportados' => [
                            'PETINI'
                        ]
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );
        $manager->persist($configModulo);
        $this->addReference('ConfigModulo-' . $configModulo->getNome(), $configModulo);

        $configModulo = (new ConfigModulo())
            ->setModulo($moduloAdministrativo)
            ->setMandatory(true)
            ->setInvalid(false)
            ->setNome('supp_core.administrativo_backend.ia.trilha.gestao_conhecimento_sentenca')
            ->setDescricao('CONFIGURAÇÕES DA TRILHA DE GESTÃO DE CONHECIMENTO DE SENTENÇA')
            ->setSigla('ADMINISTRATIVO_TRILHA_GESTAO_CONHECIMENTO_SENTENCA')
            ->setDataType('json')
            ->setDataSchema(
                json_encode(
                    [
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'supp_core.administrativo_backend.ia.trilha.gestao_conhecimento_sentenca',
                        'title' => 'CONFIGURAÇÕES DA TRILHA DE GESTÃO DE CONHECIMENTO DE SENTENÇA',
                        'type' => 'object',
                        'required' => [
                            'ativo',
                            'tipos_documentos_suportados'
                        ],
                        'properties' => [
                            'ativo' => [
                                'type' => 'boolean',
                                'title' => 'Trilha Habilitada',
                                'description' => 'Indica se a execução da trilha esta habilitada',
                                'examples' => [
                                    true,
                                    false
                                ]
                            ],
                            'tipos_documentos_suportados' => [
                                'type' => 'array',
                                'title' => 'Sigla dos Tipos Documentos Suportados',
                                'description' => 'Lista dos tipos de documentos suportados pela trilha',
                                'items' => [
                                    'type' => 'string',
                                    'examples' => [
                                        'IMPUGSENT'
                                    ]
                                ]
                            ]
                        ],
                        'additionalProperties' => false
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            )
            ->setDataValue(
                json_encode(
                    [
                        'ativo' => true,
                        'tipos_documentos_suportados' => [
                            'IMPUGSENT'
                        ]
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );
        $manager->persist($configModulo);
        $this->addReference('ConfigModulo-' . $configModulo->getNome(), $configModulo);

        $configModulo = (new ConfigModulo())
            ->setModulo($moduloAdministrativo)
            ->setMandatory(true)
            ->setInvalid(false)
            ->setNome('supp_core.administrativo_backend.ia.trilha.sentenca_previdenciaria')
            ->setDescricao('CONFIGURAÇÕES DA TRILHA DE SENTENÇA PREVIDENCIARIA')
            ->setSigla('ADMINISTRATIVO_TRILHA_SENTENCA_PREVIDENCIARIA')
            ->setDataType('json')
            ->setDataSchema(
                json_encode(
                    [
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'supp_core.administrativo_backend.ia.trilha.sentenca_previdenciaria',
                        'title' => 'CONFIGURAÇÕES DA TRILHA DE SENTENÇA PREVIDENCIARIA',
                        'type' => 'object',
                        'required' => [
                            'ativo',
                            'tipos_documentos_suportados',
                            'nome_especie_setor',
                            'nome_modalidade_interessado',
                            'nome_interessado',
                            'contem_string_dispositivos_legais',
                            'nao_deve_constar_palavras_chave'
                        ],
                        'properties' => [
                            'ativo' => [
                                'type' => 'boolean',
                                'title' => 'Trilha Habilitada',
                                'description' => 'Indica se a execução da trilha esta habilitada',
                                'examples' => [
                                    true,
                                    false
                                ]
                            ],
                            'tipos_documentos_suportados' => [
                                'type' => 'array',
                                'title' => 'Sigla dos Tipos de Documentos Suportados',
                                'description' => 'Lista dos tipos de documentos suportados pela trilha',
                                'items' => [
                                    'type' => 'string',
                                    'examples' => [
                                        'SENTENCA'
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
                            ],
                        ],
                        'additionalProperties' => false
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            )
            ->setDataValue(
                json_encode(
                    [
                        'ativo' => true,
                        'tipos_documentos_suportados' => [
                            'IMPUGSENT'
                        ],
                        'nome_especie_setor' => 'PROTOCOLO',
                        'nome_modalidade_interessado' => 'REQUERIDO (PÓLO PASSIVO)',
                        'nome_interessado' => 'INSTITUTO NACIONAL DO SEGURO SOCIAL - INSS'
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );
        $manager->persist($configModulo);
        $this->addReference('ConfigModulo-' . $configModulo->getNome(), $configModulo);

        $configModulo = (new ConfigModulo())
            ->setModulo($moduloAdministrativo)
            ->setMandatory(true)
            ->setInvalid(false)
            ->setNome('supp_core.administrativo_backend.ia.trilha.peticao_inicial_previdenciaria')
            ->setDescricao('CONFIGURAÇÕES DA TRILHA DE PETIÇÃO INICIAL PREVIDENCIARIA')
            ->setSigla('ADMINISTRATIVO_TRILHA_PETICAO_INICIAL_PREVIDENCIARIA')
            ->setDataType('json')
            ->setDataSchema(
                json_encode(
                    [
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'supp_core.administrativo_backend.ia.trilha.peticao_inicial_previdenciaria',
                        'title' => 'CONFIGURAÇÕES DA TRILHA DE PETIÇÃO INICIAL PREVIDENCIARIA',
                        'type' => 'object',
                        'required' => [
                            'ativo',
                            'tipos_documentos_suportados',
                            'nome_modalidade_interessado',
                            'ramos_direito',
                        ],
                        'properties' => [
                            'ativo' => [
                                'type' => 'boolean',
                                'title' => 'Trilha Habilitada',
                                'description' => 'Indica se a execução da trilha esta habilitada',
                                'examples' => [
                                    true,
                                    false
                                ]
                            ],
                            'tipos_documentos_suportados' => [
                                'type' => 'array',
                                'title' => 'Sigla dos Tipos de Documentos Suportados',
                                'description' => 'Lista dos tipos de documentos suportados pela trilha',
                                'items' => [
                                    'type' => 'string',
                                    'examples' => [
                                        'PETINI'
                                    ]
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
                            'ramos_direito' => [
                                'type' => 'array',
                                'title' => 'Ramos do direito suportados',
                                'description' => 'Lista dos ramos do direito suportados pela trilha',
                                'items' => [
                                    'type' => 'string',
                                    'examples' => [
                                        'DIREITO PREVIDENCIÁRIO',
                                        'PREVIDENCIÁRIO',
                                    ]
                                ]
                            ],
                        ],
                        'additionalProperties' => false
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            )
            ->setDataValue(
                json_encode(
                    [
                        'ativo' => true,
                        'tipos_documentos_suportados' => [
                            'PETINI'
                        ],
                        'nome_modalidade_interessado' => 'REQUERIDO (PÓLO PASSIVO)',
                        'nome_interessado' => 'INSTITUTO NACIONAL DO SEGURO SOCIAL - INSS',
                        'ramos_direito' => [
                            'DIREITO PREVIDENCIÁRIO',
                            'PREVIDENCIÁRIO',
                        ]
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );
        $manager->persist($configModulo);
        $this->addReference('ConfigModulo-' . $configModulo->getNome(), $configModulo);

        $configModulo = (new ConfigModulo())
            ->setModulo($moduloAdministrativo)
            ->setMandatory(true)
            ->setInvalid(false)
            ->setNome('supp_core.administrativo_backend.ia.classifica_tipo_documento')
            ->setDescricao('CONFIGURAÇÕES DE CLASSIFICAÇÃO DE TIPO DE DOCUMENTOS POR IA')
            ->setSigla('ADMINISTRATIVO_CLASSIFICA_TIPO_DOCUMENTO')
            ->setDataType('json')
            ->setDataSchema(
                json_encode(
                    [
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'supp_core.administrativo_backend.ia.classifica_tipo_documento',
                        'description' => 'Configurações de Classificação de Tipo de Documentos por IA',
                        'type' => 'object',
                        'required' => [
                            'ativo',
                            'apenas_integracao',
                            'especies_processo'
                        ],
                        'properties' => [
                            'ativo' => [
                                'title' => 'Classificação Ativa',
                                'description' => 'Indica se a classificação de Tipo de Documento por IA está ativa',
                                'type' => 'boolean',
                                'examples' => [true, false]
                            ],
                            'apenas_integracao' => [
                                'title' => 'Apenas Documentos de Integração',
                                'description' => 'Indica se apenas serão classificados documentos vindos por integração',
                                'type' => 'boolean',
                                'examples' => [false, true]
                            ],
                            'especies_processo' => [
                                'title' => 'Nome de Espécies de Processo',
                                'description' => 'Nome de espécies de processos suportadas pela classificação',
                                'type' => 'array',
                                'items' => [
                                    'type' => 'string',
                                    'examples' => [
                                        'ELABORAÇÃO DE ATO NORMATIVO',
                                        'COMUM',
                                        'PROCESSO ADMINISTRATIVO DISCIPLINAR'
                                    ]
                                ]
                            ]
                        ],
                        'additionalProperties' => false
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            )
            ->setDataValue(
                json_encode(
                    [
                        'ativo' => true,
                        'apenas_integracao' => false,
                        'especies_processo' => [
                            'COMUM',
                            'ELABORAÇÃO DE ATO NORMATIVO',
                            'PROCESSO ADMINISTRATIVO DISCIPLINAR'
                        ],
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );
        $manager->persist($configModulo);
        $this->addReference('ConfigModulo-' . $configModulo->getNome(), $configModulo);

        $configModulo = (new ConfigModulo())
            ->setModulo($moduloAdministrativo)
            ->setMandatory(false)
            ->setInvalid(false)
            ->setNome('supp_core.administrativo_backend.ia.assistente')
            ->setDescricao('CONFIGURAÇÕES DO ASSISTENTE DE IA')
            ->setSigla('ADMINISTRATIVO_ASSISTENTE_IA')
            ->setDataType('json')
            ->setDataSchema(
                json_encode(
                    [
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                        '$id' => 'supp_core.administrativo_backend.ia.assistente',
                        'description' => 'Configurações do Assistente de IA',
                        'type' => 'object',
                        'required' => [
                            'ativo',
                            'rag',
                            'apenasUsuarios',
                        ],
                        'properties' => [
                            'ativo' => [
                                'title' => 'Assistente Ativo',
                                'description' => 'Indica se o assistente de IA esta ativo',
                                'type' => 'boolean',
                                'examples' => [true, false]
                            ],
                            'rag' => [
                                'title' => 'RAG Ativo',
                                'description' => 'Indica se a utilização de RAG nos prompts do usuário está ativa. Caso ativa, só será realizado o RAG para os prompts com o indicador rag como true.',
                                'type' => 'boolean',
                                'examples' => [true, false]
                            ],
                            'apenasUsuarios' => [
                                'title' => 'Lista de Usuários (username) que tem permissão para utilizar o Assistente de IA',
                                'description' => 'Se a lista for preenchida, apenas os usuários informados terão permissão para utilizar o Assistente de IA',
                                'type' => 'array',
                                'items' => [
                                    'type' => 'string',
                                    'examples' => [
                                        '00000000002',
                                        'eduardo.romao',
                                        'eduardo.romao@agu.gov.br'
                                    ]
                                ],
                            ],
                        ],
                        'additionalProperties' => false
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            )
            ->setDataValue(
                json_encode(
                    [
                        'ativo' => true,
                        'apenasUsuarios' => [],
                        'rag' => true,
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );
        $manager->persist($configModulo);
        $this->addReference('ConfigModulo-' . $configModulo->getNome(), $configModulo);

        $configModulo = $manager
            ->createQuery(
                "SELECT c 
        FROM SuppCore\AdministrativoBackend\Entity\ConfigModulo c 
        WHERE c.nome = 'supp_core.administrativo_backend.assinatura.config'"
            )
            ->getOneOrNullResult() ?: new ConfigModulo();
        $configModulo->setModulo($moduloAdministrativo);
        $configModulo->setNome('supp_core.administrativo_backend.assinatura.config');
        $configModulo->setDescricao('Configuração das Assinaturas Digitais');
        $configModulo->setInvalid(false);
        $configModulo->setMandatory(false);
        $configModulo->setDataType('json');
        $configModulo->setSigla('ASSINATURA');
        $configModulo->setDataSchema(
            json_encode(
                [
                    '$schema' => 'http://json-schema.org/draft-07/schema#',
                    '$id' => 'supp_core.administrativo_backend.assinatura.config',
                    'description' => 'Configuração das Assinaturas Digitais',
                    'type' => 'object',
                    'required' => [
                        'ambiente',
                    ],
                    '$defs' => [
                        'assinatura_CAdES' => [
                            'title' => 'Assinatura CAdES',
                            'description' => 'CMS Advanced Electronic Signature',
                            'type' => 'object',
                            'required' => ['active', 'test'],
                            'additionalProperties' => true,
                            'properties' => [
                                'active' => [
                                    'title' => 'Ativo ou Inativo',
                                    'description' => 'Determina se a assinatura CAdES estará habilitada ou desabilitada',
                                    'type' => 'boolean',
                                ],
                                'test' => [
                                    'title' => 'Teste',
                                    'description' => 'Indica se o sistema fará assinaturas em modo teste ou real',
                                    'type' => 'boolean',
                                ],
                            ],
                        ],
                        'assinatura_PAdES' => [
                            'title' => 'Assinatura PAdES',
                            'description' => 'PDF Advanced Electronic Signature',
                            'type' => 'object',
                            'required' => ['active', 'orientation', 'visible', 'convertToHtmlAfterRemove', 'test', 'imageBase64'],
                            'additionalProperties' => true,
                            'properties' => [
                                'active' => [
                                    'title' => 'Ativo ou Inativo',
                                    'description' => 'Determina se a assinatura PAdES estará habilitada ou desabilitada',
                                    'type' => 'boolean',
                                ],
                                'orientation' => [
                                    'title' => 'Orientação/Posição da chancela',
                                    'description' => 'Orientação/Posição da chancela na página',
                                    'type' => 'string',
                                    'anyOf' => [
                                        ['const' => 'HB-LR', 'title' => 'Horizontal Bottom - Left Right'],
                                        ['const' => 'VR-TD', 'title' => 'Vertical Right - Top Down'],
                                        ['const' => 'VL-BU', 'title' => 'Vertical Left - Bottom Up'],
                                    ],
                                ],
                                'visible' => [
                                    'title' => 'Visibilidade da chancela',
                                    'description' => 'Indica se chancela será visível ou não',
                                    'type' => 'boolean',
                                ],
                                'convertToHtmlAfterRemove' => [
                                    'title' => 'Converter em HTML após exclusão de assinatura',
                                    'description' => 'Indica se o sistema converterá o PDF em HTML após exclusão de assinatura PAdES',
                                    'type' => 'boolean',
                                ],
                                'test' => [
                                    'title' => 'Assinatura em modo teste',
                                    'description' => 'Indica se o sistema fará assinaturas em modo teste ou real',
                                    'type' => 'boolean',
                                ],
                                'imageBase64' => [
                                    'title' => 'Imagem JPEG da chancela em Base64',
                                    'description' => 'Pode ter o padrão Data URI (data:image/jpeg;base64,) ou somente o Base64 do binário do JPEG',
                                    'type' => 'string',
                                    'nullable' => true,
                                ],
                            ],
                        ],
                    ],

                    'properties' => [
                        'ambiente' => [
                            'title' => 'Ambiente',
                            'description' => 'Indica qual o ambiente: dev, prod',
                            'type' => 'object',
                            'required' => ['dev', 'prod'],
                            'additionalProperties' => true,
                            'properties' => [
                                'dev' => [
                                    'title' => 'Ambiente dev',
                                    'description' => 'Configuração para o Ambiente de Desenvolvimento',
                                    'type' => 'object',
                                    'required' => ["cnpj","cn","CAdES","PAdES"],
                                    'additionalProperties' => true,
                                    'properties' => [
                                        'cnpj' => [
                                            'title' => 'CNPJ Institucional',
                                            'description' => 'Conjunto de CNPJs que já constaram como titular de Certificado Digital AGU',
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'string'
                                            ],
                                            'default' => [
                                                '26994558008027',
                                                '26994558000123'
                                            ]
                                        ],
                                        'cn' => [
                                            'title' => 'Common Name (CN)',
                                            'description' => 'Conjunto de Common Names que já constaram em Certificado Digital AGU',
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'string'
                                            ],
                                            'default' => [
                                                '*.agu.gov.br',
                                                'SAPIENS',
                                                '*.AGU.GOV.BR'
                                            ]
                                        ],
                                        'CAdES' => ['$ref' => '#/$defs/assinatura_CAdES'],
                                        'PAdES' => ['$ref' => '#/$defs/assinatura_PAdES'],
                                    ],
                                ],
                                'prod' => [
                                    'title' => 'Ambiente prod',
                                    'description' => 'Configuração para o Ambiente de Produção',
                                    'type' => 'object',
                                    'required' => ["cnpj","cn","CAdES","PAdES"],
                                    'additionalProperties' => true,
                                    'properties' => [
                                        'cnpj' => [
                                            'title' => 'CNPJ Institucional',
                                            'description' => 'Conjunto de CNPJs que já constaram como titular de Certificado Digital AGU',
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'string'
                                            ],
                                            'default' => [
                                                '26994558008027',
                                                '26994558000123'
                                            ]
                                        ],
                                        'cn' => [
                                            'title' => 'Common Name (CN)',
                                            'description' => 'Conjunto de Common Names que já constaram em Certificado Digital AGU',
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'string'
                                            ],
                                            'default' => [
                                                '*.agu.gov.br',
                                                'SAPIENS',
                                                '*.AGU.GOV.BR'
                                            ]
                                        ],
                                        'CAdES' => ['$ref' => '#/$defs/assinatura_CAdES'],
                                        'PAdES' => ['$ref' => '#/$defs/assinatura_PAdES'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
        $configModulo->setDataValue(
            json_encode(
                [
                    'ambiente' => [
                        'dev' => [
                            'cnpj' => [
                                '26994558008027',
                                '26994558000123'
                            ],
                            'cn' => [
                                '*.agu.gov.br',
                                'SAPIENS',
                                '*.AGU.GOV.BR'
                            ],
                            'CAdES' => [
                                'active' => true,
                                'test' => true,
                            ],
                            'PAdES' => [
                                'active' => true,
                                'orientation' => 'VR-TD',
                                'visible' => true,
                                'test' => true,
                                'convertToHtmlAfterRemove' => true,
                                'imageBase64' => 'data:image/jpeg;base64,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',
                            ],
                        ],
                        'prod' => [
                            'cnpj' => [
                                '26994558008027',
                                '26994558000123'
                            ],
                            'cn' => [
                                '*.agu.gov.br',
                                'SAPIENS',
                                '*.AGU.GOV.BR'
                            ],
                            'CAdES' => [
                                'active' => true,
                                'test' => false,
                            ],
                            'PAdES' => [
                                'active' => true,
                                'orientation' => 'VR-TD',
                                'visible' => true,
                                'convertToHtmlAfterRemove' => true,
                                'test' => false,
                                'imageBase64' => 'data:image/jpeg;base64,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',
                            ],
                        ],
                    ],
                ]
            )
        );
        $manager->persist($configModulo);
        $this->addReference('ConfigModulo-' . $configModulo->getNome(), $configModulo);

        if (null === $manager
                ->createQuery(
                    "
                SELECT c 
                FROM SuppCore\AdministrativoBackend\Entity\ConfigModulo c 
                WHERE c.nome = 'supp_core.administrativo_backend.solicitacao_automatizada'"
                )
                ->getOneOrNullResult()) {
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
                            '$comment' => 'Espécie da atividade que será monitorada o acompanhamento do cumprimento.',
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
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );
            $configModulo->setDataValue(
                json_encode(
                    [
                        'especie_tarefa_analise' => 'ANÁLISE SOLICITAÇÃO AUTOMATIZADA PROTOCOLO EXTERNO',
                        'especie_tarefa_dados_cumprimento' => 'ANÁLISE SOLICITAÇÃO AUTOMATIZADA PROTOCOLO EXTERNO',
                        'especie_tarefa_erro_solicitacao' => 'ANÁLISE SOLICITAÇÃO AUTOMATIZADA PROTOCOLO EXTERNO',
                        'especie_tarefa_acompanhamento_cumprimento' => 'ANÁLISE SOLICITAÇÃO AUTOMATIZADA PROTOCOLO EXTERNO',
                        'especie_atividade_deferimento' => 'DEFERIMENTO. SOLICITAÇÃO PROTOCOLO EXTERNO',
                        'especie_atividade_indeferimento' => 'INDEFERIMENTO. SOLICITAÇÃO PROTOCOLO EXTERNO',
                        'especie_atividade_erro_solicitacao' => 'PROVIDÊNCIAS ADMINISTRATIVAS, ADOTADAS',
                        'especie_atividade_acompanhamento_cumprimento' => 'INDEFERIMENTO. SOLICITAÇÃO PROTOCOLO EXTERNO',
                        'tipo_documento' => 'OFICIO',
                        'prazo_timeout_verificacao_dossies' => 12,
                        'analise_positiva' => [
                            'etiquetas_processo' => [],
                        ],
                        'analise_negativa' => [
                            'etiquetas_processo' => [],
                        ],
                        'setor_erro_solicitacao' => 4,
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );
            $manager->persist($configModulo);
            $this->addReference(
                'ConfigModulo-'.$configModulo->getNome(),
                $configModulo
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT c 
                FROM SuppCore\AdministrativoBackend\Entity\ConfigModulo c 
                WHERE c.nome = 'supp_core.administrativo_backend.solicitacao_automatizada.advogados_dpu'"
                )
                ->getOneOrNullResult()) {
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
            $configModulo->setDataValue(
                json_encode(
                    [
                        "00000000004"
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );
            $manager->persist($configModulo);
            $this->addReference(
                'ConfigModulo-'.$configModulo->getNome(),
                $configModulo
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT c 
                FROM SuppCore\AdministrativoBackend\Entity\ConfigModulo c 
                WHERE c.nome = 'supp_core.administrativo_backend.solicitacao_automatizada.salario_maternidade_rural'"
                )
                ->getOneOrNullResult()) {

            $configModulo = new ConfigModulo();
            $configModulo->setModulo($this->getReference('Modulo-ADMINISTRATIVO', Modulo::class));
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
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );
            $configModulo->setDataValue(
                json_encode(
                    [
                        'setor_analise_procurador' => 4,
                        'setor_dados_cumprimento' => 4,
                        'setor_tarefa_acompanhamento_cumprimento' => 4,
                        'especie_documento_avulso_analise' => 'SOLICITACAO CUMPRIMENTO DO INSS',
                        'especie_documento_avulso_cumprimento' => 'CUMPRIMENTO DE SENTENÇA INSS',
                        'modelo_documento_avulso_analise' => 'ANALISE SOLICITAÇÃO AUTOMATICA',
                        'modelo_documento_avulso_cumprimento' => 'SOLICITACAO CUMPRIMENTO DO INSS',
                        'dossies_beneficiario' => [
                            'DOSPREV',
                            'DOSLABRA',
                            'DOSOC',
                        ],
                        'dossies_conjuge' => [
                            'DOSPREV',
                            'DOSLABRA',
                        ],
                        'analise_negativa_pelo_procurador' => true,
                        'analise_positiva_pelo_procurador' => true,
                        'dados_cumprimento_sumario' => false,
                        'id_pessoa_cumprimento_destino' => 4,
                        'analise_inicial' => [
                            'analises_beneficiario' => [
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialAusenciaRequerimentoAdministrativo',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialPrescricaoBeneficiario',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialLitispendenciaBeneficiario',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialBeneficioIncompativelBeneficiario',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialPagamentoAnteriorBeneficiario',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialEmpregoBeneficiario',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialEmpregoPublicoBeneficiario',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialEmpresa',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialBensTSE',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialVeiculo',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialImovelSP',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialImovelRural',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialEmbarcacao',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialAeronave',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'analises_conjuge' => [
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialEmpregoConjuge',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialEmpregoPublicoConjuge',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialEmpresa',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialBensTSE',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialVeiculo',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialImovelSP',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialImovelRural',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialEmbarcacao',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseInicialAeronave',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => false,
                                            'etiquetas' => [
                                                82,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'analise_prova_material' => [
                            'analises_beneficiario' => [
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseProvaMaterialDossieLabraBeneficiario',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => true,
                                            'etiquetas' => [
                                                19,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseProvaMaterialDossieSocialBeneficiario',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => true,
                                            'etiquetas' => [
                                                19,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseProvaMaterialPrevidenciarioEnderecoRuralBeneficiario',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => true,
                                            'etiquetas' => [
                                                19,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseProvaMaterialPrevidenciarioSeguradoEspecialBeneficiario',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => true,
                                            'etiquetas' => [
                                                19,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'analises_conjuge' => [
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseProvaMaterialDossieLabraConjuge',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => true,
                                            'etiquetas' => [
                                                19,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseProvaMaterialPrevidenciarioEnderecoRuralConjuge',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => true,
                                            'etiquetas' => [
                                                19,
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'analise' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Analises\\AnaliseProvaMaterialPrevidenciarioSeguradoEspecialConjuge',
                                    'etiquetas_tarefa_analise_procurador' => [
                                        [
                                            'passou_analise' => true,
                                            'etiquetas' => [
                                                19,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'extracoes_conjuge' => [
                            [
                                'extrator' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Extratores\\ExtratorDadosConjugeDossieLabra',
                            ],
                            [
                                'extrator' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Extratores\\ExtratorDadosConjugeDossieSocial',
                            ],
                        ],
                        'extracoes_dados_cumprimento' => [
                            [
                                'extrator' => 'SuppCore\\AdministrativoBackend\\Helpers\\SolicitacaoAutomatizada\\Drivers\\SalarioMaternidadeRural\\Extratores\\ExtratorDadosCumprimento',
                            ],
                        ],
                        'termos_documento' => [
                            'requerimento' => <<<EOT
                                <div class="subtitulo">
                                    II - TERMOS DE USO DA PLATAFORMA PACIFICA
                                </div>
                                
                                <div>
                                    <p><b>1. Análise de Proposta de Acordo:</b> A Advocacia-Geral da União (AGU), através da Procuradoria-Geral Federal (PGF), analisará a possibilidade de propositura de acordo extrajudicial de seu benefício, considerando os documentos apresentados no processo administrativo e o cruzamento de dados nos sistemas públicos de Governo.</p>
                                    <p><b>2. Autorização de Acesso a Dados:</b> Você autoriza o acesso da AGU/PGF aos seus dados nos sistemas públicos de Governo.</p>
                                    <p><b>3. Condições de Análise:</b> Somente serão analisados pela PACIFICA os benefícios requeridos e negados pelo Instituto Nacional do Seguro Social (INSS), desde que não sejam objeto de ação judicial.</p>
                                    <p><b>4. Declaração de Inexistência de Ação Judicial:</b> Ao aderir à PACIFICA, você declara que não possui ação judicial com o mesmo pedido.</p>
                                    <p><b>5. Prazo para Análise:</b> O seu pedido será analisado em até 30 dias úteis a partir da data em que você o submeteu nesta plataforma.</p>
                                    <p><b>6. Comunicação do Resultado:</b> Você será informado do resultado do seu pedido pela plataforma PACIFICA ao final do prazo estabelecido. Esta comunicação vale como notificação da conclusão do procedimento.</p>
                                    <ul>A resposta do seu pedido será:
                                        <li>a concordância com seu direito ao benefício (acordo celebrado); ou</li>
                                        <li>a manutenção da decisão do INSS que negou seu benefício (acordo não celebrado).</li>
                                    </ul>
                                </div>
                                <br/>
                                
                                <div class="subtitulo">
                                    III - TERMOS DE PRIVACIDADE
                                </div>
                                
                                <div>
                                    <p><b>7. Uso de Dados Pessoais:</b> Os dados pessoais que você fornecer serão utilizados para avaliar seu pedido. Será realizado o cruzamento desses dados com outros presentes nas bases de informações acessadas pela AGU.</p>
                                    <p><b>8. Medidas de Proteção de Privacidade:</b> Serão aplicadas medidas de restrição de acesso e uso de sistemas seguros para proteger sua privacidade. Os dados pessoais serão armazenados pelo período necessário para atingir a finalidade descrita e, posteriormente, para a manutenção do histórico de solicitações, em conformidade com a Lei 13.709, de 2018 (Lei Geral de Proteção de Dados Pessoais - LGPD).</p>
                                    <p><b>9. Compartilhamento de Dados:</b> haverá compartilhamento dos dados com o Instituto Nacional do Seguro Social (INSS), e poderá haver compartilhamento com outros órgãos da administração pública federal.</p>
                                    <p><b>10. Dúvidas:</b> Se tiver dúvidas ou solicitações sobre o tratamento dos seus dados pessoais, fale conosco na <a href="https://falabr.cgu.gov.br/web/home">Plataforma Fala.BR</a>.</p>
                                </div>
                                <br />
                                
                                <div class="subtitulo">
                                    IV - TERMOS DE ACORDO
                                </div>
                                
                                <div>
                                    <p><b>11. Consentimento:</b> caso a manifestação seja pela concessão do benefício (acordo celebrado), você concorda com as cláusulas a seguir.</p>
                                    <p>Cláusula 1ª - Benefício. O INSS reconhece o direito da requerente ao benefício de salário maternidade para segurada especial desde o parto.</p>
                                    <p>Cláusula 2ª - Pagamento. O INSS pagará diretamente todos os valores que seriam devidos se tivesse concedido o benefício em até 45 (quarenta e cinco dias) após a implantação do benefício.</p>
                                    <p>Parágrafo único. Por esse termo, a requerente concorda com todos os valores que serão pagos e que nada mais lhe é devido (quitação total e plena do principal e acessórios).</p>
                                    <p>Cláusula 3ª - Comunicação. As informações para implantação e pagamento do benefício serão enviadas pela Procuradoria-Geral Federal diretamente ao INSS.   </p>
                                    <p>Parágrafo único. Assim que o INSS conceder o benefício encaminhará uma comunicação à requerente. </p>
                                    <p>Cláusula 4ª - Renúncia. A requerente renuncia a eventuais direitos decorrentes dos fatos objeto desse acordo.</p>
                                    <p>Cláusula 5ª - Causas de Invalidade. Caso exista ação judicial sobre o objeto desse acordo (litispendência ou coisa julgada), seja constatada alguma fraude ou declaração falsa, ou falte algum requisito essencial para concessão do benefício, o requerente concorda que o acordo fica sem efeito. </p>
                                    <p>Cláusula 6ª - Pagamento Indevido. Caso se verifique que algum valor objeto desse acordo foi pago de forma indevida ou que houve recebimento de benefício incompatível no período, a requerente concorda em devolver esses valores diretamente ao INSS ou que este realize compensação dos valores. </p>
                                    <p>Cláusula 7ª - Declaração. A requerente declara que não tem nenhum pedido judicial pendente requerendo o salário-maternidade pelo nascimento do seu filho (aquele indicado em seu pedido).</p>
                                </div>
                            EOT,
                            'cumprimento' => <<<EOT
                                <div class="subtitulo">
                                    ACORDO EXTRAJUDICIAL POR ADESÃO
                                </div>
                                
                                <div>
                                    <p>PARTES</p>
                                    <table border="1" cellpadding="1" cellspacing="1" style="height:67px">
                                        <tbody>
                                        <tr>
                                            <td>REQUERIDO</td>
                                            <td><p>INSTITUTO NACIONAL DO SEGURO SOCIAL - INSS, pessoa jurídica de direito público, representado(a) pelo membro da Advocacia-Geral da União infra-assinado</p></td>
                                        </tr>
                                        <tr>
                                            <td>REQUERENTE</td>
                                            <td>acima identificada</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br />
                                    <p>OBJETO</p>
                                    <table border="1" cellpadding="1" cellspacing="1" style="height:30px">
                                        <tbody>
                                        <tr>
                                            <td><span style="display:none">&nbsp;</span>Concessão de Salário-Maternidade Rural desde o parto ou da data do pedido (Data de Entrada do Requerimento Administrativo).<span style="display:none">&nbsp;</span></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <p>TERMO DE ACORDO EXTRAJUDICIAL POR ADESÃO</p>
                                    <table border="1" cellpadding="1" cellspacing="1">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <p>A requerente concordou, quando da adesão a PACIFICA, com as seguintes cláusulas: </p>
                                                <p>Cláusula 1ª - <b>Benefício.</b> O INSS reconhece o direito da requerente ao benefício de salário maternidade para segurada especial desde o parto. </p>
                                                <p>Cláusula 2ª - <b>Pagamento.</b> O INSS pagará diretamente todos os valores que seriam devidos se tivesse concedido o benefício em até 45 (quarenta e cinco dias) após a implantação do benefício.</p>
                                                <p>Parágrafo único. Por esse termo, a requerente concorda com todos os valores que serão pagos e que nada mais lhe é devido (quitação total e plena do principal e acessórios).</p>
                                                <p>Cláusula 3ª - <b>Comunicação.</b> As informações para implantação e pagamento do benefício serão enviadas pela Procuradoria-Geral Federal diretamente ao INSS.    </p>
                                                <p>Parágrafo único. Assim que o INSS conceder o benefício encaminhará uma comunicação à requerente.  </p>
                                                <p>Cláusula 4ª - <b>Renúncia.</b> A requerente renuncia a eventuais direitos decorrentes dos fatos objeto desse acordo. </p>
                                                <p>Cláusula 5ª - <b>Causas de Invalidade.</b> Caso exista ação judicial sobre o objeto desse acordo (litispendência ou coisa julgada), seja constatada alguma fraude ou declaração falsa, ou falte algum requisito essencial para concessão do benefício, a requerente concorda que o acordo fica sem efeito.  </p>
                                                <p>Cláusula 6ª - <b>Pagamento Indevido.</b> Caso se verifique que algum valor objeto desse acordo foi pago de forma indevida ou que houve recebimento de benefício incompatível no período, a requerente concorda em devolver esses valores diretamente ao INSS ou que este realize compensação dos valores. </p>
                                                <p>Cláusula 7ª - <b>Declaração.</b> A requerente declara que não tem nenhum pedido judicial pendente requerendo o salário-maternidade pelo nascimento do seu filho (aquele indicado em seu pedido).</p>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            EOT,
                        ],
                        'dias_prazo_verificacao_cumprimento' => 1,
                        'dias_final_prazo_tarefa_acompanhamento_cumprimento' => 1,
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );
            $manager->persist($configModulo);
            $this->addReference(
                'ConfigModulo-'.$configModulo->getNome(),
                $configModulo
            );
        }


        if (null === $manager
                ->createQuery(
                    "
                SELECT c 
                FROM SuppCore\AdministrativoBackend\Entity\ConfigModulo c 
                WHERE c.nome = 'supp_core.administrativo_backend.ia.triagem'"
                )
                ->getOneOrNullResult()) {

            $configModulo = new ConfigModulo();
            $configModulo->setModulo($moduloAdministrativo);
            $configModulo->setNome('supp_core.administrativo_backend.ia.triagem');
            $configModulo->setDescricao('CONFIGURAÇÕES RELATIVAS À TRIAGEM DE DOCUMENTOS');
            $configModulo->setSigla('ADMINISTRATIVO_TRIAGEM');
            $configModulo->setDataType('json');
            $configModulo->setMandatory(false);
            $configModulo->setDataSchema(
                json_encode([
                    '$schema' => 'http://json-schema.org/draft-07/schema#',
                    '$id' => 'supp_core.administrativo_backend.ia.triagem',
                    '$comment' => 'CONFIGURAÇÕES RELATIVAS À TRIAGEM DE DOCUMENTOS.',
                    'type' => 'object',
                    'additionalProperties' => false,
                    'required' => [
                        'ativo',
                        'apenas_classificados',
                        'apenas_integracao',
                        'apenas_tipo_documento_igual',
                        'executa_triagem_juntada',
                        'especies_processo',
                    ],
                    'properties' => [
                        'ativo' => [
                            'type' => 'boolean',
                            'title' => 'Triagem de documentos ativa?',
                            'description' => 'Indica se a triagem de documentos esta ativa.',
                            'examples' => [
                                true,
                                false
                            ],
                        ],
                        'apenas_classificados' => [
                            'type' => 'boolean',
                            'title' => 'Apenas documentos classificados?',
                            'description' => 'Indica se apenas documentos classificados serão triados.',
                            'examples' => [
                                true,
                                false
                            ],
                        ],
                        'apenas_integracao' => [
                            'type' => 'boolean',
                            'title' => 'Apenas documentos vindo de integração?',
                            'description' => 'Indica se apenas serão triados documentos vindos por integração.',
                            'examples' => [
                                true,
                                false
                            ],
                        ],
                        'apenas_tipo_documento_igual' => [
                            'type' => 'boolean',
                            'title' => 'Apenas documentos com o mesmo tipo de documento igual a classificação?',
                            'description' => 'Indica se apenas serão triados documentos onde o tipo documento predito for igual ao tipo documento.',
                            'examples' => [
                                true,
                                false
                            ],
                        ],
                        'apenas_documentos_classificados' => [
                            'type' => 'boolean',
                            'title' => 'Apenas documentos classificados serão triados?',
                            'description' => 'Indica se apenas documentos com tipo documento predito serão triados.',
                            'examples' => [
                                true,
                                false
                            ],
                        ],
                        'executa_triagem_juntada' => [
                            'type' => 'boolean',
                            'title' => 'Dispara mensagem para triagem na juntada do documento?',
                            'description' => 'Indica se o documento será encaminhado para triagem na juntada.',
                            'examples' => [
                                true,
                                false
                            ],
                        ],
                        'especies_processo' => [
                            'type' => 'array',
                            'title' => 'Nome de Espécies de Processo',
                            'description' => 'Nome das espécies de processos que serão triados. Caso a lista esteja vazia, serão considerados todos.',
                            'items' => [
                                'type' => 'string',
                                'examples' => [
                                    'ELABORAÇÃO DE ATO NORMATIVO',
                                    'COMUM',
                                    'PROCESSO ADMINISTRATIVO DISCIPLINAR'
                                ]
                            ],
                        ],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            )
            ->setDataValue(
                json_encode([
                    'ativo' => true,
                    'apenas_classificados' => false,
                    'apenas_integracao' => false,
                    'apenas_tipo_documento_igual' => false,
                    'executa_triagem_juntada' => true,
                    'especies_processo' => [
                        'COMUM',
                        'ELABORAÇÃO DE ATO NORMATIVO',
                        'PROCESSO ADMINISTRATIVO DISCIPLINAR'
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );
            $manager->persist($configModulo);
            $this->addReference(
                'ConfigModulo-'.$configModulo->getNome(),
                $configModulo
            );
        }

        $configModulo = $manager
            ->createQuery(
                "
                SELECT c 
                FROM SuppCore\AdministrativoBackend\Entity\ConfigModulo c 
                WHERE c.nome = 'supp_core.administrativo_backend.ldap.config'"
            )
            ->getOneOrNullResult() ?: new ConfigModulo();
        $configModulo->setModulo($moduloAdministrativo);
        $configModulo->setNome('supp_core.administrativo_backend.ldap.config');
        $configModulo->setDescricao('CONFIGURAÇÃO DE LDAP');
        $configModulo->setInvalid(false);
        $configModulo->setMandatory(false);
        $configModulo->setDataType('json');
        $configModulo->setDataSchema(
            json_encode(
                [
                    '$schema' => 'http://json-schema.org/draft-07/schema#',
                    '$id' => 'supp_core.administrativo_backend.ldap.conf',
                    '$comment' => 'Configuração do LDAP, se existente na infraestrutura',
                    'title' => 'Configuração do LDAP',
                    'type' => 'object',
                    'required' => [
                        'arrLdapConf',
                        'ldapServiceUser',
                    ],
                    'properties' => [
                        'arrLdapConf' => [
                            '$comment' => 'Array de configurações do LDAP',
                            'type' => 'array',
                            'items' => [
                                'type' => 'object',
                                'properties' => [
                                    'name' => [
                                        '$comment' => 'Nome do serviço',
                                        'type' => 'string',
                                        'examples' => 'LDAP'
                                    ],
                                    'domain'=> [
                                        '$comment'=> 'Domínio do serviço',
                                        'type'=> 'string',
                                        'examples'=> '@agu.gov.br'
                                    ],
                                    'type_auth'=> [
                                        '$comment'=> 'Tipo de autenticação (AD, LDAP, etc.)',
                                        'type'=> 'string',
                                        'examples'=> 'AD'
                                    ],
                                    'host'=> [
                                        '$comment'=> 'Host do servidor LDAP',
                                        'type'=> 'string',
                                        'examples'=> 'ldap'
                                    ],
                                    'port'=> [
                                        '$comment'=> 'Porta do servidor LDAP',
                                        'type'=> 'integer',
                                        'examples'=> 10389
                                    ],
                                    'protocol_version'=> [
                                        '$comment'=> 'Versão do protocolo LDAP',
                                        'type'=> 'integer',
                                        'examples'=> 3
                                    ],
                                    'referrals'=> [
                                        '$comment'=> 'Indica se os referrals são seguidos',
                                        'type'=> 'boolean',
                                        'examples'=> false
                                    ],
                                    'base_dn'=> [
                                        '$comment'=> 'Base DN para busca de usuários',
                                        'type'=> 'string',
                                        'examples'=> 'dc=agu,dc=gov,dc=br'
                                    ],
                                    'ui_key'=> [
                                        '$comment'=> 'Atributo usado para identificação de usuários na interface',
                                        'type'=> 'string',
                                        'examples'=> 'sAMAccountName'
                                    ],
                                    'search_dn'=> [
                                        '$comment'=> 'DN para busca de usuários (se necessário)',
                                        'type'=> 'string',
                                        'examples'=> null
                                    ],
                                    'search_password'=> [
                                        '$comment'=> 'Senha para busca de usuários (se necessário)',
                                        'type'=> 'string',
                                        'examples'=> null
                                    ],
                                    'filter'=> [
                                        '$comment'=> 'Filtro de busca de usuários',
                                        'type'=> 'string',
                                        'examples'=> '({uid_key}={username})'
                                    ],
                                    'extra_fields'=> [
                                        '$comment'=> 'Campos extras para informações de usuários',
                                        'type'=> 'object',
                                        'examples'=> '[]'
                                    ],
                                    'password_attribute'=> [
                                        '$comment'=> 'Atributo da senha do usuário',
                                        'type'=> 'string',
                                        'examples'=> 'userpassword'
                                    ],
                                    'name_attribute'=> [
                                        '$comment'=> 'Atributo do nome do usuário',
                                        'type'=> 'string',
                                        'examples'=> 'name'
                                    ],
                                    'email_attribute'=> [
                                        '$comment'=> 'Atributo do email do usuário',
                                        'type'=> 'string',
                                        'examples'=> 'mail'
                                    ],
                                    'cpf_attribute'=> [
                                        '$comment'=> 'Atributo do CPF do usuário',
                                        'type'=> 'string',
                                        'examples'=> 'postOfficeBox'
                                    ],
                                    'encryption'=> [
                                        '$comment'=> 'Tipo de criptografia (none, ssl, tls)',
                                        'type'=> 'string',
                                        'examples'=> 'none'
                                    ]
                                ],
                            ],
                        ],
                        'ldapServiceUser' => [
                            '$comment' => 'Se há ou não usuário de serviço da aplicação',
                            'type' => 'boolean',
                            'examples' => [
                                true,
                                false
                            ],
                        ],
                        'ldapUser' => [
                            '$comment' => 'Usuário de serviço LDAP (Necessário conter domínio)',
                            'type' => 'string',
                            'examples' => ['usuario_servico@agu.gov.br'],
                        ],
                        'ldapPass' => [
                            '$comment' => 'Senha do usuário de acesso ao datalake',
                            'type' => 'string',
                            'examples' => ['mypassword'],
                        ],
                    ],
                ],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
        $configModulo->setDataValue(
            json_encode(
                [
                    'arrLdapConf' => [
                        [
                            'name' => 'LDAP',
                            'domain'=> '@agu.gov.br',
                            'type_auth'=> 'AD',
                            'host'=> 'ldap',
                            'port'=> 10389,
                            'protocol_version'=> 3,
                            'referrals'=> false,
                            'base_dn'=> 'dc=agu,dc=gov,dc=br',
                            'ui_key'=> 'sAMAccountName',
                            'search_dn'=> '',
                            'search_password'=> '',
                            'filter'=> '({uid_key}={username})',
                            'extra_fields'=> [],
                            'password_attribute'=> 'userpassword',
                            'name_attribute'=> 'name',
                            'email_attribute'=> 'mail',
                            'cpf_attribute'=> 'postOfficeBox',
                            'encryption'=> 'none'
                        ]
                    ],
                    'ldapServiceUser' => false,
                    'ldapUser' => 'usuario_servico@supp.gov.br',
                    'ldapPass' => 'mypassword',
                ],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
        $manager->persist($configModulo);
        $this->addReference('ConfigModulo-'.$configModulo->getNome(), $configModulo);


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
        return 10012;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return [
            'dev', 'test',
        ];
    }
}
