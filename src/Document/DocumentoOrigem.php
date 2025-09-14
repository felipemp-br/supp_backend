<?php

declare(strict_types=1);
/**
 * /src/Document/DocumentoOrigem.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class DocumentoOrigem.
 *
 * @ES\ObjectType()
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DocumentoOrigem
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
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Setor")
     */
    protected ArrayCollection $setorOrigem;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected ?string $autor;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected ?string $redator;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected ?string $destinatario;

    /**
     * Documento constructor.
     */
    public function __construct()
    {
        $this->tipoDocumento = new ArrayCollection();
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
     * @return DocumentoOrigem
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
     * @return DocumentoOrigem
     */
    public function setTipoDocumento(TipoDocumento $tipoDocumento): self
    {
        $this->tipoDocumento->add($tipoDocumento);

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
     * @return DocumentoOrigem
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
     * @return DocumentoOrigem
     */
    public function setSetorOrigem(Setor $setorOrigem): self
    {
        $this->setorOrigem->add($setorOrigem);

        return $this;
    }

    /**
     * @return null|string
     */
    public function getAutor(): ?string
    {
        return $this->autor;
    }

    /**
     * @param null|string $autor
     *
     * @return DocumentoOrigem
     */
    public function setAutor(?string $autor): self
    {
        $this->autor = $autor;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getRedator(): ?string
    {
        return $this->redator;
    }

    /**
     * @param null|string $redator
     *
     * @return DocumentoOrigem
     */
    public function setRedator(?string $redator): self
    {
        $this->redator = $redator;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDestinatario(): ?string
    {
        return $this->destinatario;
    }

    /**
     * @param null|string $destinatario
     *
     * @return DocumentoOrigem
     */
    public function setDestinatario(?string $destinatario): self
    {
        $this->destinatario = $destinatario;

        return $this;
    }
}
