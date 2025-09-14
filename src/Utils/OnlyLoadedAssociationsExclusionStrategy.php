<?php

declare(strict_types=1);
/**
 * /src/Utils/MercureJWTProvider.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Proxy\Proxy;
use JMS\Serializer\Context;
use JMS\Serializer\Exclusion\ExclusionStrategyInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\SerializationContext;

/**
 * Class OnlyLoadedAssociationsExclusionStrategy.
 *
 * http://stackoverflow.com/questions/11851197/avoiding-recursion-with-doctrine-entities-and-jmsserializer
 */
class OnlyLoadedAssociationsExclusionStrategy implements ExclusionStrategyInterface
{
    public function shouldSkipClass(ClassMetadata $metadata, Context $context): bool
    {
        return false;
    }

    public function shouldSkipProperty(PropertyMetadata $property, Context $context): bool
    {
        if ($context instanceof SerializationContext) {
            if (str_contains($property->class, 'Entity')) {
                throw new \Exception('Serializacao de Entity detectada!');
            }
            /*$propertyValue = $property->getValue();

            if ($propertyValue instanceof Proxy) {
                // skip not loaded one association
                if (!$propertyValue->__isInitialized__) {
                    return true;
                }
            }

            if ($propertyValue instanceof PersistentCollection) {
                // skip not loaded many association
                if (!$propertyValue->isInitialized()) {
                    return true;
                }
            }*/
        }

        return false;
    }
}
