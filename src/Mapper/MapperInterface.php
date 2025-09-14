<?php

declare(strict_types=1);
/**
 * /src//Mapper/MapperInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Mapper;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 * Class MapperInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface MapperInterface
{
    /**
     * @param string          $dtoClass
     * @param EntityInterface $entity
     *
     * @return RestDtoInterface
     */
    public function createDTOFromEntity(string $dtoClass, EntityInterface $entity): RestDtoInterface;

    /**
     * @param EntityInterface $entity
     * @param string          $dtoClass
     * @param array|null      $populate
     *
     * @return RestDtoInterface
     */
    public function convertEntityToDto(EntityInterface $entity, string $dtoClass, ?array $populate): RestDtoInterface;

    /**
     * @param EntityInterface  $entity
     * @param RestDtoInterface $dto
     *
     * @return EntityInterface
     */
    public function update(EntityInterface $entity, RestDtoInterface $dto): EntityInterface;

    /**
     * @param RestDtoInterface $dtoPatch
     * @param RestDtoInterface $dto
     *
     * @return RestDtoInterface
     */
    public function patch(RestDtoInterface $dtoPatch, RestDtoInterface $dto): RestDtoInterface;
}
