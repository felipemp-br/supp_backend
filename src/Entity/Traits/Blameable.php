<?php

declare(strict_types=1);
/**
 * /src/Entity/Traits/Blameable.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Trait Blameable.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait Blameable
{
    #[Gedmo\Blameable(on: 'create')]
    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Usuario')]
    #[ORM\JoinColumn(name: 'criado_por', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $criadoPor = null;

    #[Gedmo\Blameable(on: 'update')]
    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Usuario')]
    #[ORM\JoinColumn(name: 'atualizado_por', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $atualizadoPor = null;

    public function getCriadoPor(): ?Usuario
    {
        return $this->criadoPor;
    }

    public function setCriadoPor(?Usuario $criadoPor = null): self
    {
        $this->criadoPor = $criadoPor;

        return $this;
    }

    public function getAtualizadoPor(): ?Usuario
    {
        return $this->atualizadoPor;
    }

    public function setAtualizadoPor(?Usuario $atualizadoPor = null): self
    {
        $this->atualizadoPor = $atualizadoPor;

        return $this;
    }
}
