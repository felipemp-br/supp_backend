<?php

declare(strict_types=1);
/**
 * /src/Entity/Tarefa.php.
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
use Symfony\Component\Validator\Constraints as Assert;
use SuppCore\JudicialBackend\Entity\Traits\ComunicacaoJudicial;

/**
 * Class Tarefa.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_tarefa')]
class Tarefa implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    use ComunicacaoJudicial;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Processo', inversedBy: 'tarefas')]
    #[ORM\JoinColumn(name: 'processo_id', referencedColumnName: 'id', nullable: false)]
    protected ?Processo $processo = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $observacao = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'local_evento', type: 'string', nullable: true)]
    protected ?string $localEvento = null;

    #[ORM\OneToOne(mappedBy: 'tarefaAprovacao', targetEntity: 'Atividade')]
    protected ?Atividade $atividadeAprovacao = null;

    #[ORM\OneToOne(mappedBy: 'tarefaAnalise', targetEntity: 'SolicitacaoAutomatizada')]
    protected ?SolicitacaoAutomatizada $solicitacaoAutomatizadaAnalise = null;

    #[ORM\OneToOne(mappedBy: 'tarefaDadosCumprimento', targetEntity: 'SolicitacaoAutomatizada')]
    protected ?SolicitacaoAutomatizada $solicitacaoAutomatizadaDadosCumprimento = null;

    #[ORM\ManyToOne(targetEntity: 'VinculacaoWorkflow')]
    #[ORM\JoinColumn(name: 'vinculacao_workflow_id', referencedColumnName: 'id', nullable: true)]
    protected ?VinculacaoWorkflow $vinculacaoWorkflow = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'post_it', type: 'string', nullable: true)]
    protected ?string $postIt = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $urgente = false;

    /**
     * Registro de data e hora de leitura da tarefa pelo usuário.
     */
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'data_hora_leitura', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraLeitura = null;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'data_hora_disponibilizacao', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraDisponibilizacao = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'data_hora_inicio_prazo', type: 'datetime', nullable: false)]
    protected ?DateTime $dataHoraInicioPrazo = null;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'data_hora_final_prazo', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraFinalPrazo = null;

    #[ORM\Column(name: 'data_hora_conclusao_prazo', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraConclusaoPrazo = null;

    #[ORM\Column(name: 'data_hora_async_lock', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraAsyncLock = null;

    #[ORM\Column(name: 'data_hora_distribuicao', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraDistribuicao = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'EspecieTarefa', inversedBy: 'tarefas')]
    #[ORM\JoinColumn(name: 'especie_tarefa_id', referencedColumnName: 'id', nullable: false)]
    protected ?EspecieTarefa $especieTarefa = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'usuario_responsavel_id', referencedColumnName: 'id', nullable: false)]
    protected Usuario $usuarioResponsavel;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_responsavel_id', referencedColumnName: 'id', nullable: false)]
    protected ?Setor $setorResponsavel = null;

    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'usuario_concl_prazo_id', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $usuarioConclusaoPrazo = null;

    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_origem_id', referencedColumnName: 'id', nullable: true)]
    protected ?Setor $setorOrigem = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'redistribuida', type: 'boolean', nullable: false)]
    protected bool $redistribuida = false;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'distribuicao_automatica', type: 'boolean', nullable: false)]
    protected bool $distribuicaoAutomatica = false;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'livre_balanceamento', type: 'boolean', nullable: false)]
    protected bool $livreBalanceamento = false;

    #[ORM\Column(name: 'auditoria_distribuicao', type: 'text', nullable: true)]
    protected ?string $auditoriaDistribuicao = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'tipo_distribuicao', type: 'integer', nullable: false)]
    protected int $tipoDistribuicao = 0;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'distribuicao_estagiario_automatica', type: 'boolean', nullable: false)]
    protected bool $distribuicaoEstagiarioAutomatica = false;

    /**
     * @var Collection<Atividade>|ArrayCollection<Atividade>
     */
    #[ORM\OneToMany(mappedBy: 'tarefa', targetEntity: 'Atividade', cascade: ['all'])]
    #[ORM\OrderBy(
        [
            'criadoEm' => 'ASC',
        ]
    )]
    protected Collection|ArrayCollection $atividades;

    /**
     * @var Collection<Distribuicao>|ArrayCollection<Distribuicao>
     */
    #[ORM\OneToMany(mappedBy: 'tarefa', targetEntity: 'Distribuicao')]
    #[ORM\OrderBy(
        [
            'dataHoraDistribuicao' => 'ASC',
        ]
    )]
    protected Collection|ArrayCollection $distribuicoes;

    /**
     * @var Collection<Compartilhamento>|ArrayCollection<Compartilhamento>
     */
    #[ORM\OneToMany(mappedBy: 'tarefa', targetEntity: 'Compartilhamento', cascade: ['all'])]
    protected Collection|ArrayCollection $compartilhamentos;

    /**
     * @var Collection<Documento>|ArrayCollection<Documento>
     */
    #[ORM\OneToMany(mappedBy: 'tarefaOrigem', targetEntity: 'Documento')]
    #[ORM\OrderBy(
        [
            'criadoEm' => 'ASC',
            'id' => 'ASC',
        ]
    )]
    protected Collection|ArrayCollection $minutas;

    /**
     * @var Collection<DocumentoAvulso>|ArrayCollection<DocumentoAvulso>
     */
    #[ORM\OneToMany(mappedBy: 'tarefaOrigem', targetEntity: 'DocumentoAvulso')]
    protected Collection|ArrayCollection $documentosAvulsos;

    /**
     * @var Collection<Juntada>|ArrayCollection<Juntada>
     */
    #[ORM\OneToMany(mappedBy: 'tarefa', targetEntity: 'Juntada')]
    protected Collection|ArrayCollection $juntadas;

    /**
     * @var Collection<VinculacaoEtiqueta>|ArrayCollection<VinculacaoEtiqueta>
     */
    #[ORM\OneToMany(mappedBy: 'tarefa', targetEntity: 'VinculacaoEtiqueta')]
    protected Collection|ArrayCollection $vinculacoesEtiquetas;

    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Folder')]
    #[ORM\JoinColumn(name: 'folder_id', referencedColumnName: 'id', nullable: true)]
    protected ?Folder $folder = null;

    #[ORM\ManyToOne(targetEntity: 'Tarefa', inversedBy: 'tarefasRelacionadas')]
    #[ORM\JoinColumn(name: 'tarefa_origem_id', referencedColumnName: 'id', nullable: true)]
    protected ?Tarefa $tarefaOrigem = null;

    /**
     * @var Collection<Tarefa>|ArrayCollection<Tarefa>
     */
    #[ORM\OneToMany(mappedBy: 'tarefaOrigem', targetEntity: 'Tarefa')]
    protected Collection|ArrayCollection $tarefasRelacionadas;

    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'estagiario_responsavel_id', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $estagiarioResponsavel = null;



    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->atividades = new ArrayCollection();
        $this->distribuicoes = new ArrayCollection();
        $this->compartilhamentos = new ArrayCollection();
        $this->minutas = new ArrayCollection();
        $this->documentosAvulsos = new ArrayCollection();
        $this->juntadas = new ArrayCollection();
        $this->vinculacoesEtiquetas = new ArrayCollection();
        $this->tarefasRelacionadas = new ArrayCollection();
    }

    public function getProcesso(): ?Processo
    {
        return $this->processo;
    }

    public function setProcesso(?Processo $processo): self
    {
        $this->processo = $processo;

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

    public function getLocalEvento(): ?string
    {
        return $this->localEvento;
    }

    public function setLocalEvento(?string $localEvento): self
    {
        $this->localEvento = $localEvento;

        return $this;
    }

    public function getDataHoraLeitura(): ?DateTime
    {
        return $this->dataHoraLeitura;
    }

    public function setDataHoraLeitura(?DateTime $dataHoraLeitura): self
    {
        $this->dataHoraLeitura = $dataHoraLeitura;

        return $this;
    }

    public function getDataHoraDisponibilizacao(): ?DateTime
    {
        return $this->dataHoraDisponibilizacao;
    }

    public function setDataHoraDisponibilizacao(?DateTime $dataHoraDisponibilizacao): void
    {
        $this->dataHoraDisponibilizacao = $dataHoraDisponibilizacao;
    }

    public function getDataHoraDistribuicao(): ?DateTime
    {
        return $this->dataHoraDistribuicao;
    }

    public function setDataHoraDistribuicao(?DateTime $dataHoraDistribuicao): self
    {
        $this->dataHoraDistribuicao = $dataHoraDistribuicao;

        return $this;
    }

    public function getPostIt(): ?string
    {
        return $this->postIt;
    }

    public function getFolder(): ?Folder
    {
        return $this->folder;
    }

    public function setFolder(?Folder $folder): self
    {
        $this->folder = $folder;

        return $this;
    }

    public function setPostIt(?string $postIt): self
    {
        $this->postIt = $postIt;

        return $this;
    }

    public function getUrgente(): bool
    {
        return $this->urgente;
    }

    public function setUrgente(bool $urgente): self
    {
        $this->urgente = $urgente;

        return $this;
    }

    public function getDataHoraInicioPrazo(): ?DateTime
    {
        return $this->dataHoraInicioPrazo;
    }

    public function setDataHoraInicioPrazo(?DateTime $dataHoraInicioPrazo): self
    {
        $this->dataHoraInicioPrazo = $dataHoraInicioPrazo;

        return $this;
    }

    public function getDataHoraFinalPrazo(): ?DateTime
    {
        return $this->dataHoraFinalPrazo;
    }

    public function setDataHoraFinalPrazo(?DateTime $dataHoraFinalPrazo): self
    {
        $this->dataHoraFinalPrazo = $dataHoraFinalPrazo;

        return $this;
    }

    public function getDataHoraConclusaoPrazo(): ?DateTime
    {
        return $this->dataHoraConclusaoPrazo;
    }

    public function setDataHoraConclusaoPrazo(?DateTime $dataHoraConclusaoPrazo): self
    {
        $this->dataHoraConclusaoPrazo = $dataHoraConclusaoPrazo;

        return $this;
    }

    public function getDataHoraAsyncLock(): ?DateTime
    {
        return $this->dataHoraAsyncLock;
    }

    public function setDataHoraAsyncLock(?DateTime $dataHoraAsyncLock): self
    {
        $this->dataHoraAsyncLock = $dataHoraAsyncLock;

        return $this;
    }

    public function getEspecieTarefa(): ?EspecieTarefa
    {
        return $this->especieTarefa;
    }

    public function setEspecieTarefa(?EspecieTarefa $especieTarefa): self
    {
        $this->especieTarefa = $especieTarefa;

        return $this;
    }

    public function getUsuarioResponsavel(): Usuario
    {
        return $this->usuarioResponsavel;
    }

    public function setUsuarioResponsavel(Usuario $usuarioResponsavel): self
    {
        $this->usuarioResponsavel = $usuarioResponsavel;

        return $this;
    }

    public function getSetorResponsavel(): ?Setor
    {
        return $this->setorResponsavel;
    }

    public function setSetorResponsavel(?Setor $setorResponsavel): self
    {
        $this->setorResponsavel = $setorResponsavel;

        return $this;
    }

    public function getUsuarioConclusaoPrazo(): ?Usuario
    {
        return $this->usuarioConclusaoPrazo;
    }

    public function setUsuarioConclusaoPrazo(?Usuario $usuarioConclusaoPrazo): self
    {
        $this->usuarioConclusaoPrazo = $usuarioConclusaoPrazo;

        return $this;
    }

    public function getEstagiarioResponsavel(): ?Usuario
    {
        return $this->estagiarioResponsavel;
    }

    public function setEstagiarioResponsavel(?Usuario $estagiarioResponsavel): self
    {
        $this->estagiarioResponsavel = $estagiarioResponsavel;

        return $this;
    }



    public function getSetorOrigem(): ?Setor
    {
        return $this->setorOrigem;
    }

    public function setSetorOrigem(?Setor $setorOrigem): self
    {
        $this->setorOrigem = $setorOrigem;

        return $this;
    }

    public function getRedistribuida(): bool
    {
        return $this->redistribuida;
    }

    public function setRedistribuida(bool $redistribuida): self
    {
        $this->redistribuida = $redistribuida;

        return $this;
    }

    public function getDistribuicaoAutomatica(): bool
    {
        return $this->distribuicaoAutomatica;
    }

    public function setDistribuicaoAutomatica(bool $distribuicaoAutomatica): self
    {
        $this->distribuicaoAutomatica = $distribuicaoAutomatica;

        return $this;
    }

    public function getLivreBalanceamento(): bool
    {
        return $this->livreBalanceamento;
    }

    public function setLivreBalanceamento(bool $livreBalanceamento): self
    {
        $this->livreBalanceamento = $livreBalanceamento;

        return $this;
    }

    public function getAuditoriaDistribuicao(): ?string
    {
        return $this->auditoriaDistribuicao;
    }

    public function setAuditoriaDistribuicao(?string $auditoriaDistribuicao): self
    {
        $this->auditoriaDistribuicao = $auditoriaDistribuicao;

        return $this;
    }

    public function getTipoDistribuicao(): int
    {
        return $this->tipoDistribuicao;
    }

    public function setTipoDistribuicao(int $tipoDistribuicao): self
    {
        $this->tipoDistribuicao = $tipoDistribuicao;

        return $this;
    }

    public function getDistribuicaoEstagiarioAutomatica(): bool
    {
        return $this->distribuicaoEstagiarioAutomatica;
    }

    public function setDistribuicaoEstagiarioAutomatica(bool $distribuicaoEstagiarioAutomatica): self
    {
        $this->distribuicaoEstagiarioAutomatica = $distribuicaoEstagiarioAutomatica;

        return $this;
    }

    public function addAtividade(Atividade $atividade): self
    {
        if (!$this->atividades->contains($atividade)) {
            $this->atividades[] = $atividade;
            $atividade->setTarefa($this);
        }

        return $this;
    }

    public function removeAtividade(Atividade $atividade): self
    {
        if ($this->atividades->contains($atividade)) {
            $this->atividades->removeElement($atividade);
        }

        return $this;
    }

    public function getAtividades(): Collection|ArrayCollection
    {
        return $this->atividades;
    }

    public function addCompartilhamento(Compartilhamento $compartilhamento): self
    {
        if (!$this->compartilhamentos->contains($compartilhamento)) {
            $this->compartilhamentos[] = $compartilhamento;
            $compartilhamento->setTarefa($this);
        }

        return $this;
    }

    public function removeCompartilhamento(Compartilhamento $compartilhamento): self
    {
        if ($this->compartilhamentos->contains($compartilhamento)) {
            $this->compartilhamentos->removeElement($compartilhamento);
        }

        return $this;
    }

    public function getCompartilhamentos(): Collection|ArrayCollection
    {
        return $this->compartilhamentos;
    }

    public function addMinuta(Documento $minuta): self
    {
        if (!$this->minutas->contains($minuta)) {
            $this->minutas[] = $minuta;
            $minuta->setTarefaOrigem($this);
        }

        return $this;
    }

    public function removeMinuta(Documento $minuta): self
    {
        if ($this->minutas->contains($minuta)) {
            $this->minutas->removeElement($minuta);
        }

        return $this;
    }

    public function getMinutas(): Collection|ArrayCollection
    {
        return $this->minutas;
    }

    public function addDocumentoAvulso(DocumentoAvulso $documentoAvulso): self
    {
        if (!$this->documentosAvulsos->contains($documentoAvulso)) {
            $this->documentosAvulsos[] = $documentoAvulso;
            $documentoAvulso->setTarefaOrigem($this);
        }

        return $this;
    }

    public function removeDocumentoAvulso(DocumentoAvulso $documentoAvulso): self
    {
        if ($this->documentosAvulsos->contains($documentoAvulso)) {
            $this->documentosAvulsos->removeElement($documentoAvulso);
        }

        return $this;
    }

    public function getDocumentosAvulsos(): Collection|ArrayCollection
    {
        return $this->documentosAvulsos;
    }

    public function addDistribuicao(Distribuicao $distribuicao): self
    {
        if (!$this->distribuicoes->contains($distribuicao)) {
            $this->distribuicoes[] = $distribuicao;
            $distribuicao->setTarefa($this);
        }

        return $this;
    }

    public function removeDistribuicao(Distribuicao $distribuicao): self
    {
        if ($this->distribuicoes->contains($distribuicao)) {
            $this->distribuicoes->removeElement($distribuicao);
        }

        return $this;
    }

    public function getDistribuicoes(): Collection|ArrayCollection
    {
        return $this->distribuicoes;
    }

    public function addJuntada(Juntada $juntada): self
    {
        if (!$this->juntadas->contains($juntada)) {
            $this->juntadas[] = $juntada;
            $juntada->setTarefa($this);
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

    public function getVinculacoesEtiquetas(): Collection|ArrayCollection
    {
        return $this->vinculacoesEtiquetas;
    }

    public function addVinculacaoEtiqueta(VinculacaoEtiqueta $vinculacaoEtiqueta): self
    {
        if (!$this->vinculacoesEtiquetas->contains($vinculacaoEtiqueta)) {
            $this->vinculacoesEtiquetas->add($vinculacaoEtiqueta);
            $vinculacaoEtiqueta->setTarefa($this);
        }

        return $this;
    }

    public function removeVinculacaoEtiqueta(VinculacaoEtiqueta $vinculacaoEtiqueta): self
    {
        if ($this->vinculacoesEtiquetas->contains($vinculacaoEtiqueta)) {
            $this->vinculacoesEtiquetas->removeElement($vinculacaoEtiqueta);
        }

        return $this;
    }

    public function getAtividadeAprovacao(): ?Atividade
    {
        return $this->atividadeAprovacao;
    }

    public function setAtividadeAprovacao(?Atividade $atividadeAprovacao): self
    {
        $this->atividadeAprovacao = $atividadeAprovacao;

        $atividadeAprovacao->setTarefaAprovacao($this);

        return $this;
    }

    public function getVinculacaoWorkflow(): ?VinculacaoWorkflow
    {
        return $this->vinculacaoWorkflow;
    }

    public function setVinculacaoWorkflow(?VinculacaoWorkflow $vinculacaoWorkflow): self
    {
        $this->vinculacaoWorkflow = $vinculacaoWorkflow;

        return $this;
    }

    public function getSolicitacaoAutomatizadaAnalise(): ?SolicitacaoAutomatizada
    {
        return $this->solicitacaoAutomatizadaAnalise;
    }

    public function setSolicitacaoAutomatizadaAnalise(?SolicitacaoAutomatizada $solicitacaoAutomatizadaAnalise): self
    {
        $this->solicitacaoAutomatizadaAnalise = $solicitacaoAutomatizadaAnalise;

        return $this;
    }

    public function getSolicitacaoAutomatizadaDadosCumprimento(): ?SolicitacaoAutomatizada
    {
        return $this->solicitacaoAutomatizadaDadosCumprimento;
    }

    public function setSolicitacaoAutomatizadaDadosCumprimento(
        ?SolicitacaoAutomatizada $solicitacaoAutomatizadaDadosCumprimento
    ): Tarefa {
        $this->solicitacaoAutomatizadaDadosCumprimento = $solicitacaoAutomatizadaDadosCumprimento;

        return $this;
    }

    public function getTarefaOrigem(): ?Tarefa
    {
        return $this->tarefaOrigem;
    }

    public function setTarefaOrigem(?Tarefa $tarefaOrigem): self
    {
        $this->tarefaOrigem = $tarefaOrigem;

        return $this;
    }

    public function getTarefasRelacionadas(): Collection|ArrayCollection
    {
        return $this->tarefasRelacionadas;
    }

    public function addTarefaRelacionada(Tarefa $tarefaRelacionada): self
    {
        if (!$this->tarefasRelacionadas->contains($tarefaRelacionada)) {
            $this->tarefasRelacionadas[] = $tarefaRelacionada;
            $tarefaRelacionada->setTarefaOrigem($this);
        }

        return $this;
    }

    public function removeTarefaRelacionada(Tarefa $tarefaRelacionada): self
    {
        if ($this->tarefasRelacionadas->contains($tarefaRelacionada)) {
            $this->tarefasRelacionadas->removeElement($tarefaRelacionada);
            $tarefaRelacionada->setTarefaOrigem(null);
        }

        return $this;
    }

}
