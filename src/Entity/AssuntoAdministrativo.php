<?php

declare(strict_types=1);
/**
 * /src/Entity/AssuntoAdministrativo.php.
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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AssuntoAdministrativo.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[Gedmo\Tree(type: 'nested')]
#[Enableable]
#[Immutable(
    fieldName: 'nome',
    expressionValues: 'env:constantes.entidades.assunto_administrativo.immutable',
    expression: Immutable::EXPRESSION_IN
)]
#[ORM\Table(name: 'ad_assunto_administrativo')]
class AssuntoAdministrativo implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'Campo não pode ser nulo!')]
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

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $glossario = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $dispositivoLegal = null;

    #[Assert\Regex(
        pattern: '/[A-Z0-9]+/',
        message: 'A codigoCNJ do assuntoAdministrativo dever ter possuir apenas letras maiúsculas ou números.'
    )]
    #[Assert\Length(
        min: 3,
        max: 25,
        minMessage: 'A codigoCNJ deve ter no mínimo {{ limit }} letras ou números',
        maxMessage: 'A codigoCNJ deve ter no máximo {{ limit }} letras ou números'
    )]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'string', length: 25, nullable: true)]
    protected ?string $codigoCNJ = null;

    #[Assert\NotNull(message: 'Campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $ativo = true;

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
    #[ORM\Column(name: 'root', type: 'integer', nullable: true)]
    protected ?int $root = null;

    #[Gedmo\Versioned]
    #[Gedmo\TreeParent]
    #[ORM\ManyToOne(targetEntity: 'AssuntoAdministrativo', inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    protected ?AssuntoAdministrativo $parent = null;

    /**
     * @var Collection|ArrayCollection|Collection<AssuntoAdministrativo>|ArrayCollection<AssuntoAdministrativo>
     */
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: 'AssuntoAdministrativo')]
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

    public function getGlossario(): ?string
    {
        return $this->glossario;
    }

    public function setGlossario(?string $glossario): self
    {
        $this->glossario = $glossario;

        return $this;
    }

    public function getDispositivoLegal(): ?string
    {
        return $this->dispositivoLegal;
    }

    public function setDispositivoLegal(?string $dispositivoLegal): self
    {
        $this->dispositivoLegal = $dispositivoLegal;

        return $this;
    }

    public function getCodigoCNJ(): ?string
    {
        return $this->codigoCNJ;
    }

    public function setCodigoCNJ(?string $codigoCNJ): self
    {
        $this->codigoCNJ = $codigoCNJ;

        return $this;
    }

    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): self
    {
        $this->ativo = $ativo;

        return $this;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }


    /**
     * @return int
     */
    public function getLft(): int
    {
        return $this->lft;
    }


    /**
     * @return int
     */
    public function getLvl(): int
    {
        return $this->lvl;
    }

    /**
     * @return int
     */
    public function getRgt(): int
    {
        return $this->rgt;
    }

    /**
     * @return int|null
     */
    public function getRoot(): ?int
    {
        return $this->root;
    }
}
