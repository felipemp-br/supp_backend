<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ConfigModulo;

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
use JsonException;

/**
 * Class Rule0001.
 *
 * @descSwagger  =O json enviado deve ser validado, quanto a forma e conteúdo
 * @classeSwagger=Rule0001
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    /**
     * @param RulesTranslate $rulesTranslate
     * @param MapperManager $dtoMapperManager
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
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

        $dataSchema = $restDto->getParadigma() ?
            $restDto->getParadigma()->getDataSchema() :
            $restDto->getDataSchema();

        switch ($restDto->getDataType()) {
            // caso seja do tipo JSON, verificamos se é um JSON sintaticamente válido
            case 'json':
                if (is_null($dataSchema)) {
                    $this->rulesTranslate->throwException(
                        'configModulo',
                        'R0001',
                        ['JSON', $restDto->getDataType()]
                    );
                }

                try {
                    json_decode($dataSchema, true, 512, JSON_THROW_ON_ERROR) ?:
                        throw new JsonException();
                } catch (JsonException) {
                    $this->rulesTranslate->throwException(
                        'configModulo',
                        'R0001a',
                        ['JSON', $restDto->getDataType()]
                    );
                }
                break;
            // para todos os outros tipos dataSchema deve ser nulo (não tem dataSchema pois é um escalar)
            case 'string':
            case 'float':
            case 'bool':
            case 'int':
            case 'datetime':
                if (!is_null($dataSchema)) {
                    $this->rulesTranslate->throwException(
                        'configModulo',
                        'R0001b',
                        [strtoupper($restDto->getDataType()), $restDto->getNome()]
                    );
                }
                break;
            // qualquer outro valor para DataType não é suportado
            // neste momento XML ainda não é suportado
            // case 'xml':
            default:
                $this->rulesTranslate->throwException(
                    'configModulo',
                    'R0001c',
                    [$restDto->getDataType()]
                );
        }

        return true;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }
}
