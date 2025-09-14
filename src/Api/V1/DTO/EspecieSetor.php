<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/EspecieSetor.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroSetor as GeneroSetorDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\GeneroSetor as GeneroSetorEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EspecieSetor.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'nome' => 'nome',
        'generoSetor' => 'generoSetor',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\EspecieSetor',
    message: 'Campo já está em utilização!'
)]
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/especie_setor/{id}',
    jsonLDType: 'EspecieSetor',
    jsonLDContext: '/api/doc/#model-EspecieSetor'
)]
#[Form\Form]
#[Form\Cacheable(expire: 86400)]
class EspecieSetor extends RestDto
{
    use IdUuid;
    use Nome;
    use Descricao;
    use Ativo;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\GeneroSetor',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: GeneroSetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroSetor')]
    protected ?EntityInterface $generoSetor = null;

    /**
     * @return EntityInterface|GeneroSetorEntity|GeneroSetorDTO|int|null
     */
    public function getGeneroSetor(): ?EntityInterface
    {
        return $this->generoSetor;
    }

    /**
     * @param EntityInterface|GeneroSetorEntity|GeneroSetorDTO|int|null $generoSetor
     */
    public function setGeneroSetor(?EntityInterface $generoSetor): self
    {
        $this->setVisited('generoSetor');

        $this->generoSetor = $generoSetor;

        return $this;
    }
}
