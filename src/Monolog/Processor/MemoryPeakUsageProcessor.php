<?php

declare(strict_types=1);
/**
 * /src/Rest/Controller.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Monolog\Processor;

use Monolog\LogRecord;
use Monolog\Processor\MemoryProcessor;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use function sys_getloadavg;

/**
 * Injects memory_get_peak_usage in all records.
 *
 * @author Rob Jensen
 */
#[AutoconfigureTag(name: 'monolog.processor')]
class MemoryPeakUsageProcessor extends MemoryProcessor
{
    /**
     * @param LogRecord $record
     * @return LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $record->extra['memory_peak_usage_bytes'] = memory_get_peak_usage(true);
        $load = sys_getloadavg();
        $record->extra['cpu_load'] = $load[0];

        return $record;
    }
}
