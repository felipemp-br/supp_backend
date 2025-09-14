<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Formulario.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use DMS\Filter\Rules as Filter;

/**
 * Class Formulario.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/formulario/{id}',
    jsonLDType: 'Formulario',
    jsonLDContext: '/api/doc/#model-Formulario'
)]
#[Form\Form]
class Formulario extends RestDto
{
    use Blameable;
    use Timeblameable;
    use Softdeleteable;

    use IdUuid;
    use Nome;
    use Ativo;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'Campo não pode ser nulo!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $sigla = null;


    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'Campo não pode ser nulo!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $dataSchema = null;


    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $template = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean')]
    #[DTOMapper\Property]
    protected ?bool $ia = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean')]
    #[DTOMapper\Property]
    protected ?bool $aceitaJsonInvalido = false;

    /**
     * @return string|null
     */
    public function getSigla(): ?string
    {
        return $this->sigla;
    }

    /**
     * @param string|null $sigla
     *
     * @return self
     */
    public function setSigla(?string $sigla): self
    {
        $this->setVisited('sigla');
        $this->sigla = $sigla;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDataSchema(): ?string
    {
        return $this->dataSchema;
    }

    /**
     * @param string|null $dataSchema
     *
     * @return self
     */
    public function setDataSchema(?string $dataSchema): self
    {
        $this->setVisited('dataSchema');
        $this->dataSchema = $dataSchema;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * @param string|null $template
     *
     * @return self
     */
    public function setTemplate(?string $template): self
    {
        $this->setVisited('template');
        $this->template = $template;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIa(): ?bool
    {
        return $this->ia;
    }

    /**
     * @param bool|null $ia
     * @return $this
     */
    public function setIa(?bool $ia): self
    {
        $this->setVisited('ia');
        $this->ia = $ia;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getAceitaJsonInvalido(): ?bool
    {
        return $this->aceitaJsonInvalido;
    }

    /**
     * @param bool|null $aceitaJsonInvalido
     * @return $this
     */
    public function setAceitaJsonInvalido(?bool $aceitaJsonInvalido): self
    {
        $this->setVisited('aceitaJsonInvalido');
        $this->aceitaJsonInvalido = $aceitaJsonInvalido;

        return $this;
    }
}
