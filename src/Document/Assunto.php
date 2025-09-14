<?php
declare(strict_types=1);
/**
 * /src/Document/Assunto.php.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class Assunto.
 *
 * @ES\ObjectType()
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Assunto
{
    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\AssuntoAdministrativo")
     */
    protected ArrayCollection $assuntoAdministrativo;

    /**
     * @ES\Property(type="boolean")
     */
    protected bool $principal;

    /**
     * Assunto constructor.
     */
    public function __construct()
    {
        $this->assuntoAdministrativo = new ArrayCollection();
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
     * @return Assunto
     */
    public function setId(int $id): Assunto
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAssuntoAdministrativo(): ArrayCollection
    {
        return $this->assuntoAdministrativo;
    }

    /**
     * @param AssuntoAdministrativo $assuntoAdministrativo
     * @return Assunto
     */
    public function setAssuntoAdministrativo(AssuntoAdministrativo $assuntoAdministrativo): Assunto
    {
        $this->assuntoAdministrativo->add($assuntoAdministrativo);

        return $this;
    }

    /**
     * @return bool
     */
    public function getPrincipal(): bool
    {
        return $this->principal;
    }

    /**
     * @param bool $principal
     * @return Assunto
     */
    public function setPrincipal(bool $principal): Assunto
    {
        $this->principal = $principal;

        return $this;
    }
}
