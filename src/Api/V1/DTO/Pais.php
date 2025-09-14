<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Pais.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Pais.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'codigo' => 'codigo',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\Pais',
    message: 'Campo já está em utilização!'
)]
#[DTOMapper\JsonLD(jsonLDId: '/v1/administrativo/pais/{id}', jsonLDType: 'Pais', jsonLDContext: '/api/doc/#model-Pais')]
#[Form\Form]
class Pais extends RestDto
{
    use IdUuid;
    use Nome;
    use Ativo;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Regex(pattern: '/^[A-Z]{2}$/', message: 'Código Inválido! Deve conter 2 letras.')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $codigo = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Filter\StripTags]
    #[Filter\StripNewlines]
    #[Filter\Trim]
    #[Assert\Regex(pattern: '/^[0-9]{3}$/', message: 'Código Inválido! Deve conter 3 digitos.')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $codigoReceitaFederal = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\Regex(pattern: '/^.{3,255}$/', message: 'O campo deve ter de 3 a 255 caracteres!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $nomeReceitaFederal = null;

    public function setCodigo(?string $codigo): self
    {
        $this->setVisited('codigo');

        $this->codigo = $codigo;

        return $this;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function getCodigoReceitaFederal(): ?string
    {
        return $this->codigoReceitaFederal;
    }

    public function setCodigoReceitaFederal(?string $codigoReceitaFederal): self
    {
        $this->setVisited('codigoReceitaFederal');

        $this->codigoReceitaFederal = $codigoReceitaFederal;

        return $this;
    }

    public function getNomeReceitaFederal(): ?string
    {
        return $this->nomeReceitaFederal;
    }

    public function setNomeReceitaFederal(?string $nomeReceitaFederal): self
    {
        $this->setVisited('nomeReceitaFederal');

        $this->nomeReceitaFederal = $nomeReceitaFederal;

        return $this;
    }
}
