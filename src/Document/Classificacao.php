<?php

declare(strict_types=1);
/**
 * /src/Document/Classificacao.php.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class Classificacao.
 *
 * @ES\ObjectType()
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Classificacao
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
     * @return Classificacao
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
     * @return Classificacao
     */
    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }
}
