<?php

declare(strict_types=1);
/**
 * /src/Document/Repositorio.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class Repositorio.
 *
 * @ES\Index(
 *     numberOfShards=2,
 *     numberOfReplicas=1
 * )
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Repositorio
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
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $descricao;

    /**
     * @ES\Property(type="boolean")
     */
    protected bool $ativo;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\ModalidadeRepositorio")
     */
    protected ArrayCollection $modalidadeRepositorio;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Documento")
     */
    protected ArrayCollection $documento;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\VinculacaoRepositorio")
     */
    protected ArrayCollection $vinculacoesRepositorios;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\ComponenteDigital")
     */
    protected ArrayCollection $componentesDigitais;

    /**
     * Repositorio constructor.
     */
    public function __construct()
    {
        $this->vinculacoesRepositorios = new ArrayCollection();
        $this->modalidadeRepositorio = new ArrayCollection();
        $this->documento = new ArrayCollection();
        $this->componentesDigitais = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Repositorio
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     *
     * @return Repositorio
     */
    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao(): string
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     *
     * @return Repositorio
     */
    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    /**
     * @param bool $ativo
     *
     * @return Repositorio
     */
    public function setAtivo(bool $ativo): self
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * @param VinculacaoRepositorio $vinculacaoRepositorio
     *
     * @return Repositorio
     */
    public function setVinculacoesRepositorios(VinculacaoRepositorio $vinculacaoRepositorio): self
    {
        $this->vinculacoesRepositorios->add($vinculacaoRepositorio);

        return $this;
    }

    /**
     * @return ArrayCollection<VinculacaoRepositorio>
     */
    public function getVinculacoesRepositorios(): ArrayCollection
    {
        return $this->vinculacoesRepositorios;
    }

    /**
     * @return ArrayCollection<ModalidadeRepositorio>
     */
    public function getModalidadeRepositorio(): ArrayCollection
    {
        return $this->modalidadeRepositorio;
    }

    /**
     * @param ModalidadeRepositorio $modalidadeRepositorio
     *
     * @return Repositorio
     */
    public function setModalidadeRepositorio(ModalidadeRepositorio $modalidadeRepositorio): self
    {
        $this->modalidadeRepositorio->add($modalidadeRepositorio);

        return $this;
    }

    /**
     * @return ArrayCollection<Documento>
     */
    public function getDocumento(): ArrayCollection
    {
        return $this->documento;
    }

    /**
     * @param Documento $documento
     *
     * @return Repositorio
     */
    public function setDocumento(Documento $documento): self
    {
        $this->documento->add($documento);

        return $this;
    }

    /**
     * @param ComponenteDigital $componenteDigital
     *
     * @return Repositorio
     */
    public function setComponentesDigitais(ComponenteDigital $componenteDigital): self
    {
        $this->componentesDigitais->add($componenteDigital);

        return $this;
    }

    /**
     * @return ArrayCollection<ComponenteDigital>
     */
    public function getComponentesDigitais(): ArrayCollection
    {
        return $this->componentesDigitais;
    }
}
