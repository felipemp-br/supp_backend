<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Doctrine\DBAL\Wrappers;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\OraclePlatform;

/**
 * ConnectionWrapper.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ConnectionWrapper extends Connection
{

    public function connect(): bool
    {
        $connected = parent::connect();

        if ($connected) {
            $databasePlatform = $this->getDatabasePlatform();
            if ($databasePlatform instanceof OraclePlatform) {
                $this->_conn
                    ->prepare(
                        "ALTER SESSION SET NLS_TIME_FORMAT = 'HH24:MI:SS' NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS' NLS_TIMESTAMP_FORMAT = 'YYYY-MM-DD HH24:MI:SS' NLS_TIMESTAMP_TZ_FORMAT = 'YYYY-MM-DD HH24:MI:SS TZH:TZM'"
                    )
                    ->execute();
            }
        }

        return $connected;
    }

}
