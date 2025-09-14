<?php

declare(strict_types=1);
/**
 * /src/Rest/Controller.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Monolog\Processor;

use ArrayAccess;
use Monolog\LogRecord;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use UnexpectedValueException;

use function array_merge;
use function file_get_contents;

/**
 * Injeta elementos do request nos logs.
 *
 * @author Eduardo Lang <edu.lang@hotmail.com>
 */
#[AutoconfigureTag(name: 'monolog.processor')]
class RequestProcessor
{
    protected array|ArrayAccess|null $serverData;

    private int $slowRequest;

    /**
     * RequestProcessor constructor.
     *
     * @param int  $slowRequest
     * @param null $serverData
     */
    public function __construct(int $slowRequest = 1, $serverData = null)
    {
        $this->slowRequest = $slowRequest;

        if (null === $serverData) {
            $this->serverData = &$_SERVER;
        } elseif (is_array($serverData) || $serverData instanceof ArrayAccess) {
            $this->serverData = $serverData;
        } else {
            throw new UnexpectedValueException('$serverData must be an array or object implementing ArrayAccess.');
        }
    }

    /**
     * @param LogRecord $record
     *
     * @return LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        if (!isset($this->serverData['REQUEST_URI'])) {
            return $record;
        }

        $request = [];

        $rawRequest = file_get_contents('php://input');

        if ($rawRequest) {
            $jsonRequest = json_decode($rawRequest);

            if (JSON_ERROR_NONE === json_last_error()) {
                if (str_contains($this->serverData['REQUEST_URI'], 'componente_digital')
                    && isset($jsonRequest->conteudo)) {
                    $jsonRequest->conteudo = 'binary';
                }

                if (isset($jsonRequest->plainPassword)) {
                    $jsonRequest->plainPassword = '******';
                }

                if (isset($jsonRequest->password)) {
                    $jsonRequest->password = '******';
                }

                if (isset($jsonRequest->currentPlainPassword)) {
                    $jsonRequest->currentPlainPassword = '******';
                }

                if (isset($jsonRequest->credential)) {
                    if (1 === preg_match("/a1:(interno|ldap)\/\//i", $jsonRequest->credential)) {
                        $parts = array_map(fn ($p) => urldecode($p), explode('//', $jsonRequest->credential));
                        [$protocol] = $parts;
                        $jsonRequest->credential = $protocol.'//******';
                    }
                }

                $rawRequest = json_encode($jsonRequest);
            }

            $request['request']['content'] = $rawRequest;
        }

        if ('statistics' == $record->channel
            && isset($_SERVER['REQUEST_TIME_FLOAT'])) {
            $request['request']['duration'] = (float) (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']);

            if ($request['request']['duration'] < $this->slowRequest) {
                unset($request['request']['content']);
            }

            if (isset($_SERVER['SESSION_TIME_FLOAT'])) {
                $request['request']['real_duration'] = (float) (microtime(true) - $_SERVER['SESSION_TIME_FLOAT']);
            }
        }

        if (isset($this->serverData['HTTP_X_RATELIMIT_API'])) {
            $record->extra['xapi'] = $this->serverData['HTTP_X_RATELIMIT_API'];
        }

        $record->extra = array_merge(
            $record->extra,
            $request
        );

        return $record;
    }
}
