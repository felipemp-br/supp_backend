<?php

declare(strict_types=1);
/**
 * /src/Rules/RuleInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rules;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 * Interface RuleInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface RuleInterface
{
    public function supports(): array;

    public function validate(
        ?RestDtoInterface $restDto,
        EntityInterface $entity,
        string $transactionId
    ): bool;

    public function getOrder(): int;
}
