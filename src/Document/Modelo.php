<?php

declare(strict_types=1);
/**
 * /src/Document/Modelo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Datetime;
use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class Modelo.
 *
 * @ES\Index(
 *     numberOfShards=2,
 *     numberOfReplicas=1
 * )
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Modelo
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
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\ModalidadeModelo")
     */
    protected ArrayCollection $modalidadeModelo;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Documento")
     */
    protected ArrayCollection $documento;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\VinculacaoModelo")
     */
    protected ArrayCollection $vinculacoesModelos;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\ComponenteDigital")
     */
    protected ArrayCollection $componentesDigitais;

    /**
     * @ES\Property(type="date")
     */
    protected ?DateTime $criadoEm = null;

    /**
     * @ES\Property(type="date")
     */
    protected ?DateTime $atualizadoEm = null;

    /**
     * Modelo constructor.
     */
    public function __construct()
    {
        $this->vinculacoesModelos = new ArrayCollection();
        $this->modalidadeModelo = new ArrayCollection();
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
     * @return Modelo
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
     * @return Modelo
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
     * @return Modelo
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
     * @return Modelo
     */
    public function setAtivo(bool $ativo): self
    {
        $this->ativo = $ativo;

        return $this;
    }


    /**
     * @return DateTime|null
     */
    public function getCriadoEm(): ?DateTime
    {
        return $this->criadoEm;
    }

    /**
     * @param DateTime|null $criadoEm
     *
     * @return self
     */
    public function setCriadoEm(?DateTime $criadoEm): self
    {
        $this->criadoEm = $criadoEm;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getAtualizadoEm(): ?DateTime
    {
        return $this->atualizadoEm;
    }

    /**
     * @param DateTime|null $atualizadoEm
     *
     * @return Modelo
     */
    public function setAtualizadoEm(?DateTime $atualizadoEm): Modelo
    {
        $this->atualizadoEm = $atualizadoEm;

        return $this;
    }

    /**
     * @param VinculacaoModelo $vinculacaoModelo
     *
     * @return Modelo
     */
    public function setVinculacoesModelos(VinculacaoModelo $vinculacaoModelo): self
    {
        $this->vinculacoesModelos->add($vinculacaoModelo);

        return $this;
    }

    /**
     * @return ArrayCollection<VinculacaoModelo>
     */
    public function getVinculacoesModelos(): ArrayCollection
    {
        return $this->vinculacoesModelos;
    }

    /**
     * @return ArrayCollection<ModalidadeModelo>
     */
    public function getModalidadeModelo(): ArrayCollection
    {
        return $this->modalidadeModelo;
    }

    /**
     * @param ModalidadeModelo $modalidadeModelo
     *
     * @return Modelo
     */
    public function setModalidadeModelo(ModalidadeModelo $modalidadeModelo): self
    {
        $this->modalidadeModelo->add($modalidadeModelo);

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
     * @return Modelo
     */
    public function setDocumento(Documento $documento): self
    {
        $this->documento->add($documento);

        return $this;
    }

    /**
     * @param ComponenteDigital $componenteDigital
     *
     * @return Modelo
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
