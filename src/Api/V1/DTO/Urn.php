<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Precedente.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeUrn as ModalidadeUrnDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Urn.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'urn' => 'urn',
        'modalidadeUrn' => 'modalidadeUrn',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\Urn',
    message: 'A URN já está em utilização!'
)]
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'tituloDispositivo' => 'tituloDispositivo',
        'modalidadeUrn' => 'modalidadeUrn',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\Urn',
    message: 'O título já está em utilização!'
)]
#[DTOMapper\JsonLD(jsonLDId: '/v1/administrativo/urn/{id}', jsonLDType: 'Urn', jsonLDContext: '/api/doc/#model-Urn')]
#[Form\Form]
class Urn extends RestDto
{
    use IdUuid;
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
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'O campo deve ter no mínimo 3 caracteres!',
        maxMessage: 'O campo deve ter no máximo 255 caracteres!'
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $tituloDispositivo = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    #[Filter\Trim]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    protected string $urn = '';

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeUrn',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ModalidadeUrnDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeUrn')]
    protected ?EntityInterface $modalidadeUrn = null;

    public function getUrn(): string
    {
        return $this->urn;
    }

    /**
     * @return $this
     */
    public function setUrn(string $urn): self
    {
        $this->setVisited('urn');
        $this->urn = $urn;

        return $this;
    }

    public function getModalidadeUrn(): ?EntityInterface
    {
        return $this->modalidadeUrn;
    }

    /**
     * @return $this
     */
    public function setModalidadeUrn(?EntityInterface $modalidadeUrn): self
    {
        $this->setVisited('modalidadeUrn');
        $this->modalidadeUrn = $modalidadeUrn;

        return $this;
    }

    public function getTituloDispositivo(): ?string
    {
        return $this->tituloDispositivo;
    }

    /**
     * @return $this
     */
    public function setTituloDispositivo(?string $tituloDispositivo): self
    {
        $this->setVisited('tituloDispositivo');
        $this->tituloDispositivo = $tituloDispositivo;

        return $this;
    }
}
