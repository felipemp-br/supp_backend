<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ConfigModulo;

use JetBrains\PhpStorm\ArrayShape;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ConfigModulo as ConfigModuloDTO;
use SuppCore\AdministrativoBackend\Entity\ConfigModulo as ConfigModuloEntity;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\ModelGenerator;

/**
 * Class Rule0004.
 *
 * @descSwagger  =Valida o objeto de acordo com o modelo gerado com swaggest/json-cli.
 * @classeSwagger=Rule0004
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004 implements RuleInterface
{
    /**
     * Rule0004 constructor.
     *
     * @param RulesTranslate $rulesTranslate
     * @param ModelGenerator $modelGenerator
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly ModelGenerator $modelGenerator,
    ) {
    }

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
     */
    public function validate(
        ConfigModuloDTO|RestDtoInterface|null $restDto,
        ConfigModuloEntity|EntityInterface $entity,
        string $transactionId
    ): bool {
        // TODO: Comentado pois a trigger0001 já faz essa verificação
        //if ($restDto->getParadigma()) {
        //    $dataSchemaDTO = $restDto->getParadigma()->getDataSchema();
        //    $dataSchemaEntity = $entity->getParadigma()?->getDataSchema();
        //} else {
        //    $dataSchemaDTO = $restDto->getDataSchema();
        //    $dataSchemaEntity = $entity->getDataSchema();
        //}

        ///** @var ConfigModuloEntity $restDto */
        //if (('json' === $restDto->getDataType()) &&
        //    $restDto->getDataValue() &&
        //    ($dataSchemaDTO !== $dataSchemaEntity)) {
        //    try {
        //        $this->modelGenerator->validateSchema($restDto->getDataValue(), $dataSchemaEntity);
        //    } catch (Throwable|Exception) {
        //        $this->rulesTranslate->throwException('configModulo', 'R0004');
        //    }
        //}

        return true;
    }

    public function getOrder(): int
    {
        return 4;
    }
}
