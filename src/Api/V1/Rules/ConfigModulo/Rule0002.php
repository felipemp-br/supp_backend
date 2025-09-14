<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ConfigModulo;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionException;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\MapperManager;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ConfigModulo as ConfigModuloDTO;
use SuppCore\AdministrativoBackend\Entity\ConfigModulo as ConfigModuloEntity;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\ModelGenerator;
use Swaggest\JsonSchema\Exception\ContentException;
use Swaggest\JsonSchema\InvalidRef;

/**
 * Class Rule0002.
 *
 * @descSwagger  =O json enviado deve ser validado, quanto a forma e conteúdo
 * @classeSwagger=Rule0002
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    /**
     * @param RulesTranslate $rulesTranslate
     * @param ModelGenerator $modelGenerator
     * @param MapperManager $dtoMapperManager
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly ModelGenerator $modelGenerator,
        private readonly MapperManager  $dtoMapperManager
    ) {
    }

    /**
     * @return array
     */
    #[ArrayShape([ConfigModuloDTO::class => "string[]"])]
    public function supports(): array
    {
        return [
            ConfigModuloDTO::class => [
                'assertCreate',
                'assertUpdate',
                'assertPatch',
            ],
        ];
    }

    /**
     * @param ConfigModuloDTO|RestDtoInterface|null $restDto
     * @param ConfigModuloEntity|EntityInterface $entity
     * @param string $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     * @throws ReflectionException
     */
    public function validate(
        ConfigModuloDTO|RestDtoInterface|null $restDto,
        ConfigModuloEntity|EntityInterface $entity,
        string $transactionId
    ): bool {
        if ($entity->getId()) {
            $dtoMapper = $this->dtoMapperManager->getMapper($restDto::class);
            $dataBaseDto = $dtoMapper->createDTOFromEntity($restDto::class, $entity);
            $dtoMapper->patch($restDto, $dataBaseDto);
        }

        $dataSchemaDTO = $restDto->getParadigma() ?
            $restDto->getParadigma()->getDataSchema() :
            $restDto->getDataSchema();

        $dataSchemaEntity = $entity->getParadigma() ?
            $entity->getParadigma()->getDataSchema() :
            $entity->getDataSchema();

        if (('json' === $restDto->getDataType()) &&
            ($dataSchemaDTO !== $dataSchemaEntity)) {
            try {
                $this->modelGenerator->validateSchema($dataSchemaDTO);
            } catch (InvalidRef|ContentException|Exception $exception) {
                $this->rulesTranslate->throwException(
                    'configModulo',
                    'R0002',
                    [$exception->getMessage()]
                );
            }
        }

        return true;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 2;
    }
}
