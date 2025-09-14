<?php

declare(strict_types=1);
/**
 * /src/Document/Setor.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class Setor.
 *
 * @ES\ObjectType()
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Setor
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
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\EspecieSetor")
     */
    protected ArrayCollection $especieSetor;

    /**
     * Setor constructor.
     */
    public function __construct()
    {
        $this->especieSetor = new ArrayCollection();
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
     * @return Setor
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
     * @return Setor
     */
    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * @return ArrayCollection<EspecieSetor>
     */
    public function getEspecieSetor(): ArrayCollection
    {
        return $this->especieSetor;
    }

    /**
     * @param EspecieSetor $especieSetor
     *
     * @return Setor
     */
    public function setEspecieSetor(EspecieSetor $especieSetor): self
    {
        $this->especieSetor->add($especieSetor);

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
     * @return Setor
     */
    public function setUnidade(Unidade $unidade): self
    {
        $this->unidade->add($unidade);

        return $this;
    }
}
