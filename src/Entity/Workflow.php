<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Workflow.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\Loggable]
#[UniqueEntity(fields: ['nome'], message: 'Nome já está em utilização!')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_workflow')]
#[ORM\UniqueConstraint(columns: ['nome', 'apagado_em'])]
class Workflow implements EntityInterface
{
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Nome;
    use Descricao;
    use Id;
    use Uuid;

    /**
     * @var Collection<VinculacaoEspecieProcessoWorkflow>|ArrayCollection<VinculacaoEspecieProcessoWorkflow>
     */
    #[ORM\OneToMany(mappedBy: 'workflow', targetEntity: 'VinculacaoEspecieProcessoWorkflow', cascade: ['all'])]
    protected ArrayCollection|Collection $vinculacoesEspecieProcessoWorkflow;

    /**
     * @var Collection<VinculacaoWorkflow>|ArrayCollection<VinculacaoWorkflow>
     */
    #[ORM\OneToMany(mappedBy: 'workflow', targetEntity: 'VinculacaoWorkflow')]
    protected Collection|ArrayCollection $vinculacoesWorkflow;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'EspecieTarefa', inversedBy: 'workflows')]
    #[ORM\JoinColumn(name: 'especie_tarefa_inicial_id', referencedColumnName: 'id', nullable: false)]
    protected ?EspecieTarefa $especieTarefaInicial = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'GeneroProcesso', inversedBy: 'workflows')]
    #[ORM\JoinColumn(name: 'genero_processo_id', referencedColumnName: 'id', nullable: false)]
    protected ?GeneroProcesso $generoProcesso = null;

    /**
     * @var Collection<TransicaoWorkflow>|ArrayCollection<TransicaoWorkflow>
     */
    #[ORM\OneToMany(mappedBy: 'workflow', targetEntity: 'TransicaoWorkflow')]
    protected Collection|ArrayCollection $transicoesWorkflow;

    /**
     * @var Collection<VinculacaoTransicaoWorkflow>|ArrayCollection<VinculacaoTransicaoWorkflow>
     */
    #[ORM\OneToMany(mappedBy: 'workflow', targetEntity: 'VinculacaoTransicaoWorkflow')]
    protected Collection|ArrayCollection $vinculacoesTransicaoWorkflow;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->transicoesWorkflow = new ArrayCollection();
        $this->vinculacoesEspecieProcessoWorkflow = new ArrayCollection();
        $this->vinculacoesWorkflow = new ArrayCollection();
        $this->vinculacoesTransicaoWorkflow = new ArrayCollection();
    }

    public function getEspecieTarefaInicial(): EspecieTarefa
    {
        return $this->especieTarefaInicial;
    }

    public function setEspecieTarefaInicial(EspecieTarefa $especieTarefaInicial): self
    {
        $this->especieTarefaInicial = $especieTarefaInicial;

        return $this;
    }

    public function addTransicaoWorkflow(TransicaoWorkflow $transicaoWorkflow): self
    {
        if (!$this->transicoesWorkflow->contains($transicaoWorkflow)) {
            $this->transicoesWorkflow[] = $transicaoWorkflow;
            $transicaoWorkflow->setWorkflow($this);
        }

        return $this;
    }

    public function removeTransicaoWorkflow(TransicaoWorkflow $transicaoWorkflow): self
    {
        if ($this->transicoesWorkflow->contains($transicaoWorkflow)) {
            $this->transicoesWorkflow->removeElement($transicaoWorkflow);
        }

        return $this;
    }

    public function getTransicoesWorkflow(): Collection|ArrayCollection
    {
        return $this->transicoesWorkflow;
    }

    public function getVinculacoesEspecieProcessoWorkflow(): ArrayCollection|Collection
    {
        return $this->vinculacoesEspecieProcessoWorkflow;
    }

    public function addVinculacaoEspecieProcessoWorkflow(
        VinculacaoEspecieProcessoWorkflow $vinculacaoEspecieProcessoWorkflow
    ): self {
        if (!$this->vinculacoesEspecieProcessoWorkflow->contains($vinculacaoEspecieProcessoWorkflow)) {
            $this->vinculacoesEspecieProcessoWorkflow->add($vinculacaoEspecieProcessoWorkflow);
            $vinculacaoEspecieProcessoWorkflow->setWorkflow($this);
        }

        return $this;
    }

    public function removeVinculacaoEspecieProcessoWorkflow(
        VinculacaoEspecieProcessoWorkflow $vinculacaoEspecieProcessoWorkflow
    ): self {
        if ($this->vinculacoesEspecieProcessoWorkflow->contains($vinculacaoEspecieProcessoWorkflow)) {
            $this->vinculacoesEspecieProcessoWorkflow->removeElement($vinculacaoEspecieProcessoWorkflow);
        }

        return $this;
    }

    public function getVinculacoesWorkflow(): ArrayCollection|Collection
    {
        return $this->vinculacoesWorkflow;
    }

    public function addVinculacaoWorkflow(VinculacaoWorkflow $vinculacaoWorkflow): self
    {
        if (!$this->vinculacoesWorkflow->contains($vinculacaoWorkflow)) {
            $this->vinculacoesWorkflow->add($vinculacaoWorkflow);
            $vinculacaoWorkflow->setWorkflow($this);
        }

        return $this;
    }

    public function removeVinculacaoWorkflow(VinculacaoWorkflow $vinculacaoWorkflow): self
    {
        if ($this->vinculacoesWorkflow->contains($vinculacaoWorkflow)) {
            $this->vinculacoesWorkflow->removeElement($vinculacaoWorkflow);
        }

        return $this;
    }

    public function getVinculacoesTransicaoWorkflow(): ArrayCollection|Collection
    {
        return $this->vinculacoesTransicaoWorkflow;
    }

    public function addVinculacaoTransicaoWorkflow(VinculacaoTransicaoWorkflow $vinculacaoTransicaoWorkflow): self
    {
        if (!$this->vinculacoesTransicaoWorkflow->contains($vinculacaoTransicaoWorkflow)) {
            $this->vinculacoesTransicaoWorkflow->add($vinculacaoTransicaoWorkflow);
            $vinculacaoTransicaoWorkflow->setWorkflow($this);
        }

        return $this;
    }

    public function removeVinculacaoTransicaoWorkflow(VinculacaoTransicaoWorkflow $vinculacaoTransicaoWorkflow): self
    {
        if ($this->vinculacoesTransicaoWorkflow->contains($vinculacaoTransicaoWorkflow)) {
            $this->vinculacoesTransicaoWorkflow->removeElement($vinculacaoTransicaoWorkflow);
        }

        return $this;
    }

    public function getGeneroProcesso(): ?GeneroProcesso
    {
        return $this->generoProcesso;
    }

    public function setGeneroProcesso(?GeneroProcesso $generoProcesso): self
    {
        $this->generoProcesso = $generoProcesso;

        return $this;
    }
}
