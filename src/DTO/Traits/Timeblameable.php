<?php

declare(strict_types=1);
/**
 * /src/DTO/Traits/Timeblameable.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DTO\Traits;

use DateTime;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;

/**
 * Trait Blameable.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait Timeblameable
{
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $criadoEm = null;

    #[OA\Property]
    #[DTOMapper\Property]
    protected ?DateTime $atualizadoEm = null;

    public function getCriadoEm(): ?DateTime
    {
        return $this->criadoEm;
    }

    public function setCriadoEm(?DateTime $criadoEm): self
    {
        $this->criadoEm = $criadoEm;

        return $this;
    }

    public function getAtualizadoEm(): ?DateTime
    {
        return $this->atualizadoEm;
    }

    public function setAtualizadoEm(?DateTime $atualizadoEm): self
    {
        $this->atualizadoEm = $atualizadoEm;

        return $this;
    }
}
