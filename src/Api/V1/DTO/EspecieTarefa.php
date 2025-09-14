<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/EspecieTarefa.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroTarefa as GeneroTarefaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\GeneroTarefa as GeneroTarefaEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EspecieTarefa.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'nome' => 'nome',
        'generoTarefa' => 'generoTarefa',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\EspecieTarefa',
    message: 'Campo já está em utilização!'
)]
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/especie_tarefa/{id}',
    jsonLDType: 'EspecieTarefa',
    jsonLDContext: '/api/doc/#model-EspecieTarefa'
)]
#[Form\Form]
class EspecieTarefa extends RestDto
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
            'class' => 'SuppCore\AdministrativoBackend\Entity\GeneroTarefa',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: GeneroTarefaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroTarefa')]
    protected ?EntityInterface $generoTarefa = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $evento = false;

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
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $corHexadecimalPrimaria = null;

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
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $corHexadecimalSecundaria = null;

    #[OA\Property(type: 'boolean')]
    protected ?bool $valida = null;

    /**
     * @return EntityInterface|GeneroTarefaEntity|GeneroTarefaDTO|int|null
     */
    public function getGeneroTarefa(): ?EntityInterface
    {
        return $this->generoTarefa;
    }

    /**
     * @param EntityInterface|GeneroTarefaEntity|GeneroTarefaDTO|int|null $generoTarefa
     */
    public function setGeneroTarefa(?EntityInterface $generoTarefa): self
    {
        $this->setVisited('generoTarefa');

        $this->generoTarefa = $generoTarefa;

        return $this;
    }

    public function setEvento(?bool $evento): self
    {
        $this->setVisited('evento');

        $this->evento = $evento;

        return $this;
    }

    /**
     * Get evento|null.
     *
     * @return bool
     */
    public function getEvento(): ?bool
    {
        return $this->evento;
    }

    public function getCorHexadecimalPrimaria(): ?string
    {
        return $this->corHexadecimalPrimaria;
    }

    public function setCorHexadecimalPrimaria(?string $corHexadecimalPrimaria): self
    {
        $this->setVisited('corHexadecimalPrimaria');

        $this->corHexadecimalPrimaria = $corHexadecimalPrimaria;

        return $this;
    }

    public function getCorHexadecimalSecundaria(): ?string
    {
        return $this->corHexadecimalSecundaria;
    }

    public function setCorHexadecimalSecundaria(?string $corHexadecimalSecundaria): self
    {
        $this->setVisited('corHexadecimalSecundaria');

        $this->corHexadecimalSecundaria = $corHexadecimalSecundaria;

        return $this;
    }

    public function getValida(): ?bool
    {
        return $this->valida;
    }

    public function setValida(?bool $valida): self
    {
        $this->setVisited('workflow');

        $this->valida = $valida;

        return $this;
    }
}
