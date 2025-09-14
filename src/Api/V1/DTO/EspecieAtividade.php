<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/EspecieAtividade.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroAtividade as GeneroAtividadeDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\GeneroAtividade as GeneroAtividadeEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EspecieAtividade.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'nome' => 'nome',
        'generoAtividade' => 'generoAtividade',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\EspecieAtividade',
    message: 'Campo já está em utilização!'
)]
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/especie_atividade/{id}',
    jsonLDType: 'EspecieAtividade',
    jsonLDContext: '/api/doc/#model-EspecieAtividade'
)]
#[Form\Form]
class EspecieAtividade extends RestDto
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
            'class' => 'SuppCore\AdministrativoBackend\Entity\GeneroAtividade',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: GeneroAtividadeDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroAtividade')]
    protected ?EntityInterface $generoAtividade = null;

    #[OA\Property(type: 'boolean')]
    protected ?bool $valida = null;

    public function __construct()
    {
    }

    /**
     * @return EntityInterface|GeneroAtividadeEntity|GeneroAtividadeDTO|int|null
     */
    public function getGeneroAtividade(): ?EntityInterface
    {
        return $this->generoAtividade;
    }

    /**
     * @param EntityInterface|GeneroAtividadeEntity|GeneroAtividadeDTO|int|null $generoAtividade
     */
    public function setGeneroAtividade(?EntityInterface $generoAtividade): self
    {
        $this->setVisited('generoAtividade');

        $this->generoAtividade = $generoAtividade;

        return $this;
    }

    public function getValida(): ?bool
    {
        return $this->valida;
    }

    public function setValida(?bool $valida): self
    {
        $this->setVisited('valida');

        $this->valida = $valida;

        return $this;
    }
}
