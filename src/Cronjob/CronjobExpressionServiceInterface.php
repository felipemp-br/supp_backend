<?php

declare(strict_types=1);
/**
 * /src/Cronjob/CronjobServiceInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
namespace SuppCore\AdministrativoBackend\Cronjob;

use DateTimeInterface;

/**
 * Class CronjobServiceInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface CronjobExpressionServiceInterface
{
    /**
     * @param string $alias
     * @param string $expression
     * @return $this
     */
    public function addCustomExpressionAlias(string $alias, string $expression): self;

    /**
     * @param string $expression
     * @return bool
     */
    public function isValid(string $expression): bool;

    /**
     * @param string $expression
     * @param DateTimeInterface $dateTime
     * @param string|null $timeZone
     * @return bool
     */
    public function isDue(string $expression, DateTimeInterface $dateTime,  string $timeZone = null): bool;

    /**
     * @param string $expression
     * @param DateTimeInterface|null $dateTime
     * @return DateTimeInterface|null
     */
    public function nextRunDate(string $expression, ?DateTimeInterface $dateTime): ?DateTimeInterface;
}
