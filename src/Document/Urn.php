<?php

declare(strict_types=1);
/**
 * /src/Document/Urn.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Datetime;
use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class Urn.
 *
 * @ES\ObjectType()
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Urn
{

    public function __construct()
    {
        $this->modalidadeUrn = new ArrayCollection();
    }

    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $urn;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $tituloDispositivo;

    /**
     * @ES\Property(type="boolean")
     */
    protected bool $ativo;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\ModalidadeUrn")
     */
    protected ArrayCollection $modalidadeUrn;

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
    public function getUrn(): string
    {
        return $this->urn;
    }

    /**
     * @param string $urn
     * @return $this
     */
    public function setUrn(string $urn): self
    {
        $this->urn = $urn;

        return $this;
    }

    /**
     * @return string
     */
    public function getTituloDispositivo(): string
    {
        return $this->tituloDispositivo;
    }

    /**
     * @param string $tituloDispositivo
     * @return $this
     */
    public function setTituloDispositivo(string $tituloDispositivo): self
    {
        $this->tituloDispositivo = $tituloDispositivo;

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
     * @return ArrayCollection
     */
    public function getModalidadeUrn(): ArrayCollection
    {
        return $this->modalidadeUrn;
    }

    /**
     * @param ModalidadeUrn $modalidadeUrn
     * @return $this
     */
    public function setModalidadeUrn(ModalidadeUrn $modalidadeUrn): self
    {
        $this->modalidadeUrn->add($modalidadeUrn);

        return $this;
    }
}
