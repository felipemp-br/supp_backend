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
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108123102 extends AbstractMigration
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
        return 'Atualiza as configurações de solicitação automatizada para fluxo de erro.';
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

    /**
     * @return void
     *
     * @throws Exception
     * @throws MappingException
     */
    private function configModuloDown(): void
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
                        ],
                    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                    'invalid' => true,
                ],
                [
                    'nome' => 'supp_core.administrativo_backend.solicitacao_automatizada'
                ]
            )
        );
    }
}
