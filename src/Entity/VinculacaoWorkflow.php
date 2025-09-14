<?php

declare(strict_types=1);
/**
 * /src/Entity/VinculacaoWorkflow.php.
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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VinculacaoWorkflow.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[UniqueEntity(
    fields: [
        'workflow',
        'tarefaInicial',
        'apagadoEm',
    ],
    message: 'O Workflow já está vinculado a uma Tarefa inicial!'
)]
#[Gedmo\Loggable]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_vinc_workflow')]
#[ORM\UniqueConstraint(columns: ['workflow_id', 'tarefa_inicial_id', 'apagado_em'])]
class VinculacaoWorkflow implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Tarefa')]
    #[ORM\JoinColumn(name: 'tarefa_inicial_id', referencedColumnName: 'id', nullable: false)]
    protected Tarefa $tarefaInicial;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Tarefa')]
    #[ORM\JoinColumn(name: 'tarefa_atual_id', referencedColumnName: 'id', nullable: false)]
    protected Tarefa $tarefaAtual;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Workflow', inversedBy: 'vinculacoesWorkflow')]
    #[ORM\JoinColumn(name: 'workflow_id', referencedColumnName: 'id', nullable: false)]
    protected Workflow $workflow;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'concluido', type: 'boolean', nullable: false)]
    protected bool $concluido = false;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getTarefaInicial(): Tarefa
    {
        return $this->tarefaInicial;
    }

    public function setTarefaInicial(Tarefa $tarefaInicial): self
    {
        $this->tarefaInicial = $tarefaInicial;

        return $this;
    }

    public function getTarefaAtual(): Tarefa
    {
        return $this->tarefaAtual;
    }

    public function setTarefaAtual(Tarefa $tarefaAtual): self
    {
        $this->tarefaAtual = $tarefaAtual;

        return $this;
    }

    public function getWorkflow(): Workflow
    {
        return $this->workflow;
    }

    public function setWorkflow(Workflow $workflow): self
    {
        $this->workflow = $workflow;

        return $this;
    }

    public function getConcluido(): bool
    {
        return $this->concluido;
    }

    public function setConcluido(bool $concluido): self
    {
        $this->concluido = $concluido;

        return $this;
    }
}
