<?php

declare(strict_types=1);
/**
 * /src/Entity/TipoAcaoWorkflow.php.
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DMS\Filter\Rules as Filter;
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
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use SuppCore\AdministrativoBackend\Entity\Traits\Valor;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TipoAcaoWorkflow.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[UniqueEntity(fields: ['valor'], message: 'Valor já está em utilização para essa modalidade!')]
#[Enableable]
#[ORM\Table(name: 'ad_tipo_acao_workflow')]
#[ORM\UniqueConstraint(columns: ['valor', 'apagado_em'])]
class TipoAcaoWorkflow implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Valor;
    use Descricao;
    use Ativo;

    /**
     * .
     */
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'trigger_name', type: 'string', nullable: false)]
    protected string $trigger;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<AcaoTransicaoWorkflow>
     */
    #[ORM\OneToMany(mappedBy: 'tipoAcaoWorkflow', targetEntity: 'AcaoTransicaoWorkflow')]
    protected $acoes;

    /**
     * TipoAcaoWorkflow constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->acoes = new ArrayCollection();
    }

    public function setTrigger(string $trigger): self
    {
        $this->trigger = $trigger;

        return $this;
    }

    public function getTrigger(): string
    {
        return $this->trigger;
    }

    /**
     * @return Collection|ArrayCollection|ArrayCollection<AcaoTransicaoWorkflow>
     */
    public function getAcoes(): Collection
    {
        return $this->acoes;
    }

    public function addAcao(AcaoTransicaoWorkflow $acao): self
    {
        if (!$this->acoes->contains($acao)) {
            $this->acoes->add($acao);
            $acao->setEtiqueta($this);
        }

        return $this;
    }

    public function removeAcao(AcaoTransicaoWorkflow $acao): self
    {
        if ($this->acoes->contains($acao)) {
            $this->acoes->removeElement($acao);
        }

        return $this;
    }
}
