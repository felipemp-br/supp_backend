<?php

declare(strict_types=1);
/**
 * /src/Entity/Setor.php.
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
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Setor.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\Loggable]
#[Gedmo\Tree(type: 'nested')]
#[Enableable]
#[ORM\Table(name: 'ad_setor')]
class Setor implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Id;
    use Uuid;

    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
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

    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'EspecieSetor')]
    #[ORM\JoinColumn(name: 'especie_setor_id', referencedColumnName: 'id', nullable: true)]
    protected ?EspecieSetor $especieSetor = null;

    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'GeneroSetor')]
    #[ORM\JoinColumn(name: 'genero_setor_id', referencedColumnName: 'id', nullable: true)]
    protected ?GeneroSetor $generoSetor = null;

    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeOrgaoCentral')]
    #[ORM\JoinColumn(name: 'mod_orgao_central_id', referencedColumnName: 'id', nullable: true)]
    protected ?ModalidadeOrgaoCentral $modalidadeOrgaoCentral = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $endereco = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Assert\Email(message: 'Email em formato inválido!')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $email = null;

    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\Regex(
        pattern: '/[A-Z0-9]+/',
        message: 'A sigla do setor deve possuir apenas letras maiúsculas ou números.'
    )]
    #[Assert\Length(
        min: 2,
        max: 25,
        minMessage: 'A sigla deve ter no mínimo {{ limit }} letras ou números',
        maxMessage: 'A sigla deve ter no máximo {{ limit }} letras ou números'
    )]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'string', length: 25, nullable: false)]
    protected string $sigla;

    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Setor', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'unidade_id', referencedColumnName: 'id', nullable: true)]
    protected ?Setor $unidade = null;

    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Setor', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'unidade_pai_id', referencedColumnName: 'id', nullable: true)]
    protected ?Setor $unidadePai = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Municipio')]
    #[ORM\JoinColumn(name: 'municipio_id', referencedColumnName: 'id', nullable: false)]
    protected Municipio $municipio;

    #[Assert\Regex(pattern: '/\d{5}/', message: 'Prefixo NUP Inválido')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'prefixo_nup', type: 'string', nullable: true)]
    protected ?string $prefixoNUP = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'sequencia_inicial_nup', type: 'integer', nullable: false)]
    protected ?int $sequenciaInicialNUP = 0;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $ativo = true;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $gerenciamento = false;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'apenas_protocolo', type: 'boolean', nullable: false)]
    protected bool $apenasProtocolo = false;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'numeracao_doc_unidade', type: 'boolean', nullable: false)]
    protected bool $numeracaoDocumentoUnidade = false;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'apenas_distribuidor', type: 'boolean', nullable: false)]
    protected bool $apenasDistribuidor = false;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'distribuicao_centena', type: 'boolean', nullable: false)]
    protected bool $distribuicaoCentena = false;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'prazo_equalizacao', type: 'integer', nullable: false)]
    protected int $prazoEqualizacao = 7;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'divergencia_maxima', type: 'integer', nullable: false)]
    protected int $divergenciaMaxima = 25;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'apenas_distrib_automatica', type: 'boolean', nullable: false)]
    protected bool $apenasDistribuicaoAutomatica = true;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'com_prevencao_relativa', type: 'boolean', nullable: false)]
    protected bool $comPrevencaoRelativa = true;

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
    #[ORM\ManyToOne(targetEntity: 'Setor', inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    protected ?Setor $parent = null;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<Setor>
     */
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: 'Setor')]
    #[ORM\OrderBy(
        [
            'lft' => 'ASC',
        ]
    )]
    protected $children;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<Lotacao>
     */
    #[ORM\OneToMany(mappedBy: 'setor', targetEntity: 'Lotacao', cascade: ['all'])]
    protected $lotacoes;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<ContaEmail>
     */
    #[ORM\OneToMany(mappedBy: 'setor', targetEntity: 'ContaEmail')]
    protected $contasEmails;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<VinculacaoEtiqueta>
     */
    #[ORM\OneToMany(mappedBy: 'setor', targetEntity: 'VinculacaoEtiqueta')]
    protected $vinculacoesEtiquetas;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->contasEmails = new ArrayCollection();
        $this->lotacoes = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->vinculacoesEtiquetas = new ArrayCollection();
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

    public function getEspecieSetor(): ?EspecieSetor
    {
        return $this->especieSetor;
    }

    public function setEspecieSetor(?EspecieSetor $especieSetor): self
    {
        $this->especieSetor = $especieSetor;

        return $this;
    }

    public function getGeneroSetor(): ?GeneroSetor
    {
        return $this->generoSetor;
    }

    public function setGeneroSetor(?GeneroSetor $generoSetor): self
    {
        $this->generoSetor = $generoSetor;

        return $this;
    }

    public function getModalidadeOrgaoCentral(): ?ModalidadeOrgaoCentral
    {
        return $this->modalidadeOrgaoCentral;
    }

    public function setModalidadeOrgaoCentral(?ModalidadeOrgaoCentral $modalidadeOrgaoCentral): self
    {
        $this->modalidadeOrgaoCentral = $modalidadeOrgaoCentral;

        return $this;
    }

    public function getEndereco(): ?string
    {
        return $this->endereco;
    }

    public function setEndereco(?string $endereco): self
    {
        $this->endereco = $endereco;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSigla(): string
    {
        return $this->sigla;
    }

    public function setSigla(string $sigla): self
    {
        $this->sigla = $sigla;

        return $this;
    }

    public function getUnidade(): ?self
    {
        return $this->unidade;
    }

    public function setUnidade(?self $unidade): self
    {
        $this->unidade = $unidade;

        return $this;
    }

    public function getUnidadePai(): ?self
    {
        return $this->unidadePai;
    }

    public function setUnidadePai(?self $unidadePai): self
    {
        $this->unidadePai = $unidadePai;

        return $this;
    }

    public function getMunicipio(): Municipio
    {
        return $this->municipio;
    }

    public function setMunicipio(Municipio $municipio): self
    {
        $this->municipio = $municipio;

        return $this;
    }

    public function getPrefixoNUP(): ?string
    {
        return $this->prefixoNUP;
    }

    public function setPrefixoNUP(?string $prefixoNUP): self
    {
        $this->prefixoNUP = $prefixoNUP;

        return $this;
    }

    public function getSequenciaInicialNUP(): ?int
    {
        return $this->sequenciaInicialNUP;
    }

    public function setSequenciaInicialNUP(?int $sequenciaInicialNUP): self
    {
        $this->sequenciaInicialNUP = $sequenciaInicialNUP;

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

    public function getGerenciamento(): bool
    {
        return $this->gerenciamento;
    }

    public function setGerenciamento(bool $gerenciamento): self
    {
        $this->gerenciamento = $gerenciamento;

        return $this;
    }

    public function getApenasProtocolo(): bool
    {
        return $this->apenasProtocolo;
    }

    public function setApenasProtocolo(bool $apenasProtocolo): self
    {
        $this->apenasProtocolo = $apenasProtocolo;

        return $this;
    }

    public function getNumeracaoDocumentoUnidade(): bool
    {
        return $this->numeracaoDocumentoUnidade;
    }

    public function setNumeracaoDocumentoUnidade(bool $numeracaoDocumentoUnidade): self
    {
        $this->numeracaoDocumentoUnidade = $numeracaoDocumentoUnidade;

        return $this;
    }

    public function getApenasDistribuidor(): bool
    {
        return $this->apenasDistribuidor;
    }

    public function setApenasDistribuidor(bool $apenasDistribuidor): self
    {
        $this->apenasDistribuidor = $apenasDistribuidor;

        return $this;
    }

    public function getDistribuicaoCentena(): bool
    {
        return $this->distribuicaoCentena;
    }

    public function setDistribuicaoCentena(bool $distribuicaoCentena): self
    {
        $this->distribuicaoCentena = $distribuicaoCentena;

        return $this;
    }

    public function getPrazoEqualizacao(): int
    {
        return $this->prazoEqualizacao;
    }

    public function setPrazoEqualizacao(int $prazoEqualizacao): self
    {
        $this->prazoEqualizacao = $prazoEqualizacao;

        return $this;
    }

    public function getDivergenciaMaxima(): int
    {
        return $this->divergenciaMaxima;
    }

    public function setDivergenciaMaxima(int $divergenciaMaxima): self
    {
        $this->divergenciaMaxima = $divergenciaMaxima;

        return $this;
    }

    public function getApenasDistribuicaoAutomatica(): bool
    {
        return $this->apenasDistribuicaoAutomatica;
    }

    public function setApenasDistribuicaoAutomatica(bool $apenasDistribuicaoAutomatica): self
    {
        $this->apenasDistribuicaoAutomatica = $apenasDistribuicaoAutomatica;

        return $this;
    }

    public function getComPrevencaoRelativa(): bool
    {
        return $this->comPrevencaoRelativa;
    }

    public function setComPrevencaoRelativa(bool $comPrevencaoRelativa): self
    {
        $this->comPrevencaoRelativa = $comPrevencaoRelativa;

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

    public function addVinculacaoEtiqueta(VinculacaoEtiqueta $vinculacaoEtiqueta): self
    {
        if (!$this->vinculacoesEtiquetas->contains($vinculacaoEtiqueta)) {
            $this->vinculacoesEtiquetas[] = $vinculacaoEtiqueta;
            $vinculacaoEtiqueta->setSetor($this);
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

    public function getVinculacoesEtiquetas(): Collection
    {
        return $this->vinculacoesEtiquetas;
    }

    public function addLotacao(Lotacao $lotacao): self
    {
        if (!$this->lotacoes->contains($lotacao)) {
            $this->lotacoes[] = $lotacao;
            $lotacao->setSetor($this);
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

    public function addContaEmail(ContaEmail $contaEmail): self
    {
        if (!$this->contasEmails->contains($contaEmail)) {
            $this->contasEmails[] = $contaEmail;
            $contaEmail->setSetor($this);
        }

        return $this;
    }

    public function removeContaEmail(ContaEmail $contaEmail): self
    {
        if ($this->contasEmails->contains($contaEmail)) {
            $this->contasEmails->removeElement($contaEmail);
        }

        return $this;
    }

    public function getContasEmails(): Collection
    {
        return $this->contasEmails;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }
}
