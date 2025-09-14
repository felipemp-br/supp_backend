<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Modelo/Message/IndexacaoMessage.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Modelo\Message;

/**
 * Class IndexacaoMessage.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class IndexacaoMessage
{
    private string $uuid;
    private ?string $entityName = null;

    /**
     * IndexacaoMessage constructor.
     */
    public function __construct(string $uuid,
        ?string $entityName = null)
    {
        $this->uuid = $uuid;
        $this->entityName = $entityName;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getEntityName(): ?string
    {
        return $this->entityName;
    }
}
