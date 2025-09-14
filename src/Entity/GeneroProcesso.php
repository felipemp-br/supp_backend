<?php

declare(strict_types=1);
/**
 * /src/Entity/GeneroProcesso.php.
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
use SuppCore\AdministrativoBackend\Doctrine\ORM\Immutable\Immutable;
use SuppCore\AdministrativoBackend\Entity\Traits\Ativo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class GeneroProcesso.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[UniqueEntity(fields: ['nome'], message: 'Nome já está em utilização para esse gênero de processo!')]
#[Enableable]
#[Immutable(
    fieldName: 'nome',
    expressionValues: 'env:constantes.entidades.genero_processo.immutable',
    expression: Immutable::EXPRESSION_IN
)]
#[ORM\Table(name: 'ad_genero_processo')]
#[ORM\UniqueConstraint(columns: ['nome', 'apagado_em'])]
class GeneroProcesso implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Nome;
    use Descricao;
    use Ativo;

    /**
     * @var Collection|ArrayCollection<Workflow>
     */
    #[ORM\OneToMany(mappedBy: 'generoProcesso', targetEntity: 'Workflow')]
    protected ArrayCollection|Collection $workflows;

    /**
     * GeneroProcesso constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->workflows = new ArrayCollection();
    }

    public function addWorkflow(Workflow $workflow): self
    {
        if (!$this->workflows->contains($workflow)) {
            $this->workflows->add($workflow);
            $workflow->setGeneroProcesso($this);
        }

        return $this;
    }

    public function removeWorkflow(Workflow $workflow): self
    {
        if ($this->workflows->contains($workflow)) {
            $this->workflows->removeElement($workflow);
        }

        return $this;
    }

    public function getWorkflows(): Collection|ArrayCollection
    {
        return $this->workflows;
    }
}
