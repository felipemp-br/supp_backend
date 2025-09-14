<?php

declare(strict_types=1);
/**
 * /src/Entity/Documento.php.
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

/**
 * Class Documento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_documento')]
class Documento implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $numeroFolhas = 0;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'data_hora_producao', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraProducao = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Gedmo\Versioned]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'outro_numero', type: 'string', nullable: true)]
    protected ?string $outroNumero = null;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'sem_efeito', type: 'boolean', nullable: false)]
    protected bool $semEfeito = false;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'localizador_original', type: 'string', nullable: true)]
    protected ?string $localizadorOriginal = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Gedmo\Versioned]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'local_producao', type: 'string', nullable: true)]
    protected ?string $localProducao = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Gedmo\Versioned]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'autor', type: 'string', nullable: true)]
    protected ?string $autor = null;

    #[ORM\ManyToOne(targetEntity: 'Processo')]
    #[ORM\JoinColumn(name: 'processo_origem_id', referencedColumnName: 'id', nullable: true)]
    protected ?Processo $processoOrigem = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Gedmo\Versioned]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'redator', type: 'string', nullable: true)]
    protected ?string $redator = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Gedmo\Versioned]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'destinatario', type: 'string', nullable: true)]
    protected ?string $destinatario = null;

    #[ORM\ManyToOne(targetEntity: 'Pessoa')]
    #[Gedmo\Versioned]
    #[ORM\JoinColumn(name: 'pessoa_procedencia_id', referencedColumnName: 'id', nullable: true)]
    protected ?Pessoa $procedencia = null;

    #[ORM\OneToOne(inversedBy: 'documento', targetEntity: 'NumeroUnicoDocumento')]
    #[ORM\JoinColumn(name: 'numero_unico_documento_id', referencedColumnName: 'id', nullable: true)]
    protected ?NumeroUnicoDocumento $numeroUnicoDocumento = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'TipoDocumento')]
    #[ORM\JoinColumn(name: 'tipo_documento_id', referencedColumnName: 'id', nullable: false)]
    protected TipoDocumento $tipoDocumento;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Gedmo\Versioned]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'descricao_outros', type: 'string', nullable: true)]
    protected ?string $descricaoOutros = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Gedmo\Versioned]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'observacao', type: 'string', nullable: true)]
    protected ?string $observacao = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $copia = false;

    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_origem_id', referencedColumnName: 'id', nullable: true)]
    protected ?Setor $setorOrigem = null;

    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Tarefa', inversedBy: 'minutas')]
    #[ORM\JoinColumn(name: 'tarefa_origem_id', referencedColumnName: 'id', nullable: true)]
    protected ?Tarefa $tarefaOrigem = null;

    #[ORM\ManyToOne(targetEntity: 'Documento')]
    #[ORM\JoinColumn(name: 'documento_origem_id', referencedColumnName: 'id', nullable: true)]
    protected ?Documento $documentoOrigem = null;

    #[ORM\ManyToOne(targetEntity: 'OrigemDados', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'origem_dados_id', referencedColumnName: 'id', nullable: true)]
    protected ?OrigemDados $origemDados = null;

    #[ORM\OneToOne(mappedBy: 'documentoRemessa', targetEntity: 'DocumentoAvulso')]
    protected ?DocumentoAvulso $documentoAvulsoRemessa = null;

    #[ORM\OneToOne(mappedBy: 'documentoResposta', targetEntity: 'DocumentoAvulso')]
    protected ?DocumentoAvulso $documentoAvulsoResposta = null;

    #[ORM\OneToOne(mappedBy: 'documento', targetEntity: 'Modelo')]
    protected ?Modelo $modelo = null;

    #[ORM\OneToOne(mappedBy: 'documento', targetEntity: 'Template')]
    protected ?Template $template = null;

    #[ORM\OneToOne(mappedBy: 'documento', targetEntity: 'Repositorio')]
    protected ?Repositorio $repositorio = null;

    #[ORM\OneToOne(mappedBy: 'documento', targetEntity: 'DocumentoIAMetadata')]
    protected ?DocumentoIAMetadata $documentoIAMetadata = null;

    #[Gedmo\Versioned]
    #[ORM\OneToOne(inversedBy: 'documentoJuntadaAtual', targetEntity: 'Juntada')]
    #[ORM\JoinColumn(name: 'juntada_atual_id', referencedColumnName: 'id', nullable: true)]
    protected ?Juntada $juntadaAtual = null;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<Juntada>
     */
    #[ORM\OneToMany(mappedBy: 'documento', targetEntity: 'Juntada')]
    protected $juntadas;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<Sigilo>
     */
    #[ORM\OneToMany(mappedBy: 'documento', targetEntity: 'Sigilo', cascade: ['all'])]
    protected $sigilos;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<AreaTrabalho>
     */
    #[ORM\OneToMany(mappedBy: 'documento', targetEntity: 'AreaTrabalho')]
    protected $areasTrabalhos;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<VinculacaoDocumento>
     */
    #[ORM\OneToMany(mappedBy: 'documento', targetEntity: 'VinculacaoDocumento', cascade: ['all'])]
    #[ORM\OrderBy(
        [
            'criadoEm' => 'ASC',
            'id' => 'ASC',
        ]
    )]
    protected $vinculacoesDocumentos;

    #[ORM\OneToOne(mappedBy: 'documento', targetEntity: 'Relatorio')]
    protected ?Relatorio $relatorio = null;

    #[ORM\OneToMany(mappedBy: 'documentoVinculado', targetEntity: 'VinculacaoDocumento')]
    protected $vinculacaoDocumentoPrincipal;

    /**
     * @var Collection<DadosFormulario>|ArrayCollection<DadosFormulario>
     */
    #[ORM\OneToMany(mappedBy: 'documento', targetEntity: 'DadosFormulario', cascade: ['all'])]
    protected Collection|ArrayCollection $dadosFormularios;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<ComponenteDigital>
     */
    #[ORM\OneToMany(mappedBy: 'documento', targetEntity: 'ComponenteDigital', cascade: ['all'])]
    #[ORM\OrderBy(
        [
            'numeracaoSequencial' => 'ASC',
        ]
    )]
    protected $componentesDigitais;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<VinculacaoEtiqueta>
     */
    #[ORM\OneToMany(mappedBy: 'documento', targetEntity: 'VinculacaoEtiqueta')]
    protected $vinculacoesEtiquetas;

    #[ORM\ManyToOne(targetEntity: 'DocumentoAvulso')]
    #[ORM\JoinColumn(name: 'doc_avulso_compl_resposta_id', referencedColumnName: 'id', nullable: true)]
    protected ?DocumentoAvulso $documentoAvulsoComplementacaoResposta = null;

    #[ORM\Column(name: 'data_hora_validade', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraValidade = null;

    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'chave_acesso', type: 'string', nullable: true)]
    protected ?string $chaveAcesso = null;

    #[ORM\ManyToOne(targetEntity: 'ModalidadeCopia')]
    #[Gedmo\Versioned]
    #[ORM\JoinColumn(name: 'mod_copia_id', referencedColumnName: 'id', nullable: true)]
    protected ?ModalidadeCopia $modalidadeCopia = null;

    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'dependencia_software', type: 'string', nullable: true)]
    protected ?string $dependenciaSoftware = null;

    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'dependencia_hardware', type: 'string', nullable: true)]
    protected ?string $dependenciaHardware = null;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<VinculacaoDocumentoAssinaturaExterna>
     */
    #[ORM\OneToMany(mappedBy: 'documento', targetEntity: 'VinculacaoDocumentoAssinaturaExterna', cascade: ['all'])]
    #[ORM\OrderBy(
        [
            'criadoEm' => 'ASC',
            'id' => 'ASC',
        ]
    )]
    protected $vinculacoesDocumentosAssinaturasExternas;

    /**
     * @var bool|null
     *
     * Propriedade para que o ACL inicial do documento seja restrito caso necessário
     */
    protected ?bool $acessoRestrito = false;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->areasTrabalhos = new ArrayCollection();
        $this->componentesDigitais = new ArrayCollection();
        $this->juntadas = new ArrayCollection();
        $this->sigilos = new ArrayCollection();
        $this->vinculacoesDocumentos = new ArrayCollection();
        $this->vinculacaoDocumentoPrincipal = new ArrayCollection();
        $this->vinculacoesEtiquetas = new ArrayCollection();
        $this->dadosFormularios = new ArrayCollection();
    }

    public function getNumeroFolhas(): int
    {
        return $this->numeroFolhas;
    }

    public function setNumeroFolhas(int $numeroFolhas): self
    {
        $this->numeroFolhas = $numeroFolhas;

        return $this;
    }

    public function getDataHoraProducao(): ?DateTime
    {
        return $this->dataHoraProducao;
    }

    public function setDataHoraProducao(?DateTime $dataHoraProducao): self
    {
        $this->dataHoraProducao = $dataHoraProducao;

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

    public function getSemEfeito(): bool
    {
        return $this->semEfeito;
    }

    public function setSemEfeito(bool $semEfeito): self
    {
        $this->semEfeito = $semEfeito;

        return $this;
    }

    public function getLocalizadorOriginal(): ?string
    {
        return $this->localizadorOriginal;
    }

    public function setLocalizadorOriginal(?string $localizadorOriginal): self
    {
        $this->localizadorOriginal = $localizadorOriginal;

        return $this;
    }

    public function getLocalProducao(): ?string
    {
        return $this->localProducao;
    }

    public function setLocalProducao(?string $localProducao): self
    {
        $this->localProducao = $localProducao;

        return $this;
    }

    public function getAutor(): ?string
    {
        return $this->autor;
    }

    public function setAutor(?string $autor): self
    {
        $this->autor = $autor;

        return $this;
    }

    public function getProcessoOrigem(): ?Processo
    {
        return $this->processoOrigem;
    }

    public function setProcessoOrigem(?Processo $processoOrigem): self
    {
        $this->processoOrigem = $processoOrigem;

        return $this;
    }

    public function getRedator(): ?string
    {
        return $this->redator;
    }

    public function setRedator(?string $redator): self
    {
        $this->redator = $redator;

        return $this;
    }

    public function getDestinatario(): ?string
    {
        return $this->destinatario;
    }

    public function setDestinatario(?string $destinatario): self
    {
        $this->destinatario = $destinatario;

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

    public function getTipoDocumento(): TipoDocumento
    {
        return $this->tipoDocumento;
    }

    public function setTipoDocumento(TipoDocumento $tipoDocumento): self
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    public function getDescricaoOutros(): ?string
    {
        return $this->descricaoOutros;
    }

    public function setDescricaoOutros(?string $descricaoOutros): self
    {
        $this->descricaoOutros = $descricaoOutros;

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

    public function getCopia(): bool
    {
        return $this->copia;
    }

    public function setCopia(bool $copia): self
    {
        $this->copia = $copia;

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

    public function getTarefaOrigem(): ?Tarefa
    {
        return $this->tarefaOrigem;
    }

    public function setTarefaOrigem(?Tarefa $tarefaOrigem): self
    {
        $this->tarefaOrigem = $tarefaOrigem;

        return $this;
    }

    public function getDocumentoOrigem(): ?self
    {
        return $this->documentoOrigem;
    }

    public function setDocumentoOrigem(?self $documentoOrigem): self
    {
        $this->documentoOrigem = $documentoOrigem;

        return $this;
    }

    public function getJuntadaAtual(): ?Juntada
    {
        return $this->juntadaAtual;
    }

    public function setJuntadaAtual(?Juntada $juntadaAtual): self
    {
        $this->juntadaAtual = $juntadaAtual;

        return $this;
    }

    public function getNumeroUnicoDocumento(): ?NumeroUnicoDocumento
    {
        return $this->numeroUnicoDocumento;
    }

    public function setNumeroUnicoDocumento(?NumeroUnicoDocumento $numeroUnicoDocumento): self
    {
        $this->numeroUnicoDocumento = $numeroUnicoDocumento;

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

    public function getDocumentoAvulsoRemessa(): ?DocumentoAvulso
    {
        return $this->documentoAvulsoRemessa;
    }

    public function setDocumentoAvulsoRemessa(?DocumentoAvulso $documentoAvulsoRemessa): self
    {
        $this->documentoAvulsoRemessa = $documentoAvulsoRemessa;

        return $this;
    }

    public function getModelo(): ?Modelo
    {
        return $this->modelo;
    }

    public function setModelo(?Modelo $modelo): self
    {
        $this->modelo = $modelo;

        return $this;
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(?Template $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getRepositorio(): ?Repositorio
    {
        return $this->repositorio;
    }

    public function setRepositorio(?Repositorio $repositorio): self
    {
        $this->repositorio = $repositorio;

        return $this;
    }

    public function getDocumentoAvulsoResposta(): ?DocumentoAvulso
    {
        return $this->documentoAvulsoResposta;
    }

    public function setDocumentoAvulsoResposta(?DocumentoAvulso $documentoAvulsoResposta): self
    {
        $this->documentoAvulsoResposta = $documentoAvulsoResposta;

        return $this;
    }

    public function addAreaTrabalho(AreaTrabalho $areaTrabalho): self
    {
        if (!$this->areasTrabalhos->contains($areaTrabalho)) {
            $this->areasTrabalhos[] = $areaTrabalho;
            $areaTrabalho->setDocumento($this);
        }

        return $this;
    }

    public function removeAreaTrabalho(AreaTrabalho $areaTrabalho): self
    {
        if ($this->areasTrabalhos->contains($areaTrabalho)) {
            $this->areasTrabalhos->removeElement($areaTrabalho);
        }

        return $this;
    }

    public function getAreasTrabalhos(): Collection
    {
        return $this->areasTrabalhos;
    }

    public function addComponenteDigital(ComponenteDigital $componenteDigital): self
    {
        if (!$this->componentesDigitais->contains($componenteDigital)) {
            $this->componentesDigitais[] = $componenteDigital;
            $componenteDigital->setDocumento($this);
        }

        return $this;
    }

    public function removeComponenteDigital(ComponenteDigital $componenteDigital): self
    {
        if ($this->componentesDigitais->contains($componenteDigital)) {
            $this->componentesDigitais->removeElement($componenteDigital);
        }

        return $this;
    }

    public function getComponentesDigitais(): Collection
    {
        return $this->componentesDigitais;
    }

    public function addJuntada(Juntada $juntada): self
    {
        if (!$this->juntadas->contains($juntada)) {
            $this->juntadas[] = $juntada;
            $juntada->setDocumento($this);
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

    public function getJuntadas(): Collection
    {
        return $this->juntadas;
    }

    public function addSigilo(Sigilo $sigilo): self
    {
        if (!$this->sigilos->contains($sigilo)) {
            $this->sigilos[] = $sigilo;
            $sigilo->setDocumento($this);
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

    public function getSigilos(): Collection
    {
        return $this->sigilos;
    }

    public function addVinculacaoDocumento(VinculacaoDocumento $vinculacaoDocumento): self
    {
        if (!$this->vinculacoesDocumentos->contains($vinculacaoDocumento)) {
            $this->vinculacoesDocumentos[] = $vinculacaoDocumento;
            $vinculacaoDocumento->setDocumento($this);
        }

        return $this;
    }

    public function removeVinculacaoDocumento(VinculacaoDocumento $vinculacaoDocumento): self
    {
        if ($this->vinculacoesDocumentos->contains($vinculacaoDocumento)) {
            $this->vinculacoesDocumentos->removeElement($vinculacaoDocumento);
        }

        return $this;
    }

    public function getVinculacoesDocumentos(): Collection
    {
        return $this->vinculacoesDocumentos;
    }

    public function getVinculacoesEtiquetas(): Collection
    {
        return $this->vinculacoesEtiquetas;
    }

    public function addVinculacaoEtiqueta(VinculacaoEtiqueta $vinculacaoEtiqueta): self
    {
        if (!$this->vinculacoesEtiquetas->contains($vinculacaoEtiqueta)) {
            $this->vinculacoesEtiquetas->add($vinculacaoEtiqueta);
            $vinculacaoEtiqueta->setDocumento($this);
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

    public function getDocumentoAvulsoComplementacaoResposta(): ?DocumentoAvulso
    {
        return $this->documentoAvulsoComplementacaoResposta;
    }

    public function setDocumentoAvulsoComplementacaoResposta(
        ?DocumentoAvulso $documentoAvulsoComplementacaoResposta
    ): self {
        $this->documentoAvulsoComplementacaoResposta = $documentoAvulsoComplementacaoResposta;

        return $this;
    }

    public function getDataHoraValidade(): ?DateTime
    {
        return $this->dataHoraValidade;
    }

    public function setDataHoraValidade(?DateTime $dataHoraValidade): self
    {
        $this->dataHoraValidade = $dataHoraValidade;

        return $this;
    }

    public function getChaveAcesso(): ?string
    {
        return $this->chaveAcesso;
    }

    public function setChaveAcesso(?string $chaveAcesso): self
    {
        $this->chaveAcesso = $chaveAcesso;

        return $this;
    }

    public function getRelatorio(): ?Relatorio
    {
        return $this->relatorio;
    }

    public function setRelatorio(?Relatorio $relatorio): self
    {
        $this->relatorio = $relatorio;

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

    public function getVinculacaoDocumentoPrincipal(): Collection
    {
        return $this->vinculacaoDocumentoPrincipal;
    }

    public function addVinculacaoDocumentoPrincipal(VinculacaoDocumento $vinculacaoDocumentoPrincipal): self
    {
        if (!$this->vinculacaoDocumentoPrincipal->contains($vinculacaoDocumentoPrincipal)) {
            $this->vinculacaoDocumentoPrincipal->add($vinculacaoDocumentoPrincipal);
            $vinculacaoDocumentoPrincipal->setDocumento($this);
        }

        return $this;
    }

    public function removeVinculacaoDocumentoPrincipal(VinculacaoDocumento $vinculacaoDocumentoPrincipal): self
    {
        if ($this->vinculacaoDocumentoPrincipal->contains($vinculacaoDocumentoPrincipal)) {
            $this->vinculacaoDocumentoPrincipal->removeElement($vinculacaoDocumentoPrincipal);
        }

        return $this;
    }

    public function getModalidadeCopia(): ?ModalidadeCopia
    {
        return $this->modalidadeCopia;
    }

    public function setModalidadeCopia(?ModalidadeCopia $modalidadeCopia): self
    {
        $this->modalidadeCopia = $modalidadeCopia;

        return $this;
    }

    public function getDependenciaSoftware(): ?string
    {
        return $this->dependenciaSoftware;
    }

    public function setDependenciaSoftware(?string $dependenciaSoftware): self
    {
        $this->dependenciaSoftware = $dependenciaSoftware;

        return $this;
    }

    public function getDependenciaHardware(): ?string
    {
        return $this->dependenciaHardware;
    }

    public function setDependenciaHardware(?string $dependenciaHardware): self
    {
        $this->dependenciaHardware = $dependenciaHardware;

        return $this;
    }

    public function getDocumentoIAMetadata(): ?DocumentoIAMetadata
    {
        return $this->documentoIAMetadata;
    }

    public function setDocumentoIAMetadata(?DocumentoIAMetadata $documentoIAMetadata): self
    {
        $this->documentoIAMetadata = $documentoIAMetadata;

        return $this;
    }

    public function getDadosFormularios(): Collection|ArrayCollection
    {
        return $this->dadosFormularios;
    }

    public function addDadosFormulario(DadosFormulario $dadosFormulario): self
    {
        if (!$this->dadosFormularios->contains($dadosFormulario)) {
            $this->dadosFormularios[] = $dadosFormulario;
            $dadosFormulario->setDocumento($this);
        }

        return $this;
    }

    public function removeDadosFormulario(DadosFormulario $dadosFormulario): self
    {
        if ($this->dadosFormularios->contains($dadosFormulario)) {
            $this->dadosFormularios->removeElement($dadosFormulario);
        }

        return $this;
    }
}
