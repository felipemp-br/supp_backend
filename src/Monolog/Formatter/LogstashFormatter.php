<?php

declare(strict_types=1);

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SuppCore\AdministrativoBackend\Monolog\Formatter;

use JsonException;
use Monolog\Formatter\NormalizerFormatter;
use Monolog\LogRecord;

use function is_array;

/**
 * Serializes a log message to Logstash Event Format.
 *
 * @see http://logstash.net/
 * @see https://github.com/logstash/logstash/blob/master/lib/logstash/event.rb
 *
 * @author Tim Mower <timothy.mower@gmail.com>
 */
class LogstashFormatter extends NormalizerFormatter
{
    const V0 = 0;
    const V1 = 1;

    /**
     * @var string the name of the system for the Logstash log message, used to fill the @source field
     */
    protected $systemName;

    /**
     * @var string an application name for the Logstash log message, used to fill the @type field
     */
    protected $applicationName;

    /**
     * @var string a prefix for 'extra' fields from the Monolog record (optional)
     */
    protected $extraPrefix;

    /**
     * @var string a prefix for 'context' fields from the Monolog record (optional)
     */
    protected $contextPrefix;

    /**
     * @var int logstash format version to use
     */
    protected $version;

    /**
     * @param string $applicationName the application that sends the data, used as the "type" field of logstash
     * @param string $systemName      the system/machine name, used as the "source" field of logstash, defaults to the hostname of the machine
     * @param string $extraPrefix     prefix for extra keys inside logstash "fields"
     * @param string $contextPrefix   prefix for context keys inside logstash "fields", defaults to ctxt_
     * @param int    $version         the logstash format version to use, defaults to 0
     */
    public function __construct(
        $applicationName,
        $systemName = null,
        $extraPrefix = null,
        $contextPrefix = 'ctxt',
        $version = self::V0
    ) {
        // logstash requires a ISO 8601 format date with optional millisecond precision.
        parent::__construct('Y-m-d\TH:i:s.uP');

        $this->systemName = $systemName ?: gethostname();
        $this->applicationName = $applicationName;
        $this->extraPrefix = $extraPrefix;
        $this->contextPrefix = $contextPrefix;
        $this->version = $version;
    }

    /**
     * {@inheritdoc}
     */
    public function format(LogRecord $record): mixed
    {
        $record = parent::format($record);

        if (self::V1 === $this->version) {
            $message = $this->formatV1($record);
        } else {
            $message = $this->formatV0($record);
        }

        return $this->toJson($message)."\n";
    }

    /**
     * @param array $record
     *
     * @return array
     * @throws JsonException
     */
    protected function formatV0(array $record): array
    {
        if (empty($record['datetime'])) {
            $record['datetime'] = gmdate('c');
        }
        $message = [
            '@timestamp' => $record['datetime'],
            '@source' => $this->systemName,
            '@fields' => [],
        ];
        if (isset($record['message'])) {
            $message['@message'] = $record['message'];
        }
        if (isset($record['channel'])) {
            $message['@tags'] = [$record['channel']];
            $message['@fields']['channel'] = $record['channel'];
        }
        if (isset($record['level'])) {
            $message['@fields']['level'] = $record['level'];
        }
        if ($this->applicationName) {
            $message['@type'] = $this->applicationName;
        }
        if (isset($record['extra']['server'])) {
            $message['@source_host'] = $record['extra']['server'];
        }
        if (isset($record['extra']['url'])) {
            $message['@source_path'] = $record['extra']['url'];
            $api = explode('?', $message['@source_path']);
            if (isset($api[0])) {
                $message['@api'] = $api[0];
                $message['@api_id'] = preg_replace('/\/\d+/', '/{id}', $api[0]);
            }
        }
        if (!empty($record['extra'])) {
            foreach ($record['extra'] as $key => $val) {
                $message['@fields'][$this->extraPrefix.$key] = $val;
            }
        }
        if (isset($record['context']['inteligencia_artificial'])) {
            $message['@inteligencia_artificial'] = $record['context']['inteligencia_artificial'];
            unset($record['context']['inteligencia_artificial']);
        }
        if (!empty($record['context']) && is_array($record['context'])) {
            $message['@fields'][$this->contextPrefix] = json_encode($record['context'], JSON_THROW_ON_ERROR);
            foreach ($record['context'] as $key => $val) {
                $message['@fields'][$this->contextPrefix.$key] = $val;
            }
        } elseif (!empty($record['context'])) {
            $message[$this->contextPrefix] = $record['context'];
        }

        return $message;
    }

    /**
     * @param array $record
     *
     * @return array
     */
    protected function formatV1(array $record)
    {
        if (empty($record['datetime'])) {
            $record['datetime'] = gmdate('c');
        }
        $message = [
            '@timestamp' => $record['datetime'],
            '@version' => 1,
            'host' => $this->systemName,
        ];
        if (isset($record['message'])) {
            $message['message'] = $record['message'];
        }
        if (isset($record['channel'])) {
            $message['type'] = $record['channel'];
            $message['channel'] = $record['channel'];
        }
        if (isset($record['level_name'])) {
            $message['level'] = $record['level_name'];
        }
        if ($this->applicationName) {
            $message['type'] = $this->applicationName;
        }
        if (!empty($record['extra'])) {
            foreach ($record['extra'] as $key => $val) {
                $message[$this->extraPrefix.$key] = $val;
            }
        }
        if (isset($record['context']['inteligencia_artificial'])) {
            $message['@inteligencia_artificial'] = $record['context']['inteligencia_artificial'];
            unset($record['context']['inteligencia_artificial']);
        }
        if (!empty($record['context'])) {
            foreach ($record['context'] as $key => $val) {
                $message[$this->contextPrefix.$key] = $val;
            }
        }

        return $message;
    }
}
