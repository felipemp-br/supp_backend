<?php

declare(strict_types=1);
/**
 * /src/Document/Documento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class Documento.
 *
 * @ES\ObjectType()
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Documento
{
    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\TipoDocumento")
     */
    protected ArrayCollection $tipoDocumento;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Juntada")
     */
    protected ArrayCollection $juntadaAtual;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\ComponenteDigital")
     */
    protected ArrayCollection $componentesDigitais;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Setor")
     */
    protected ArrayCollection $setorOrigem;

    /**
     * Documento constructor.
     */
    public function __construct()
    {
        $this->tipoDocumento = new ArrayCollection();
        $this->componentesDigitais = new ArrayCollection();
        $this->juntadaAtual = new ArrayCollection();
        $this->setorOrigem = new ArrayCollection();
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
     * @return Documento
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return ArrayCollection<TipoDocumento>
     */
    public function getTipoDocumento(): ArrayCollection
    {
        return $this->tipoDocumento;
    }

    /**
     * @param TipoDocumento $tipoDocumento
     *
     * @return Documento
     */
    public function setTipoDocumento(TipoDocumento $tipoDocumento): self
    {
        $this->tipoDocumento->add($tipoDocumento);

        return $this;
    }

    /**
     * @return ArrayCollection<ComponenteDigital>
     */
    public function getComponentesDigitais(): ArrayCollection
    {
        return $this->componentesDigitais;
    }

    /**
     * @param ComponenteDigital $componenteDigital
     *
     * @return Documento
     */
    public function setComponentesDigitais(ComponenteDigital $componenteDigital): self
    {
        $this->componentesDigitais->add($componenteDigital);

        return $this;
    }

    /**
     * @return ArrayCollection<Juntada>
     */
    public function getJuntadaAtual(): ArrayCollection
    {
        return $this->juntadaAtual;
    }

    /**
     * @param Juntada $juntadaAtual
     *
     * @return Documento
     */
    public function setJuntadaAtual(Juntada $juntadaAtual): self
    {
        $this->juntadaAtual->add($juntadaAtual);

        return $this;
    }

    /**
     * @return ArrayCollection<Setor>
     */
    public function getSetorOrigem(): ArrayCollection
    {
        return $this->setorOrigem;
    }

    /**
     * @param Setor $setorOrigem
     *
     * @return Documento
     */
    public function setSetorOrigem(Setor $setorOrigem): self
    {
        $this->setorOrigem->add($setorOrigem);

        return $this;
    }
}
