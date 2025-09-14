<?php

declare(strict_types=1);
/**
 * /src/Cronjob/CronjobService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Cronjob;


use Carbon\Carbon;
use Cron\CronExpression;
use DateTime;
use DateTimeInterface;
use Exception;

/**
 * Class CronjobService.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CronjobExpressionExpressionService implements CronjobExpressionServiceInterface
{
    /**
     * Sempre recria os alias para evitar que um pod que intercepte 2 meses não tenha o valor de referência errado
     * @return void
     */
    private function recreateCustomAlias(): void
    {
        $dateExecucao = new Carbon();
        $businessDayDate = (clone $dateExecucao)->modify('first day of this month');
        while ($businessDayDate->isWeekend()) {
            $businessDayDate->add('1 day');
        }
        $this->addCustomExpressionAlias(
            '@firstBunsinessDayOfMonth',
            join(
                ' ',
                [
                    '0',
                    '0',
                    $businessDayDate->day,
                    '*',
                    '*',
                ]
            )
        );
        $businessDayDate->add('1 day');
        while ($businessDayDate->isWeekend()) {
            $businessDayDate->add('1 day');
        }
        $this->addCustomExpressionAlias(
            '@secondBunsinessDaysOfMonth',
            join(
                ' ',
                [
                    '0',
                    '0',
                    $businessDayDate->day,
                    '*',
                    '*',
                ]
            )
        );
        $businessDayDate->add('1 day');
        while ($businessDayDate->isWeekend()) {
            $businessDayDate->add('1 day');
        }
        $this->addCustomExpressionAlias(
            '@thirdBunsinessDaysOfMonth',
            join(
                ' ',
                [
                    '0',
                    '0',
                    $businessDayDate->day,
                    '*',
                    '*',
                ]
            )
        );
    }

    /**
     * @param string $alias
     * @param string $expression
     * @return $this
     */
    public function addCustomExpressionAlias(string $alias, string $expression): self
    {
        CronExpression::unregisterAlias($alias);
        CronExpression::registerAlias($alias, $expression);

        return $this;
    }

    /**
     * @param string $expression
     * @return bool
     */
    public function isValid(string $expression): bool
    {
        $this->recreateCustomAlias();
        return CronExpression::isValidExpression($expression);
    }

    /**
     * @param string $expression
     * @param DateTimeInterface $dateTime
     * @param string|null $timeZone
     * @return bool
     */
    public function isDue(string $expression, DateTimeInterface $dateTime,  string $timeZone = null): bool
    {
        $this->recreateCustomAlias();
        return (new CronExpression($expression))->isDue($dateTime);
    }

    /**
     * @param string $expression
     * @param DateTimeInterface|null $dateTime
     * @return DateTimeInterface|null
     * @throws Exception
     */
    public function nextRunDate(string $expression, ?DateTimeInterface $dateTime): ?DateTimeInterface
    {
        $nextRunDate = null;
        $this->recreateCustomAlias();

        if ($this->isValid($expression)) {
            $nextRunDate = (new CronExpression($expression))
                ->getNextRunDate($dateTime ?? new DateTime());
        }

        return $nextRunDate;
    }
}
