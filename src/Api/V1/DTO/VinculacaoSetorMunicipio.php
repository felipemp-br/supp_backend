<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/VinculacaoSetorMunicipio.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Municipio as MunicipioDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Municipio as MunicipioEntity;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VinculacaoSetorMunicipio.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'setor' => 'setor',
        'municipio' => 'municipio',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\VinculacaoSetorMunicipio',
    message: 'Usuários já se encontram vinculados!'
)]
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/vinculacao_setor_municipio/{id}',
    jsonLDType: 'VinculacaoSetorMunicipio',
    jsonLDContext: '/api/doc/#model-VinculacaoSetorMunicipio'
)]
#[Form\Form]
class VinculacaoSetorMunicipio extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    /**
     * @var SetorDTO|SetorEntity|EntityInterface|int|null
     */
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected $setor;

    /**
     * @var MunicipioDTO|MunicipioEntity|EntityInterface|int|null
     */
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Municipio',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: MunicipioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Municipio')]
    protected $municipio;

    public function __construct()
    {
    }

    /**
     * @return int|Setor|EntityInterface|SetorEntity|null
     */
    public function getSetor()
    {
        return $this->setor;
    }

    /**
     * @param int|Setor|EntityInterface|SetorEntity|null $setor
     */
    public function setSetor($setor): self
    {
        $this->setVisited('setor');
        $this->setor = $setor;

        return $this;
    }

    /**
     * @return int|Municipio|EntityInterface|MunicipioEntity|null
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * @param int|Municipio|EntityInterface|MunicipioEntity|null $municipio
     */
    public function setMunicipio($municipio): self
    {
        $this->setVisited('municipio');
        $this->municipio = $municipio;

        return $this;
    }
}
