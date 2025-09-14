<?php

declare(strict_types=1);
/**
 * /src/Document/RamoDireito.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class ModalidadeUrn.
 *
 * @ES\ObjectType()
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeUrn
{
    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $valor;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $descricao;

    /**
     * @ES\Property(type="boolean")
     */
    protected bool $ativo;

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
    public function getValor(): string
    {
        return $this->valor;
    }

    /**
     * @param string $valor
     *
     * @return $this
     */
    public function setValor(string $valor): self
    {
        $this->valor = $valor;

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
}
