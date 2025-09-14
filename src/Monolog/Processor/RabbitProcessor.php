<?php

declare(strict_types=1);
/**
 * /src/Rest/Controller.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Monolog\Processor;

use Monolog\LogRecord;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Class UserProcessor.
 */
#[AutoconfigureTag(name: 'monolog.processor')]
class RabbitProcessor
{
    /**
     * @param LogRecord $record
     *
     * @return LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        global $argv;
        if ('cli' === PHP_SAPI && isset($argv) && is_array($argv) && count($argv)) {
            $queueIndex = array_search('messenger:consume', $argv);
            if ((false !== $queueIndex) && isset($argv[$queueIndex + 1])) {
                $record->extra['rabbit'] = $argv[$queueIndex + 1];
            }
        }
        return $record;
    }
}
