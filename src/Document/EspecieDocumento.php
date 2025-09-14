<?php

declare(strict_types=1);
/**
 * /src/Document/EspecieDocumento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class EspecieDocumento.
 *
 * @ES\ObjectType()
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieDocumento
{
    public function __construct()
    {
        $this->generoDocumento = new ArrayCollection();
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
    protected string $descricao;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $sigla;

    /**
     * @ES\Property(type="boolean")
     */
    protected bool $ativo;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\GeneroDocumento")
     */
    protected ArrayCollection $generoDocumento;

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
     *
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
     *
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
     *
     * @return $this
     */
    public function setAtivo(bool $ativo): self
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getGeneroDocumento(): ArrayCollection
    {
        return $this->generoDocumento;
    }

    /**
     * @param ArrayCollection $generoDocumento
     *
     * @return $this
     */
    public function setGeneroDocumento(GeneroDocumento $generoDocumento): self
    {
        $this->generoDocumento->add($generoDocumento);

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
     * @return $this
     */
    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }
}
