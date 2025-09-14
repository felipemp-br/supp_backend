<?php

declare(strict_types=1);
/**
 * /src/Entity/Traits/Ativo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Ramsey\Uuid\Uuid as Ruuid;

/**
 * Trait Uuid.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait Uuid
{
    #[ORM\Column(name: 'uuid', type: 'guid', unique: true, nullable: false)]
    protected ?string $uuid = null;

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @throws Exception
     */
    public function setUuid(): string
    {
        return $this->uuid = Ruuid::uuid4()->toString();
    }
}
