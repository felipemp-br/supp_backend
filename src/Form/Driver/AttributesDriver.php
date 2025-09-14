<?php

declare(strict_types=1);
/**
 * /src/Form/Driver/AttributesDriver.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Form\Driver;

use LogicException;
use Psr\Cache\InvalidArgumentException;
use ReflectionClass;
use SuppCore\AdministrativoBackend\Form\Attributes\Field as FieldAttribute;
use SuppCore\AdministrativoBackend\Form\Attributes\Form as FormAttribute;
use SuppCore\AdministrativoBackend\Form\FormMetadata;
use Symfony\Contracts\Cache\CacheInterface;

use function is_object;

/**
 * Class AttributesDriver.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class AttributesDriver implements MetadataDriverInterface
{
    public function __construct(
        private CacheInterface $appCache
    ) {
    }

    /**
     * @param string $dtoClassName
     *
     * @return FormMetadata
     *
     * @throws InvalidArgumentException
     */
    public function getMetadata(string $dtoClassName): FormMetadata
    {
        return $this->appCache->get('form_'.str_replace('\\', '_', $dtoClassName), function () use ($dtoClassName) {
            $metadata = new FormMetadata();
            $reflectionClass = new ReflectionClass($dtoClassName);
            /* @var FormAttribute $formMetadata */
            foreach ($reflectionClass->getAttributes() as $attribute) {
                if (FormAttribute::class === $attribute->getName()) {
                    $formMetadata = $attribute->newInstance();
                    break;
                }
            }

            if (!$formMetadata instanceof FormAttribute) {
                throw new LogicException(sprintf('DTO %s não possui o atributo #Form', $dtoClassName));
            } else {
                $metadata->setForm($formMetadata);
            }
            while (is_object($reflectionClass)) {
                $properties = $reflectionClass->getProperties();
                foreach ($properties as $property) {
                    /* @var FieldAttribute $fieldMetadata */
                    $fieldMetadata = null;
                    foreach ($property->getAttributes() as $attribute) {
                        if (FieldAttribute::class === $attribute->getName()) {
                            $fieldMetadata = $attribute->newInstance();
                            break;
                        }
                    }

                    if ($fieldMetadata instanceof FieldAttribute) {
                        if (null === $fieldMetadata->name) {
                            $fieldMetadata->name = $property->getName();
                        }
                        $metadata->addField($fieldMetadata);
                    }
                }
                $reflectionClass = $reflectionClass->getParentClass();
            }

            return $metadata;
        });
    }
}
