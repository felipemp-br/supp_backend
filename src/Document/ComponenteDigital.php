<?php

declare(strict_types=1);
/**
 * /src/Document/ComponenteDigital.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class ComponenteDigital.
 *
 * @ES\Index(
 *     numberOfShards=2,
 *     numberOfReplicas=1
 * )
 * @ES\ObjectType()
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ComponenteDigital
{
    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Property(type="text")
     */
    protected ?string $fileName = null;

    /**
     * @ES\Property(type="text", analyzer="attachment_analyzer")
     */
    protected string $conteudo;

    /**
     * @ES\Property(type="date")
     */
    protected ?DateTime $criadoEm = null;

    /**
     * @ES\Property(type="text")
     */
    protected ?string $extensao = null;

    /**
     * @ES\Property(type="boolean")
     */
    protected ?bool $editavel = null;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected ?string $numeroUnicoDocumento = null;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Usuario")
     */
    protected ArrayCollection $criadoPor;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\DocumentoOrigem")
     */
    protected ArrayCollection $documento;

    /**
     * ComponenteDigital constructor.
     */
    public function __construct()
    {
        $this->criadoPor = new ArrayCollection();
        $this->documento = new ArrayCollection();
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
     * @return ComponenteDigital
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     *
     * @return ComponenteDigital
     */
    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return string
     */
    public function getConteudo(): string
    {
        return $this->conteudo;
    }

    /**
     * @param string $conteudo
     *
     * @return ComponenteDigital
     */
    public function setConteudo(string $conteudo): self
    {
        $this->conteudo = $conteudo;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCriadoEm(): ?DateTime
    {
        return $this->criadoEm;
    }

    /**
     * @param DateTime $criadoEm
     *
     * @return ComponenteDigital
     */
    public function setCriadoEm(DateTime $criadoEm): self
    {
        $this->criadoEm = $criadoEm;

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
     * @return ComponenteDigital
     */
    public function setCriadoPor(Usuario $criadoPor): self
    {
        $this->criadoPor->add($criadoPor);

        return $this;
    }

    /**
     * @return string
     */
    public function getExtensao(): ?string
    {
        return $this->extensao;
    }

    /**
     * @param string $extensao
     *
     * @return ComponenteDigital
     */
    public function setExtensao(string $extensao): self
    {
        $this->extensao = $extensao;

        return $this;
    }

    /**
     * @return bool
     */
    public function getEditavel(): ?bool
    {
        return $this->editavel;
    }

    /**
     * @param bool $editavel
     *
     * @return ComponenteDigital
     */
    public function setEditavel(bool $editavel): self
    {
        $this->editavel = $editavel;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNumeroUnicoDocumento(): ?string
    {
        return $this->numeroUnicoDocumento;
    }

    /**
     * @param string|null $numeroUnicoDocumento
     *
     * @return ComponenteDigital
     */
    public function setNumeroUnicoDocumento(?string $numeroUnicoDocumento): self
    {
        $this->numeroUnicoDocumento = $numeroUnicoDocumento;

        return $this;
    }

    /**
     * @return ArrayCollection<DocumentoOrigem>
     */
    public function getDocumento(): ArrayCollection
    {
        return $this->documento;
    }

    /**
     * @param DocumentoOrigem $documento
     *
     * @return ComponenteDigital
     */
    public function setDocumento(DocumentoOrigem $documento): self
    {
        $this->documento->add($documento);

        return $this;
    }
}
