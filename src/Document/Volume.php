<?php

declare(strict_types=1);
/**
 * /src/Document/Volume.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class Volume.
 *
 * @ES\ObjectType()
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Volume
{
    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\ProcessoVolume")
     */
    protected ArrayCollection $processo;

    /**
     * @ES\Property(type="integer")
     */
    protected ?int $numeracaoSequencial = null;

    /**
     * Volume constructor.
     */
    public function __construct()
    {
        $this->processo = new ArrayCollection();
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
     * @return Volume
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return ArrayCollection<Processo>
     */
    public function getProcesso(): ArrayCollection
    {
        return $this->processo;
    }

    /**
     * @param ProcessoVolume $processo
     *
     * @return Volume
     */
    public function setProcesso(ProcessoVolume $processo): self
    {
        $this->processo->add($processo);

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNumeracaoSequencial(): ?int
    {
        return $this->numeracaoSequencial;
    }

    /**
     * @param int|null $numeracaoSequencial
     *
     * @return Volume
     */
    public function setNumeracaoSequencial(?int $numeracaoSequencial): self
    {
        $this->numeracaoSequencial = $numeracaoSequencial;

        return $this;
    }
}
