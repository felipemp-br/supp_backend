<?php

declare(strict_types=1);
/**
 * /src/Entity/Volume.php.
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
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Volume.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\Loggable]
#[UniqueEntity(
    fields: [
        'numeracaoSequencial',
        'processo',
    ],
    message: 'Numeração Sequencial já está em utilização para essa processo!'
)]
#[ORM\Table(name: 'ad_volume')]
#[ORM\UniqueConstraint(columns: ['numeracao_sequencial', 'processo_id'])]
class Volume implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'numeracao_sequencial', type: 'integer', nullable: false)]
    protected int $numeracaoSequencial;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeMeio')]
    #[ORM\JoinColumn(name: 'mod_meio_id', referencedColumnName: 'id', nullable: false)]
    protected ModalidadeMeio $modalidadeMeio;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $encerrado = false;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Processo', inversedBy: 'volumes')]
    #[ORM\JoinColumn(name: 'processo_id', referencedColumnName: 'id', nullable: false)]
    protected Processo $processo;

    #[ORM\ManyToOne(targetEntity: 'OrigemDados', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'origem_dados_id', referencedColumnName: 'id', nullable: true)]
    protected ?OrigemDados $origemDados = null;

    /**
     * @var Collection|ArrayCollection|Collection<Juntada>|ArrayCollection<Juntada>
     */
    #[ORM\OneToMany(mappedBy: 'volume', targetEntity: 'Juntada', cascade: ['all'])]
    #[ORM\OrderBy(
        [
            'numeracaoSequencial' => 'ASC',
        ]
    )]
    protected $juntadas;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getNumeracaoSequencial(): int
    {
        return $this->numeracaoSequencial;
    }

    public function setNumeracaoSequencial(int $numeracaoSequencial): self
    {
        $this->numeracaoSequencial = $numeracaoSequencial;

        return $this;
    }

    public function getModalidadeMeio(): ?ModalidadeMeio
    {
        return $this->modalidadeMeio;
    }

    public function setModalidadeMeio(?ModalidadeMeio $modalidadeMeio): self
    {
        $this->modalidadeMeio = $modalidadeMeio;

        return $this;
    }

    public function getEncerrado(): bool
    {
        return $this->encerrado;
    }

    public function setEncerrado(bool $encerrado): self
    {
        $this->encerrado = $encerrado;

        return $this;
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

    public function getOrigemDados(): ?OrigemDados
    {
        return $this->origemDados;
    }

    public function setOrigemDados(?OrigemDados $origemDados): self
    {
        $this->origemDados = $origemDados;

        return $this;
    }

    public function addJuntada(Juntada $juntada): self
    {
        if (!$this->juntadas->contains($juntada)) {
            $this->juntadas[] = $juntada;
            $juntada->setVolume($this);
        }

        return $this;
    }

    public function removeJuntada(Juntada $juntada): self
    {
        if ($this->juntadas->contains($juntada)) {
            $this->juntadas->removeElement($juntada);
        }

        return $this;
    }

    public function getJuntadas(): Collection
    {
        return $this->juntadas;
    }
}
