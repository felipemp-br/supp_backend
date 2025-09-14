<?php

declare(strict_types=1);
/**
 * /src/Entity/Colaborador.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable\Enableable;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Colaborador.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\Loggable]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[UniqueEntity(fields: ['usuario'], message: 'Usuário já está associado a um colaborador')]
#[Enableable]
#[ORM\Table(name: 'ad_colaborador')]
class Colaborador implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'Campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Cargo')]
    #[ORM\JoinColumn(name: 'cargo_id', referencedColumnName: 'id', nullable: false)]
    protected Cargo $cargo;

    #[Assert\NotNull(message: 'Campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeColaborador')]
    #[ORM\JoinColumn(name: 'mod_colaborador_id', referencedColumnName: 'id', nullable: false)]
    protected ModalidadeColaborador $modalidadeColaborador;

    /**
     * @var Usuario
     */
    #[ORM\OneToOne(inversedBy: 'colaborador', targetEntity: 'Usuario', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $usuario = null;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<Afastamento>
     */
    #[ORM\OneToMany(mappedBy: 'colaborador', targetEntity: 'Afastamento', cascade: ['all'])]
    protected $afastamentos;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<Lotacao>
     */
    #[ORM\OneToMany(mappedBy: 'colaborador', targetEntity: 'Lotacao', cascade: ['all'])]
    protected $lotacoes;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'ativo', type: 'boolean', nullable: false)]
    protected bool $ativo = true;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->afastamentos = new ArrayCollection();
        $this->lotacoes = new ArrayCollection();
    }

    public function setCargo(Cargo $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
    }

    public function getCargo(): Cargo
    {
        return $this->cargo;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setModalidadeColaborador(ModalidadeColaborador $modalidadeColaborador): self
    {
        $this->modalidadeColaborador = $modalidadeColaborador;

        return $this;
    }

    public function getModalidadeColaborador(): ModalidadeColaborador
    {
        return $this->modalidadeColaborador;
    }

    public function addAfastamento(Afastamento $afastamento): self
    {
        if (!$this->afastamentos->contains($afastamento)) {
            $this->afastamentos[] = $afastamento;
            $afastamento->setColaborador($this);
        }

        return $this;
    }

    public function removeAfastamento(Afastamento $afastamento): self
    {
        if ($this->afastamentos->contains($afastamento)) {
            $this->afastamentos->removeElement($afastamento);
        }

        return $this;
    }

    public function getAfastamentos(): Collection
    {
        return $this->afastamentos;
    }

    public function addLotacao(Lotacao $lotacao): self
    {
        if (!$this->lotacoes->contains($lotacao)) {
            $this->lotacoes[] = $lotacao;
            $lotacao->setColaborador($this);
        }

        return $this;
    }

    public function removeLotacao(Lotacao $lotacao): self
    {
        if ($this->lotacoes->contains($lotacao)) {
            $this->lotacoes->removeElement($lotacao);
        }

        return $this;
    }

    public function getLotacoes(): Collection
    {
        return $this->lotacoes;
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
