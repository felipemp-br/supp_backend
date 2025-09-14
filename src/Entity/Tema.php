<?php

declare(strict_types=1);
/**
 * /src/Entity/Tema.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable\Enableable;
use SuppCore\AdministrativoBackend\Entity\Traits\Ativo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Sigla;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Tema.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[UniqueEntity(fields: ['nome', 'ramoDireito'], message: 'Nome já está em utilização!')]
#[Enableable]
#[ORM\Table(name: 'ad_tema')]
#[ORM\UniqueConstraint(columns: ['nome', 'ramo_direito_id', 'apagado_em'])]
class Tema implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Nome;
    use Sigla;
    use Ativo;

    #[ORM\ManyToOne(targetEntity: 'RamoDireito', cascade: ['persist'], inversedBy: 'temas')]
    #[ORM\JoinColumn(name: 'ramo_direito_id', referencedColumnName: 'id', nullable: false)]
    protected RamoDireito $ramoDireito;

    /**
     * @var Collection<Tese>|ArrayCollection<Tese>
     */
    #[ORM\OneToMany(mappedBy: 'tema', targetEntity: 'SuppCore\AdministrativoBackend\Entity\Tese', cascade: ['all'])]
    protected Collection|ArrayCollection $teses;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->teses = new ArrayCollection();
    }

    public function getRamoDireito(): RamoDireito
    {
        return $this->ramoDireito;
    }

    public function setRamoDireito(RamoDireito $ramoDireito): self
    {
        $this->ramoDireito = $ramoDireito;

        return $this;
    }

    public function getTeses(): ArrayCollection|Collection
    {
        return $this->teses;
    }

    public function addTese(Tese $tese): self
    {
        if (!$this->teses->contains($tese)) {
            $this->teses->add($tese);
        }

        return $this;
    }

    public function removeTese(Tese $tese): self
    {
        if ($this->teses->contains($tese)) {
            $this->teses->remove($tese);
        }
    }
}
