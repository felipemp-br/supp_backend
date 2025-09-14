<?php
declare(strict_types=1);

/**
 * /src/Document/Etiqueta.php.
 *
 */

namespace SuppCore\AdministrativoBackend\Document;

use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class VinculacaoProcesso.
 *
 * @ES\ObjectType()

 */
class Etiqueta
{
    /**
     * @ES\Id()
     */
    protected int $id;


    /**
     * Etiqueta constructor.
     */
    public function __construct()
    {
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
     * @return Etiqueta
     */
    public function setId(int $id): Etiqueta
    {
        $this->id = $id;

        return $this;
    }
}
