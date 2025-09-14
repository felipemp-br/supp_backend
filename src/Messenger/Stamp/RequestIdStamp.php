<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Messenger\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * RequestIdStamp.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 * @deprecated
 */
class RequestIdStamp implements StampInterface
{
    /**
     * Constructor.
     *
     * @param string $id
     */
    public function __construct(
        private readonly string $id
    ) {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
