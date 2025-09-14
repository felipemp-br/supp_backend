<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;
use Override;
use SuppCore\AdministrativoBackend\Entity\ModalidadeAcaoEtiqueta;
use SuppCore\AdministrativoBackend\Enums\IdentificadorModalidadeAcaoEtiqueta;
use SuppCore\AdministrativoBackend\Migrations\Helpers\MigrationHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241126125734 extends AbstractMigration
{
    private ContainerInterface $container;
    private MigrationHelper $migrationHelper;
    private EntityManagerInterface $entityManager;

    /**
     * @param ContainerInterface $container
     *
     * @return void
     */
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
        $this->migrationHelper = $container->get(MigrationHelper::class);
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
    }

    /**
     * @return bool
     */
    #[Override]
    public function isTransactional(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    #[Override]
    public function getDescription(): string
    {
        return 'Alterações relacionadas a ações de etiquetas.';
    }

    /**
     * @param Schema $schema
     *
     * @return void
     *
     * @throws Exception
     * @throws MappingException
     * @throws SchemaException
     */
    public function up(Schema $schema): void
    {
        $this->modalidadeAcaoEtiquetaUp($schema);
        $this->acaoUp($schema);
//        dd(implode('; ', array_map(fn($q) => $q->getStatement(), $this->getSql())).';');
    }

    /**
     * @param Schema $schema
     *
     * @return void
     *
     * @throws Exception
     * @throws MappingException
     * @throws SchemaException
     */
    protected function modalidadeAcaoEtiquetaUp(Schema $schema): void
    {
        $schemaModalidadeAcaoEtiqueta = clone $schema;
        $preUpSchema = $this->modalidadeAcaoEtiquetaPreUp($schemaModalidadeAcaoEtiqueta);
        $table = $schemaModalidadeAcaoEtiqueta->getTable('ad_mod_acao_etiqueta');
        $table->dropColumn('trigger_name');
        $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaModalidadeAcaoEtiqueta);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($this->platform->getAlterSchemaSQL($schemaDiff) as $sql) {
            $this->addSql($sql);
        }

        $this->modalidadeAcaoEtiquetaPostUp($preUpSchema);
    }

    /**
     * @param Schema $schema
     *
     * @return Schema
     *
     * @throws Exception
     * @throws SchemaException
     * @throws MappingException
     */
    protected function modalidadeAcaoEtiquetaPreUp(Schema $schema): Schema
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_mod_acao_etiqueta');
        $table->addColumn(
            'identificador',
            Type::getType('string')->getName(),
            [
                'notnull' => false,
                'length' => 255,
            ]
        );

        $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaTo);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($this->platform->getAlterSchemaSQL($schemaDiff) as $sql) {
            $this->addSql($sql);
        }

        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ModalidadeAcaoEtiqueta::class,
                [
                    'identificador' => IdentificadorModalidadeAcaoEtiqueta::TAREFA_CRIAR_MINUTA->value,
                ],
                [
                    'trigger_name' => 'SuppCore\\AdministrativoBackend\\Api\\V1\\Triggers\\VinculacaoEtiqueta\\Trigger0001',
                ]
            )
        );
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ModalidadeAcaoEtiqueta::class,
                [
                    'identificador' => IdentificadorModalidadeAcaoEtiqueta::TAREFA_DISTRIBUIR_TAREFA->value,
                ],
                [
                    'trigger_name' => 'SuppCore\\AdministrativoBackend\\Api\\V1\\Triggers\\VinculacaoEtiqueta\\Trigger0003',
                ]
            )
        );
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ModalidadeAcaoEtiqueta::class,
                [
                    'identificador' => IdentificadorModalidadeAcaoEtiqueta::TAREFA_COMPARTILHAR_TAREFA->value,
                ],
                [
                    'trigger_name' => 'SuppCore\\AdministrativoBackend\\Api\\V1\\Triggers\\VinculacaoEtiqueta\\Trigger0004',
                ]
            )
        );
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ModalidadeAcaoEtiqueta::class,
                [
                    'identificador' => IdentificadorModalidadeAcaoEtiqueta::TAREFA_CRIAR_OFICIO->value,
                ],
                [
                    'trigger_name' => 'SuppCore\\AdministrativoBackend\\Api\\V1\\Triggers\\VinculacaoEtiqueta\\Trigger0005',
                ]
            )
        );
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ModalidadeAcaoEtiqueta::class,
                [
                    'identificador' => IdentificadorModalidadeAcaoEtiqueta::TAREFA_CRIAR_DOSSIE->value,
                ],
                [
                    'trigger_name' => 'SuppCore\\AdministrativoBackend\\Api\\V1\\Triggers\\VinculacaoEtiqueta\\Trigger0008',
                ]
            )
        );
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ModalidadeAcaoEtiqueta::class,
                [
                    'identificador' => IdentificadorModalidadeAcaoEtiqueta::TAREFA_CRIAR_TAREFA->value,
                ],
                [
                    'trigger_name' => 'SuppCore\\AdministrativoBackend\\Api\\V1\\Triggers\\VinculacaoEtiqueta\\Trigger0009',
                ]
            )
        );
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ModalidadeAcaoEtiqueta::class,
                [
                    'identificador' => IdentificadorModalidadeAcaoEtiqueta::TAREFA_LANCAR_ATIVIDADE->value,
                ],
                [
                    'trigger_name' => 'SuppCore\\AdministrativoBackend\\Api\\V1\\Triggers\\VinculacaoEtiqueta\\Trigger0010',
                ]
            )
        );

        return $schemaTo;
    }

    /**
     * @param Schema $schema
     *
     * @return void
     *
     * @throws SchemaException
     */
    protected function modalidadeAcaoEtiquetaPostUp(Schema $schema): void
    {
        $schemaTo = clone $schema;

        $table = $schemaTo->getTable('ad_mod_acao_etiqueta');
        $table->modifyColumn(
            'identificador',
            [
                'notnull' => true,
                'length' => 255,
            ]
        );
        $table->addUniqueIndex(
            [
                'identificador',
            ]
        );

        $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaTo);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($this->platform->getAlterSchemaSQL($schemaDiff) as $sql) {
            $this->addSql($sql);
        }
    }

    /**
     * @param Schema $schema
     *
     * @return void
     *
     * @throws Exception
     * @throws SchemaException
     */
    protected function acaoUp(Schema $schema): void
    {
        $schemaTo = clone $schema;
        $table = $schemaTo->getTable('ad_acao');
        $table->addColumn(
            'descricao_acao',
            Type::getType('string')->getName(),
            [
                'notnull' => false,
                'length' => 255,
            ]
        );
        $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaTo);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($this->platform->getAlterSchemaSQL($schemaDiff) as $sql) {
            $this->addSql($sql);
        }
    }

    /**
     * @param Schema $schema
     *
     * @return void
     *
     * @throws Exception
     * @throws MappingException
     * @throws SchemaException
     */
    public function down(Schema $schema): void
    {
        $this->modalidadeAcaoEtiquetaDown($schema);
        $this->acaoDown($schema);
//        dd(implode('; ', array_map(fn($q) => $q->getStatement(), $this->getSql())).';');
    }

    /**
     * @param Schema $schema
     *
     * @return void
     *
     * @throws Exception
     * @throws MappingException
     * @throws SchemaException
     */
    protected function modalidadeAcaoEtiquetaDown(Schema $schema): void
    {
        $schemaModalidadeAcaoEtiqueta = clone $schema;
        $preUpSchema = $this->modalidadeAcaoEtiquetaPreDown($schema);
        $table = $schemaModalidadeAcaoEtiqueta->getTable('ad_mod_acao_etiqueta');
        $table->dropColumn('identificador');
        $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaModalidadeAcaoEtiqueta);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($this->platform->getAlterSchemaSQL($schemaDiff) as $sql) {
            $this->addSql($sql);
        }

        $this->modalidadeAcaoEtiquetaPostDown($preUpSchema);
    }

    /**
     * @param Schema $schema
     *
     * @return Schema
     *
     * @throws Exception
     * @throws SchemaException
     * @throws MappingException
     */
    protected function modalidadeAcaoEtiquetaPreDown(Schema $schema): Schema
    {
        $schemaTo = $this->sm->createSchema();
        $table = $schemaTo->getTable('ad_mod_acao_etiqueta');
        $table->addColumn(
            'trigger_name',
            Type::getType('string')->getName(),
            [
                'notnull' => false,
                'length' => 255,
            ]
        );

        $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaTo);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($this->platform->getAlterSchemaSQL($schemaDiff) as $sql) {
            $this->addSql($sql);
        }

        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ModalidadeAcaoEtiqueta::class,
                [
                    'trigger_name' => 'SuppCore\\AdministrativoBackend\\Api\\V1\\Triggers\\VinculacaoEtiqueta\\Trigger0001',
                ],
                [
                    'identificador' => IdentificadorModalidadeAcaoEtiqueta::TAREFA_CRIAR_MINUTA->value,
                ],
                $schemaTo,
            )
        );
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ModalidadeAcaoEtiqueta::class,
                [
                    'trigger_name' => 'SuppCore\\AdministrativoBackend\\Api\\V1\\Triggers\\VinculacaoEtiqueta\\Trigger0003',
                ],
                [
                    'identificador' => IdentificadorModalidadeAcaoEtiqueta::TAREFA_DISTRIBUIR_TAREFA->value,
                ],
                $schemaTo,
            )
        );
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ModalidadeAcaoEtiqueta::class,
                [
                    'trigger_name' => 'SuppCore\\AdministrativoBackend\\Api\\V1\\Triggers\\VinculacaoEtiqueta\\Trigger0004',
                ],
                [
                    'identificador' => IdentificadorModalidadeAcaoEtiqueta::TAREFA_COMPARTILHAR_TAREFA->value,
                ],
                $schemaTo,
            )
        );
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ModalidadeAcaoEtiqueta::class,
                [
                    'trigger_name' => 'SuppCore\\AdministrativoBackend\\Api\\V1\\Triggers\\VinculacaoEtiqueta\\Trigger0005',
                ],
                [
                    'identificador' => IdentificadorModalidadeAcaoEtiqueta::TAREFA_CRIAR_OFICIO->value,
                ],
                $schemaTo,
            )
        );
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ModalidadeAcaoEtiqueta::class,
                [
                    'trigger_name' => 'SuppCore\\AdministrativoBackend\\Api\\V1\\Triggers\\VinculacaoEtiqueta\\Trigger0008',
                ],
                [
                    'identificador' => IdentificadorModalidadeAcaoEtiqueta::TAREFA_CRIAR_DOSSIE->value,
                ],
                $schemaTo,
            )
        );
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ModalidadeAcaoEtiqueta::class,
                [
                    'trigger_name' => 'SuppCore\\AdministrativoBackend\\Api\\V1\\Triggers\\VinculacaoEtiqueta\\Trigger0009',
                ],
                [
                    'identificador' => IdentificadorModalidadeAcaoEtiqueta::TAREFA_CRIAR_TAREFA->value,
                ],
                $schemaTo,
            )
        );
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                ModalidadeAcaoEtiqueta::class,
                [
                    'trigger_name' => 'SuppCore\\AdministrativoBackend\\Api\\V1\\Triggers\\VinculacaoEtiqueta\\Trigger0010',
                ],
                [
                    'identificador' => IdentificadorModalidadeAcaoEtiqueta::TAREFA_LANCAR_ATIVIDADE->value,
                ],
                $schemaTo,
            )
        );

        return $schemaTo;
    }

    /**
     * @param Schema $schema
     *
     * @return void
     *
     * @throws SchemaException
     */
    protected function modalidadeAcaoEtiquetaPostDown(Schema $schema): void
    {
        $schemaTo = clone $schema;

        $table = $schemaTo->getTable('ad_mod_acao_etiqueta');
        $table->modifyColumn(
            'trigger_name',
            [
                'notnull' => true,
                'length' => 255,
            ]
        );

        foreach ($table->getUniqueConstraints() as $constraint) {
            if (empty(array_diff(['identificador'], $constraint->getColumns()))
                && empty(array_diff($constraint->getColumns(), ['identificador']))) {
                $table->removeUniqueConstraint($constraint->getName());
            }
        }

        $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaTo);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($this->platform->getAlterSchemaSQL($schemaDiff) as $sql) {
            $this->addSql($sql);
        }
    }

    /**
     * @param Schema $schema
     *
     * @return void
     *
     * @throws SchemaException
     */
    protected function acaoDown(Schema $schema): void
    {
        $schemaTo = clone $schema;
        $table = $schemaTo->getTable('ad_acao');
        $table->dropColumn('descricao_acao');

        $schemaDiff = (new Comparator())->compareSchemas($schema, $schemaTo);

        // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
        foreach ($this->platform->getAlterSchemaSQL($schemaDiff) as $sql) {
            $this->addSql($sql);
        }
    }
}