<?php

declare(strict_types=1);
/**
 * /src/Entity/Classificacao.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DMS\Filter\Rules as Filter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable\Enableable;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Immutable\Immutable;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Classificacao.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[Gedmo\Tree(type: 'nested')]
#[UniqueEntity(fields: ['nome', 'parent', 'codigo'], message: 'Nome já está em utilização para essa classe!')]
#[Enableable]
#[Immutable(
    fieldName: 'codigo',
    expressionValues: 'env:constantes.entidades.classificacao.immutable',
    expression: Immutable::EXPRESSION_IN
)]
#[ORM\Table(name: 'ad_classificacao')]
#[ORM\UniqueConstraint(columns: ['nome', 'parent_id', 'codigo', 'apagado_em'])]
class Classificacao implements EntityInterface
{
    // Traits
    use Blameable;
    use Softdeleteable;
    use Timestampable;
    use Id;
    use Uuid;

    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Campo ter no mínimo {{ limit }} letras ou números',
        maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números'
    )]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'string', nullable: false)]
    protected string $nome;

    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeDestinacao')]
    #[ORM\JoinColumn(name: 'mod_destinacao_id', referencedColumnName: 'id', nullable: true)]
    protected ?ModalidadeDestinacao $modalidadeDestinacao = null;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'corrente_ano', type: 'integer', nullable: true)]
    protected ?int $prazoGuardaFaseCorrenteAno = null;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'corrente_mes', type: 'integer', nullable: true)]
    protected ?int $prazoGuardaFaseCorrenteMes = null;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'corrente_dia', type: 'integer', nullable: true)]
    protected ?int $prazoGuardaFaseCorrenteDia = null;

    #[Gedmo\Versioned]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'corrente_evento', type: 'string', nullable: true)]
    protected ?string $prazoGuardaFaseCorrenteEvento = null;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'intermediaria_ano', type: 'integer', nullable: true)]
    protected ?int $prazoGuardaFaseIntermediariaAno = null;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'intermediaria_mes', type: 'integer', nullable: true)]
    protected ?int $prazoGuardaFaseIntermediariaMes = null;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'intermediaria_dia', type: 'integer', nullable: true)]
    protected ?int $prazoGuardaFaseIntermediariaDia = null;

    #[Gedmo\Versioned]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'intermediaria_evento', type: 'string', nullable: true)]
    protected ?string $prazoGuardaFaseIntermediariaEvento = null;

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
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'string', length: 25, nullable: false)]
    protected string $codigo;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'ativo', type: 'boolean', nullable: false)]
    protected bool $ativo = true;

    #[Assert\NotNull(message: 'Campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $permissaoUso = true;

    #[Assert\Length(max: 500, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'string', length: 500, nullable: true)]
    protected ?string $observacao = null;

    #[Gedmo\TreeLeft]
    #[ORM\Column(name: 'lft', type: 'integer')]
    protected int $lft;

    #[Gedmo\TreeLevel]
    #[ORM\Column(name: 'lvl', type: 'integer')]
    protected int $lvl;

    #[Gedmo\TreeRight]
    #[ORM\Column(name: 'rgt', type: 'integer')]
    protected int $rgt;

    #[Gedmo\TreeRoot]
    #[ORM\Column(name: 'root', type: 'integer')]
    protected int $root;

    #[Gedmo\Versioned]
    #[Gedmo\TreeParent]
    #[ORM\ManyToOne(targetEntity: 'Classificacao', inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    protected ?Classificacao $parent = null;

    /**
     * @var Collection|ArrayCollection|Collection<Classificacao>|ArrayCollection<Classificacao>
     */
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: 'Classificacao')]
    #[ORM\OrderBy(
        [
            'lft' => 'ASC',
        ]
    )]
    protected $children;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->children = new ArrayCollection();
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getModalidadeDestinacao(): ?ModalidadeDestinacao
    {
        return $this->modalidadeDestinacao;
    }

    public function setModalidadeDestinacao(?ModalidadeDestinacao $modalidadeDestinacao): self
    {
        $this->modalidadeDestinacao = $modalidadeDestinacao;

        return $this;
    }

    public function getPrazoGuardaFaseCorrenteAno(): ?int
    {
        return $this->prazoGuardaFaseCorrenteAno;
    }

    public function setPrazoGuardaFaseCorrenteAno(?int $prazoGuardaFaseCorrenteAno): self
    {
        $this->prazoGuardaFaseCorrenteAno = $prazoGuardaFaseCorrenteAno;

        return $this;
    }

    public function getPrazoGuardaFaseCorrenteMes(): ?int
    {
        return $this->prazoGuardaFaseCorrenteMes;
    }

    public function setPrazoGuardaFaseCorrenteMes(?int $prazoGuardaFaseCorrenteMes): self
    {
        $this->prazoGuardaFaseCorrenteMes = $prazoGuardaFaseCorrenteMes;

        return $this;
    }

    public function getPrazoGuardaFaseCorrenteDia(): ?int
    {
        return $this->prazoGuardaFaseCorrenteDia;
    }

    public function setPrazoGuardaFaseCorrenteDia(?int $prazoGuardaFaseCorrenteDia): self
    {
        $this->prazoGuardaFaseCorrenteDia = $prazoGuardaFaseCorrenteDia;

        return $this;
    }

    public function getPrazoGuardaFaseCorrenteEvento(): ?string
    {
        return $this->prazoGuardaFaseCorrenteEvento;
    }

    public function setPrazoGuardaFaseCorrenteEvento(?string $prazoGuardaFaseCorrenteEvento): self
    {
        $this->prazoGuardaFaseCorrenteEvento = $prazoGuardaFaseCorrenteEvento;

        return $this;
    }

    public function getPrazoGuardaFaseIntermediariaAno(): ?int
    {
        return $this->prazoGuardaFaseIntermediariaAno;
    }

    public function setPrazoGuardaFaseIntermediariaAno(?int $prazoGuardaFaseIntermediariaAno): self
    {
        $this->prazoGuardaFaseIntermediariaAno = $prazoGuardaFaseIntermediariaAno;

        return $this;
    }

    public function getPrazoGuardaFaseIntermediariaMes(): ?int
    {
        return $this->prazoGuardaFaseIntermediariaMes;
    }

    public function setPrazoGuardaFaseIntermediariaMes(?int $prazoGuardaFaseIntermediariaMes): self
    {
        $this->prazoGuardaFaseIntermediariaMes = $prazoGuardaFaseIntermediariaMes;

        return $this;
    }

    public function getPrazoGuardaFaseIntermediariaDia(): ?int
    {
        return $this->prazoGuardaFaseIntermediariaDia;
    }

    public function setPrazoGuardaFaseIntermediariaDia(?int $prazoGuardaFaseIntermediariaDia): self
    {
        $this->prazoGuardaFaseIntermediariaDia = $prazoGuardaFaseIntermediariaDia;

        return $this;
    }

    public function getPrazoGuardaFaseIntermediariaEvento(): ?string
    {
        return $this->prazoGuardaFaseIntermediariaEvento;
    }

    public function setPrazoGuardaFaseIntermediariaEvento(?string $prazoGuardaFaseIntermediariaEvento): self
    {
        $this->prazoGuardaFaseIntermediariaEvento = $prazoGuardaFaseIntermediariaEvento;

        return $this;
    }

    public function getCodigo(): string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getPermissaoUso(): bool
    {
        return $this->permissaoUso;
    }

    public function setPermissaoUso(bool $permissaoUso): self
    {
        $this->permissaoUso = $permissaoUso;

        return $this;
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    public function setObservacao(?string $observacao): self
    {
        $this->observacao = $observacao;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function getLvl(): int
    {
        return $this->lvl;
    }

    public function setAtivo(bool $ativo): self
    {
        $this->ativo = $ativo;

        return $this;
    }

    public function getAtivo(): bool
    {
        return $this->ativo;
    }
}
