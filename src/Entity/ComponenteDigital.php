<?php

declare(strict_types=1);
/**
 * /src/Entity/ComponenteDigital.php.
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
use SuppCore\AdministrativoBackend\Entity\Traits\CryptoService;
use SuppCore\AdministrativoBackend\Entity\Traits\FilesystemService;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ComponenteDigital.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_componente_digital')]
#[ORM\UniqueConstraint(columns: ['numeracao_sequencial', 'documento_id', 'apagado_em'])]
class ComponenteDigital implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use CryptoService;
    use FilesystemService;

    // Status Verificação Vírus
    public const SVV_PENDENTE = 0;
    public const SVV_EXECUTANDO = 1;
    public const SVV_SEGURO = 2;
    public const SVV_INSEGURO = 3;
    public const SVV_ERRO = 3;

    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Campo ter no mínimo {{ limit }} letras ou números',
        maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números'
    )]
    #[Gedmo\Versioned]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'file_name', type: 'string', nullable: false)]
    protected string $fileName;

    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\Trim]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'string', nullable: false)]
    protected ?string $hash = null;

    /**
     * Gerenciamento das versões.
     */
    protected ?string $hashAntigo = null;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'numeracao_sequencial', type: 'integer', nullable: false)]
    protected int $numeracaoSequencial = 1;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'data_hora_indexacao', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraIndexacao = null;

    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    protected ?string $conteudo = null;

    #[Gedmo\Versioned]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $tamanho = 0;

    #[Gedmo\Versioned]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $versaoEditor = null;

    /**
     * Nível de composição informa se o componente digital está sujeito a algum ní-
     * vel de compressão ou criptografia e qual é este nível. Nível de composição <0>
     * (zero) indica que o componente digital não está sujeito a nenhum processo de
     * compressão ou criptografia. Nível de composição <1> (um) ou maior indica que
     * o componente digital foi submetido a um ou mais processos de compressão ou
     * criptografia e que deve ser decodificado para que o documento possa ser aces-
     * sado. Por exemplo, um arquivo A pode ser comprimido e gerar um arquivo B,
     * que por sua vez é cifrado e gera um arquivo C. Para se ter acesso ao arquivo A
     * é necessário decifrar o arquivo C e depois descomprimir o arquivo B.
     *
     * 0 - arquivo comum
     * 1 - arquivo comprimido
     * 2 - arquivo criptografado
     * 3 - arquivo comprimido e criptografado
     */
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'nivel_composicao', type: 'integer', nullable: false)]
    protected int $nivelComposicao = 0;

    #[ORM\Column(type: 'integer', nullable: true)]
    protected ?int $interacoes = 0;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'sw_criacao', type: 'string', nullable: true)]
    protected ?string $softwareCriacao = null;

    /**
     * Inibidor_tipo: proteção por senha
     * Inibidor_alvo: impressão
     * Inibidor_chave: xyz.
     */
    #[Gedmo\Versioned]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[ORM\Column(name: 'chave_inibidor', type: 'string', nullable: true)]
    protected ?string $chaveInibidor = null;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'data_hora_sw_criacao', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraSoftwareCriacao = null;

    #[Gedmo\Versioned]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[ORM\Column(name: 'versao_sw_criacao', type: 'string', nullable: true)]
    protected ?string $versaoSoftwareCriacao = null;

    #[Gedmo\Versioned]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $mimetype = null;

    #[Gedmo\Versioned]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $extensao = null;

    #[Gedmo\Versioned]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $editavel = false;

    #[Gedmo\Versioned]
    #[ORM\Column(
        type: 'boolean',
        nullable: false,
        options: [
            'default' => 0,
        ]
    )]
    protected ?bool $convertidoPdf = false;

    #[ORM\ManyToOne(targetEntity: 'Processo')]
    #[ORM\JoinColumn(name: 'processo_origem_id', referencedColumnName: 'id', nullable: true)]
    protected ?Processo $processoOrigem = null;

    #[ORM\ManyToOne(targetEntity: 'Tarefa')]
    #[ORM\JoinColumn(name: 'tarefa_origem_id', referencedColumnName: 'id', nullable: true)]
    protected ?Tarefa $tarefaOrigem = null;

    #[ORM\ManyToOne(targetEntity: 'DocumentoAvulso')]
    #[ORM\JoinColumn(name: 'doc_avulso_origem_id', referencedColumnName: 'id', nullable: true)]
    protected ?DocumentoAvulso $documentoAvulsoOrigem = null;

    #[ORM\Column(name: 'data_hora_lock_edicao', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraLockEdicao = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[ORM\Column(name: 'username_lock_edicao', type: 'string', nullable: true)]
    protected ?string $usernameLockEdicao = null;

    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeAlvoInibidor')]
    #[ORM\JoinColumn(name: 'mod_alvo_inibidor_id', referencedColumnName: 'id', nullable: true)]
    protected ?ModalidadeAlvoInibidor $modalidadeAlvoInibidor = null;

    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeTipoInibidor')]
    #[ORM\JoinColumn(name: 'mod_tipo_inibidor_id', referencedColumnName: 'id', nullable: true)]
    protected ?ModalidadeTipoInibidor $modalidadeTipoInibidor = null;

    #[ORM\ManyToOne(targetEntity: 'Modelo')]
    #[ORM\JoinColumn(name: 'modelo_id', referencedColumnName: 'id', nullable: true)]
    protected ?Modelo $modelo = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Documento', inversedBy: 'componentesDigitais')]
    #[ORM\JoinColumn(name: 'documento_id', referencedColumnName: 'id', nullable: true)]
    protected ?Documento $documento = null;

    #[ORM\ManyToOne(targetEntity: 'OrigemDados', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'origem_dados_id', referencedColumnName: 'id', nullable: true)]
    protected ?OrigemDados $origemDados = null;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<Assinatura>
     */
    #[ORM\OneToMany(mappedBy: 'componenteDigital', targetEntity: 'Assinatura', cascade: ['all'])]
    protected $assinaturas;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<Bookmark>
     */
    #[ORM\OneToMany(mappedBy: 'componenteDigital', targetEntity: 'Bookmark', cascade: ['all'])]
    protected $bookmarks;

    #[ORM\Column(name: 'status_verificacao_virus', type: 'integer', nullable: true)]
    protected ?int $statusVerificacaoVirus = 0;

    /**
     * Utilizado para exibir o resumo do elasticsearch.
     */
    protected ?string $highlights = null;



    /**
     * Utilizado para confiar no conteudo.
     */
    protected ?bool $allowUnsafe = false;

    #[ORM\Column(name: 'sumarizacao', type: 'string', length: 4000, nullable: true)]
    protected ?string $sumarizacao = null;

    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $json = null;

    /**
     * @var Collection|ArrayCollection<VinculacaoTese>
     */
    #[ORM\OneToMany(mappedBy: 'componenteDigital', targetEntity: 'VinculacaoTese')]
    protected Collection|ArrayCollection $vinculacoesTeses;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->assinaturas = new ArrayCollection();
        $this->bookmarks = new ArrayCollection();
        $this->vinculacoesTeses = new ArrayCollection();
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getHashAntigo(): ?string
    {
        return $this->hashAntigo;
    }

    public function setHashAntigo(?string $hashAntigo): self
    {
        $this->hashAntigo = $hashAntigo;

        return $this;
    }

    public function getNumeracaoSequencial(): int
    {
        return $this->numeracaoSequencial;
    }

    public function setNumeracaoSequencial(int $numeracaoSequencial): self
    {
        $this->numeracaoSequencial = $numeracaoSequencial;

        return $this;
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

    public function getConteudo(): ?string
    {
        return $this->conteudo;
    }

    public function setConteudo(?string $conteudo): self
    {
        $this->conteudo = $conteudo;

        return $this;
    }

    public function getTamanho(): int
    {
        return $this->tamanho;
    }

    public function setTamanho(int $tamanho): self
    {
        $this->tamanho = $tamanho;

        return $this;
    }

    public function getNivelComposicao(): int
    {
        return $this->nivelComposicao;
    }

    public function setNivelComposicao(int $nivelComposicao): self
    {
        $this->nivelComposicao = $nivelComposicao;

        return $this;
    }

    public function getSoftwareCriacao(): ?string
    {
        return $this->softwareCriacao;
    }

    public function setSoftwareCriacao(?string $softwareCriacao): self
    {
        $this->softwareCriacao = $softwareCriacao;

        return $this;
    }

    public function getChaveInibidor(): ?string
    {
        return $this->chaveInibidor;
    }

    public function setChaveInibidor(?string $chaveInibidor): self
    {
        $this->chaveInibidor = $chaveInibidor;

        return $this;
    }

    public function getDataHoraSoftwareCriacao(): ?DateTime
    {
        return $this->dataHoraSoftwareCriacao;
    }

    public function setDataHoraSoftwareCriacao(?DateTime $dataHoraSoftwareCriacao): self
    {
        $this->dataHoraSoftwareCriacao = $dataHoraSoftwareCriacao;

        return $this;
    }

    public function getVersaoSoftwareCriacao(): ?string
    {
        return $this->versaoSoftwareCriacao;
    }

    public function setVersaoSoftwareCriacao(?string $versaoSoftwareCriacao): self
    {
        $this->versaoSoftwareCriacao = $versaoSoftwareCriacao;

        return $this;
    }

    public function getMimetype(): ?string
    {
        return $this->mimetype;
    }

    public function setMimetype(?string $mimetype): self
    {
        $this->mimetype = $mimetype;

        return $this;
    }

    public function getDataHoraLockEdicao(): ?DateTime
    {
        return $this->dataHoraLockEdicao;
    }

    public function setDataHoraLockEdicao(?DateTime $dataHoraLockEdicao): self
    {
        $this->dataHoraLockEdicao = $dataHoraLockEdicao;

        return $this;
    }

    public function getUsernameLockEdicao(): ?string
    {
        return $this->usernameLockEdicao;
    }

    public function setUsernameLockEdicao(?string $usernameLockEdicao): self
    {
        $this->usernameLockEdicao = $usernameLockEdicao;

        return $this;
    }

    public function getExtensao(): string
    {
        return $this->extensao;
    }

    public function setExtensao(string $extensao): self
    {
        $this->extensao = $extensao;

        return $this;
    }

    public function getEditavel(): bool
    {
        return $this->editavel;
    }

    public function setEditavel(bool $editavel): self
    {
        $this->editavel = $editavel;

        return $this;
    }

    public function getConvertidoPdf(): ?bool
    {
        return $this->convertidoPdf;
    }

    public function setConvertidoPdf(?bool $convertidoPdf): self
    {
        $this->convertidoPdf = $convertidoPdf;

        return $this;
    }

    public function getModalidadeAlvoInibidor(): ?ModalidadeAlvoInibidor
    {
        return $this->modalidadeAlvoInibidor;
    }

    public function setModalidadeAlvoInibidor(?ModalidadeAlvoInibidor $modalidadeAlvoInibidor): self
    {
        $this->modalidadeAlvoInibidor = $modalidadeAlvoInibidor;

        return $this;
    }

    public function getModalidadeTipoInibidor(): ?ModalidadeTipoInibidor
    {
        return $this->modalidadeTipoInibidor;
    }

    public function setModalidadeTipoInibidor(?ModalidadeTipoInibidor $modalidadeTipoInibidor): self
    {
        $this->modalidadeTipoInibidor = $modalidadeTipoInibidor;

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

    public function getDocumento(): ?Documento
    {
        return $this->documento;
    }

    public function setDocumento(?Documento $documento): self
    {
        $this->documento = $documento;
        $this->documento->addComponenteDigital($this);

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

    public function addAssinatura(Assinatura $assinatura): self
    {
        if (!$this->assinaturas->contains($assinatura)) {
            $this->assinaturas[] = $assinatura;
            $assinatura->setComponenteDigital($this);
        }

        return $this;
    }

    public function removeAssinatura(Assinatura $assinatura): self
    {
        if ($this->assinaturas->contains($assinatura)) {
            $this->assinaturas->removeElement($assinatura);
        }

        return $this;
    }

    public function getAssinaturas(): Collection
    {
        return $this->assinaturas;
    }

    public function addBookmark(Bookmark $bookmark): self
    {
        if (!$this->bookmarks->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
            $bookmark->setComponenteDigital($this);
        }

        return $this;
    }

    public function removeBookmark(Bookmark $bookmark): self
    {
        if ($this->bookmarks->contains($bookmark)) {
            $this->bookmarks->removeElement($bookmark);
        }

        return $this;
    }

    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
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

    public function getTarefaOrigem(): ?Tarefa
    {
        return $this->tarefaOrigem;
    }

    public function setTarefaOrigem(?Tarefa $tarefaOrigem): self
    {
        $this->tarefaOrigem = $tarefaOrigem;

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

    public function setHighlights(?string $highlights): self
    {
        $this->highlights = $highlights;

        return $this;
    }

    public function getHighlights(): ?string
    {
        return $this->highlights;
    }



    public function getAllowUnsafe(): ?bool
    {
        return $this->allowUnsafe;
    }

    public function setAllowUnsafe(?bool $allowUnsafe): void
    {
        $this->allowUnsafe = $allowUnsafe;
    }

    public function getStatusVerificacaoVirus(): ?int
    {
        return $this->statusVerificacaoVirus;
    }

    public function setStatusVerificacaoVirus(?int $statusVerificacaoVirus): self
    {
        $this->statusVerificacaoVirus = $statusVerificacaoVirus;

        return $this;
    }

    public function getInteracoes(): ?int
    {
        return $this->interacoes;
    }

    public function setInteracoes(?int $interacoes): self
    {
        $this->interacoes = $interacoes;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSumarizacao(): ?string
    {
        return $this->sumarizacao;
    }

    /**
     * @param string|null $sumarizacao
     * @return $this
     */
    public function setSumarizacao(?string $sumarizacao): self
    {
        $this->sumarizacao = $sumarizacao;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getJson(): ?string
    {
        return $this->json;
    }

    /**
     * @param string|null $json
     * @return $this
     */
    public function setJson(?string $json): self
    {
        $this->json = $json;

        return $this;
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

    public function getVersaoEditor(): ?string
    {
        return $this->versaoEditor;
    }

    public function setVersaoEditor(?string $versaoEditor): self
    {
        $this->versaoEditor = $versaoEditor;

        return $this;
    }
}
