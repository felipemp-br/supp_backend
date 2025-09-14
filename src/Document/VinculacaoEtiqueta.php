<?php
declare(strict_types=1);

/**
 * /src/Document/VinculacaoEtiqueta.php.
 *
 */

namespace SuppCore\AdministrativoBackend\Document;

use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class VinculacaoEtiqueta.
 *
 * @ES\ObjectType()
 */
class VinculacaoEtiqueta
{
    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Etiqueta")
     */
    protected ArrayCollection $etiqueta;

    /**
     * VinculacaoEtiqueta constructor.
     */
    public function __construct()
    {
        $this->etiqueta = new ArrayCollection();
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
     * @return VinculacaoEtiqueta
     */
    public function setId(int $id): VinculacaoEtiqueta
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getEtiqueta(): ArrayCollection
    {
        return $this->etiqueta;
    }

    /**
     * @param Etiqueta $etiqueta
     * @return VinculacaoEtiqueta
     */
    public function setEtiqueta(Etiqueta $etiqueta): VinculacaoEtiqueta
    {
        $this->etiqueta->add($etiqueta);

        return $this;
    }
}
