<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations\Helpers;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\OraclePlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\MappingException;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use Symfony\Component\VarDumper\VarDumper;

/**
 * MigrationHelper.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class MigrationHelper
{
    /** @var array<string, ClassMetadataInfo> $cacheMetadata */
    private array $cacheMetadata = [];
    /** @var string[] $alocatedAliases */
    private array $alocatedAliases = [];

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param Connection             $connection
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Connection $connection
    ) {
    }

    /**
     * @param string $entityClass
     *
     * @return ClassMetadataInfo
     */
    protected function getClassMetadata(string $entityClass): ClassMetadataInfo
    {
        if (!isset($this->cacheMetadata[$entityClass])) {
            $this->cacheMetadata[$entityClass] = $this->entityManager->getClassMetadata($entityClass);
        }

        return $this->cacheMetadata[$entityClass];
    }


    /**
     * @param EntityInterface $entity
     * @param array           $extraData
     * @param Schema|null     $schema
     *
     * @return string
     *
     * @throws Exception
     * @throws MappingException
     */
    public function generateInsertSQL(
        EntityInterface $entity,
        array $extraData = [],
        ?Schema $schema = null
    ): string {
        $metadata = $this->getClassMetadata($entity::class);
        $changedColumns = [
            ...$metadata->getFieldNames(),
            ...array_filter(
                $metadata->getAssociationNames(),
                function ($associationName) use ($metadata) {
                    $associationMapping = $metadata->getAssociationMapping($associationName);

                    return isset($associationMapping['joinColumnFieldNames']);
                }
            ),
        ];

        $data = array_merge(
            array_map(
                fn ($entityColumn) => $this->getParsedValueDataFromTableColumnData(
                    $entity::class,
                    $entityColumn,
                    $metadata->getFieldValue($entity, $entityColumn),
                    $schema
                ),
                $changedColumns
            ),
            $extraData
        );

        $qb = $this->connection->createQueryBuilder()
            ->insert($metadata->getTableName());

        foreach ($data as $item) {
            $qb->setValue($item['column'], $item['value']);
        }

        return $qb->getSQL();
    }

    /**
     * Retorna as especificações da coluna da tabela.
     *
     * @param string      $entityClass
     * @param string      $entityColumn
     * @param Schema|null $schema
     *
     * @return array
     *
     * @throws Exception
     * @throws MappingException
     * @throws SchemaException
     */
    public function getTableColumnData(
        string $entityClass,
        string $entityColumn,
        ?Schema $schema = null
    ): array {
        $schema ??= $this->entityManager
            ->getConnection()
            ->createSchemaManager()
            ->introspectSchema();
        $metadata = $this->getClassMetadata($entityClass);
        if ($metadata->hasAssociation($entityColumn)) {
            $associationMapping = $metadata->getAssociationMapping($entityColumn);
            if (isset($associationMapping['joinColumnFieldNames'])) {
                $relatedMetadata = $this->getClassMetadata($associationMapping['targetEntity']);
                $relatedIdField = $relatedMetadata->getSingleIdentifierFieldName();

                return [
                    'column' => reset($associationMapping['joinColumnFieldNames']),
                    'type' => $relatedMetadata->getTypeOfField($relatedIdField),
                ];
            }

            throw new \Exception(
                sprintf(
                    'Campo %s não suportado.',
                    $entityColumn
                )
            );
        }
        if (!$metadata->hasField($entityColumn)) {
            $table = $schema->getTable($metadata->getTableName());
            $tableColumnName = $this->entityManager
                ->getConfiguration()
                ->getNamingStrategy()
                ->propertyToColumnName($metadata->getColumnName($entityColumn));
            if ($table->hasColumn($tableColumnName)) {
                $column = $table->getColumn($tableColumnName);
                return [
                    'column' => $tableColumnName,
                    'type' => $column->getType()->getName(),
                ];
            }
        }
        return [
            'column' => $metadata->getColumnName($entityColumn),
            'type' => $metadata->getTypeOfField($entityColumn),
        ];
    }

    /**
     * Retorna os dados da coluna da tabela, convertendo o valor para o tipo esperado no banco de dados.
     *
     * @param string      $entityClass
     * @param string      $entityColumn
     * @param mixed       $value
     * @param Schema|null $schema
     *
     * @return array
     *
     * @throws Exception
     * @throws MappingException
     */
    public function getParsedValueDataFromTableColumnData(
        string $entityClass,
        string $entityColumn,
        mixed $value,
        ?Schema $schema = null
    ): array {
        $databasePlatform = $this->connection->getDatabasePlatform();
        $tableColumnData = $this->getTableColumnData(
            $entityClass,
            $entityColumn,
            $schema
        );
        $metadata = $this->getClassMetadata($entityClass);
        $parsedValue = match (true) {
            $value instanceof EntityInterface => $value->getId(),
            is_callable($value) => sprintf(
                '(%s)',
                $value()
            ),
            default => $this->connection->convertToDatabaseValue(
                $value,
                $tableColumnData['type']
            )
        };

        $isExpression = false;
        if ($parsedValue === null
            && $metadata->isIdentifier($entityColumn)
            && $metadata->generatorType === ClassMetadataInfo::GENERATOR_TYPE_SEQUENCE) {
            if ($databasePlatform instanceof OraclePlatform) {
                $isExpression = true;
                $parsedValue = sprintf(
                    '%s.nextval',
                    substr($metadata->getSequenceName($databasePlatform), 0, 30)
                );
            } elseif ($databasePlatform instanceof PostgreSQLPlatform) {
                $isExpression = true;
                $parsedValue = sprintf(
                    'nextval(\'%s\')',
                    $metadata->getSequenceName($databasePlatform)
                );
            } else {
                throw new \Error(
                    sprintf(
                        'Migration helper doesn\'t support %s',
                        $databasePlatform::class
                    )
                );
            }
        } elseif (is_callable($value)) {
            $isExpression = true;
        } elseif ($parsedValue && $tableColumnData['type'] === 'text' && $databasePlatform instanceof OraclePlatform) {
            $isExpression = true;
            $parsedValue = join(
                ' || ',
                array_map(
                    function ($chunk) {
                        return sprintf('TO_CLOB(\'%s\')', $chunk);
                    },
                    mb_str_split($parsedValue, 3000)
                )
            );
        } else {
            if (is_string($parsedValue) && $tableColumnData['type'] !== 'boolean') {
                $parsedValue = sprintf('\'%s\'', $parsedValue);
            }
            if ($parsedValue === null) {
                $parsedValue = 'NULL';
            } elseif ($tableColumnData['type'] === 'boolean') {
                $parsedValue = $this->connection->getDatabasePlatform()->convertBooleans($parsedValue);
            }
        }

        return [
            ...$tableColumnData,
            'is_expression' => $isExpression,
            'value' => $parsedValue,
        ];
    }

    /**
     * @param string      $entityClass
     * @param array       $updatedFields
     * @param array       $criteria
     * @param Schema|null $schema
     *
     * @return string
     *
     * @throws Exception
     * @throws MappingException
     */
    public function generateUpdateSQL(
        string $entityClass,
        array $updatedFields,
        array $criteria,
        ?Schema $schema = null
    ): string {
        $metadata = $this->getClassMetadata($entityClass);
        $updateData = array_map(
            fn ($entityColumn, $value) => $this->getParsedValueDataFromTableColumnData(
                $entityClass,
                $entityColumn,
                $value,
                $schema
            ),
            array_keys($updatedFields),
            array_values($updatedFields)
        );

        $qb = $this->connection->createQueryBuilder()
            ->update($metadata->getTableName());

        foreach ($updateData as $item) {
            $qb->set(
                $item['column'],
                $item['value']
            );
        }
        $criteriaData = array_reduce(
            array_map(
                fn ($column, $value) => $this->getParsedValueDataFromTableColumnData(
                    $entityClass,
                    $column,
                    $value
                ),
                array_keys($criteria),
                array_values($criteria)
            ),
            function ($data, $item) {
                $data[$item['column']] = $item['value'];

                return $data;
            },
            []
        );

        foreach ($criteriaData as $column => $value) {
            $qb->andWhere(
                $qb->expr()->eq(
                    $column,
                    $value
                )
            );
        }

        return $qb->getSQL();
    }

    /**
     * @param string      $entityClass
     * @param array       $selectEntityColumns
     * @param array       $criteria
     * @param string|null $alias
     * @param Schema|null $schema
     *
     * @return string
     *
     * @throws Exception
     * @throws MappingException
     */
    public function generateSelectSQL(
        string $entityClass,
        array $selectEntityColumns,
        array $criteria,
        ?string $alias = null,
        ?Schema $schema = null,
    ): string {
        $alias ??= $this->generateAlias();
        $metadata = $this->getClassMetadata($entityClass);
        $qb = $this->connection->createQueryBuilder()
            ->from($metadata->getTableName(), $alias);
        $selectData = array_map(
            fn ($entityColumn) => $this->getTableColumnData(
                $entityClass,
                $entityColumn,
                $schema
            ),
            $selectEntityColumns
        );
        foreach ($selectData as $item) {
            $qb->addSelect(
                sprintf(
                    '%s.%s',
                    $alias,
                    $item['column']
                )
            );
        }
        foreach ($criteria as $column => $value) {
            $criteriaData = $this->getParsedValueDataFromTableColumnData($entityClass, $column, $value);
            $qb->andWhere(
                $qb->expr()->eq(
                    sprintf(
                        '%s.%s',
                        $alias,
                        $criteriaData['column']
                    ),
                    $criteriaData['value']
                )
            );
        }

        return $qb->getSQL();
    }

    /**
     * @param string      $entityClass
     * @param array       $criteria
     * @param Schema|null $criteria
     *
     * @return string
     *
     * @throws Exception
     * @throws MappingException
     */
    public function generateDeleteSQL(
        string $entityClass,
        array $criteria,
        ?Schema $schema = null
    ): string {
        $metadata = $this->getClassMetadata($entityClass);

        $qb = $this->connection->createQueryBuilder()
            ->delete($metadata->getTableName());

        $criteriaData = array_reduce(
            array_map(
                fn ($column, $value) => $this->getParsedValueDataFromTableColumnData(
                    $entityClass,
                    $column,
                    $value,
                    $schema
                ),
                array_keys($criteria),
                array_values($criteria)
            ),
            function ($data, $item) {
                $data[$item['column']] = $item['value'];

                return $data;
            },
            []
        );

        foreach ($criteriaData as $column => $value) {
            $qb->andWhere(
                $qb->expr()->eq(
                    $column,
                    $value
                )
            );
        }

        return $qb->getSQL();
    }

    /**
     * Retorna um alias aleatório.
     *
     * @return string
     */
    public function generateAlias(): string
    {
        $alias = '';
        do {
            $alias .= chr(rand(97, 122));
        } while (isset($this->alocatedAliases[$alias]));

        return $alias;
    }
}
