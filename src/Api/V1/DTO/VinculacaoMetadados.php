<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/VinculacaoMetadados.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\DTO\RestDto;
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
 * Class VinculacaoMetadados.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'tese' => 'tese',
        'urn' => 'urn',
        'idDispositivo' => 'idDispositivo',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\VinculacaoMetadados',
    message: 'Informações já vinculadas a Tese!'
)]
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/vinculacao_metadados/{id}',
    jsonLDType: 'VinculacaoMetadados',
    jsonLDContext: '/api/doc/#model-VinculacaoMetadados'
)]
#[Form\Form]
class VinculacaoMetadados extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Tese',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Tese'))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Tese')]
    protected ?EntityInterface $tese = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Urn',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Urn'))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Urn')]
    protected ?EntityInterface $urn = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToLower(encoding: 'UTF-8')]
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
    protected ?string $idDispositivo = null;

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
    protected ?string $textoDispositivo = null;

    public function getTese(): ?EntityInterface
    {
        return $this->tese;
    }

    /**
     * @return $this
     */
    public function setTese(?EntityInterface $tese): self
    {
        $this->setVisited('tese');
        $this->tese = $tese;

        return $this;
    }

    public function getUrn(): ?EntityInterface
    {
        return $this->urn;
    }

    /**
     * @return $this
     */
    public function setUrn(?EntityInterface $urn): self
    {
        $this->setVisited('urn');
        $this->urn = $urn;

        return $this;
    }

    public function getIdDispositivo(): ?string
    {
        return $this->idDispositivo;
    }

    /**
     * @return $this
     */
    public function setIdDispositivo(?string $idDispositivo): self
    {
        $this->setVisited('idDispositivo');
        $this->idDispositivo = $idDispositivo;

        return $this;
    }

    public function getTextoDispositivo(): ?string
    {
        return $this->textoDispositivo;
    }

    /**
     * @return $this
     */
    public function setTextoDispositivo(?string $textoDispositivo): self
    {
        $this->setVisited('textoDispositivo');
        $this->textoDispositivo = $textoDispositivo;

        return $this;
    }
}
