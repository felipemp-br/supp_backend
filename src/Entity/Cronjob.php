<?php

declare(strict_types=1);
/**
 * /src/Entity/Cronjob.php.
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DateTimeInterface;
use DMS\Filter\Rules as Filter;
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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Cronjob.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[UniqueEntity(fields: ['nome'], message: 'Nome já está em utilização!')]
#[Enableable]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_cronjob')]
#[ORM\UniqueConstraint(columns: ['nome', 'apagado_em'])]
class Cronjob implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Ativo;
    use Nome;
    use Descricao;
    public const ST_EXECUCAO_ERRO = 0;
    public const ST_EXECUCAO_EM_EXECUCAO = 1;
    public const ST_EXECUCAO_SUCESSO = 2;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[ORM\Column(type: 'string', nullable: false)]
    protected ?string $periodicidade = '';

    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[ORM\Column(type: 'string', nullable: false)]
    protected ?string $comando = '';

    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'usuario_ultima_execucao', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $usuarioUltimaExecucao = null;

    #[ORM\Column(name: 'status_ultima_execucao', type: 'integer', nullable: true)]
    protected ?int $statusUltimaExecucao = null;

    #[ORM\Column(name: 'ultimo_pid', type: 'integer', nullable: true)]
    protected ?int $ultimoPid = null;

    #[Assert\Range(notInRangeMessage: 'O campo deve ter um valor entre 0 e 100', min: 0, max: 100)]
    #[ORM\Column(name: 'percentual_execucao', type: 'float', nullable: true)]
    protected ?float $percentualExecucao = null;

    #[ORM\Column(name: 'data_hora_ultima_execucao', type: 'datetime', nullable: true)]
    protected ?DateTimeInterface $dataHoraUltimaExecucao = null;

    #[ORM\Column(name: 'sincrono', type: 'boolean', nullable: true)]
    protected ?bool $sincrono = true;

    #[Assert\PositiveOrZero]
    #[ORM\Column(name: 'num_jobs_pendentes', type: 'integer', nullable: true)]
    protected ?int $numeroJobsPendentes = null;

    #[Assert\PositiveOrZero(message: "Somente números positivos")]
    #[ORM\Column(name: 'timeout', type: 'integer', nullable: true)]
    protected ?int $timeout = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(?string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getPeriodicidade(): ?string
    {
        return $this->periodicidade;
    }

    public function setPeriodicidade(?string $periodicidade): self
    {
        $this->periodicidade = $periodicidade;

        return $this;
    }

    public function getComando(): ?string
    {
        return $this->comando;
    }

    public function setComando(?string $comando): self
    {
        $this->comando = $comando;

        return $this;
    }

    public function getStatusUltimaExecucao(): ?int
    {
        return $this->statusUltimaExecucao;
    }

    public function setStatusUltimaExecucao(?int $statusUltimaExecucao): self
    {
        $this->statusUltimaExecucao = $statusUltimaExecucao;

        return $this;
    }

    public function getDataHoraUltimaExecucao(): ?DateTimeInterface
    {
        return $this->dataHoraUltimaExecucao;
    }

    public function setDataHoraUltimaExecucao(?DateTimeInterface $dataHoraUltimaExecucao): self
    {
        $this->dataHoraUltimaExecucao = $dataHoraUltimaExecucao;

        return $this;
    }

    public function getUsuarioUltimaExecucao(): ?Usuario
    {
        return $this->usuarioUltimaExecucao;
    }

    public function setUsuarioUltimaExecucao(?Usuario $usuarioUltimaExecucao): self
    {
        $this->usuarioUltimaExecucao = $usuarioUltimaExecucao;

        return $this;
    }

    public function getUltimoPid(): ?int
    {
        return $this->ultimoPid;
    }

    public function setUltimoPid(?int $ultimoPid): self
    {
        $this->ultimoPid = $ultimoPid;

        return $this;
    }

    public function getPercentualExecucao(): ?float
    {
        return $this->percentualExecucao;
    }

    public function setPercentualExecucao(?float $percentualExecucao): self
    {
        $this->percentualExecucao = $percentualExecucao;

        return $this;
    }

    public function getSincrono(): ?bool
    {
        return $this->sincrono;
    }

    public function setSincrono(?bool $sincrono): self
    {
        $this->sincrono = $sincrono;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNumeroJobsPendentes(): ?int
    {
        return $this->numeroJobsPendentes;
    }

    /**
     * @param int|null $numeroJobsPendentes
     * @return Cronjob
     */
    public function setNumeroJobsPendentes(?int $numeroJobsPendentes): Cronjob
    {
        $this->numeroJobsPendentes = $numeroJobsPendentes;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTimeout(): ?int
    {
        return $this->timeout;
    }

    /**
     * @param int|null $timeout
     *
     * @return $this
     */
    public function setTimeout(?int $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }
}
