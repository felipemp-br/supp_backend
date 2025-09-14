<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/DadosFormulario.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Formulario as FormularioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Formulario as FormularioEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;

/**
 * Class DadosFormulario.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/dados_formulario/{id}',
    jsonLDType: 'DadosFormulario',
    jsonLDContext: '/api/doc/#model-DadosFormulario'
)]
#[Form\Form]
class DadosFormulario extends RestDto
{
    use Blameable;
    use Timeblameable;
    use Softdeleteable;

    use IdUuid;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $dataValue = null;

    /**
     * @var FormularioEntity|FormularioDTO|null
     */
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Formulario',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: FormularioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Formulario')]
    protected ?EntityInterface $formulario = null;

    /**
     * @var ComponenteDigitalEntity|ComponenteDigitalDTO|null
     */
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ComponenteDigital',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ComponenteDigitalDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital')]
    protected ?EntityInterface $componenteDigital = null;

    /**
     * @var DocumentoEntity|DocumentoDTO|null
     */
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Documento',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: DocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Documento')]
    protected ?EntityInterface $documento = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean')]
    #[DTOMapper\Property]
    protected ?bool $invalido = false;

    /**
     * @return string|null
     */
    public function getDataValue(): ?string
    {
        return $this->dataValue;
    }

    /**
     * @param string|null $dataValue
     *
     * @return self
     * @noinspection PhpUnused
     */
    public function setDataValue(?string $dataValue): self
    {
        $this->setVisited('dataValue');
        $this->dataValue = $dataValue;

        return $this;
    }

    /**
     * @return FormularioEntity|FormularioDTO|null
     */
    public function getFormulario(): ?EntityInterface
    {
        return $this->formulario;
    }

    /**
     * @param FormularioEntity|FormularioDTO|null $formulario
     *
     * @return self
     */
    public function setFormulario(?EntityInterface $formulario): self
    {
        $this->setVisited('formulario');
        $this->formulario = $formulario;

        return $this;
    }

    /**
     * @return ComponenteDigitalEntity|ComponenteDigitalDTO|null
     */
    public function getComponenteDigital(): ?EntityInterface
    {
        return $this->componenteDigital;
    }

    /**
     * @param ComponenteDigitalEntity|ComponenteDigitalDTO|null $componenteDigital
     *
     * @return self
     */
    public function setComponenteDigital(?EntityInterface $componenteDigital): self
    {
        $this->setVisited('componenteDigital');
        $this->componenteDigital = $componenteDigital;

        return $this;
    }

    /**
     * @return DocumentoEntity|DocumentoDTO|null
     */
    public function getDocumento(): ?EntityInterface
    {
        return $this->documento;
    }

    /**
     * @param DocumentoEntity|DocumentoDTO|null $documento
     *
     * @return self
     */
    public function setDocumento(?EntityInterface $documento): self
    {
        $this->setVisited('documento');
        $this->documento = $documento;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getInvalido(): ?bool
    {
        return $this->invalido;
    }

    /**
     * @param bool|null $invalido
     * @return $this
     */
    public function setInvalido(?bool $invalido): self
    {
        $this->setVisited('invalido');
        $this->invalido = $invalido;

        return $this;
    }
}
