<?php

namespace SuppCore\AdministrativoBackend\Doctrine\DBAL\Middleware;

use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\Middleware;
use Doctrine\DBAL\Driver\Middleware\AbstractDriverMiddleware;
use Doctrine\DBAL\Platforms\OraclePlatform;
use SensitiveParameter;

/**
 * InitializeSession.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class InitializeSession implements Middleware
{
    public function wrap(Driver $driver): Driver
    {
        return new class ($driver) extends AbstractDriverMiddleware {
            public function __construct(Driver $driver)
            {
                parent::__construct($driver);
            }

            /**
             * {@inheritDoc}
             */
            public function connect(
                #[SensitiveParameter]
                array $params
            ): Connection {
                $connection = parent::connect($params);

                if ($this->getDatabasePlatform() instanceof OraclePlatform) {
                    $connection->exec(
                        'ALTER SESSION SET'
                        . " NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS'"
                        . " NLS_TIME_FORMAT = 'HH24:MI:SS'"
                        . " NLS_TIMESTAMP_FORMAT = 'YYYY-MM-DD HH24:MI:SS'"
                        . " NLS_TIMESTAMP_TZ_FORMAT = 'YYYY-MM-DD HH24:MI:SS TZH:TZM'"
                        . " NLS_COMP = 'BINARY'"
                        . " NLS_SORT = 'BINARY'"
                        . " NLS_NUMERIC_CHARACTERS = '.,'",
                    );
                }

                return $connection;
            }
        };
    }
}
