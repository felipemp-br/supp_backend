<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ConfigModulo/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ConfigModulo;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ConfigModulo as ConfigModuloDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ConfigModuloResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ConfigModulo as ConfigModuloEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use SuppCore\AdministrativoBackend\Utils\ModelGenerator;
use SuppCore\AdministrativoBackend\Utils\StringService;
use Swaggest\JsonSchema\Exception;
use Throwable;

/**
 * Class Trigger0001.
 *
 * @descSwagger  =Ao autualizar uma configModulo pai, seus filhos devem ser revalidados
 * @classeSwagger=Trigger0001
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{

    /**
     * Trigger0001 constructor.
     * @param ModelGenerator $modelGenerator
     * @param ConfigModuloResource $configModuloResource
     */
    public function __construct(
        private readonly ModelGenerator $modelGenerator,
        private readonly ConfigModuloResource $configModuloResource
    ) {
    }


    public function supports(): array
    {
        return [
            ConfigModuloDTO::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param ConfigModuloDTO|RestDtoInterface|null $restDto
     * @param ConfigModuloEntity|EntityInterface $entity
     * @param string $transactionId
     *
     * @return void
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function execute(
        ConfigModuloDTO|RestDtoInterface|null $restDto,
        ConfigModuloEntity|EntityInterface $entity,
        string $transactionId
    ): void {
        if ($restDto->getParadigma()) {
            $dataSchemaDTO = $restDto->getParadigma()->getDataSchema();
            $dataSchemaEntity = $entity->getParadigma()?->getDataSchema();
        } else {
            $dataSchemaDTO = $restDto->getDataSchema();
            $dataSchemaEntity = $entity->getDataSchema();
        }

        if (('json' === $restDto->getDataType()) &&
            $dataSchemaDTO &&
            ($dataSchemaDTO !== $dataSchemaEntity)) {
            if ($restDto->getDataValue()) {
                try {
                    $this->modelGenerator->validateSchema($restDto->getDataValue(), $dataSchemaDTO);
                } catch (Throwable|Exception) {
                    $restDto->setInvalid(true);
                }
            } elseif ($restDto->getMandatory()) {
                $restDto->setInvalid(true);
            }

            $configuracoesFilhas =
                $this->configModuloResource->getRepository()->findBy(['paradigma' => $restDto->getId()]);

            foreach ($configuracoesFilhas as $config) {
                try {
                    if ($config->getDataValue()) {
                        $this->modelGenerator->validateSchema($config->getDataValue(), $dataSchemaDTO);
                    } elseif ($config->getMandatory()) {
                        throw new Exception();
                    }
                } catch (Throwable|Exception) {
                    /** @var ConfigModuloDTO $configDTO */
                    $configDTO = $this->configModuloResource->getDtoForEntity(
                        $config->getId(),
                        ConfigModuloDTO::class
                    );
                    $configDTO->setInvalid(true);
                    $this->configModuloResource->update($config->getId(), $configDTO, $transactionId);
                }
            }
        }

        if ('bool' === $restDto->getDataType()) {
            $restDto->setDataValue(StringService::boolval($restDto->getDataValue()) ? "true" : "false");
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
