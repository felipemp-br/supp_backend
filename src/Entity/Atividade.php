<?php

declare(strict_types=1);
/**
 * /src/Entity/Atividade.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DateTime;
use DMS\Filter\Rules as Filter;
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
use SuppCore\JudicialBackend\Entity\Traits\AtividadeJudicial;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Atividade.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_atividade')]
class Atividade implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use AtividadeJudicial;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'data_hora_conclusao', type: 'datetime', nullable: false)]
    protected DateTime $dataHoraConclusao;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'observacao', type: 'string', nullable: true)]
    protected ?string $observacao = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'encerra_tarefa', type: 'boolean', nullable: false)]
    protected bool $encerraTarefa = false;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'EspecieAtividade')]
    #[ORM\JoinColumn(name: 'especie_atividade_id', referencedColumnName: 'id', nullable: false)]
    protected EspecieAtividade $especieAtividade;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_id', referencedColumnName: 'id', nullable: false)]
    protected Setor $setor;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: false)]
    protected Usuario $usuario;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Tarefa', inversedBy: 'atividades')]
    #[ORM\JoinColumn(name: 'tarefa_id', referencedColumnName: 'id', nullable: false)]
    protected Tarefa $tarefa;

    #[ORM\OneToOne(inversedBy: 'atividadeAprovacao', targetEntity: 'Tarefa')]
    #[ORM\JoinColumn(name: 'tarefa_aprovacao_id', referencedColumnName: 'id', nullable: true)]
    protected ?Tarefa $tarefaAprovacao = null;

    #[ORM\ManyToOne(targetEntity: 'Documento')]
    #[ORM\JoinColumn(name: 'documento_id', referencedColumnName: 'id', nullable: true)]
    protected ?Documento $documento = null;

    /**
     * @var Collection|ArrayCollection<Juntada>
     */
    #[ORM\OneToMany(mappedBy: 'atividade', targetEntity: 'Juntada')]
    protected $juntadas;

    #[ORM\ManyToOne(targetEntity: 'Workflow')]
    #[ORM\JoinColumn(name: 'workflow_id', referencedColumnName: 'id', nullable: true)]
    protected ?Workflow $workflow = null;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[ORM\Column(name: 'info_complementar_1', type: 'string', nullable: true)]
    private ?string $informacaoComplementar1 = null;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[ORM\Column(name: 'info_complementar_2', type: 'string', nullable: true)]
    private ?string $informacaoComplementar2 = null;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[ORM\Column(name: 'info_complementar_3', type: 'string', nullable: true)]
    private ?string $informacaoComplementar3 = null;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[ORM\Column(name: 'info_complementar_4', type: 'string', nullable: true)]
    private ?string $informacaoComplementar4 = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->juntadas = new ArrayCollection();
    }

    public function getDataHoraConclusao(): DateTime
    {
        return $this->dataHoraConclusao;
    }

    public function setDataHoraConclusao(DateTime $dataHoraConclusao): self
    {
        $this->dataHoraConclusao = $dataHoraConclusao;

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

    public function getEncerraTarefa(): bool
    {
        return $this->encerraTarefa;
    }

    public function setEncerraTarefa(bool $encerraTarefa): self
    {
        $this->encerraTarefa = $encerraTarefa;

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

    public function getSetor(): Setor
    {
        return $this->setor;
    }

    public function setSetor(Setor $setor): self
    {
        $this->setor = $setor;

        return $this;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getTarefa(): Tarefa
    {
        return $this->tarefa;
    }

    public function setTarefa(Tarefa $tarefa): self
    {
        $this->tarefa = $tarefa;

        return $this;
    }

    public function getTarefaAprovacao(): Tarefa
    {
        return $this->tarefaAprovacao;
    }

    public function setTarefaAprovacao(Tarefa $tarefaAprovacao): self
    {
        $this->tarefaAprovacao = $tarefaAprovacao;

        return $this;
    }

    public function getDocumento(): ?Documento
    {
        return $this->documento;
    }

    public function setDocumento(?Documento $documento): self
    {
        $this->documento = $documento;

        return $this;
    }

    public function addJuntada(Juntada $juntada): self
    {
        if (!$this->juntadas->contains($juntada)) {
            $this->juntadas[] = $juntada;
            $juntada->setAtividade($this);
        }

        return $this;
    }

    public function removeJuntada(Juntada $juntada): self
    {
        if ($this->juntadas->contains($juntada)) {
            $this->juntadas->removeElement($juntada);
        }

        return $this;
    }

    public function getJuntadas(): Collection|ArrayCollection
    {
        return $this->juntadas;
    }

    public function getWorkflow(): ?Workflow
    {
        return $this->workflow;
    }

    public function setWorkflow(?Workflow $workflow): self
    {
        $this->workflow = $workflow;

        return $this;
    }

    public function getInformacaoComplementar1(): ?string
    {
        return $this->informacaoComplementar1;
    }

    public function setInformacaoComplementar1(?string $informacaoComplementar1): self
    {
        $this->informacaoComplementar1 = $informacaoComplementar1;

        return $this;
    }

    public function getInformacaoComplementar2(): ?string
    {
        return $this->informacaoComplementar2;
    }

    public function setInformacaoComplementar2(?string $informacaoComplementar2): self
    {
        $this->informacaoComplementar2 = $informacaoComplementar2;

        return $this;
    }

    public function getInformacaoComplementar3(): ?string
    {
        return $this->informacaoComplementar3;
    }

    public function setInformacaoComplementar3(?string $informacaoComplementar3): self
    {
        $this->informacaoComplementar3 = $informacaoComplementar3;

        return $this;
    }

    public function getInformacaoComplementar4(): ?string
    {
        return $this->informacaoComplementar4;
    }

    public function setInformacaoComplementar4(?string $informacaoComplementar4): self
    {
        $this->informacaoComplementar4 = $informacaoComplementar4;

        return $this;
    }
}
