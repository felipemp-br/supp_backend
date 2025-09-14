<?php

declare(strict_types=1);
/**
 * /src/Scheduler/Message/CronjobManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
namespace SuppCore\AdministrativoBackend\Scheduler\Message;

/**
 * Class CronjobManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CronjobManager
{
    public function __construct(
        private readonly int $id,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
