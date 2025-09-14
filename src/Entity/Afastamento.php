<?php

declare(strict_types=1);
/**
 * /src/Entity/Afastamento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class Afastamento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_afastamento')]
class Afastamento implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    /**
     * Modalidade do afastamento.
     */
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeAfastamento')]
    #[ORM\JoinColumn(name: 'mod_afastamento_id', referencedColumnName: 'id', nullable: false)]
    protected ModalidadeAfastamento $modalidadeAfastamento;

    /**
     * Colaborador do afastamento.
     */
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Colaborador', inversedBy: 'afastamentos')]
    #[ORM\JoinColumn(name: 'colaborador_id', referencedColumnName: 'id', nullable: false)]
    protected Colaborador $colaborador;

    /**
     * Data início do afastamento.
     */
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'data_inicio', type: 'date', nullable: false)]
    protected DateTime $dataInicio;

    /**
     * Data início do bloqueio do afastamento.
     */
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'data_inicio_bloqueio', type: 'date', nullable: false)]
    protected DateTime $dataInicioBloqueio;

    /**
     * Data início do afastamento.
     */
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'data_fim', type: 'date', nullable: false)]
    protected DateTime $dataFim;

    /**
     * Data fim do bloqueio do afastamento.
     */
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'data_fim_bloqueio', type: 'date', nullable: false)]
    protected DateTime $dataFimBloqueio;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getDataInicio(): DateTime
    {
        return $this->dataInicio;
    }

    public function setDataInicio(DateTime $dataInicio): self
    {
        $this->dataInicio = $dataInicio;

        return $this;
    }

    public function getDataInicioBloqueio(): DateTime
    {
        return $this->dataInicioBloqueio;
    }

    public function setDataInicioBloqueio(DateTime $dataInicioBloqueio): self
    {
        $this->dataInicioBloqueio = $dataInicioBloqueio;

        return $this;
    }

    public function getDataFim(): DateTime
    {
        return $this->dataFim;
    }

    public function setDataFim(DateTime $dataFim): self
    {
        $this->dataFim = $dataFim;

        return $this;
    }

    public function getDataFimBloqueio(): DateTime
    {
        return $this->dataFimBloqueio;
    }

    public function setDataFimBloqueio(DateTime $dataFimBloqueio): self
    {
        $this->dataFimBloqueio = $dataFimBloqueio;

        return $this;
    }

    public function setModalidadeAfastamento(ModalidadeAfastamento $modalidadeAfastamento): self
    {
        $this->modalidadeAfastamento = $modalidadeAfastamento;

        return $this;
    }

    public function getModalidadeAfastamento(): ModalidadeAfastamento
    {
        return $this->modalidadeAfastamento;
    }

    public function setColaborador(Colaborador $colaborador): self
    {
        $this->colaborador = $colaborador;

        return $this;
    }

    public function getColaborador(): Colaborador
    {
        return $this->colaborador;
    }

    #[Assert\Callback]
    public function isDateIntervalValid(ExecutionContextInterface $context): void
    {
        if ($this->getDataInicio() > $this->getDataFim()) {
            $context->buildViolation('A data do final do afastamento não pode ser menor que a do início!')
                ->atPath('dataFim')
                ->addViolation();
        }

        if ($this->getDataInicioBloqueio() > $this->getDataFimBloqueio()) {
            $context->buildViolation(
                'A data do final do bloqueio de distribuição não pode ser menor que a do início!'
            )
                ->atPath('dataFimBloqueio')
                ->addViolation();
        }

        if ($this->getDataInicioBloqueio() > $this->getDataInicio()) {
            $context->buildViolation(
                'A data do início do bloqueio de distribuição não pode ser maior que a do início do afastamento!'
            )
                ->atPath('dataInicioBloqueio')
                ->addViolation();
        }

        if ($this->getDataFimBloqueio() > $this->getDataFim()) {
            $context->buildViolation(
                'A data do final do bloqueio de distribuição não pode ser maior que a do final do afastamento!'
            )
                ->atPath('dataFimBloqueio')
                ->addViolation();
        }

        if ($this->getDataInicio() > $this->getDataFimBloqueio()) {
            $context->buildViolation(
                'A data do final do bloqueio de distribuição não pode ser menor que a do início do afastamento!'
            )
                ->atPath('dataFimBloqueio')
                ->addViolation();
        }
    }
}
