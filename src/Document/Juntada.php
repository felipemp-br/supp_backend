<?php

declare(strict_types=1);
/**
 * /src/Document/Juntada.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class Juntada.
 *
 * @ES\ObjectType()
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Juntada
{
    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Property(type="date")
     */
    protected ?DateTime $criadoEm = null;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Volume")
     */
    protected ArrayCollection $volume;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Usuario")
     */
    protected ArrayCollection $criadoPor;

    /**
     * @ES\Property(type="integer")
     */
    protected ?int $numeracaoSequencial = null;

    /**
     * Juntada constructor.
     */
    public function __construct()
    {
        $this->volume = new ArrayCollection();
        $this->criadoPor = new ArrayCollection();
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
     * @return Juntada
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCriadoEm(): ?DateTime
    {
        return $this->criadoEm;
    }

    /**
     * @param DateTime|null $criadoEm
     *
     * @return Juntada
     */
    public function setCriadoEm(?DateTime $criadoEm): self
    {
        $this->criadoEm = $criadoEm;

        return $this;
    }

    /**
     * @return ArrayCollection<Volume>
     */
    public function getVolume(): ArrayCollection
    {
        return $this->volume;
    }

    /**
     * @param Volume $volume
     *
     * @return Juntada
     */
    public function setVolume(Volume $volume): self
    {
        $this->volume->add($volume);

        return $this;
    }

    /**
     * @return ArrayCollection<Usuario>
     */
    public function getCriadoPor(): ArrayCollection
    {
        return $this->criadoPor;
    }

    /**
     * @param Usuario $criadoPor
     *
     * @return Juntada
     */
    public function setCriadoPor(Usuario $criadoPor): self
    {
        $this->criadoPor->add($criadoPor);

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
     * @return ComponenteDigital
     */
    public function setNumeracaoSequencial(?int $numeracaoSequencial): self
    {
        $this->numeracaoSequencial = $numeracaoSequencial;

        return $this;
    }
}
