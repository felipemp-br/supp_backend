<?php

declare(strict_types=1);
/**
 * /src/Entity/Feriado.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable\Enableable;
use SuppCore\AdministrativoBackend\Entity\Traits\Ativo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Feriado.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Enableable]
#[ORM\Table(name: 'ad_feriado')]
class Feriado implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Nome;
    use Ativo;

    #[ORM\ManyToOne(targetEntity: 'Estado')]
    #[ORM\JoinColumn(name: 'estado_id', referencedColumnName: 'id', nullable: true)]
    protected ?Estado $estado = null;

    #[ORM\ManyToOne(targetEntity: 'Municipio')]
    #[ORM\JoinColumn(name: 'municipio_id', referencedColumnName: 'id', nullable: true)]
    protected ?Municipio $municipio = null;

    /**
     * Todas as notificações devem possuir uma data e hora de expiração.
     */
    #[Assert\NotNull(message: 'A data do feriado não pode ser nula!')]
    #[ORM\Column(name: 'data_feriado', type: 'datetime', nullable: false)]
    protected DateTime $dataFeriado;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getEstado(): ?Estado
    {
        return $this->estado;
    }

    public function setEstado(?Estado $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getMunicipio(): Municipio
    {
        return $this->municipio;
    }

    public function setMunicipio(?Municipio $municipio): self
    {
        $this->municipio = $municipio;

        return $this;
    }

    public function getDataFeriado(): DateTime
    {
        return $this->dataFeriado;
    }

    public function setDataFeriado(DateTime $dataFeriado): self
    {
        $this->dataFeriado = $dataFeriado;

        return $this;
    }
}
