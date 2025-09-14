<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/DominioAdministrativo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeOrgaoCentral as ModalidadeOrgaoCentralDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral as ModalidadeOrgaoCentralEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;

/**
 * Class DominioAdministrativo.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/dominio_administrativo/{id}',
    jsonLDType: 'DominioAdministrativo',
    jsonLDContext: '/api/doc/#model-DominioAdministrativo'
)]
#[Form\Form]
class DominioAdministrativo extends RestDto
{
    use IdUuid;
    use Nome;
    use Descricao;
    use Ativo;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    /**
     * @var ModalidadeOrgaoCentralDTO|ModalidadeOrgaoCentralEntity|EntityInterface|int|null
     */
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ModalidadeOrgaoCentralDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeOrgaoCentral')]
    protected $modOrgaoCentral;


    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $flat = true;


    public function __construct()
    {
    }

    public function getModOrgaoCentral()
    {
        return $this->modOrgaoCentral;
    }

    /**
     * @param int|ModalidadeOrgaoCentral|EntityInterface|ModalidadeOrgaoCentralEntity|null $modOrgaoCentral
     *
     * @return DominioAdministrativo
     */
    public function setModOrgaoCentral($modOrgaoCentral)
    {
        $this->setVisited('modOrgaoCentral');
        $this->modOrgaoCentral = $modOrgaoCentral;

        return $this;
    }

    public function setFlat(?bool $flat): self
    {
        $this->setVisited('flat');

        $this->flat = $flat;

        return $this;
    }

    public function getFlat(): ?bool
    {
        return $this->flat;
    }
}
