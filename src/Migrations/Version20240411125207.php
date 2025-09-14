<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Exception\MigrationException;
use Doctrine\Migrations\Query\Query;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\NonUniqueResultException;
use SuppCore\AdministrativoBackend\Entity\ConfigModulo;
use SuppCore\AdministrativoBackend\Entity\DadosFormulario;
use SuppCore\AdministrativoBackend\Entity\DocumentoIAMetadata;
use SuppCore\AdministrativoBackend\Entity\Formulario;
use SuppCore\AdministrativoBackend\Entity\ModalidadeEtiqueta;
use SuppCore\AdministrativoBackend\Entity\Modulo;
use SuppCore\AdministrativoBackend\Entity\MomentoDisparoRegraEtiqueta;
use SuppCore\AdministrativoBackend\Entity\RegraEtiqueta;
use SuppCore\AdministrativoBackend\Enums\SiglaMomentoDisparoRegraEtiqueta;
use SuppCore\AdministrativoBackend\Enums\StatusExecucaoTrilhaTriagem;
use SuppCore\AdministrativoBackend\Migrations\Helpers\MigrationHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\Service\Attribute\Required;
use Throwable;

/**
 * /src/Migrations/Version20240411125207.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
final class Version20240411125207 extends AbstractMigration
{
    private ContainerInterface $container;
    private ModalidadeEtiqueta $modalidadeEtiquetaTarefa;
    private ModalidadeEtiqueta $modalidadeEtiquetaProcesso;
    private ?MigrationHelper $migrationHelper;

    /**
     * @param ContainerInterface|null $container
     *
     * @return void
     */
    #[Required]
    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
        $this->migrationHelper = $this->container->get(MigrationHelper::class);
    }

    /**
     * Migration description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return 'Migration inicial para assuntos relacionados a regras de etiqueta.';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function preUp(Schema $schema): void
    {
        $this->warnIf(
            true,
            <<<EOF
                AVISO: Não é possivel fazer o rollback integral da tabela "ad_dados_formulario" caso seja necessário. 
                O campo componente_id não tem como voltar a ser "NOT NULL" e os novos dados serão perdidos.
            EOF
        );
    }

    /**
     * Schema upgrade.
     *
     * @param Schema $schema schema
     *
     * @return void
     *
     * @throws Throwable
     * @throws Exception
     * @throws SchemaException
     */
    public function up(Schema $schema): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $qb = $em->createQueryBuilder();
        $qb
            ->select('me')
            ->from(ModalidadeEtiqueta::class, 'me')
            ->where($qb->expr()->in('me.valor', ':valor'))
            ->setParameter('valor', ['TAREFA', 'PROCESSO']);
        /** @var ModalidadeEtiqueta $modalidadeEtiqueta */
        foreach ($qb->getQuery()->execute() as $modalidadeEtiqueta) {
            match ($modalidadeEtiqueta->getValor()) {
                'TAREFA' => $this->modalidadeEtiquetaTarefa = $modalidadeEtiqueta,
                'PROCESSO' => $this->modalidadeEtiquetaProcesso = $modalidadeEtiqueta
            };
        }

        $this->momentoDisparoRegraEtiquetaUp($schema);
        $this->regraEtiquetaUp($schema);
        $this->documentoIaMetadataUp($schema);
        $this->dadosFormularioUp($schema);
        $this->formularioUp($schema);
        $this->configModuloUp($schema);
        $this->regraEtiquetaPostUp($schema);
