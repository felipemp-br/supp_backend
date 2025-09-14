<?php

declare(strict_types=1);
/**
 * /src/Document/Pessoa.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class Pessoa.
 *
 * @ES\Index(
 *     numberOfShards=2,
 *     numberOfReplicas=1
 * )
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pessoa
{
    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $nome;

    /**
     * @ES\Property(type="date")
     */
    protected ?DateTime $dataNascimento = null;

    /**
     * @ES\Property(type="date")
     */
    protected ?DateTime $dataObito = null;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected ?string $nomeGenitora = null;

    /**
     * @ES\Property(type="boolean")
     */
    protected bool $pessoaValidada;

    /**
     * @ES\Property(type="boolean")
     */
    protected bool $pessoaConveniada;

    /**
     * @ES\Property(type="text")
     */
    protected ?string $numeroDocumentoPrincipal = null;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\ModalidadeGeneroPessoa")
     */
    protected ArrayCollection $modalidadeGeneroPessoa;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\ModalidadeQualificacaoPessoa")
     */
    protected ArrayCollection $modalidadeQualificacaoPessoa;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\OrigemDados")
     */
    protected ?ArrayCollection $origemDados;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\VinculacaoPessoaBarramento")
     */
    protected ArrayCollection $vinculacaoPessoaBarramento;

    /**
     * Pessoa constructor.
     */
    public function __construct()
    {
        $this->modalidadeGeneroPessoa = new ArrayCollection();
        $this->modalidadeQualificacaoPessoa = new ArrayCollection();
        $this->origemDados = new ArrayCollection();
        $this->vinculacaoPessoaBarramento = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Pessoa
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @return Pessoa
     */
    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getDataNascimento(): ?DateTime
    {
        return $this->dataNascimento;
    }

    /**
     * @return Pessoa
     */
    public function setDataNascimento(?DateTime $dataNascimento): self
    {
        $this->dataNascimento = $dataNascimento;

        return $this;
    }

    public function getDataObito(): ?DateTime
    {
        return $this->dataObito;
    }

    /**
     * @return Pessoa
     */
    public function setDataObito(?DateTime $dataObito): self
    {
        $this->dataObito = $dataObito;

        return $this;
    }

    public function getNomeGenitora(): ?string
    {
        return $this->nomeGenitora;
    }

    /**
     * @return Pessoa
     */
    public function setNomeGenitora(?string $nomeGenitora): self
    {
        $this->nomeGenitora = $nomeGenitora;

        return $this;
    }

    public function getPessoaValidada(): bool
    {
        return $this->pessoaValidada;
    }

    /**
     * @return Pessoa
     */
    public function setPessoaValidada(bool $pessoaValidada): self
    {
        $this->pessoaValidada = $pessoaValidada;

        return $this;
    }

    public function getPessoaConveniada(): bool
    {
        return $this->pessoaConveniada;
    }

    /**
     * @return Pessoa
     */
    public function setPessoaConveniada(bool $pessoaConveniada): self
    {
        $this->pessoaConveniada = $pessoaConveniada;

        return $this;
    }

    public function getNumeroDocumentoPrincipal(): ?string
    {
        return $this->numeroDocumentoPrincipal;
    }

    /**
     * @return Pessoa
     */
    public function setNumeroDocumentoPrincipal(?string $numeroDocumentoPrincipal): self
    {
        $this->numeroDocumentoPrincipal = $numeroDocumentoPrincipal;

        return $this;
    }

    /**
     * @return ArrayCollection<ModalidadeGeneroPessoa>
     */
    public function getModalidadeGeneroPessoa(): ArrayCollection
    {
        return $this->modalidadeGeneroPessoa;
    }

    /**
     * @return Pessoa
     */
    public function setModalidadeGeneroPessoa(ModalidadeGeneroPessoa $modalidadeGeneroPessoa): self
    {
        $this->modalidadeGeneroPessoa->add($modalidadeGeneroPessoa);

        return $this;
    }

    /**
     * @return ArrayCollection<ModalidadeQualificacaoPessoa>
     */
    public function getModalidadeQualificacaoPessoa(): ArrayCollection
    {
        return $this->modalidadeQualificacaoPessoa;
    }

    /**
     * @return Pessoa
     */
    public function setModalidadeQualificacaoPessoa(ModalidadeQualificacaoPessoa $modalidadeQualificacaoPessoa): self
    {
        $this->modalidadeQualificacaoPessoa->add($modalidadeQualificacaoPessoa);

        return $this;
    }

    /**
     * @return ArrayCollection<OrigemDados>
     */
    public function getOrigemDados(): ArrayCollection
    {
        return $this->origemDados;
    }

    /**
     * @return Pessoa
     */
    public function setOrigemDados(?OrigemDados $origemDados): self
    {
        $this->origemDados->add($origemDados);

        return $this;
    }

    /**
     * @return ArrayCollection<VinculacaoPessoaBarramento>
     */
    public function getVinculacaoPessoaBarramento(): ArrayCollection
    {
        return $this->vinculacaoPessoaBarramento;
    }

    /**
     * @param VinculacaoPessoaBarramento $vinculacaoPessoaBarramento
     *
     * @return Pessoa
     */
    public function setVinculacaoPessoaBarramento(VinculacaoPessoaBarramento $vinculacaoPessoaBarramento): self
    {
        $this->vinculacaoPessoaBarramento->add($vinculacaoPessoaBarramento);

        return $this;
    }
}
