<?php

declare(strict_types=1);
/**
 * /src/Document/SetorAtual.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class SetorAtual.
 *
 * @ES\ObjectType()
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SetorAtual
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
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Unidade")
     */
    protected ArrayCollection $unidade;

    /**
     * Setor constructor.
     */
    public function __construct()
    {
        $this->unidade = new ArrayCollection();
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
     * @return SetorAtual
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
     * @return SetorAtual
     */
    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * @return ArrayCollection<Unidade>
     */
    public function getUnidade(): ArrayCollection
    {
        return $this->unidade;
    }

    /**
     * @param Unidade $unidade
     *
     * @return SetorAtual
     */
    public function setUnidade(Unidade $unidade): self
    {
        $this->unidade->add($unidade);

        return $this;
    }
}
