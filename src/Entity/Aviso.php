<?php

declare(strict_types=1);
/**
 * /src/Entity/Aviso.php.
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
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;

/**
 * Class Aviso.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Enableable]
#[ORM\Table(name: 'ad_aviso')]
class Aviso implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Ativo;
    use Nome;
    use Descricao;
    use Id;
    use Uuid;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<VinculacaoAviso>
     */
    #[ORM\OneToMany(mappedBy: 'aviso', targetEntity: 'VinculacaoAviso')]
    protected ArrayCollection|Collection $vinculacoesAvisos;

    #[ORM\Column(name: 'sistema', type: 'boolean', nullable: false)]
    protected bool $sistema = false;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->vinculacoesAvisos = new ArrayCollection();
    }

    public function getVinculacoesAvisos(): Collection
    {
        return $this->vinculacoesAvisos;
    }

    public function addVinculacaoAviso(VinculacaoAviso $vinculacaoAviso): self
    {
        if (!$this->vinculacoesAvisos->contains($vinculacaoAviso)) {
            $this->vinculacoesAvisos->add($vinculacaoAviso);
            $vinculacaoAviso->setAviso($this);
        }

        return $this;
    }

    public function removeVinculacaoAviso(VinculacaoAviso $vinculacaoAviso): self
    {
        if ($this->vinculacoesAvisos->contains($vinculacaoAviso)) {
            $this->vinculacoesAvisos->removeElement($vinculacaoAviso);
        }

        return $this;
    }

    public function getSistema(): bool
    {
        return $this->sistema;
    }

    public function setSistema(bool $sistema): self
    {
        $this->sistema = $sistema;

        return $this;
    }
}
