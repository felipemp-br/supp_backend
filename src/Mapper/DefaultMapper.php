<?php

declare(strict_types=1);
/**
 * /src//Mapper/DefaultMapper.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Mapper;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Psr\Cache\InvalidArgumentException;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;

use function in_array;
use function method_exists;

/**
 * Class DefaultMapper.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DefaultMapper extends BaseMapper implements MapperInterface
{
    /**
     * @param string          $dtoClass
     * @param EntityInterface $entity
     *
     * @return RestDtoInterface
     *
     * @throws InvalidArgumentException
     */
    public function createDTOFromEntity(string $dtoClass, EntityInterface $entity): RestDtoInterface
    {
        $dto = new $dtoClass();

        $mapperMetadata = $this->mapperManager->getMapperMetadata($dtoClass);

        foreach ($mapperMetadata->getProperties() as $property) {
            if ($property->collection) {
                continue;
            }

            $dtoSetter = $property->dtoSetter ?? 'set'.ucfirst($property->name);
            $entityGetter = $property->entityGetter ?? 'get'.ucfirst($property->name);

            if (!method_exists($dto, $dtoSetter) || !method_exists($entity, $entityGetter)) {
                continue;
            }

            $dto->{$dtoSetter}($entity->$entityGetter());
        }

        return $dto;
    }

    /**
     * @param EntityInterface $entity
     * @param string          $dtoClass
     * @param array|null      $populate
     *
     * @return RestDtoInterface
     *
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function convertEntityToDto(EntityInterface $entity, string $dtoClass, ?array $populate): RestDtoInterface
    {
        $entityClass = ClassUtils::getClass($entity);

        $entityId = $entity->getId();

        $populateHash = md5(json_encode($populate));
        $cacheHash = $entityClass.'-'.$entityId.'-'.$populateHash;

        if (isset($this->cache[$cacheHash])) {
            if (!method_exists($entity, 'getAtualizadoEm')
                || $entity?->getAtualizadoEm() == $this->cache[$cacheHash]?->getAtualizadoEm()
            ) {
                return $this->cache[$cacheHash];
            }
        }

        $dto = new $dtoClass();

        $mapperMetadata = $this->mapperManager->getMapperMetadata($dtoClass);

        $this->stopwatch->start($entity->getId().':'.get_class($entity));
        foreach ($mapperMetadata->getProperties() as $property) {
            $dtoSetter = $property->dtoSetter ?? 'set'.ucfirst($property->name);
            $entityGetter = $property->entityGetter ?? 'get'.ucfirst($property->name);

            if (!method_exists($dto, $dtoSetter) || !method_exists($entity, $entityGetter)) {
                continue;
            }

            // associacoes, inclusive colecoes
            if (null !== $property->dtoClass) {
                if (!in_array($property->name, $populate, true)) {
                    continue;
                }

                $subPopulate = $this->getSubPopulate($populate, $property->name);

                $mapper = $this->mapperManager->getMapper($property->dtoClass);
                if ($property->collection) {
                    foreach ($entity->$entityGetter() as $item) {
                        if ($item) {
                            $dto->{$dtoSetter}($mapper->convertEntityToDto($item, $property->dtoClass, $subPopulate));
                        }
                    }
                } elseif ($entity->$entityGetter()) {
                    $dto->{$dtoSetter}(
                        $mapper->convertEntityToDto($entity->$entityGetter(), $property->dtoClass, $subPopulate)
                    );
                }

                continue;
            }

            $dto->{$dtoSetter}($entity->$entityGetter());
        }
        $this->stopwatch->stop($entity->getId().':'.get_class($entity));

        // jsonLD
        if ($mapperMetadata->getJsonLD()) {
            $dto->setJsonLdId(str_replace('{id}', (string) $entity->getId(), $mapperMetadata->getJsonLD()->jsonLDId));
            $dto->setJsonLdType($mapperMetadata->getJsonLD()->jsonLDType);
            $dto->setJsonLdContext($mapperMetadata->getJsonLD()->jsonLDContext);
        }

        $this->pipesManager->proccess($dto, $entity, 'onCreateDTOFromEntity');

        // sanidade do Dto
        foreach ($mapperMetadata->getProperties() as $property) {
            $dtoGetter = $property->dtoGetter ?? 'get'.ucfirst($property->name);
            if (!method_exists($dto, $dtoGetter)) {
                throw new Exception('Erro de configuração do DTO! '.$dtoClass.' : '.$dtoGetter);
            }
            $target = $dto->{$dtoGetter}();
            if (is_object($target)) {
                $clazz = get_class($target);
                if (str_contains($clazz, '\\Entity\\')) {
                    throw new Exception('Erro de mapeamento do DTO! '.$dtoClass.' : '.$clazz);
                }
            }
        }

        $this->cache[$cacheHash] = $dto;

        return $dto;
    }

    /**
     * @param array  $populate
     * @param string $property
     *
     * @return array
     */
    public function getSubPopulate(array $populate, string $property): array
    {
        $subPopulate = [];
        foreach ($populate as $p) {
            if (0 === strpos($p, $property.'.')) {
                $subPopulate[] = str_replace($property.'.', '', $p);
            }
        }

        return $subPopulate;
    }

    /**
     * @param EntityInterface  $entity
     * @param RestDtoInterface $dto
     *
     * @return EntityInterface
     */
    public function update(EntityInterface $entity, RestDtoInterface $dto): EntityInterface
    {
        foreach ($dto->getVisited() as $visited) {
            if ('id' === $visited || 'uuid' === $visited) {
                continue;
            }

            $property = $this->mapperManager->getPropertyDTO(get_class($dto), $visited);

            if (!$property) {
                continue;
            }

            $dtoGetter = $property->dtoGetter ?? 'get'.ucfirst($property->name);
            $entitySetter = $property->entitySetter ?? 'set'.ucfirst($property->name);

            if (!method_exists($entity, $entitySetter) || !method_exists($dto, $dtoGetter)) {
                continue;
            }

            $value = $dto->$dtoGetter($visited);
            if ('' === $value) {
                $value = null;
            }

            $entity->{$entitySetter}($value);
        }

        return $entity;
    }

    /**
     * @param RestDtoInterface $dtoPatch
     * @param RestDtoInterface $dto
     *
     * @return RestDtoInterface
     */
    public function patch(RestDtoInterface $dtoPatch, RestDtoInterface $dto): RestDtoInterface
    {
        foreach ($dtoPatch->getVisited() as $visited) {
            $dtoGetter = $property->dtoGetter ?? 'get'.ucfirst($visited);
            $dtoSetter = $property->dtoSetter ?? 'set'.ucfirst($visited);
            if (!method_exists($dto, $dtoSetter) || !method_exists($dtoPatch, $dtoGetter)) {
                continue;
            }
            $dto->{$dtoSetter}($dtoPatch->{$dtoGetter}());
        }

        return $dto;
    }
}
