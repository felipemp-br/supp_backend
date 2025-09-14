<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use SuppCore\AdministrativoBackend\Entity\ConfigModulo;
use SuppCore\AdministrativoBackend\Entity\Modulo;
use SuppCore\AdministrativoBackend\Migrations\Helpers\MigrationHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241022174816 extends AbstractMigration
{
    private ContainerInterface $container;
    private MigrationHelper|null $migrationHelper;

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
     * @return string
     */
    public function getDescription(): string
    {
        return 'Migration para configuração de módulo referente a triagem de documentos pela IA.';
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

    private function configModuloUp(): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $moduloAdministrativo = $em
            ->getRepository(Modulo::class)
            ->findOneBy(['sigla' => 'AD']);

        $configModulo = new ConfigModulo();
        $configModulo->setModulo($moduloAdministrativo);
        $configModulo->setNome('supp_core.administrativo_backend.ia.triagem');
        $configModulo->setDescricao('CONFIGURAÇÕES RELATIVAS À TRIAGEM DE DOCUMENTOS');
        $configModulo->setSigla('ADMINISTRATIVO_TRIAGEM');
        $configModulo->setDataType('json');
        $configModulo->setMandatory(false);
        $configModulo->setInvalid(true);
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
        );

        $this->addSql($this->migrationHelper->generateInsertSQL($configModulo));
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
            $this->migrationHelper->generateDeleteSQL(
                ConfigModulo::class,
                [
                    'nome' => 'supp_core.administrativo_backend.ia.triagem',
                ]
            )
        );
    }

    /**
     * @return bool
     */
    public function isTransactional(): bool
    {
        return false;
    }
}
