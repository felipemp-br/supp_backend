<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TransicaoWorkflow.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[UniqueEntity(
    fields: [
        'workflow',
        'especieTarefaFrom',
        'especieAtividade',
        'apagadoEm',
    ],
    message: '"Nãoépossívelcadastrarmaisdeumatransiçãoparaumworkflowcomamesmaespeciedetarefainicial'
)]
#[Gedmo\Loggable]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_transicao_workflow')]
#[ORM\UniqueConstraint(columns: ['workflow_id', 'especie_tarefa_from_id', 'especie_atividade_id', 'apagado_em'])]
class TransicaoWorkflow implements EntityInterface
{
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Workflow', inversedBy: 'transicoesWorkflow')]
    #[ORM\JoinColumn(name: 'workflow_id', referencedColumnName: 'id', nullable: false)]
    protected ?Workflow $workflow = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'EspecieAtividade', inversedBy: 'transicoesWorkflow')]
    #[ORM\JoinColumn(name: 'especie_atividade_id', referencedColumnName: 'id', nullable: false)]
    protected ?EspecieAtividade $especieAtividade = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'EspecieTarefa', inversedBy: 'transicoesWorkflowFrom')]
    #[ORM\JoinColumn(name: 'especie_tarefa_from_id', referencedColumnName: 'id', nullable: false)]
    protected ?EspecieTarefa $especieTarefaFrom = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'EspecieTarefa', inversedBy: 'transicoesWorkflowTo')]
    #[ORM\JoinColumn(name: 'especie_tarefa_to_id', referencedColumnName: 'id', nullable: false)]
    protected ?EspecieTarefa $especieTarefaTo = null;

    /**
     * @var Collection|ArrayCollection<VinculacaoTransicaoWorkflow>
     */
    #[ORM\OneToMany(mappedBy: 'transicaoWorkflow', targetEntity: 'VinculacaoTransicaoWorkflow', cascade: ['all'])]
    protected ArrayCollection|Collection $vinculacoesTransicaoWorkflow;

    /**
     * @var Collection<VinculacaoWorkflow>|ArrayCollection<VinculacaoWorkflow>
     */
    #[ORM\OneToMany(mappedBy: 'workflow', targetEntity: 'VinculacaoWorkflow', cascade: ['all'])]
    protected ArrayCollection|Collection $vinculacoesWorkflow;

    /**
     * @var Collection|ArrayCollection<AcaoTransicaoWorkflow>
     */
    #[ORM\OneToMany(mappedBy: 'transicaoWorkflow', targetEntity: 'AcaoTransicaoWorkflow')]
    protected ArrayCollection|Collection $acoes;

    /**
     * @var Collection<ValidacaoTransicaoWorkflow>|ArrayCollection<ValidacaoTransicaoWorkflow>
     */
    #[ORM\OneToMany(mappedBy: 'transicaoWorkflow', targetEntity: 'ValidacaoTransicaoWorkflow')]
    protected Collection|ArrayCollection $validacoes;

    #[ORM\Column(name: 'qtd_dias_prazo', type: 'integer', nullable: true)]
    protected ?int  $qtdDiasPrazo = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->acoes = new ArrayCollection();
        $this->validacoes = new ArrayCollection();
        $this->vinculacoesTransicaoWorkflow = new ArrayCollection();
        $this->vinculacoesWorkflow = new ArrayCollection();
    }

    public function getWorkflow(): Workflow
    {
        return $this->workflow;
    }

    public function setWorkflow(Workflow $workflow): self
    {
        $this->workflow = $workflow;

        return $this;
    }

    public function getEspecieAtividade(): EspecieAtividade
    {
        return $this->especieAtividade;
    }

    public function setEspecieAtividade(EspecieAtividade $especieAtividade): self
    {
        $this->especieAtividade = $especieAtividade;

        return $this;
    }

    public function getEspecieTarefaFrom(): EspecieTarefa
    {
        return $this->especieTarefaFrom;
    }

    public function setEspecieTarefaFrom(EspecieTarefa $especieTarefaFrom): self
    {
        $this->especieTarefaFrom = $especieTarefaFrom;

        return $this;
    }

    public function getEspecieTarefaTo(): EspecieTarefa
    {
        return $this->especieTarefaTo;
    }

    public function setEspecieTarefaTo(EspecieTarefa $especieTarefaTo): self
    {
        $this->especieTarefaTo = $especieTarefaTo;

        return $this;
    }

    public function getAcoes(): Collection|ArrayCollection
    {
        return $this->acoes;
    }

    public function getValidacoes(): Collection|ArrayCollection
    {
        return $this->validacoes;
    }

    public function addValidacao(ValidacaoTransicaoWorkflow $validacaoTransicaoWorkflow): self
    {
        if (!$this->validacoes->contains($validacaoTransicaoWorkflow)) {
            $this->validacoes->add($validacaoTransicaoWorkflow);
            $validacaoTransicaoWorkflow->setTransicaoWorkflow($this);
        }

        return $this;
    }

    public function removeValidacao(ValidacaoTransicaoWorkflow $validacaoTransicaoWorkflow): self
    {
        if ($this->validacoes->contains($validacaoTransicaoWorkflow)) {
            $this->validacoes->removeElement($validacaoTransicaoWorkflow);
        }

        return $this;
    }

    public function addAcao(AcaoTransicaoWorkflow $acaoTransicaoWorkflow): self
    {
        if (!$this->acoes->contains($acaoTransicaoWorkflow)) {
            $this->acoes->add($acaoTransicaoWorkflow);
            $acaoTransicaoWorkflow->setTransicaoWorkflow($this);
        }

        return $this;
    }

    public function removeAcao(AcaoTransicaoWorkflow $acaoTransicaoWorkflow): self
    {
        if ($this->acoes->contains($acaoTransicaoWorkflow)) {
            $this->acoes->removeElement($acaoTransicaoWorkflow);
        }

        return $this;
    }

    public function getQtdDiasPrazo(): ?int
    {
        return $this->qtdDiasPrazo;
    }

    public function setQtdDiasPrazo(?int $qtdDiasPrazo): self
    {
        $this->qtdDiasPrazo = $qtdDiasPrazo;

        return $this;
    }

    public function getVinculacoesTransicaoWorkflow(): ArrayCollection|Collection
    {
        return $this->vinculacoesTransicaoWorkflow;
    }

    public function addVinculacaoTransicaoWorkflow(
        VinculacaoTransicaoWorkflow $vinculacaoTransicaoWorkflow
    ): self {
        if (!$this->vinculacoesTransicaoWorkflow->contains($vinculacaoTransicaoWorkflow)) {
            $this->vinculacoesTransicaoWorkflow->add($vinculacaoTransicaoWorkflow);
            $vinculacaoTransicaoWorkflow->setTransicaoWorkflow($this);
        }

        return $this;
    }

    public function removeVinculacaoTransicaoWorkflow(
        VinculacaoTransicaoWorkflow $vinculacaoTransicaoWorkflow
    ): self {
        if ($this->vinculacoesTransicaoWorkflow->contains($vinculacaoTransicaoWorkflow)) {
            $this->vinculacoesTransicaoWorkflow->removeElement($vinculacaoTransicaoWorkflow);
        }

        return $this;
    }

    public function getVinculacoesWorkflow(): ArrayCollection|Collection
    {
        return $this->vinculacoesWorkflow;
    }

    public function addVinculacaoWorkflow(
        VinculacaoWorkflow $vinculacaoWorkflow
    ): self {
        if (!$this->vinculacoesWorkflow->contains($vinculacaoWorkflow)) {
            $this->vinculacoesWorkflow->add($vinculacaoWorkflow);
        }

        return $this;
    }

    public function removeVinculacaoWorkflow(
        VinculacaoWorkflow $vinculacaoWorkflow
    ): self {
        if ($this->vinculacoesWorkflow->contains($vinculacaoWorkflow)) {
            $this->vinculacoesWorkflow->removeElement($vinculacaoWorkflow);
        }

        return $this;
    }
}
