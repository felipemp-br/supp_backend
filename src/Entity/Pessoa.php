<?php

declare(strict_types=1);
/**
 * /src/Entity/Pessoa.php.
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
use SuppCore\AdministrativoBackend\Entity\VinculacaoPessoaBarramento as VinculacaoPessoaBarramentoEntity;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use SuppCore\JudicialBackend\Entity\Traits\PessoaRepresentada;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Pessoa.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[UniqueEntity(fields: ['numeroDocumentoPrincipal'], message: 'CPF/CNPJ já cadastrado!')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_pessoa')]
class Pessoa implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Id;
    use Uuid;

    use PessoaRepresentada;

    #[ORM\ManyToOne(targetEntity: 'Municipio')]
    #[ORM\JoinColumn(name: 'municipio_naturalidade_id', referencedColumnName: 'id', nullable: true)]
    protected ?Municipio $naturalidade = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $profissao = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $contato = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'pessoa_validada', type: 'boolean', nullable: false)]
    protected bool $pessoaValidada = false;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'pessoa_conveniada', type: 'boolean', nullable: false)]
    protected bool $pessoaConveniada = false;

    #[ORM\Column(name: 'data_hora_indexacao', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraIndexacao = null;

    #[ORM\Column(name: 'data_nascimento', type: 'date', nullable: true)]
    #[Assert\GreaterThan('1800-01-01', message: 'A data não pode ser menor que 1800-01-01!')]
    protected ?DateTime $dataNascimento = null;

    #[ORM\Column(name: 'data_obito', type: 'date', nullable: true)]
    protected ?DateTime $dataObito = null;

    #[ORM\ManyToOne(targetEntity: 'Pais')]
    #[ORM\JoinColumn(name: 'pais_nacionalidade_id', referencedColumnName: 'id', nullable: true)]
    protected ?Pais $nacionalidade = null;

    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'string', nullable: false)]
    protected string $nome;

    #[Filter\Digits(allowWhitespace: false)]
    #[AppAssert\CpfCnpj]
    #[Assert\Length(max: 14, maxMessage: 'O campo deve ter no máximo 14 caracteres!')]
    #[ORM\Column(name: 'numero_doc_principal', type: 'string', unique: true, nullable: true)]
    protected ?string $numeroDocumentoPrincipal = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'nome_genitor', type: 'string', nullable: true)]
    protected ?string $nomeGenitor = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'nome_genitora', type: 'string', nullable: true)]
    protected ?string $nomeGenitora = null;

    #[ORM\ManyToOne(targetEntity: 'ModalidadeGeneroPessoa')]
    #[ORM\JoinColumn(name: 'mod_genero_pessoa_id', referencedColumnName: 'id', nullable: true)]
    protected ?ModalidadeGeneroPessoa $modalidadeGeneroPessoa = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeQualificacaoPessoa', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'mod_qual_pessoa_id', referencedColumnName: 'id', nullable: false)]
    protected ModalidadeQualificacaoPessoa $modalidadeQualificacaoPessoa;

    #[ORM\ManyToOne(targetEntity: 'OrigemDados', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'origem_dados_id', referencedColumnName: 'id', nullable: true)]
    protected ?OrigemDados $origemDados = null;

    #[ORM\ManyToOne(targetEntity: 'ModalidadeNaturezaJuridica')]
    #[ORM\JoinColumn(name: 'mod_natur_juridica_id', referencedColumnName: 'id', nullable: true)]
    protected ?ModalidadeNaturezaJuridica $modalidadeNaturezaJuridica = null;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<Endereco>
     */
    #[ORM\OneToMany(mappedBy: 'pessoa', targetEntity: 'Endereco', cascade: ['all'])]
    #[ORM\OrderBy(
        [
            'principal' => 'DESC',
        ]
    )]
    protected $enderecos;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<Dossie>
     */
    #[ORM\OneToMany(mappedBy: 'pessoa', targetEntity: 'Dossie', cascade: ['all'])]
    #[ORM\OrderBy(
        [
            'criadoEm' => 'DESC',
        ]
    )]
    protected $dossies;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<RelacionamentoPessoal>
     */
    #[ORM\OneToMany(mappedBy: 'pessoa', targetEntity: 'RelacionamentoPessoal', cascade: ['all'])]
    protected $relacionamentosPessoais;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<Nome>
     */
    #[ORM\OneToMany(mappedBy: 'pessoa', targetEntity: 'Nome', cascade: ['all'])]
    protected $outrosNomes;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<DocumentoIdentificador>
     */
    #[ORM\OneToMany(mappedBy: 'pessoa', targetEntity: 'DocumentoIdentificador', cascade: ['all'])]
    protected $documentosIdentificadores;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<Interessado>
     */
    #[ORM\OneToMany(mappedBy: 'pessoa', targetEntity: 'Interessado')]
    protected $interessados;

    #[ORM\OneToOne(mappedBy: 'pessoa', targetEntity: 'VinculacaoPessoaBarramento', cascade: ['persist'])]
    protected ?VinculacaoPessoaBarramentoEntity $vinculacaoPessoaBarramento = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->dossies = new ArrayCollection();
        $this->enderecos = new ArrayCollection();
        $this->documentosIdentificadores = new ArrayCollection();
        $this->relacionamentosPessoais = new ArrayCollection();
        $this->outrosNomes = new ArrayCollection();
        $this->interessados = new ArrayCollection();
    }

    public function getNaturalidade(): ?Municipio
    {
        return $this->naturalidade;
    }

    public function setNaturalidade(?Municipio $naturalidade): self
    {
        $this->naturalidade = $naturalidade;

        return $this;
    }

    public function getProfissao(): ?string
    {
        return $this->profissao;
    }

    public function setProfissao(?string $profissao): self
    {
        $this->profissao = $profissao;

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

    public function getContato(): ?string
    {
        return $this->contato;
    }

    public function setContato(?string $contato): self
    {
        $this->contato = $contato;

        return $this;
    }

    public function getPessoaValidada(): bool
    {
        return $this->pessoaValidada;
    }

    public function setPessoaValidada(bool $pessoaValidada): self
    {
        $this->pessoaValidada = $pessoaValidada;

        return $this;
    }

    public function getPessoaConveniada(): bool
    {
        return $this->pessoaConveniada;
    }

    public function setPessoaConveniada(bool $pessoaConveniada): self
    {
        $this->pessoaConveniada = $pessoaConveniada;

        return $this;
    }

    public function getDataNascimento(): ?DateTime
    {
        return $this->dataNascimento;
    }

    public function setDataNascimento(?DateTime $dataNascimento): self
    {
        $this->dataNascimento = $dataNascimento;

        return $this;
    }

    public function getDataObito(): ?DateTime
    {
        return $this->dataObito;
    }

    public function setDataObito(?DateTime $dataObito): self
    {
        $this->dataObito = $dataObito;

        return $this;
    }

    public function getNacionalidade(): ?Pais
    {
        return $this->nacionalidade;
    }

    public function setNacionalidade(?Pais $nacionalidade): self
    {
        $this->nacionalidade = $nacionalidade;

        return $this;
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

    public function getNumeroDocumentoPrincipal(): ?string
    {
        return $this->numeroDocumentoPrincipal;
    }

    public function setNumeroDocumentoPrincipal(?string $numeroDocumentoPrincipal): self
    {
        $this->numeroDocumentoPrincipal = $numeroDocumentoPrincipal;

        return $this;
    }

    public function getNomeGenitor(): ?string
    {
        return $this->nomeGenitor;
    }

    public function setNomeGenitor(?string $nomeGenitor): self
    {
        $this->nomeGenitor = $nomeGenitor;

        return $this;
    }

    public function getNomeGenitora(): ?string
    {
        return $this->nomeGenitora;
    }

    public function setNomeGenitora(?string $nomeGenitora): self
    {
        $this->nomeGenitora = $nomeGenitora;

        return $this;
    }

    public function getModalidadeGeneroPessoa(): ?ModalidadeGeneroPessoa
    {
        return $this->modalidadeGeneroPessoa;
    }

    public function setModalidadeGeneroPessoa(?ModalidadeGeneroPessoa $modalidadeGeneroPessoa): self
    {
        $this->modalidadeGeneroPessoa = $modalidadeGeneroPessoa;

        return $this;
    }

    public function getModalidadeQualificacaoPessoa(): ModalidadeQualificacaoPessoa
    {
        return $this->modalidadeQualificacaoPessoa;
    }

    public function setModalidadeQualificacaoPessoa(ModalidadeQualificacaoPessoa $modalidadeQualificacaoPessoa): self
    {
        $this->modalidadeQualificacaoPessoa = $modalidadeQualificacaoPessoa;

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

    public function addEndereco(Endereco $endereco): self
    {
        if (!$this->enderecos->contains($endereco)) {
            $this->enderecos[] = $endereco;
            $endereco->setPessoa($this);
        }

        return $this;
    }

    public function removeEndereco(Endereco $endereco): self
    {
        if ($this->enderecos->contains($endereco)) {
            $this->enderecos->removeElement($endereco);
        }

        return $this;
    }

    public function getEnderecos(): Collection
    {
        return $this->enderecos;
    }

    public function addDossie(Dossie $dossie): self
    {
        if (!$this->dossies->contains($dossie)) {
            $this->dossies[] = $dossie;
            $dossie->setPessoa($this);
        }

        return $this;
    }

    public function removeDossie(Dossie $dossie): self
    {
        if ($this->dossies->contains($dossie)) {
            $this->dossies->removeElement($dossie);
        }

        return $this;
    }

    public function getDossies(): Collection
    {
        return $this->dossies;
    }

    public function addDocumentoIdentificador(DocumentoIdentificador $documentoIdentificador): self
    {
        if (!$this->documentosIdentificadores->contains($documentoIdentificador)) {
            $this->documentosIdentificadores[] = $documentoIdentificador;
            $documentoIdentificador->setPessoa($this);
        }

        return $this;
    }

    public function removeDocumentoIdentificador(DocumentoIdentificador $documentoIdentificador): self
    {
        if ($this->documentosIdentificadores->contains($documentoIdentificador)) {
            $this->documentosIdentificadores->removeElement($documentoIdentificador);
        }

        return $this;
    }

    public function getDocumentosIdentificadores(): Collection
    {
        return $this->documentosIdentificadores;
    }

    public function addRelacionamentoPessoal(RelacionamentoPessoal $relacionamentoPessoal): self
    {
        if (!$this->relacionamentosPessoais->contains($relacionamentoPessoal)) {
            $this->relacionamentosPessoais[] = $relacionamentoPessoal;
            $relacionamentoPessoal->setPessoa($this);
        }

        return $this;
    }

    public function removeRelacionamentoPessoal(RelacionamentoPessoal $relacionamentoPessoal): self
    {
        if ($this->relacionamentosPessoais->contains($relacionamentoPessoal)) {
            $this->relacionamentosPessoais->removeElement($relacionamentoPessoal);
        }

        return $this;
    }

    public function getRelacionamentosPessoais(): Collection
    {
        return $this->relacionamentosPessoais;
    }

    public function addOutroNome(Nome $outroNome): self
    {
        if (!$this->outrosNomes->contains($outroNome)) {
            $this->outrosNomes[] = $outroNome;
            $outroNome->setPessoa($this);
        }

        return $this;
    }

    public function removeOutroNome(Nome $outroNome): self
    {
        if ($this->outrosNomes->contains($outroNome)) {
            $this->outrosNomes->removeElement($outroNome);
        }

        return $this;
    }

    public function getOutrosNomes(): Collection
    {
        return $this->outrosNomes;
    }

    public function addInteressado(Nome $interessado): self
    {
        if (!$this->interessados->contains($interessado)) {
            $this->interessados[] = $interessado;
            $interessado->setPessoa($this);
        }

        return $this;
    }

    public function removeInteressado(Nome $interessado): self
    {
        if ($this->interessados->contains($interessado)) {
            $this->interessados->removeElement($interessado);
        }

        return $this;
    }

    public function getInteressados(): Collection
    {
        return $this->interessados;
    }

    public function getVinculacaoPessoaBarramento(): ?VinculacaoPessoaBarramentoEntity
    {
        return $this->vinculacaoPessoaBarramento;
    }

    public function setVinculacaoPessoaBarramento(?VinculacaoPessoaBarramentoEntity $vinculacaoPessoaBarramento): self
    {
        $this->vinculacaoPessoaBarramento = $vinculacaoPessoaBarramento;

        return $this;
    }

    public function getModalidadeNaturezaJuridica(): ?ModalidadeNaturezaJuridica
    {
        return $this->modalidadeNaturezaJuridica;
    }

    public function setModalidadeNaturezaJuridica(?ModalidadeNaturezaJuridica $modalidadeNaturezaJuridica): self
    {
        $this->modalidadeNaturezaJuridica = $modalidadeNaturezaJuridica;

        return $this;
    }
}
