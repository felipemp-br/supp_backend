<?php

declare(strict_types=1);
/**
 * /src/Entity/Lotacao.php.
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
 * Class Lotacao.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_lotacao')]
class Lotacao implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Colaborador', inversedBy: 'lotacoes')]
    #[ORM\JoinColumn(name: 'colaborador_id', referencedColumnName: 'id', nullable: false)]
    protected Colaborador $colaborador;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Setor', inversedBy: 'lotacoes')]
    #[ORM\JoinColumn(name: 'setor_id', referencedColumnName: 'id', nullable: false)]
    protected Setor $setor;

    #[Assert\Range(notInRangeMessage: 'Campo ser entre {{ min }} e {{ max }}', min: 0, max: 100)]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'peso', type: 'integer', nullable: false)]
    protected int $peso = 100;

    #[Gedmo\Versioned]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $principal = false;

    #[Gedmo\Versioned]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $distribuidor = false;

    #[Gedmo\Versioned]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $arquivista = false;

    #[Gedmo\Versioned]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $pcu = false;

    #[Assert\Regex(
        pattern: '/^\d(-\d)?(,\d(-\d)?)*$/',
        message: 'Formato inválido, utilize de acordo com o exemplo: 1,2-5,8'
    )]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'digitos_distribuicao', type: 'string', nullable: true)]
    protected ?string $digitosDistribuicao = null;

    #[Assert\Regex(
        pattern: '/^\d{2}(-\d{2})?(,\d{2}(-\d{2})?)*$/',
        message: 'Formato inválido, utilize de acordo com o exemplo: 01,20-50,80'
    )]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'centenas_distribuicao', type: 'string', nullable: true)]
    protected ?string $centenasDistribuicao = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
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

    public function setSetor(Setor $setor): self
    {
        $this->setor = $setor;

        return $this;
    }

    public function getSetor(): Setor
    {
        return $this->setor;
    }

    public function getPeso(): int
    {
        return $this->peso;
    }

    public function setPeso(int $peso): self
    {
        $this->peso = $peso;

        return $this;
    }

    public function getPrincipal(): bool
    {
        return $this->principal;
    }

    public function setPrincipal(bool $principal): self
    {
        $this->principal = $principal;

        return $this;
    }

    public function getDistribuidor(): bool
    {
        return $this->distribuidor;
    }

    public function setDistribuidor(bool $distribuidor): self
    {
        $this->distribuidor = $distribuidor;

        return $this;
    }

    public function getArquivista(): bool
    {
        return $this->arquivista;
    }

    public function setArquivista(bool $arquivista): self
    {
        $this->arquivista = $arquivista;

        return $this;
    }

    public function getPcu(): bool
    {
        return $this->pcu;
    }

    public function setPcu(bool $pcu): self
    {
        $this->pcu = $pcu;

        return $this;
    }

    public function getDigitosDistribuicao(): ?string
    {
        return $this->digitosDistribuicao;
    }

    public function setDigitosDistribuicao(?string $digitosDistribuicao): self
    {
        $this->digitosDistribuicao = $digitosDistribuicao;

        return $this;
    }

    public function getCentenasDistribuicao(): ?string
    {
        return $this->centenasDistribuicao;
    }

    public function setCentenasDistribuicao(?string $centenasDistribuicao): self
    {
        $this->centenasDistribuicao = $centenasDistribuicao;

        return $this;
    }
}
