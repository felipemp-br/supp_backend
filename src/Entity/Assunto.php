<?php

declare(strict_types=1);
/**
 * /src/Entity/Assunto.php.
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
 * Class Assunto.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_assunto')]
class Assunto implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'AssuntoAdministrativo')]
    #[ORM\JoinColumn(name: 'assunto_administrativo_id', referencedColumnName: 'id', nullable: false)]
    protected AssuntoAdministrativo $assuntoAdministrativo;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $principal = true;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Processo', inversedBy: 'assuntos')]
    #[ORM\JoinColumn(name: 'processo_id', referencedColumnName: 'id', nullable: false)]
    protected Processo $processo;

    #[ORM\ManyToOne(targetEntity: 'OrigemDados', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'origem_dados_id', referencedColumnName: 'id', nullable: true)]
    protected ?OrigemDados $origemDados = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getAssuntoAdministrativo(): AssuntoAdministrativo
    {
        return $this->assuntoAdministrativo;
    }

    public function setAssuntoAdministrativo(AssuntoAdministrativo $assuntoAdministrativo): self
    {
        $this->assuntoAdministrativo = $assuntoAdministrativo;

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

    public function getProcesso(): Processo
    {
        return $this->processo;
    }

    public function setProcesso(Processo $processo): self
    {
        $this->processo = $processo;

        return $this;
    }

    public function getOrigemDados(): ?OrigemDados
    {
        return $this->origemDados;
    }

    public function setOrigemDados(?OrigemDados $origemDados): self
    {
        $this->origemDados = $origemDados;

        return $this;
    }
}
