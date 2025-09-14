<?php

declare(strict_types=1);
/**
 * /src/Entity/Traits/Blameable.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Trait Softdeleteable.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait Softdeleteable
{
    #[ORM\Column(name: 'apagado_em', type: 'datetime', nullable: true)]
    protected ?DateTime $apagadoEm = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Usuario')]
    #[ORM\JoinColumn(name: 'apagado_por', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $apagadoPor = null;

    public function setApagadoEm(?DateTime $apagadoEm): self
    {
        $this->apagadoEm = $apagadoEm;

        return $this;
    }

    public function getApagadoEm(): ?DateTime
    {
        return $this->apagadoEm;
    }

    public function getApagadoPor(): ?Usuario
    {
        return $this->apagadoPor;
    }

    public function setApagadoPor(?Usuario $apagadoPor = null): self
    {
        $this->apagadoPor = $apagadoPor;

        return $this;
    }
}
