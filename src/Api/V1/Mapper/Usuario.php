<?php

declare(strict_types=1);
/**
 * /src//Mapper/Custom/V1/Usuario.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper;

use Doctrine\Common\Annotations\AnnotationException;
use ReflectionException;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\DefaultMapper;

/**
 * Class Default.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Usuario extends DefaultMapper
{
    /**
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function update(EntityInterface $entity, RestDtoInterface $dto): EntityInterface
    {
        // não faz nada diferente, é apenas um exemplo de um custom mapper
        parent::update($entity, $dto);

        return $entity;
    }
}
