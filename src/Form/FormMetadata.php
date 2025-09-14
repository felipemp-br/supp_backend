<?php

declare(strict_types=1);
/**
 * /src/Form/FormMetadata.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Form;

use SuppCore\AdministrativoBackend\Form\Attributes\Field as FieldAttribute;
use SuppCore\AdministrativoBackend\Form\Attributes\Form as FormAttribute;

/**
 * Class FormMetadata.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class FormMetadata
{
    /**
     * @var FormAttribute
     */
    protected FormAttribute $form;

    /**
     * @var FieldAttribute[]
     */
    protected array $fields = [];

    /**
     * @param FormAttribute $form
     *
     * @return $this
     */
    public function setForm(FormAttribute $form): self
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return FormAttribute
     */
    public function getForm(): FormAttribute
    {
        return $this->form;
    }

    /**
     * @param FieldAttribute $field
     *
     * @return FormMetadata
     */
    public function addField(FieldAttribute $field): self
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @return FieldAttribute[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}
