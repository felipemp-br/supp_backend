<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Classificacao.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Classificacao as ClassificacaoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeDestinacao as ModalidadeDestinacaoDTO;
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
 * Class Classificacao.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/classificacao/{id}',
    jsonLDType: 'Classificacao',
    jsonLDContext: '/api/doc/#model-Classificacao'
)]
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'nome' => 'nome',
        'codigo' => 'codigo',
        'parent' => 'parent',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\Classificacao',
    message: 'Classificação já utilizada!'
)]
#[Form\Form]
class Classificacao extends RestDto
{
    use Ativo;
    use Blameable;
    use IdUuid;
    use Nome;
    use Softdeleteable;
    use Timeblameable;

    #[OA\Property(type: 'boolean')]
    protected ?bool $expansable = null;

    #[OA\Property(type: 'boolean')]
    protected ?bool $hasChild = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeDestinacao',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: ModalidadeDestinacaoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeDestinacao')]
    protected ?EntityInterface $modalidadeDestinacao = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $prazoGuardaFaseCorrenteAno = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $prazoGuardaFaseCorrenteMes = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $prazoGuardaFaseCorrenteDia = null;

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
    protected ?string $prazoGuardaFaseCorrenteEvento = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $prazoGuardaFaseIntermediariaAno = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $prazoGuardaFaseIntermediariaMes = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $prazoGuardaFaseIntermediariaDia = null;

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
    protected ?string $prazoGuardaFaseIntermediariaEvento = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\Regex(
        pattern: '/[A-Z0-9]+/',
        message: 'O codigo do assuntoAdministrativo dever ter possuir apenas letras maiúsculas ou números.'
    )]
    #[Assert\NotNull(message: 'Campo não pode ser nulo!')]
    #[Assert\Length(
        min: 3,
        max: 25,
        minMessage: 'O codigo deve ter no mínimo {{ limit }} letras ou números',
        maxMessage: 'O codigo deve ter no máximo {{ limit }} letras ou números'
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $codigo = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\NotNull(message: 'Campo não pode ser nulo!')]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected bool $permissaoUso = true;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Length(max: 500, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $observacao = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Classificacao',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ClassificacaoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Classificacao')]
    protected ?EntityInterface $parent = null;

    #[OA\Property(type: 'string')]
    protected ?string $nomeCompleto = null;

    public function setNomeCompleto(?string $nomeCompleto): self
    {
        $this->setVisited('nomeCompleto');

        $this->nomeCompleto = $nomeCompleto;

        return $this;
    }

    public function getNomeCompleto(): ?string
    {
        return $this->nomeCompleto;
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

    public function getModalidadeDestinacao(): ?EntityInterface
    {
        return $this->modalidadeDestinacao;
    }

    public function setModalidadeDestinacao(?EntityInterface $modalidadeDestinacao): self
    {
        $this->setVisited('modalidadeDestinacao');

        $this->modalidadeDestinacao = $modalidadeDestinacao;

        return $this;
    }

    public function getPrazoGuardaFaseCorrenteAno(): ?int
    {
        return $this->prazoGuardaFaseCorrenteAno;
    }

    public function setPrazoGuardaFaseCorrenteAno(?int $prazoGuardaFaseCorrenteAno): self
    {
        $this->setVisited('prazoGuardaFaseCorrenteAno');

        $this->prazoGuardaFaseCorrenteAno = $prazoGuardaFaseCorrenteAno;

        return $this;
    }

    public function getPrazoGuardaFaseCorrenteMes(): ?int
    {
        return $this->prazoGuardaFaseCorrenteMes;
    }

    public function setPrazoGuardaFaseCorrenteMes(?int $prazoGuardaFaseCorrenteMes): self
    {
        $this->setVisited('prazoGuardaFaseCorrenteMes');

        $this->prazoGuardaFaseCorrenteMes = $prazoGuardaFaseCorrenteMes;

        return $this;
    }

    public function getPrazoGuardaFaseCorrenteDia(): ?int
    {
        return $this->prazoGuardaFaseCorrenteDia;
    }

    public function setPrazoGuardaFaseCorrenteDia(?int $prazoGuardaFaseCorrenteDia): self
    {
        $this->setVisited('prazoGuardaFaseCorrenteDia');

        $this->prazoGuardaFaseCorrenteDia = $prazoGuardaFaseCorrenteDia;

        return $this;
    }

    public function getPrazoGuardaFaseCorrenteEvento(): ?string
    {
        return $this->prazoGuardaFaseCorrenteEvento;
    }

    public function setPrazoGuardaFaseCorrenteEvento(?string $prazoGuardaFaseCorrenteEvento): self
    {
        $this->setVisited('prazoGuardaFaseCorrenteEvento');

        $this->prazoGuardaFaseCorrenteEvento = $prazoGuardaFaseCorrenteEvento;

        return $this;
    }

    public function getPrazoGuardaFaseIntermediariaAno(): ?int
    {
        return $this->prazoGuardaFaseIntermediariaAno;
    }

    public function setPrazoGuardaFaseIntermediariaAno(?int $prazoGuardaFaseIntermediariaAno): self
    {
        $this->setVisited('prazoGuardaFaseIntermediariaAno');

        $this->prazoGuardaFaseIntermediariaAno = $prazoGuardaFaseIntermediariaAno;

        return $this;
    }

    public function getPrazoGuardaFaseIntermediariaMes(): ?int
    {
        return $this->prazoGuardaFaseIntermediariaMes;
    }

    public function setPrazoGuardaFaseIntermediariaMes(?int $prazoGuardaFaseIntermediariaMes): self
    {
        $this->setVisited('prazoGuardaFaseIntermediariaMes');

        $this->prazoGuardaFaseIntermediariaMes = $prazoGuardaFaseIntermediariaMes;

        return $this;
    }

    public function getPrazoGuardaFaseIntermediariaDia(): ?int
    {
        return $this->prazoGuardaFaseIntermediariaDia;
    }

    public function setPrazoGuardaFaseIntermediariaDia(?int $prazoGuardaFaseIntermediariaDia): self
    {
        $this->setVisited('prazoGuardaFaseIntermediariaDia');

        $this->prazoGuardaFaseIntermediariaDia = $prazoGuardaFaseIntermediariaDia;

        return $this;
    }

    public function getPrazoGuardaFaseIntermediariaEvento(): ?string
    {
        return $this->prazoGuardaFaseIntermediariaEvento;
    }

    public function setPrazoGuardaFaseIntermediariaEvento(?string $prazoGuardaFaseIntermediariaEvento): self
    {
        $this->setVisited('prazoGuardaFaseIntermediariaEvento');

        $this->prazoGuardaFaseIntermediariaEvento = $prazoGuardaFaseIntermediariaEvento;

        return $this;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(?string $codigo): self
    {
        $this->setVisited('codigo');

        $this->codigo = $codigo;

        return $this;
    }

    public function getPermissaoUso(): ?bool
    {
        return $this->permissaoUso;
    }

    public function setPermissaoUso(?bool $permissaoUso): self
    {
        $this->setVisited('permissaoUso');

        $this->permissaoUso = $permissaoUso;

        return $this;
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    public function setObservacao(?string $observacao): self
    {
        $this->setVisited('observacao');

        $this->observacao = $observacao;

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
}
