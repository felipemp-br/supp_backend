<?php

declare(strict_types=1);
/**
 * /src/Document/Tese.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Datetime;
use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class Tese.
 *
 * @ES\Index(
 *     numberOfShards=2,
 *     numberOfReplicas=1
 * )
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Tese
{

    public function __construct()
    {
        $this->tema = new ArrayCollection();
        $this->vinculacoesMetadados = new ArrayCollection();
        $this->vinculacoesOrgaoCentralMetadados = new ArrayCollection();
    }

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
    protected string $sigla;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $enunciado;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $ementa;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $keywords;

    /**
     * @ES\Property(type="boolean")
     */
    protected bool $ativo;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Tema")
     */
    protected ArrayCollection $tema;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\VinculacaoMetadados")
     */
    protected ArrayCollection $vinculacoesMetadados;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\VinculacaoOrgaoCentralMetadados")
     */
    protected ArrayCollection $vinculacoesOrgaoCentralMetadados;

    /**
     * @ES\Property(type="date")
     */
    protected ?DateTime $criadoEm = null;

    /**
     * @ES\Property(type="date")
     */
    protected ?DateTime $atualizadoEm = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
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
     * @return $this
     */
    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * @return string
     */
    public function getSigla(): string
    {
        return $this->sigla;
    }

    /**
     * @param string $sigla
     * @return $this
     */
    public function setSigla(string $sigla): self
    {
        $this->sigla = $sigla;

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
     * @return $this
     */
    public function setAtivo(bool $ativo): self
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * @return Datetime|null
     */
    public function getCriadoEm(): ?Datetime
    {
        return $this->criadoEm;
    }

    /**
     * @param Datetime|null $criadoEm
     * @return $this
     */
    public function setCriadoEm(?Datetime $criadoEm): self
    {
        $this->criadoEm = $criadoEm;

        return $this;
    }

    /**
     * @return Datetime|null
     */
    public function getAtualizadoEm(): ?Datetime
    {
        return $this->atualizadoEm;
    }

    /**
     * @param Datetime|null $atualizadoEm
     * @return $this
     */
    public function setAtualizadoEm(?Datetime $atualizadoEm): self
    {
        $this->atualizadoEm = $atualizadoEm;

        return $this;
    }

    /**
     * @return string
     */
    public function getEnunciado(): string
    {
        return $this->enunciado;
    }

    /**
     * @param string $enunciado
     * @return $this
     */
    public function setEnunciado(string $enunciado): self
    {
        $this->enunciado = $enunciado;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmenta(): string
    {
        return $this->ementa;
    }

    /**
     * @param string $ementa
     * @return $this
     */
    public function setEmenta(string $ementa): self
    {
        $this->ementa = $ementa;

        return $this;
    }

    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * @param string $keywords
     * @return $this
     */
    public function setKeywords(string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTema(): ArrayCollection
    {
        return $this->tema;
    }

    /**
     * @param ArrayCollection $tema
     * @return $this
     */
    public function setTema(Tema $tema): self
    {
        $this->tema->add($tema);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getVinculacoesMetadados(): ArrayCollection
    {
        return $this->vinculacoesMetadados;
    }

    /**
     * @param VinculacaoMetadados $vinculacoesMetadados
     * @return $this
     */
    public function setVinculacoesMetadados(VinculacaoMetadados $vinculacoesMetadados): self
    {
        $this->vinculacoesMetadados->add($vinculacoesMetadados);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getVinculacoesOrgaoCentralMetadados(): ArrayCollection
    {
        return $this->vinculacoesOrgaoCentralMetadados;
    }

    /**
     * @param VinculacaoOrgaoCentralMetadados $vinculacoesOrgaoCentralMetadados
     * @return $this
     */
    public function setVinculacoesOrgaoCentralMetadados(VinculacaoOrgaoCentralMetadados $vinculacoesOrgaoCentralMetadados): self
    {
        $this->vinculacoesOrgaoCentralMetadados->add($vinculacoesOrgaoCentralMetadados);

        return $this;
    }
}
