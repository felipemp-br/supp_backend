<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/ParametroAdministrativo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DominioAdministrativo as DominioAdministrativoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ParametroAdministrativo as ParametroAdministrativoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\DominioAdministrativo as DominioAdministrativoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;

/**
 * Class ParametroAdministrativo.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/parametro_administrativo/{id}',
    jsonLDType: 'ParametroAdministrativo',
    jsonLDContext: '/api/doc/#model-ParametroAdministrativo'
)]
#[Form\Form]
class ParametroAdministrativo extends RestDto
{
    use IdUuid;
    use Nome;
    use Descricao;
    use Ativo;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[OA\Property(type: 'boolean')]
    protected ?bool $hasChild = false;

    #[OA\Property(type: 'boolean')]
    protected ?bool $expansable = null;

    /**
     * @var DominioAdministrativoDTO|DominioAdministrativoEntity|EntityInterface|int|null
     */
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\DominioAdministrativo',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: DominioAdministrativoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\DominioAdministrativo')]
    protected $dominioAdministrativo;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ParametroAdministrativo',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ParametroAdministrativoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ParametroAdministrativo')]
    protected ?EntityInterface $parent = null;


    public function __construct()
    {
    }

    public function getDominioAdministrativo()
    {
        return $this->dominioAdministrativo;
    }

    /**
     * @param int|DominioAdministrativo|EntityInterface|DominioAdministrativoEntity|null $dominioAdministrativo
     *
     * @return DominioAdministrativo
     */
    public function setDominioAdministrativo($dominioAdministrativo)
    {
        $this->setVisited('dominioAdministrativo');
        $this->dominioAdministrativo = $dominioAdministrativo;

        return $this;
    }

    public function getParent(): ?EntityInterface
    {
        return $this->parent;
    }

    public function setParent(?EntityInterface $parent): self
    {
        $this->setVisited('parent');

        $this->parent = $parent;

        return $this;
    }

    public function getHasChild(): ?bool
    {
        return $this->hasChild;
    }

    public function setHasChild(?bool $hasChild): self
    {
        $this->setVisited('hasChild');

        $this->hasChild = $hasChild;

        return $this;
    }

    public function getExpansable(): ?bool
    {
        return $this->expansable;
    }

    public function setExpansable(?bool $expansable): self
    {
        $this->setVisited('expansable');

        $this->expansable = $expansable;

        return $this;
    }
}
