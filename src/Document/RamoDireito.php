<?php

declare(strict_types=1);
/**
 * /src/Document/RamoDireito.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Datetime;
use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class RamoDireito.
 *
 * @ES\ObjectType()
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RamoDireito
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
    protected string $sigla;

    /**
     * @ES\Property(type="boolean")
     */
    protected bool $ativo;

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
}
