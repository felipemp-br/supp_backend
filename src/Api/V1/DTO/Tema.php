<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Tema.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\RamoDireito as RamoDireitoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Sigla;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tema.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'nome' => 'nome',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\Tema',
    message: 'Campo já está em utilização!'
)]
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/ramo_direito/{id}',
    jsonLDType: 'Tema',
    jsonLDContext: '/api/doc/#model-Tema'
)]
#[Form\Form]
class Tema extends RestDto
{
    use IdUuid;
    use Nome;
    use Sigla;
    use Ativo;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\RamoDireito',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: RamoDireitoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\RamoDireito')]
    protected ?EntityInterface $ramoDireito = null;

    public function getRamoDireito(): ?EntityInterface
    {
        return $this->ramoDireito;
    }

    /**
     * @return $this
     */
    public function setRamoDireito(?EntityInterface $ramoDireito): self
    {
        $this->setVisited('ramoDireito');
        $this->ramoDireito = $ramoDireito;

        return $this;
    }
}
