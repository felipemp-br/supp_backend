<?php

declare(strict_types=1);

/**
 * /src/Document/VinculacaoPessoaBarramento.php.
 */

namespace SuppCore\AdministrativoBackend\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class VinculacaoPessoaBarramento.
 *
 * @ES\ObjectType()
 */
class VinculacaoPessoaBarramento
{
    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Property(type="integer")
     */
    protected int $repositorio;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected ?string $nomeRepositorio = null;

    /**
     * @ES\Property(type="integer")
     */
    protected int $estrutura;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected ?string $nomeEstrutura = null;

    /**
     * VinculacaoPessoaBarramento constructor.
     */
    public function __construct()
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): VinculacaoPessoaBarramento
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getRepositorio(): int
    {
        return $this->repositorio;
    }

    /**
     * @param int $repositorio
     */
    public function setRepositorio(int $repositorio): void
    {
        $this->repositorio = $repositorio;
    }

    /**
     * @return string|null
     */
    public function getNomeRepositorio(): ?string
    {
        return $this->nomeRepositorio;
    }

    /**
     * @param string|null $nomeRepositorio
     */
    public function setNomeRepositorio(?string $nomeRepositorio): void
    {
        $this->nomeRepositorio = $nomeRepositorio;
    }

    /**
     * @return int
     */
    public function getEstrutura(): int
    {
        return $this->estrutura;
    }

    /**
     * @param int $estrutura
     */
    public function setEstrutura(int $estrutura): void
    {
        $this->estrutura = $estrutura;
    }

    /**
     * @return string|null
     */
    public function getNomeEstrutura(): ?string
    {
        return $this->nomeEstrutura;
    }

    /**
     * @param string|null $nomeEstrutura
     */
    public function setNomeEstrutura(?string $nomeEstrutura): void
    {
        $this->nomeEstrutura = $nomeEstrutura;
    }
}
