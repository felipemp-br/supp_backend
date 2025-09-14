<?php

declare(strict_types=1);
/**
 * /src/Entity/ParametroAdministrativo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable\Enableable;
use SuppCore\AdministrativoBackend\Entity\Traits\Ativo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 *
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Tree(type: 'nested')]
#[UniqueEntity(fields: ['nome', 'parent'], message: 'Nome já está em utilização para essa classe!')]
#[Enableable]
#[ORM\Table(name: 'ad_param_administrativo')]
#[ORM\UniqueConstraint(columns: ['nome', 'parent_id', 'apagado_em'])]
class ParametroAdministrativo implements EntityInterface
{
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Nome;
    use Descricao;
    use Ativo;

    #[ORM\ManyToOne(targetEntity: 'DominioAdministrativo')]
    #[ORM\JoinColumn(name: 'dominio_administrativo_id', referencedColumnName: 'id', nullable: true)]
    protected ?DominioAdministrativo $dominioAdministrativo = null;

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


    #[Gedmo\TreeParent]
    #[ORM\ManyToOne(targetEntity: 'ParametroAdministrativo', inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    protected ?ParametroAdministrativo $parent = null;

    /**
     * @var Collection|ArrayCollection|Collection<ParametroAdministrativo>|ArrayCollection<ParametroAdministrativo>
     */
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: 'ParametroAdministrativo')]
    #[ORM\OrderBy(
        [
            'lft' => 'ASC',
        ]
    )]
    protected $children;

    public function __construct()
    {
        $this->setUuid();
        $this->children = new ArrayCollection();
    }


    public function getDominioAdministrativo(): ?DominioAdministrativo
    {
        return $this->dominioAdministrativo;
    }

    public function setDominioAdministrativo(?DominioAdministrativo $dominioAdministrativo): self
    {
        $this->dominioAdministrativo = $dominioAdministrativo;

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
