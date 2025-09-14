<?php

declare(strict_types=1);
/**
 * /src/Entity/Distribuicao.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Distribuicao.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[ORM\Table(name: 'ad_distribuicao')]
class Distribuicao implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Id;
    use Uuid;

    #[ORM\ManyToOne(targetEntity: 'Tarefa', inversedBy: 'distribuicoes')]
    #[ORM\JoinColumn(name: 'tarefa_id', referencedColumnName: 'id', nullable: false)]
    protected Tarefa $tarefa;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'data_hora_distribuicao', type: 'datetime', nullable: false)]
    protected DateTime $dataHoraDistribuicao;

    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'usuario_anterior_id', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $usuarioAnterior = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'usuario_posterior_id', referencedColumnName: 'id', nullable: false)]
    protected Usuario $usuarioPosterior;

    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_anterior_id', referencedColumnName: 'id', nullable: true)]
    protected ?Setor $setorAnterior = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_posterior_id', referencedColumnName: 'id', nullable: false)]
    protected Setor $setorPosterior;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'distribuicao_automatica', type: 'boolean', nullable: false)]
    protected bool $distribuicaoAutomatica = false;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'livre_balanceamento', type: 'boolean', nullable: false)]
    protected bool $livreBalanceamento = false;

    #[ORM\Column(name: 'auditoria_distribuicao', type: 'text', nullable: true)]
    protected ?string $auditoriaDistribuicao = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'tipo_distribuicao', type: 'integer', nullable: false)]
    protected int $tipoDistribuicao = 0;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getTarefa(): Tarefa
    {
        return $this->tarefa;
    }

    public function setTarefa(Tarefa $tarefa): self
    {
        $this->tarefa = $tarefa;

        return $this;
    }

    public function getDataHoraDistribuicao(): DateTime
    {
        return $this->dataHoraDistribuicao;
    }

    public function setDataHoraDistribuicao(DateTime $dataHoraDistribuicao): self
    {
        $this->dataHoraDistribuicao = $dataHoraDistribuicao;

        return $this;
    }

    public function getUsuarioAnterior(): ?Usuario
    {
        return $this->usuarioAnterior;
    }

    public function setUsuarioAnterior(?Usuario $usuarioAnterior): self
    {
        $this->usuarioAnterior = $usuarioAnterior;

        return $this;
    }

    public function getUsuarioPosterior(): Usuario
    {
        return $this->usuarioPosterior;
    }

    public function setUsuarioPosterior(Usuario $usuarioPosterior): self
    {
        $this->usuarioPosterior = $usuarioPosterior;

        return $this;
    }

    public function getSetorAnterior(): ?Setor
    {
        return $this->setorAnterior;
    }

    public function setSetorAnterior(?Setor $setorAnterior): self
    {
        $this->setorAnterior = $setorAnterior;

        return $this;
    }

    public function getSetorPosterior(): Setor
    {
        return $this->setorPosterior;
    }

    public function setSetorPosterior(Setor $setorPosterior): self
    {
        $this->setorPosterior = $setorPosterior;

        return $this;
    }

    public function getDistribuicaoAutomatica(): bool
    {
        return $this->distribuicaoAutomatica;
    }

    public function setDistribuicaoAutomatica(bool $distribuicaoAutomatica): self
    {
        $this->distribuicaoAutomatica = $distribuicaoAutomatica;

        return $this;
    }

    public function getLivreBalanceamento(): bool
    {
        return $this->livreBalanceamento;
    }

    public function setLivreBalanceamento(bool $livreBalanceamento): self
    {
        $this->livreBalanceamento = $livreBalanceamento;

        return $this;
    }

    public function getAuditoriaDistribuicao(): ?string
    {
        return $this->auditoriaDistribuicao;
    }

    public function setAuditoriaDistribuicao(?string $auditoriaDistribuicao): self
    {
        $this->auditoriaDistribuicao = $auditoriaDistribuicao;

        return $this;
    }

    public function getTipoDistribuicao(): int
    {
        return $this->tipoDistribuicao;
    }

    public function setTipoDistribuicao(int $tipoDistribuicao): self
    {
        $this->tipoDistribuicao = $tipoDistribuicao;

        return $this;
    }
}
