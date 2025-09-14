<?php

declare(strict_types=1);
/**
 * /src/Entity/UserInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

/**
 * Interface UserInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface UserInterface
{
    public function getId(): ?int;

    public function getUuid(): ?string;

    public function getUsername(): string;

    public function getEmail(): string;
}
