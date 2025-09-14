<?php

declare(strict_types=1);
/**
 * /src/Rest/Controller.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Monolog\Processor;

use ArrayAccess;
use Monolog\Level;
use Monolog\LogRecord;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use UnexpectedValueException;

/**
 * Injects url/method and remote IP of the current web request in all records.
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
#[AutoconfigureTag(name: 'monolog.processor')]
class WebProcessor
{
    private Level $level;

    protected array|ArrayAccess $serverData;

    protected array $extraFields = [
        'url' => 'REQUEST_URI',
        'ip' => 'REMOTE_ADDR',
        'http_method' => 'REQUEST_METHOD',
        'server' => 'SERVER_NAME',
        'referrer' => 'HTTP_REFERER',
        'agent' => 'HTTP_USER_AGENT',
    ];

    /**
     * WebProcessor constructor.
     *
     * @param int        $level
     * @param null       $serverData
     * @param array|null $extraFields
     */
    public function __construct(
        int $level = Level::Debug->value,
        $serverData = null,
        array $extraFields = null
    ) {
        $this->level = Level::fromValue($level);

        if (null === $serverData) {
            $this->serverData = &$_SERVER;
        } elseif (is_array($serverData) || $serverData instanceof ArrayAccess) {
            $this->serverData = $serverData;
        } else {
            throw new UnexpectedValueException('$serverData must be an array or object implementing ArrayAccess.');
        }

        if (null !== $extraFields) {
            foreach (array_keys($this->extraFields) as $fieldName) {
                if (!in_array($fieldName, $extraFields)) {
                    unset($this->extraFields[$fieldName]);
                }
            }
        }
    }

    public function __invoke(LogRecord $record): LogRecord
    {
        // return if the level is not high enough
        if ($record->level->value < $this->level->value) {
            return $record;
        }

        // skip processing if for some reason request data
        // is not present (CLI or wonky SAPIs)
        if (!isset($this->serverData['REQUEST_URI'])) {
            return $record;
        }

        $record->extra = $this->appendExtraFields($record->extra);
        return $record;
    }

    /**
     * @param string $extraName
     * @param string $serverName
     *
     * @return $this
     */
    public function addExtraField(string $extraName, string $serverName): static
    {
        $this->extraFields[$extraName] = $serverName;

        return $this;
    }

    private function appendExtraFields(array $extra): array
    {
        foreach ($this->extraFields as $extraName => $serverName) {
            $extra[$extraName] = isset($this->serverData[$serverName]) ? $this->serverData[$serverName] : null;

            if ('url' === $extraName) {
                $extra[$extraName] = urldecode($extra[$extraName]);
                $extra[$extraName] = preg_replace('/"password":".+"/', '"password":"*****"', $extra[$extraName]);
                $extra[$extraName] = preg_replace(
                    '/"plainPassword":".+"/',
                    '"plainPassword":"*****"',
                    $extra[$extraName]
                );
                $extra[$extraName] = preg_replace(
                    '/"currentPlainPassword":".+"/',
                    '"currentPlainPassword":"*****"',
                    $extra[$extraName]
                );
            }
        }

        if (isset($this->serverData['UNIQUE_ID'])) {
            $extra['unique_id'] = $this->serverData['UNIQUE_ID'];
        }

        return $extra;
    }
}
