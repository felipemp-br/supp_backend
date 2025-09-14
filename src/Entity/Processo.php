<?php

declare(strict_types=1);
/**
 * /src/Entity/Processo.php.
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
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use SuppCore\DisciplinarBackend\Entity\Traits\ProcessoDisciplinar;
use SuppCore\JudicialBackend\Entity\Traits\VinculacaoProcessoJudicialProcesso;
/**
 * Class Processo.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[UniqueEntity(fields: ['NUP'], message: 'NUP já está em utilização!')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_processo')]
class Processo implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Id;
    use Uuid;

    use ProcessoDisciplinar;
    use VinculacaoProcessoJudicialProcesso;

    // unidade arquivistica
    public const UA_PROCESSO = 1;
    public const UA_DOCUMENTO_AVULSO = 2;
    public const UA_DOSSIE = 3;

    // tipo protocolo
    public const TP_NOVO = 1;
    public const TP_INFORMADO = 2;
    public const TP_PENDENTE = 3;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'unidade_arquivistica', type: 'integer', nullable: false)]
    protected ?int $unidadeArquivistica = self::UA_PROCESSO;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'tipo_protocolo', type: 'integer', nullable: false)]
    protected ?int $tipoProtocolo = self::TP_NOVO;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'valor_economico', type: 'float', nullable: true)]
    protected ?float $valorEconomico = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'sem_valor_economico', type: 'boolean', nullable: false)]
    protected bool $semValorEconomico = false;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'protocolo_eletronico', type: 'boolean', nullable: false)]
    protected ?bool $protocoloEletronico = false;

    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'string', unique: true, nullable: false)]
    protected string $NUP;

    #[ORM\ManyToOne(targetEntity: 'Processo')]
    #[ORM\JoinColumn(name: 'processo_origem_id', referencedColumnName: 'id', nullable: true)]
    protected ?Processo $processoOrigem = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'EspecieProcesso')]
    #[ORM\JoinColumn(name: 'especie_processo_id', referencedColumnName: 'id', nullable: false)]
    protected EspecieProcesso $especieProcesso;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'visibilidade_externa', type: 'boolean', nullable: false)]
    protected bool $visibilidadeExterna = false;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'data_hora_abertura', type: 'datetime', nullable: false)]
    protected DateTime $dataHoraAbertura;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'data_hora_encerramento', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraEncerramento = null;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'data_hora_prox_transicao', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraProximaTransicao = null;

    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'string', nullable: false)]
    protected string $titulo;

    #[Gedmo\Versioned]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'lembrete_arquivista', type: 'string', nullable: true)]
    protected ?string $lembreteArquivista = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'outro_numero', type: 'string', nullable: true)]
    protected ?string $outroNumero = null;

    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[Gedmo\Versioned]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[ORM\Column(name: 'chave_acesso', type: 'string', nullable: false)]
    protected string $chaveAcesso;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'data_hora_indexacao', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraIndexacao = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Gedmo\Versioned]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $descricao = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeMeio')]
    #[ORM\JoinColumn(name: 'mod_meio_id', referencedColumnName: 'id', nullable: false)]
    protected ModalidadeMeio $modalidadeMeio;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeFase')]
    #[ORM\JoinColumn(name: 'mod_fase_id', referencedColumnName: 'id', nullable: false)]
    protected ModalidadeFase $modalidadeFase;

    #[Gedmo\Versioned]
    #[ORM\OneToOne(inversedBy: 'processoDestino', targetEntity: 'DocumentoAvulso')]
    #[ORM\JoinColumn(name: 'doc_avulso_origem_id', referencedColumnName: 'id', nullable: true)]
    protected ?DocumentoAvulso $documentoAvulsoOrigem = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Classificacao')]
    #[ORM\JoinColumn(name: 'classificacao_id', referencedColumnName: 'id', nullable: false)]
    protected Classificacao $classificacao;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Pessoa')]
    #[ORM\JoinColumn(name: 'pessoa_procedencia_id', referencedColumnName: 'id', nullable: false)]
    protected ?Pessoa $procedencia = null;

    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Localizador')]
    #[ORM\JoinColumn(name: 'localizador_id', referencedColumnName: 'id', nullable: true)]
    protected ?Localizador $localizador = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_atual_id', referencedColumnName: 'id', nullable: false)]
    protected Setor $setorAtual;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_inicial_id', referencedColumnName: 'id', nullable: false)]
    protected Setor $setorInicial;

    #[ORM\ManyToOne(targetEntity: 'OrigemDados', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'origem_dados_id', referencedColumnName: 'id', nullable: true)]
    protected ?OrigemDados $origemDados = null;

    /**
     * @var Collection<Volume>|ArrayCollection<Volume>
     */
    #[ORM\OneToMany(mappedBy: 'processo', targetEntity: 'Volume', cascade: ['all'])]
    #[ORM\OrderBy(
        [
            'criadoEm' => 'ASC',
        ]
    )]
    protected Collection|ArrayCollection $volumes;

    /**
     * @var Collection<Interessado>|ArrayCollection<Interessado>
     */
    #[ORM\OneToMany(mappedBy: 'processo', targetEntity: 'Interessado', cascade: ['all'])]
    protected Collection|ArrayCollection $interessados;

    /**
     * @var Collection<Assunto>|ArrayCollection<Assunto>
     */
    #[ORM\OneToMany(mappedBy: 'processo', targetEntity: 'Assunto', cascade: ['all'])]
    #[ORM\OrderBy(
        [
            'principal' => 'DESC',
        ]
    )]
    protected Collection|ArrayCollection $assuntos;

    /**
     * @var Collection<VinculacaoProcesso>|ArrayCollection<VinculacaoProcesso>
     */
    #[ORM\OneToMany(mappedBy: 'processo', targetEntity: 'VinculacaoProcesso', cascade: ['all'])]
    protected Collection|ArrayCollection $vinculacoesProcessos;

    /**
     * @var Collection<Transicao>|ArrayCollection<Transicao>
     */
    #[ORM\OneToMany(mappedBy: 'processo', targetEntity: 'Transicao', cascade: ['all'])]
    protected Collection|ArrayCollection $transicoes;

    /**
     * @var Collection<Sigilo>|ArrayCollection<Sigilo>
     */
    #[ORM\OneToMany(mappedBy: 'processo', targetEntity: 'Sigilo', cascade: ['all'])]
    protected Collection|ArrayCollection $sigilos;

    /**
     * @var Collection<Relevancia>|ArrayCollection<Relevancia>
     */
    #[ORM\OneToMany(mappedBy: 'processo', targetEntity: 'Relevancia', cascade: ['all'])]
    protected Collection|ArrayCollection $relevancias;

    /**
     * @var Collection<Tramitacao>|ArrayCollection<Tramitacao>
     */
    #[ORM\OneToMany(mappedBy: 'processo', targetEntity: 'Tramitacao', cascade: ['all'])]
    protected Collection|ArrayCollection $tramitacoes;

    /**
     * @var ArrayCollection<DocumentoAvulso>|Collection<DocumentoAvulso>
     */
    #[ORM\OneToMany(mappedBy: 'processo', targetEntity: 'DocumentoAvulso', cascade: ['all'])]
    protected Collection|ArrayCollection $documentosAvulsos;

    /**
     * @var Collection<Historico>|ArrayCollection<Historico>
     */
    #[ORM\OneToMany(mappedBy: 'processo', targetEntity: 'Historico', cascade: ['all'])]
    protected Collection|ArrayCollection $historicos;

    /**
     * @var Collection<Tarefa>|ArrayCollection<Tarefa>
     */
    #[ORM\OneToMany(mappedBy: 'processo', targetEntity: 'Tarefa', cascade: ['all'])]
    protected Collection|ArrayCollection $tarefas;

    /**
     * @var Collection<Lembrete>|ArrayCollection<Lembrete>
     */
    #[ORM\OneToMany(mappedBy: 'processo', targetEntity: 'Lembrete', cascade: ['all'])]
    protected Collection|ArrayCollection $lembretes;

    /**
     * @var Collection<Compartilhamento>|ArrayCollection<Compartilhamento>
     */
    #[ORM\OneToMany(mappedBy: 'processo', targetEntity: 'Compartilhamento', cascade: ['all'])]
    protected Collection|ArrayCollection $compartilhamentos;

    /**
     * @var Collection<VinculacaoEtiqueta>|ArrayCollection<VinculacaoEtiqueta>
     */
    #[ORM\OneToMany(mappedBy: 'processo', targetEntity: 'VinculacaoEtiqueta')]
    protected Collection|ArrayCollection $vinculacoesEtiquetas;

    /**
     * @var Collection|ArrayCollection<VinculacaoTese>
     */
    #[ORM\OneToMany(mappedBy: 'processo', targetEntity: 'VinculacaoTese')]
    protected Collection|ArrayCollection $vinculacoesTeses;

    /**
     * @var bool|null
     *
     * Propriedade para que o ACL inicial do processo seja restrito ao usuário que está criando
     */
    protected ?bool $acessoRestrito = false;

    #[ORM\Column(name: 'data_hora_desarquivamento', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraDesarquivamento = null;

    #[ORM\ManyToOne(targetEntity: 'ConfiguracaoNup')]
    #[ORM\JoinColumn(name: 'configuracao_nup_id', referencedColumnName: 'id', nullable: true)]
    protected ?ConfiguracaoNup $configuracaoNup = null;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'nup_invalido', type: 'boolean', nullable: true)]
    protected ?bool $nupInvalido = false;

    protected ?bool $alterarChave = false;

    /**
     * @var Collection<FundamentacaoRestricao>|ArrayCollection<FundamentacaoRestricao>
     */
    #[ORM\OneToMany(mappedBy: 'processo', targetEntity: 'FundamentacaoRestricao', cascade: ['all'])]
    protected Collection|ArrayCollection $fundamentacoesRestricao;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->volumes = new ArrayCollection();
        $this->interessados = new ArrayCollection();
        $this->historicos = new ArrayCollection();
        $this->vinculacoesProcessos = new ArrayCollection();
        $this->transicoes = new ArrayCollection();
        $this->tramitacoes = new ArrayCollection();
        $this->documentosAvulsos = new ArrayCollection();
        $this->tarefas = new ArrayCollection();
        $this->lembretes = new ArrayCollection();
        $this->sigilos = new ArrayCollection();
        $this->relevancias = new ArrayCollection();
        $this->assuntos = new ArrayCollection();
        $this->compartilhamentos = new ArrayCollection();
        $this->vinculacoesEtiquetas = new ArrayCollection();
        $this->vinculacoesTeses = new ArrayCollection();
        $this->fundamentacoesRestricao = new ArrayCollection();
    }

    public function getValorEconomico(): ?float
    {
        return $this->valorEconomico;
    }

    public function setValorEconomico(?float $valorEconomico): self
    {
        $this->valorEconomico = $valorEconomico;

        return $this;
    }

    public function getSemValorEconomico(): bool
    {
        return $this->semValorEconomico;
    }

    public function setSemValorEconomico(bool $semValorEconomico): self
    {
        $this->semValorEconomico = $semValorEconomico;

        return $this;
    }

    public function getProtocoloEletronico(): bool
    {
        return $this->protocoloEletronico;
    }

    public function setProtocoloEletronico(bool $protocoloEletronico): self
    {
        $this->protocoloEletronico = $protocoloEletronico;

        return $this;
    }

    public function getNUP(): string
    {
        return $this->NUP;
    }

    public function setNUP(string $NUP): self
    {
        $this->NUP = $NUP;

        return $this;
    }

    public function getEspecieProcesso(): EspecieProcesso
    {
        return $this->especieProcesso;
    }

    public function setEspecieProcesso(EspecieProcesso $especieProcesso): self
    {
        $this->especieProcesso = $especieProcesso;

        return $this;
    }

    public function getVisibilidadeExterna(): bool
    {
        return $this->visibilidadeExterna;
    }

    public function setVisibilidadeExterna(bool $visibilidadeExterna): self
    {
        $this->visibilidadeExterna = $visibilidadeExterna;

        return $this;
    }

    public function getDataHoraAbertura(): DateTime
    {
        return $this->dataHoraAbertura;
    }

    public function setDataHoraAbertura(DateTime $dataHoraAbertura): self
    {
        $this->dataHoraAbertura = $dataHoraAbertura;

        return $this;
    }

    public function getDataHoraEncerramento(): ?DateTime
    {
        return $this->dataHoraEncerramento;
    }

    public function setDataHoraEncerramento(?DateTime $dataHoraEncerramento): self
    {
        $this->dataHoraEncerramento = $dataHoraEncerramento;

        return $this;
    }

    public function getDataHoraProximaTransicao(): ?DateTime
    {
        return $this->dataHoraProximaTransicao;
    }

    public function setDataHoraProximaTransicao(?DateTime $dataHoraProximaTransicao): self
    {
        $this->dataHoraProximaTransicao = $dataHoraProximaTransicao;

        return $this;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getOutroNumero(): ?string
    {
        return $this->outroNumero;
    }

    public function setOutroNumero(?string $outroNumero): self
    {
        $this->outroNumero = $outroNumero;

        return $this;
    }

    public function getChaveAcesso(): string
    {
        return $this->chaveAcesso;
    }

    public function setChaveAcesso(string $chaveAcesso): self
    {
        $this->chaveAcesso = $chaveAcesso;

        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getUnidadeArquivistica(): ?int
    {
        return $this->unidadeArquivistica;
    }

    public function setUnidadeArquivistica(?int $unidadeArquivistica): void
    {
        $this->unidadeArquivistica = $unidadeArquivistica;
    }

    public function getTipoProtocolo(): ?int
    {
        return $this->tipoProtocolo;
    }

    public function setTipoProtocolo(?int $tipoProtocolo): void
    {
        $this->tipoProtocolo = $tipoProtocolo;
    }

    public function getModalidadeMeio(): ModalidadeMeio
    {
        return $this->modalidadeMeio;
    }

    public function setModalidadeMeio(ModalidadeMeio $modalidadeMeio): self
    {
        $this->modalidadeMeio = $modalidadeMeio;

        return $this;
    }

    public function getModalidadeFase(): ModalidadeFase
    {
        return $this->modalidadeFase;
    }

    public function setModalidadeFase(ModalidadeFase $modalidadeFase): self
    {
        $this->modalidadeFase = $modalidadeFase;

        return $this;
    }

    public function getDocumentoAvulsoOrigem(): ?DocumentoAvulso
    {
        return $this->documentoAvulsoOrigem;
    }

    public function setDocumentoAvulsoOrigem(?DocumentoAvulso $documentoAvulsoOrigem): self
    {
        $this->documentoAvulsoOrigem = $documentoAvulsoOrigem;

        return $this;
    }

    public function getClassificacao(): Classificacao
    {
        return $this->classificacao;
    }

    public function setClassificacao(Classificacao $classificacao): self
    {
        $this->classificacao = $classificacao;

        return $this;
    }

    public function getProcedencia(): ?Pessoa
    {
        return $this->procedencia;
    }

    public function setProcedencia(?Pessoa $procedencia): self
    {
        $this->procedencia = $procedencia;

        return $this;
    }

    public function getLocalizador(): ?Localizador
    {
        return $this->localizador;
    }

    public function setLocalizador(?Localizador $localizador): self
    {
        $this->localizador = $localizador;

        return $this;
    }

    public function getLembreteArquivista(): ?string
    {
        return $this->lembreteArquivista;
    }

    public function setLembreteArquivista(?string $lembreteArquivista): void
    {
        $this->lembreteArquivista = $lembreteArquivista;
    }

    public function getSetorAtual(): Setor
    {
        return $this->setorAtual;
    }

    public function setSetorAtual(Setor $setorAtual): self
    {
        $this->setorAtual = $setorAtual;

        return $this;
    }

    public function getSetorInicial(): ?Setor
    {
        return $this->setorInicial;
    }

    public function setSetorInicial(Setor $setorInicial): self
    {
        $this->setorInicial = $setorInicial;

        return $this;
    }

    public function getOrigemDados(): ?OrigemDados
    {
        return $this->origemDados;
    }

    public function setOrigemDados(?OrigemDados $origemDados): self
    {
        $this->origemDados = $origemDados;

        return $this;
    }

    public function addVolume(Volume $volume): self
    {
        if (!$this->volumes->contains($volume)) {
            $this->volumes[] = $volume;
            $volume->setProcesso($this);
        }

        return $this;
    }

    public function removeVolume(Volume $volume): self
    {
        if ($this->volumes->contains($volume)) {
            $this->volumes->removeElement($volume);
        }

        return $this;
    }

    public function getVolumes(): Collection|ArrayCollection
    {
        return $this->volumes;
    }

    public function addInteressado(Interessado $interessado): self
    {
        if (!$this->interessados->contains($interessado)) {
            $this->interessados[] = $interessado;
            $interessado->setProcesso($this);
        }

        return $this;
    }

    public function removeInteressado(Interessado $interessado): self
    {
        if ($this->interessados->contains($interessado)) {
            $this->interessados->removeElement($interessado);
        }

        return $this;
    }

    public function getInteressados(): Collection|ArrayCollection
    {
        return $this->interessados;
    }

    public function addHistorico(Historico $historico): self
    {
        if (!$this->historicos->contains($historico)) {
            $this->historicos[] = $historico;
            $historico->setProcesso($this);
        }

        return $this;
    }

    public function removeHistorico(Historico $historico): self
    {
        if ($this->historicos->contains($historico)) {
            $this->historicos->removeElement($historico);
        }

        return $this;
    }

    public function getHistoricos(): Collection|ArrayCollection
    {
        return $this->historicos;
    }

    public function addTarefa(Tarefa $tarefa): self
    {
        if (!$this->tarefas->contains($tarefa)) {
            $this->tarefas[] = $tarefa;
            $tarefa->setProcesso($this);
        }

        return $this;
    }

    public function removeTarefa(Tarefa $tarefa): self
    {
        if ($this->tarefas->contains($tarefa)) {
            $this->tarefas->removeElement($tarefa);
        }

        return $this;
    }

    public function getTarefas(): Collection|ArrayCollection
    {
        return $this->tarefas;
    }

    public function addLembrete(Lembrete $lembrete): self
    {
        if (!$this->lembretes->contains($lembrete)) {
            $this->lembretes[] = $lembrete;
            $lembrete->setProcesso($this);
        }

        return $this;
    }

    public function removeLembrete(Lembrete $lembrete): self
    {
        if ($this->lembretes->contains($lembrete)) {
            $this->lembretes->removeElement($lembrete);
        }

        return $this;
    }

    public function getLembretes(): Collection|ArrayCollection
    {
        return $this->lembretes;
    }

    public function addSigilo(Sigilo $sigilo): self
    {
        if (!$this->sigilos->contains($sigilo)) {
            $this->sigilos[] = $sigilo;
            $sigilo->setProcesso($this);
        }

        return $this;
    }

    public function removeSigilo(Sigilo $sigilo): self
    {
        if ($this->sigilos->contains($sigilo)) {
            $this->sigilos->removeElement($sigilo);
        }

        return $this;
    }

    public function getSigilos(): Collection|ArrayCollection
    {
        return $this->sigilos;
    }

    public function addRelevancia(Relevancia $relevancia): self
    {
        if (!$this->relevancias->contains($relevancia)) {
            $this->relevancias[] = $relevancia;
            $relevancia->setProcesso($this);
        }

        return $this;
    }

    public function removeRelevancia(Relevancia $relevancia): self
    {
        if ($this->relevancias->contains($relevancia)) {
            $this->relevancias->removeElement($relevancia);
        }

        return $this;
    }

    public function getRelevancias(): Collection|ArrayCollection
    {
        return $this->relevancias;
    }

    public function addAssunto(Assunto $assunto): self
    {
        if (!$this->assuntos->contains($assunto)) {
            $this->assuntos[] = $assunto;
            $assunto->setProcesso($this);
        }

        return $this;
    }

    public function removeAssunto(Assunto $assunto): self
    {
        if ($this->assuntos->contains($assunto)) {
            $this->assuntos->removeElement($assunto);
        }

        return $this;
    }

    public function getAssuntos(): Collection|ArrayCollection
    {
        return $this->assuntos;
    }

    public function addCompartilhamento(Compartilhamento $compartilhamento): self
    {
        if (!$this->compartilhamentos->contains($compartilhamento)) {
            $this->compartilhamentos[] = $compartilhamento;
            $compartilhamento->setProcesso($this);
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

    public function addTransicao(Transicao $transicao): self
    {
        if (!$this->transicoes->contains($transicao)) {
            $this->transicoes[] = $transicao;
            $transicao->setProcesso($this);
        }

        return $this;
    }

    public function removeTransicao(Transicao $transicao): self
    {
        if ($this->transicoes->contains($transicao)) {
            $this->transicoes->removeElement($transicao);
        }

        return $this;
    }

    public function getTransicoes(): Collection|ArrayCollection
    {
        return $this->transicoes;
    }

    public function getDataHoraIndexacao(): ?DateTime
    {
        return $this->dataHoraIndexacao;
    }

    public function setDataHoraIndexacao(?DateTime $dataHoraIndexacao): self
    {
        $this->dataHoraIndexacao = $dataHoraIndexacao;

        return $this;
    }

    public function addTramitacao(Tramitacao $tramitacao): self
    {
        if (!$this->tramitacoes->contains($tramitacao)) {
            $this->tramitacoes[] = $tramitacao;
            $tramitacao->setProcesso($this);
        }

        return $this;
    }

    public function removeTramitacao(Tramitacao $tramitacao): self
    {
        if ($this->tramitacoes->contains($tramitacao)) {
            $this->tramitacoes->removeElement($tramitacao);
        }

        return $this;
    }

    public function getTramitacoes(): Collection|ArrayCollection
    {
        return $this->tramitacoes;
    }

    public function addDocumentoAvulso(DocumentoAvulso $documentoAvulso): self
    {
        if (!$this->documentosAvulsos->contains($documentoAvulso)) {
            $this->documentosAvulsos[] = $documentoAvulso;
            $documentoAvulso->setProcesso($this);
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

    public function addVinculacaoProcesso(VinculacaoProcesso $vinculacaoProcesso): self
    {
        if (!$this->vinculacoesProcessos->contains($vinculacaoProcesso)) {
            $this->vinculacoesProcessos[] = $vinculacaoProcesso;
            $vinculacaoProcesso->setProcesso($this);
        }

        return $this;
    }

    public function removeVinculacaoProcesso(VinculacaoProcesso $vinculacaoProcesso): self
    {
        if ($this->vinculacoesProcessos->contains($vinculacaoProcesso)) {
            $this->vinculacoesProcessos->removeElement($vinculacaoProcesso);
        }

        return $this;
    }

    public function getVinculacoesProcessos(): Collection|ArrayCollection
    {
        return $this->vinculacoesProcessos;
    }

    public function getVinculacoesEtiquetas(): Collection|ArrayCollection
    {
        return $this->vinculacoesEtiquetas;
    }

    public function addVinculacaoEtiqueta(VinculacaoEtiqueta $vinculacaoEtiqueta): self
    {
        if (!$this->vinculacoesEtiquetas->contains($vinculacaoEtiqueta)) {
            $this->vinculacoesEtiquetas->add($vinculacaoEtiqueta);
            $vinculacaoEtiqueta->setProcesso($this);
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

    public function getAcessoRestrito(): ?bool
    {
        return $this->acessoRestrito;
    }

    public function setAcessoRestrito(?bool $acessoRestrito): self
    {
        $this->acessoRestrito = $acessoRestrito;

        return $this;
    }

    public function getNupInvalido(): ?bool
    {
        return $this->nupInvalido;
    }

    public function setNupInvalido(?bool $nupInvalido): self
    {
        $this->nupInvalido = $nupInvalido;

        return $this;
    }

    public function getNUPFormatado(): ?string
    {
        if (self::UA_PROCESSO === $this->unidadeArquivistica
            || self::UA_DOCUMENTO_AVULSO === $this->unidadeArquivistica
        ) {
            if (17 === mb_strlen($this->NUP, 'UTF-8')) {
                return substr($this->NUP, 0, 5).'.'.
                    substr($this->NUP, 5, 6).'/'.
                    substr($this->NUP, 11, 4).'-'.
                    substr($this->NUP, 15, 2);
            }

            return substr($this->NUP, 0, 5).'.'.
                substr($this->NUP, 5, 6).'/'.
                substr($this->NUP, 11, 2).'-'.
                substr($this->NUP, 13, 2);
        }

        return 'NÃO PROTOCOLADO';
    }

    public function getProcessoOrigem(): ?self
    {
        return $this->processoOrigem;
    }

    public function setProcessoOrigem(?self $processoOrigem): self
    {
        $this->processoOrigem = $processoOrigem;

        return $this;
    }

    public function getDataHoraDesarquivamento(): ?DateTime
    {
        return $this->dataHoraDesarquivamento;
    }

    public function setDataHoraDesarquivamento(?DateTime $dataHoraDesarquivamento): self
    {
        $this->dataHoraDesarquivamento = $dataHoraDesarquivamento;

        return $this;
    }

    public function getConfiguracaoNup(): ?ConfiguracaoNup
    {
        return $this->configuracaoNup;
    }

    public function setConfiguracaoNup(?ConfiguracaoNup $configuracaoNup): self
    {
        $this->configuracaoNup = $configuracaoNup;

        return $this;
    }

    public function getAlterarChave(): ?bool
    {
        return $this->alterarChave;
    }

    public function setAlterarChave(?bool $alterarChave): self
    {
        $this->alterarChave = $alterarChave;

        return $this;
    }

    /**
     * @throws Exception
     */
    #[Assert\Callback]
    public function isNUPValid(ExecutionContextInterface $context)
    {
        if (self::UA_PROCESSO !== $this->unidadeArquivistica
            && self::UA_DOCUMENTO_AVULSO !== $this->unidadeArquivistica
        ) {
            return;
        }

        $digitos = str_replace(['-', '.', '/', '\\', ' '], '', $this->getNUP());

        $tamanho = mb_strlen($digitos, 'UTF-8');

        if ((17 !== $tamanho) && (15 !== $tamanho)) {
            $context->buildViolation('NUP inválido, deve conter 15 ou 17 dígitos!')
                ->atPath('NUP')
                ->addViolation();

            return;
        }

        $agora = new DateTime();

        if (17 === $tamanho) {
            $ano = (int) substr($digitos, 11, 4);
            $anoAtual = (int) $agora->format('Y');

            if ($ano > $anoAtual) {
                $context->buildViolation('NUP inválido, o ano não pode ser no futuro!')
                    ->atPath('NUP')
                    ->addViolation();
            }
        }

        // pega o digito verificador informado
        $dvTeste = substr($digitos, -2);

        for ($dv1 = 0, $i = ($tamanho - 3), $peso = 2; $i >= 0; $i--, $peso++) {
            $dv1 += $digitos[$i] * $peso;
        }
        if (($dv1 = 11 - (int) bcmod((string) $dv1, '11')) >= 10) {
            $dv1 -= 10;
        }

        // calculo de dv2 esperado
        $digitos .= $dv1;

        for ($dv2 = 0, $i = ($tamanho - 2), $peso = 2; $i >= 0; $i--, $peso++) {
            $dv2 += $digitos[$i] * $peso;
        }
        if (($dv2 = 11 - (int) bcmod((string) $dv2, '11')) >= 10) {
            $dv2 -= 10;
        }
        $dvEsperado = (string) $dv1.(string) $dv2;

        if ($dvTeste !== $dvEsperado) {
            $context->buildViolation(
                'NUP inválido, dígito verificador não confere! O correto seria '.$dvEsperado.'!'
            )
                ->atPath('NUP')
                ->addViolation();
        }
    }

    public function getVinculacoesTeses(): Collection
    {
        return $this->vinculacoesTeses;
    }

    public function addVinculacaoTese(VinculacaoTese $vinculacaoTese): self
    {
        if (!$this->vinculacoesTeses->contains($vinculacaoTese)) {
            $this->vinculacoesTeses[] = $vinculacaoTese;
            $vinculacaoTese->setProcesso($this);
        }

        return $this;
    }

    /**
     * @noinspection PhpUnused
     */
    public function removeVinculacaoTese(VinculacaoTese $vinculacaoTese): self
    {
        if ($this->vinculacoesTeses->contains($vinculacaoTese)) {
            $this->vinculacoesTeses->removeElement($vinculacaoTese);
        }

        return $this;
    }

    public function addFundamentacaoRestricao(FundamentacaoRestricao $fundamentacoesRestricao): self
    {
        if (!$this->fundamentacoesRestricao->contains($fundamentacoesRestricao)) {
            $this->fundamentacoesRestricao[] = $fundamentacoesRestricao;
            $fundamentacoesRestricao->setProcesso($this);
        }

        return $this;
    }

    public function removeFundamentacaoRestricao(FundamentacaoRestricao $fundamentacoesRestricao): self
    {
        if ($this->fundamentacoesRestricao->contains($fundamentacoesRestricao)) {
            $this->fundamentacoesRestricao->removeElement($fundamentacoesRestricao);
        }

        return $this;
    }

    public function getFundamentacoesRestricao(): Collection|ArrayCollection
    {
        return $this->fundamentacoesRestricao;
    }

}
