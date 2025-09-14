<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Cronjob.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DateTimeInterface;
use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Cronjob.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'nome' => 'nome',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\Cronjob',
    message: 'Nome já está em utilização!'
)]
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/cron_job/{id}',
    jsonLDType: 'Cronjob',
    jsonLDContext: '/api/doc/#model-Cronjob'
)]
#[Form\Form]
class Cronjob extends RestDto
{
    use IdUuid;
    use Nome;
    use Descricao;
    use Ativo;
    use Blameable;
    use Softdeleteable;
    use Timeblameable;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\Length(
        min: 9,
        max: 255,
        minMessage: 'O campo deve ter no mínimo 9 caracteres!',
        maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números'
    )]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $periodicidade = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'O campo deve ter no mínimo 3 caracteres!',
        maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números'
    )]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $comando = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected bool $sincrono = true;

    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuarioUltimaExecucao = null;

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTimeInterface $dataHoraUltimaExecucao = null;

    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $statusUltimaExecucao = null;

    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $ultimoPid = null;

    #[Assert\Range(notInRangeMessage: 'O campo deve ter um valor entre 0 e 100', min: 0, max: 100)]
    #[OA\Property(type: 'float')]
    #[DTOMapper\Property]
    protected ?float $percentualExecucao = null;

    #[OA\Property(type: 'string', format: 'date-time')]
    protected ?DateTimeInterface $dataHoraProximaExecucao = null;

    #[OA\Property(type: 'string')]
    protected ?string $textoStatusUltimaExecucao = null;

    #[DTOMapper\Property]
    #[OA\Property(type: 'integer')]
    #[Assert\PositiveOrZero]
    protected ?int $numeroJobsPendentes = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[DTOMapper\Property]
    #[OA\Property(type: 'integer')]
    #[Assert\PositiveOrZero(message: "Somente números positivos")]
    protected ?int $timeout = null;

    public function getPeriodicidade(): ?string
    {
        return $this->periodicidade;
    }

    /**
     * @return $this
     */
    public function setPeriodicidade(?string $periodicidade): self
    {
        $this->setVisited('periodicidade');
        $this->periodicidade = $periodicidade;

        return $this;
    }

    public function getComando(): ?string
    {
        return $this->comando;
    }

    /**
     * @return $this
     */
    public function setComando(?string $comando): self
    {
        $this->setVisited('comando');
        $this->comando = $comando;

        return $this;
    }

    public function getUsuarioUltimaExecucao(): ?EntityInterface
    {
        return $this->usuarioUltimaExecucao;
    }

    /**
     * @return $this
     */
    public function setUsuarioUltimaExecucao(?EntityInterface $usuarioUltimaExecucao): self
    {
        $this->setVisited('usuarioUltimaExecucao');
        $this->usuarioUltimaExecucao = $usuarioUltimaExecucao;

        return $this;
    }

    public function getDataHoraUltimaExecucao(): ?DateTimeInterface
    {
        return $this->dataHoraUltimaExecucao;
    }

    /**
     * @return $this
     */
    public function setDataHoraUltimaExecucao(?DateTimeInterface $dataHoraUltimaExecucao): self
    {
        $this->setVisited('dataHoraUltimaExecucao');
        $this->dataHoraUltimaExecucao = $dataHoraUltimaExecucao;

        return $this;
    }

    public function getStatusUltimaExecucao(): ?int
    {
        return $this->statusUltimaExecucao;
    }

    /**
     * @return $this
     */
    public function setStatusUltimaExecucao(?int $statusUltimaExecucao): self
    {
        $this->setVisited('statusUltimaExecucao');
        $this->statusUltimaExecucao = $statusUltimaExecucao;

        return $this;
    }

    public function getUltimoPid(): ?int
    {
        return $this->ultimoPid;
    }

    /**
     * @return $this
     */
    public function setUltimoPid(?int $ultimoPid): self
    {
        $this->setVisited('ultimoPid');
        $this->ultimoPid = $ultimoPid;

        return $this;
    }

    public function getDataHoraProximaExecucao(): ?DateTimeInterface
    {
        return $this->dataHoraProximaExecucao;
    }

    /**
     * @return $this
     */
    public function setDataHoraProximaExecucao(?DateTimeInterface $dataHoraProximaExecucao): self
    {
        $this->dataHoraProximaExecucao = $dataHoraProximaExecucao;

        return $this;
    }

    public function getTextoStatusUltimaExecucao(): ?string
    {
        return $this->textoStatusUltimaExecucao;
    }

    /**
     * @return $this
     */
    public function setTextoStatusUltimaExecucao(?string $textoStatusUltimaExecucao): self
    {
        $this->textoStatusUltimaExecucao = $textoStatusUltimaExecucao;

        return $this;
    }

    public function getPercentualExecucao(): ?float
    {
        return $this->percentualExecucao;
    }

    /**
     * @return $this
     */
    public function setPercentualExecucao(?float $percentualExecucao): self
    {
        $this->setVisited('percentualExecucao');
        $this->percentualExecucao = $percentualExecucao;

        return $this;
    }

    public function getSincrono(): bool
    {
        return $this->sincrono;
    }

    public function setSincrono(bool $sincrono): self
    {
        $this->setVisited('sincrono');
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
     * @return self
     */
    public function setNumeroJobsPendentes(?int $numeroJobsPendentes): self
    {
        $this->setVisited('numeroJobsPendentes');
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
        $this->setVisited('timeout');
        $this->timeout = $timeout;

        return $this;
    }
}
