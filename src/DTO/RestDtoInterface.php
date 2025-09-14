<?php

declare(strict_types=1);
/**
 * /src/DTO/RestDtoInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DTO;

/**
 * Interface RestDtoInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface RestDtoInterface
{
    /**
     * Getter method for visited setters. This is needed for dto patching.
     *
     * @return string[]
     */
    public function getVisited(): array;

    /**
     * Setter for visited data. This is needed for dto patching.
     */
    public function setVisited(string $property): self;

    /**
     * Setter for immutable object.
     */
    public function setImmutable(?bool $immutable): self;
}