//            dd(implode('; ', array_map(fn($q) => $q->getStatement(), $this->getSql())).';');
    }

    /**
     * @param Schema $schemaFrom
     *
     * @return void
     *
     * @throws Exception
     * @throws SchemaException
     */
    private function momentoDisparoRegraEtiquetaUp(Schema $schemaFrom): void
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        /** @var ClassMetadataInfo $metadata */
        $metadata = $em->getClassMetadata(MomentoDisparoRegraEtiqueta::class);
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->createTable('ad_momento_disparo_reg_etiq');

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
            'nome',
            Type::getType('string')->getName(),
            [
                'notnull' => true,
                'length' => 255,
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
            'sigla',
            Type::getType('string')->getName(),
            [
                'notnull' => true,
                'length' => 255,
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
            'mod_etiqueta_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
            ]
        );

        $table->addColumn(
            'disponivel_usuario',
            Type::getType('boolean')->getName(),
            [
                'notnull' => true,
            ]
        );

        $table->addColumn(
            'disponivel_setor',
            Type::getType('boolean')->getName(),
            [
                'notnull' => true,
            ]
        );

        $table->addColumn(
            'disponivel_unidade',
            Type::getType('boolean')->getName(),
            [
                'notnull' => true,
            ]
        );

        $table->addColumn(
            'disponivel_orgao_central',
            Type::getType('boolean')->getName(),
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
                'ad_mod_etiqueta',
                ['mod_etiqueta_id'],
                ['id']
            )
            ->addUniqueIndex(
                [
                    'sigla',
                ]
            )
            ->addIndex(
                [
                    'mod_etiqueta_id',
                ]
            );

        $schemaDiff = (new Comparator())->compareSchemas($schemaFrom, $schemaTo);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schemaDiff->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }

        $momentoDisparoRegraEtiqueta = (new MomentoDisparoRegraEtiqueta())
            ->setNome('CRIAÇÃO DE PROCESSO ADMINISTRATIVO')
            ->setDescricao('MOMENTO EM QUE UM PROCESSO ADMINISTRATIVO É CRIADO.')
            ->setSigla(SiglaMomentoDisparoRegraEtiqueta::PROCESSO_CRIACAO_PROCESSO_ADMINISTRATIVO->value)
            ->setModalidadeEtiqueta($this->modalidadeEtiquetaProcesso)
            ->setDisponivelUsuario(false)
            ->setDisponivelSetor(true)
            ->setDisponivelUnidade(true)
            ->setDisponivelOrgaoCentral(true);

        $this->addSql($this->migrationHelper->generateInsertSQL($momentoDisparoRegraEtiqueta));

        $momentoDisparoRegraEtiqueta = (new MomentoDisparoRegraEtiqueta())
            ->setNome('(RE)DISTRIBUIÇÃO DO PROCESSO')
            ->setDescricao('MOMENTO EM QUE É FEITA A DISTRIBUIÇÃO OU REDISTRIBUIÇÃO DO PROCESSO.')
            ->setSigla(SiglaMomentoDisparoRegraEtiqueta::PROCESSO_DISTRIBUICAO->value)
            ->setModalidadeEtiqueta($this->modalidadeEtiquetaProcesso)
            ->setDisponivelUsuario(false)
            ->setDisponivelSetor(true)
            ->setDisponivelUnidade(true)
            ->setDisponivelOrgaoCentral(true);

        $this->addSql($this->migrationHelper->generateInsertSQL($momentoDisparoRegraEtiqueta));

        $momentoDisparoRegraEtiqueta = (new MomentoDisparoRegraEtiqueta())
            ->setNome('APÓS CRIAÇÃO DA PRIMEIRA TAREFA DO PROCESSO')
            ->setDescricao('MOMENTO EM QUE A PRIMEIRA TAREFA DO PROCESSO É CRIADA.')
            ->setSigla(SiglaMomentoDisparoRegraEtiqueta::PROCESSO_PRIMEIRA_TAREFA->value)
            ->setModalidadeEtiqueta($this->modalidadeEtiquetaProcesso)
            ->setDisponivelUsuario(false)
            ->setDisponivelSetor(true)
            ->setDisponivelUnidade(true)
            ->setDisponivelOrgaoCentral(true);

        $this->addSql($this->migrationHelper->generateInsertSQL($momentoDisparoRegraEtiqueta));

        $momentoDisparoRegraEtiqueta = (new MomentoDisparoRegraEtiqueta())
            ->setNome('(RE)DISTRIBUIÇÃO DA TAREFA')
            ->setDescricao('MOMENTO EM QUE É FEITA A DISTRIBUIÇÃO OU REDISTRIBUIÇÃO DA TAREFA.')
            ->setSigla(SiglaMomentoDisparoRegraEtiqueta::TAREFA_DISTRIBUICAO->value)
            ->setModalidadeEtiqueta($this->modalidadeEtiquetaTarefa)
            ->setDisponivelUsuario(true)
            ->setDisponivelSetor(true)
            ->setDisponivelUnidade(true)
            ->setDisponivelOrgaoCentral(true);

        $this->addSql($this->migrationHelper->generateInsertSQL($momentoDisparoRegraEtiqueta));
    }

    /**
     * @param Schema $schemaFrom
     *
     * @return void
     *
     * @throws Exception
     * @throws SchemaException
     */
    private function regraEtiquetaUp(Schema $schemaFrom): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_regra_etiqueta');

        $table->addColumn(
            'momento_disparo_reg_etiq_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table
            ->addForeignKeyConstraint(
                'ad_momento_disparo_reg_etiq',
                ['momento_disparo_reg_etiq_id'],
                ['id']
            );

        $schemaDiff = (new Comparator())->compareSchemas($schemaFrom, $schemaTo);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schemaDiff->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    /**
     * @param Schema $schemaFrom
     *
     * @return void
     *
     * @throws Exception
     * @throws SchemaException
     */
    private function documentoIaMetadataUp(Schema $schemaFrom): void
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        /** @var ClassMetadataInfo $metadata */
        $metadata = $em->getClassMetadata(DocumentoIAMetadata::class);
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->createTable('ad_doc_ia_metadata');

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
            'documento_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => true,
            ]
        );

        $table->addColumn(
            'status_exec_trilha_triagem',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
                'default' => StatusExecucaoTrilhaTriagem::PENDENTE->value,
            ]
        );

        $table->addColumn(
            'data_exec_trilha_triagem',
            Type::getType('datetime')->getName(),
            [
                'notnull' => false,
            ]
        );

        $table->addColumn(
            'tipo_documento_pred_id',
            Type::getType('integer')->getName(),
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
            ->addUniqueIndex(
                [
                    'documento_id',
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
                'ad_documento',
                ['documento_id'],
                ['id']
            )
            ->addForeignKeyConstraint(
                'ad_tipo_documento',
                ['tipo_documento_pred_id'],
                ['id']
            )
            ->addIndex(
                [
                    'tipo_documento_pred_id',
                ]
            );

        $schemaDiff = (new Comparator())->compareSchemas($schemaFrom, $schemaTo);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schemaDiff->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    /**
     * @param Schema $schemaFrom
     *
     * @return void
     *
     * @throws Exception
     * @throws SchemaException
     */
    private function dadosFormularioUp(Schema $schemaFrom): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_dados_formulario');
        $table->addColumn(
            'documento_id',
            Type::getType('integer')->getName(),
            [
                'notnull' => false,
            ]
        );
        $table->addColumn(
            'invalido',
            Type::getType('boolean')->getName(),
            [
                'notnull' => false,
            ]
        );
        $table
            ->addForeignKeyConstraint(
                'ad_documento',
                ['documento_id'],
                ['id']
            )
            ->addIndex(
                [
                    'documento_id',
                ]
            );

        $column = $table->getColumn('componente_digital_id');
        $column->setNotnull(false);

        $schemaDiff = (new Comparator())->compareSchemas($schemaFrom, $schemaTo);

        foreach ($schemaDiff->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    /**
     * @param Schema $schemaFrom
     *
     * @return void
     *
     * @throws Exception
     * @throws SchemaException
     */
    private function formularioUp(Schema $schemaFrom): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_formulario');
        $table->addColumn(
            'ia',
            Type::getType('boolean')->getName(),
            [
                'notnull' => false,
            ]
        );
        $table->addColumn(
            'aceita_json_invalido',
            Type::getType('boolean')->getName(),
            [
                'notnull' => false,
            ]
        );

        $schemaDiff = (new Comparator())->compareSchemas($schemaFrom, $schemaTo);

        foreach ($schemaDiff->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }

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

        $this->addSql($this->migrationHelper->generateInsertSQL($formulario));

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

        $this->addSql($this->migrationHelper->generateInsertSQL($formulario));

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

        $this->addSql($this->migrationHelper->generateInsertSQL($formulario));

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

        $this->addSql($this->migrationHelper->generateInsertSQL($formulario));
    }

    /**
     * @param Schema $schemaFrom
     *
     * @return void
     */
    private function regraEtiquetaPostUp(Schema $schemaFrom): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $qb = $em->createQueryBuilder();
        $qb
            ->select('re.id, me.valor')
            ->from(RegraEtiqueta::class, 're')
            ->join('re.etiqueta', 'e')
            ->join('e.modalidadeEtiqueta', 'me')
            ->where($qb->expr()->in('me.id', ':modalidades'))
            ->setParameter(
                'modalidades',
                [
                    $this->modalidadeEtiquetaTarefa->getId(),
                    $this->modalidadeEtiquetaProcesso->getId(),
                ]
            );

        $selectMomentoProcesso = $this->migrationHelper->generateSelectSQL(
            MomentoDisparoRegraEtiqueta::class,
            ['id'],
            [
                'sigla' => SiglaMomentoDisparoRegraEtiqueta::PROCESSO_DISTRIBUICAO->value
            ],
        );

        $selectMomentoTarefa = $this->migrationHelper->generateSelectSQL(
            MomentoDisparoRegraEtiqueta::class,
            ['id'],
            [
                'sigla' => SiglaMomentoDisparoRegraEtiqueta::TAREFA_DISTRIBUICAO->value
            ],
        );

        $regrasEtiquetas = $qb->getQuery()->getResult();
        foreach ($regrasEtiquetas as $data) {
            $this->addSql(
                $this->migrationHelper->generateUpdateSQL(
                    RegraEtiqueta::class,
                    [
                        'momentoDisparoRegraEtiqueta' => fn () => match ($data['valor']) {
                            'PROCESSO' => $selectMomentoProcesso,
                            default => $selectMomentoTarefa
                        }
                    ],
                    ['id' => $data['id']],
                )
            );
        }
    }

    /**
     * @param Schema $schema
     * @return void
     * @throws Exception
     * @throws MappingException
     * @throws NonUniqueResultException
     */
    private function configModuloUp(Schema $schema): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $qb = $em->createQueryBuilder();
        $qb
            ->select('m')
            ->from(Modulo::class, 'm')
            ->where($qb->expr()->eq('m.nome', ':nome'))
            ->setParameter('nome', 'ADMINISTRATIVO');

        $moduloAdm = $qb->getQuery()->getOneOrNullResult();

        $configModulo = (new ConfigModulo())
            ->setModulo($moduloAdm)
            ->setMandatory(true)
            ->setInvalid(true)
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
            );

        $this->addSql(
            $this->migrationHelper->generateInsertSQL($configModulo)
        );

        $configModulo = (new ConfigModulo())
            ->setModulo($moduloAdm)
            ->setMandatory(true)
            ->setInvalid(true)
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
                                'title' => 'Sigla dos Tipos de Documentos Suportados',
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
            );

        $this->addSql(
            $this->migrationHelper->generateInsertSQL($configModulo)
        );

        $configModulo = (new ConfigModulo())
            ->setModulo($moduloAdm)
            ->setMandatory(true)
            ->setInvalid(true)
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
            );

        $this->addSql(
            $this->migrationHelper->generateInsertSQL($configModulo)
        );

        $configModulo = (new ConfigModulo())
            ->setModulo($moduloAdm)
            ->setMandatory(true)
            ->setInvalid(true)
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
                        'additionalProperties' => false
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );

        $this->addSql(
            $this->migrationHelper->generateInsertSQL($configModulo)
        );

        $configModulo = (new ConfigModulo())
            ->setModulo($moduloAdm)
            ->setMandatory(true)
            ->setInvalid(true)
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
            );

        $this->addSql(
            $this->migrationHelper->generateInsertSQL($configModulo)
        );

        $configModulo = (new ConfigModulo())
            ->setModulo($moduloAdm)
            ->setMandatory(false)
            ->setInvalid(true)
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
            );

        $this->addSql(
            $this->migrationHelper->generateInsertSQL($configModulo)
        );
    }

    /**
     * @param Schema $schema
     *
     * @return void
     *
     * @throws Exception
     * @throws Throwable
     */
    public function down(Schema $schema): void
    {
        $this->momentoDisparoRegraEtiquetaDown($schema);
        $this->regraEtiquetaDown($schema);
        $this->documentoIaMetadataDown($schema);
        $this->dadosFormularioDown($schema);
        $this->formularioDown($schema);
        $this->configModuloDown($schema);
//        dd(implode('; ', array_map(fn(Query $q) => $q->getStatement(), $this->getSql())).';');
    }

    /**
     * @param Schema $schemaFrom
     *
     * @return void
     *
     * @throws Exception
     * @throws SchemaException
     */
    private function regraEtiquetaDown(Schema $schemaFrom): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_regra_etiqueta');
        $table->dropColumn('momento_disparo_reg_etiq_id');

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schemaFrom->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    /**
     * @param Schema $schemaFrom
     *
     * @return void
     *
     * @throws Exception
     * @throws SchemaException
     */
    private function momentoDisparoRegraEtiquetaDown(Schema $schemaFrom): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_momento_disparo_reg_etiq');
        foreach ($table->getIndexes() as $index) {
            $table->dropIndex($index->getName());
        }
        foreach ($table->getForeignKeys() as $fk) {
            $table->removeForeignKey($fk->getName());
        }
        foreach ($table->getUniqueConstraints() as $uc) {
            $table->removeUniqueConstraint($uc->getName());
        }
        $schemaTo->dropTable($table->getName());

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schemaFrom->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    /**
     * @param Schema $schemaFrom
     *
     * @return void
     *
     * @throws Exception
     * @throws SchemaException
     */
    private function documentoIaMetadataDown(Schema $schemaFrom): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_doc_ia_metadata');
        foreach ($table->getIndexes() as $index) {
            $table->dropIndex($index->getName());
        }
        foreach ($table->getForeignKeys() as $fk) {
            $table->removeForeignKey($fk->getName());
        }
        foreach ($table->getUniqueConstraints() as $uc) {
            $table->removeUniqueConstraint($uc->getName());
        }
        $schemaTo->dropTable($table->getName());

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schemaFrom->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    /**
     * @param Schema $schemaFrom
     *
     * @return void
     *
     * @throws Exception
     * @throws SchemaException
     */
    private function dadosFormularioDown(Schema $schemaFrom): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_dados_formulario');
        foreach ($table->getIndexes() as $index) {
            if ($index->getColumns() === ['documento_id']) {
                $table->dropIndex($index->getName());
            }
        }
        foreach ($table->getForeignKeys() as $fk) {
            if ('ad_documento' === $fk->getForeignTableName()) {
                $table->removeForeignKey($fk->getName());
            }
        }

        $table->dropColumn('documento_id');
        $table->dropColumn('invalido');

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schemaFrom->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }
        $formularios = [
            'ia_gestao_conhecimento_petini_1',
            'ia_gestao_conhecimento_sentenca_1',
            'ia_sentenca_previdenciaria_1',
        ];

        foreach ($formularios as $siglaFormulario) {
            $select = $this->migrationHelper->generateSelectSQL(
                Formulario::class,
                ['id'],
                ['sigla' => $siglaFormulario]
            );
            $this->addSql(
                $this->migrationHelper->generateDeleteSQL(
                    DadosFormulario::class,
                    [
                        'formulario' => fn () => $select,
                    ]
                )
            );
        }
    }

    /**
     * @param Schema $schemaFrom
     *
     * @return void
     *
     * @throws Exception
     * @throws SchemaException
     */
    private function formularioDown(Schema $schemaFrom): void
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_formulario');

        $table->dropColumn('ia');
        $table->dropColumn('aceita_json_invalido');

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($schemaFrom->getMigrateToSql($schemaTo, $this->platform) as $sql) {
            $this->addSql($sql);
        }

        $formularios = [
            'ia_gestao_conhecimento_petini_1',
            'ia_gestao_conhecimento_sentenca_1',
            'ia_petini_previdenciaria_1',
            'ia_sentenca_previdenciaria_1',
        ];

        foreach ($formularios as $siglaFormulario) {
            $this->addSql(
                $this->migrationHelper->generateDeleteSQL(
                    Formulario::class,
                    ['sigla' => $siglaFormulario]
                )
            );
        }
    }

    /**
     * @param Schema $schema
     * @return void
     * @throws Exception
     */
    private function configModuloDown(Schema $schema): void
    {
        $siglas = [
            'ADMINISTRATIVO_TRILHA_GESTAO_CONHECIMENTO_PETICAO_INICIAL',
            'ADMINISTRATIVO_TRILHA_GESTAO_CONHECIMENTO_SENTENCA',
            'ADMINISTRATIVO_TRILHA_SENTENCA_PREVIDENCIARIA',
            'ADMINISTRATIVO_TRILHA_PETICAO_INICIAL_PREVIDENCIARIA',
            'ADMINISTRATIVO_CLASSIFICA_TIPO_DOCUMENTO',
            'ADMINISTRATIVO_ASSISTENTE_IA',
        ];

        foreach ($siglas as $sigla) {
            $this->addSql(
                $this->migrationHelper->generateDeleteSQL(
                    ConfigModulo::class,
                    ['sigla' => $sigla]
                )
            );
        }
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
