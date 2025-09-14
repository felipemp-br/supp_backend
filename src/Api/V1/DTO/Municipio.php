<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Municipio.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Estado as EstadoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Municipio.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'nome' => 'nome',
        'estado' => 'estado',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\Municipio',
    message: 'Campo já está em utilização!'
)]
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/municipio/{id}',
    jsonLDType: 'Municipio',
    jsonLDContext: '/api/doc/#model-Municipio'
)]
#[Form\Form]
class Municipio extends RestDto
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
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Regex(pattern: '/^[0-9]{7}$/', message: 'Código inválido!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $codigoIBGE = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Regex(pattern: '/^[0-9]{4}$/', message: 'Código inválido!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $codigoSIAFI = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Estado',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: EstadoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Estado')]
    protected ?EntityInterface $estado = null;

    public function setCodigoIBGE(?string $codigoIBGE): self
    {
        $this->setVisited('codigoIBGE');

        $this->codigoIBGE = $codigoIBGE;

        return $this;
    }

    public function getCodigoIBGE(): ?string
    {
        return $this->codigoIBGE;
    }

    public function setCodigoSIAFI(?string $codigoSIAFI): self
    {
        $this->setVisited('codigoSIAFI');

        $this->codigoSIAFI = $codigoSIAFI;

        return $this;
    }

    public function getCodigoSIAFI(): ?string
    {
        return $this->codigoSIAFI;
    }

    public function getEstado(): ?EntityInterface
    {
        return $this->estado;
    }

    public function setEstado(?EntityInterface $estado): self
    {
        $this->setVisited('estado');

        $this->estado = $estado;

        return $this;
    }
}
