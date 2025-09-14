<?php

declare(strict_types=1);
/**
 * /src/Entity/Interessado.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Interessado.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_interessado')]
class Interessado implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Processo', inversedBy: 'interessados')]
    #[ORM\JoinColumn(name: 'processo_id', referencedColumnName: 'id', nullable: false)]
    protected Processo $processo;

    #[Assert\NotNull(message: 'Campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeInteressado')]
    #[ORM\JoinColumn(name: 'mod_interessado_id', referencedColumnName: 'id', nullable: false)]
    protected ModalidadeInteressado $modalidadeInteressado;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Pessoa', inversedBy: 'interessados')]
    #[ORM\JoinColumn(name: 'pessoa_id', referencedColumnName: 'id', nullable: false)]
    protected Pessoa $pessoa;

    #[ORM\ManyToOne(targetEntity: 'OrigemDados', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'origem_dados_id', referencedColumnName: 'id', nullable: true)]
    protected ?OrigemDados $origemDados = null;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<Representante>
     */
    #[ORM\OneToMany(mappedBy: 'interessado', targetEntity: 'Representante', cascade: ['all'])]
    protected $representantes;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->representantes = new ArrayCollection();
    }

    public function getProcesso(): Processo
    {
        return $this->processo;
    }

    public function setProcesso(Processo $processo): self
    {
        $this->processo = $processo;

        return $this;
    }

    public function getModalidadeInteressado(): ModalidadeInteressado
    {
        return $this->modalidadeInteressado;
    }

    public function setModalidadeInteressado(ModalidadeInteressado $modalidadeInteressado): self
    {
        $this->modalidadeInteressado = $modalidadeInteressado;

        return $this;
    }

    public function getPessoa(): Pessoa
    {
        return $this->pessoa;
    }

    public function setPessoa(Pessoa $pessoa): self
    {
        $this->pessoa = $pessoa;

        return $this;
    }

    public function getOrigemDados(): ?OrigemDados
    {
        return $this->origemDados;
    }

    public function setOrigemDados(?OrigemDados $origemDados): self
    {
        $this->origemDados = $origemDados;

        return $this;
    }

    public function addRepresentante(Representante $representante): self
    {
        if (!$this->representantes->contains($representante)) {
            $this->representantes[] = $representante;
            $representante->setInteressado($this);
        }

        return $this;
    }

    public function removeRepresentante(Representante $representante): self
    {
        if ($this->representantes->contains($representante)) {
            $this->representantes->removeElement($representante);
        }

        return $this;
    }

    public function getRepresentantes(): Collection
    {
        return $this->representantes;
    }
}
