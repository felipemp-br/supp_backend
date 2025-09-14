<?php

declare(strict_types=1);
/**
 * /src/Document/VinculacaoMetadados.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Datetime;
use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class VinculacaoMetadados.
 *
 * @ES\ObjectType()
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoMetadados
{

    public function __construct()
    {
        $this->urn = new ArrayCollection();
    }

    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $idDispositivo;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $textoDispositivo;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Urn")
     */
    protected ArrayCollection $urn;

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
    public function getIdDispositivo(): string
    {
        return $this->idDispositivo;
    }

    /**
     * @param string $idDispositivo
     * @return $this
     */
    public function setIdDispositivo(string $idDispositivo): self
    {
        $this->idDispositivo = $idDispositivo;

        return $this;
    }

    /**
     * @return string
     */
    public function getTextoDispositivo(): string
    {
        return $this->textoDispositivo;
    }

    /**
     * @param string $textoDispositivo
     * @return $this
     */
    public function setTextoDispositivo(string $textoDispositivo): self
    {
        $this->textoDispositivo = $textoDispositivo;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getUrn(): ArrayCollection
    {
        return $this->urn;
    }

    /**
     * @param ArrayCollection $urn
     * @return $this
     */
    public function setUrn(Urn $urn): self
    {
        $this->urn->add($urn);

        return $this;
    }
    
}
