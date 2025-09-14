<?php

declare(strict_types=1);
/**
 * /src/Form/FormMapper.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Form;

use Psr\Cache\InvalidArgumentException;
use SuppCore\AdministrativoBackend\Form\Attributes\Form as FormAttribute;
use SuppCore\AdministrativoBackend\Form\Attributes\Method;
use SuppCore\AdministrativoBackend\Form\Driver\AttributesDriver;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class FormMethod.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class FormMapper
{
    /**
     * @param FormFactoryInterface          $factory
     * @param AttributesDriver              $attributesDriver
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        private FormFactoryInterface $factory,
        private AttributesDriver $attributesDriver,
        private AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    /**
     * @param string $dtoClass
     * @param string $method
     *
     * @return FormInterface
     *
     * @throws InvalidArgumentException
     */
    public function buildForm(string $dtoClass, string $method): FormInterface
    {
        $formMetatada = $this->getForm($dtoClass);

        $httpMethod = match ($method) {
            'createMethod' => 'POST',
            'updateMethod' => 'PUT',
            default => 'PATCH',
        };

        // default value
        if (empty($formMetatada->validationGroups)) {
            $formMetatada->validationGroups = [$method, 'Default'];
        }

        // Create form, load possible entity data for form and handle request
        $form = $this->factory->createNamed(
            '',
            FormType::class,
            null,
            [
                'data_class' => $dtoClass,
                'validation_groups' => $formMetatada->validationGroups,
                'method' => $httpMethod,
                'allow_extra_fields' => true,
            ]
        );

        $fields = $this->getFields($dtoClass, $method);

        foreach ($fields as $field) {
            $form->add($field->name, $field->value, $field->options);
        }

        return $form;
    }

    /**
     * @param string $dtoClass
     *
     * @return FormAttribute
     *
     * @throws InvalidArgumentException
     */
    public function getForm(string $dtoClass): FormAttribute
    {
        return $this->attributesDriver->getMetadata($dtoClass)->getForm();
    }

    /**
     * @param string $dtoClass
     * @param string $methodName
     * @return array
     *
     * @throws InvalidArgumentException
     */
    public function getFields(string $dtoClass, string $methodName): array
    {
        $fields = [];
        foreach ($this->attributesDriver->getMetadata($dtoClass)->getFields() as $field) {
            if (empty($field->methods)) {
                // qualquer method
                $fields[] = $field;
            } else {
                // liberado para o method?
                /** @var Method $method */
                foreach ($field->methods as $method) {
                    // método existe
                    if ($method->value === $methodName) {
                        if (empty($method->roles)) {
                            // qualquer role para esse method
                            $fields[] = $field;
                            break;
                        }
                        /** @var string $role */
                        foreach ($method->roles as $role) {
                            if ($this->authorizationChecker->isGranted($role)) {
                                // usuario tem acesso ao method
                                $fields[] = $field;
                                break;
                            }
                        }
                    }
                }
            }
        }

        return $fields;
    }
}
