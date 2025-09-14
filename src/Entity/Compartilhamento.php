<?php

declare(strict_types=1);
/**
 * /src/Entity/Compartilhamento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

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
 * Class Compartilhamento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_compartilhamento')]
class Compartilhamento implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[ORM\ManyToOne(targetEntity: 'Tarefa', inversedBy: 'compartilhamentos')]
    #[ORM\JoinColumn(name: 'tarefa_id', referencedColumnName: 'id', nullable: true)]
    protected ?Tarefa $tarefa = null;

    #[ORM\ManyToOne(targetEntity: 'Processo', inversedBy: 'compartilhamentos')]
    #[ORM\JoinColumn(name: 'processo_id', referencedColumnName: 'id', nullable: true)]
    protected ?Processo $processo = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: false)]
    protected Usuario $usuario;

    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $assessor = false;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getTarefa(): ?Tarefa
    {
        return $this->tarefa;
    }

    public function setTarefa(?Tarefa $tarefa): self
    {
        $this->tarefa = $tarefa;

        return $this;
    }

    public function getProcesso(): ?Processo
    {
        return $this->processo;
    }

    public function setProcesso(?Processo $processo): self
    {
        $this->processo = $processo;

        return $this;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getAssessor(): bool
    {
        return $this->assessor;
    }

    public function setAssessor(bool $assessor): self
    {
        $this->assessor = $assessor;

        return $this;
    }
}
