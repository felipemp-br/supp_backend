<?php

declare(strict_types=1);
/**
 * /src/Entity/Repositorio.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable\Enableable;
use SuppCore\AdministrativoBackend\Entity\Traits\Ativo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Repositorio.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Enableable]
#[ORM\Table(name: 'ad_repositorio')]
class Repositorio implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Nome;
    use Descricao;
    use Ativo;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\OneToOne(inversedBy: 'repositorio', targetEntity: 'Documento')]
    #[ORM\JoinColumn(name: 'documento_id', referencedColumnName: 'id', nullable: false)]
    protected Documento $documento;

    #[Assert\NotNull(message: 'Campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeRepositorio')]
    #[ORM\JoinColumn(name: 'mod_repositorio_id', referencedColumnName: 'id', nullable: false)]
    protected ?ModalidadeRepositorio $modalidadeRepositorio = null;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<VinculacaoRepositorio>
     */
    #[ORM\OneToMany(mappedBy: 'repositorio', targetEntity: 'VinculacaoRepositorio')]
    protected $vinculacoesRepositorios;

    #[ORM\Column(name: 'data_hora_indexacao', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraIndexacao = null;

    /**
     * Utilizado para exibir o resumo do elasticsearch.
     */
    protected ?string $highlights = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->vinculacoesRepositorios = new ArrayCollection();
    }

    public function setModalidadeRepositorio(?ModalidadeRepositorio $modalidadeRepositorio): self
    {
        $this->modalidadeRepositorio = $modalidadeRepositorio;

        return $this;
    }

    public function getModalidadeRepositorio(): ?ModalidadeRepositorio
    {
        return $this->modalidadeRepositorio;
    }

    public function getDocumento(): Documento
    {
        return $this->documento;
    }

    public function setDocumento(Documento $documento): self
    {
        $this->documento = $documento;

        return $this;
    }

    public function getDataHoraIndexacao(): ?DateTime
    {
        return $this->dataHoraIndexacao;
    }

    public function setDataHoraIndexacao(?DateTime $dataHoraIndexacao): self
    {
        $this->dataHoraIndexacao = $dataHoraIndexacao;

        return $this;
    }

    public function getVinculacoesRepositorios(): Collection
    {
        return $this->vinculacoesRepositorios;
    }

    /**
     * Method to attach new usuario group to usuario.
     */
    public function addVinculacaoRepositorio(VinculacaoRepositorio $vinculacaoRepositorio): self
    {
        if (!$this->vinculacoesRepositorios->contains($vinculacaoRepositorio)) {
            $this->vinculacoesRepositorios->add($vinculacaoRepositorio);
            $vinculacaoRepositorio->setRepositorio($this);
        }

        return $this;
    }

    public function removeVinculacaoRepositorio(VinculacaoRepositorio $vinculacaoRepositorio): self
    {
        if ($this->vinculacoesRepositorios->contains($vinculacaoRepositorio)) {
            $this->vinculacoesRepositorios->removeElement($vinculacaoRepositorio);
        }

        return $this;
    }

    public function setHighlights(?string $highlights): self
    {
        $this->highlights = $highlights;

        return $this;
    }

    public function getHighlights(): ?string
    {
        return $this->highlights;
    }
}
