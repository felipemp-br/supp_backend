<?php

declare(strict_types=1);
/**
 * /src/Document/GeneroProcesso.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class GeneroProcesso.
 *
 * @ES\ObjectType()
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroProcesso
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return GeneroProcesso
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
     * @return GeneroProcesso
     */
    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }
}
