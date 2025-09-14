<?php

declare(strict_types=1);
/**
 * /src/Document/EspecieProcesso.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class EspecieProcesso.
 *
 * @ES\ObjectType()
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieProcesso
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
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\GeneroProcesso")
     */
    protected ArrayCollection $generoProcesso;

    /**
     * EspecieProcesso constructor.
     */
    public function __construct()
    {
        $this->generoProcesso = new ArrayCollection();
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
     * @return EspecieProcesso
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
     * @return EspecieProcesso
     */
    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getGeneroProcesso(): ArrayCollection
    {
        return $this->generoProcesso;
    }

    /**
     * @param GeneroProcesso $generoProcesso
     * @return EspecieProcesso
     */
    public function setGeneroProcesso(GeneroProcesso $generoProcesso): self
    {
        $this->generoProcesso->add($generoProcesso);

        return $this;
    }
}
