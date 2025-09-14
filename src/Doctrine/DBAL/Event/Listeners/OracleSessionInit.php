<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Doctrine\DBAL\Event\Listeners;

use const CASE_UPPER;
use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Event\ConnectionEventArgs;
use Doctrine\DBAL\Events;

/**
 * Should be used when Oracle Server default environment does not match the Doctrine requirements.
 *
 * The following environment variables are required for the Doctrine default date format:
 *
 * NLS_TIME_FORMAT="HH24:MI:SS"
 * NLS_DATE_FORMAT="YYYY-MM-DD HH24:MI:SS"
 * NLS_TIMESTAMP_FORMAT="YYYY-MM-DD HH24:MI:SS"
 * NLS_TIMESTAMP_TZ_FORMAT="YYYY-MM-DD HH24:MI:SS TZH:TZM"
 *
 * @see   www.doctrine-project.org
 * @since  2.0
 *
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 */
class OracleSessionInit implements EventSubscriber
{
    protected array $_defaultSessionVars = [
        'NLS_TIME_FORMAT' => 'HH24:MI:SS',
        'NLS_DATE_FORMAT' => 'YYYY-MM-DD HH24:MI:SS',
        'NLS_TIMESTAMP_FORMAT' => 'YYYY-MM-DD HH24:MI:SS',
        'NLS_TIMESTAMP_TZ_FORMAT' => 'YYYY-MM-DD HH24:MI:SS TZH:TZM',
        'NLS_COMP' => 'BINARY',
        'NLS_SORT' => 'BINARY',
        'NLS_NUMERIC_CHARACTERS' => '.,',
    ];

    /**
     * @param array $oracleSessionVars
     */
    public function __construct(array $oracleSessionVars = [])
    {
        $this->_defaultSessionVars = [...$this->_defaultSessionVars, ...$oracleSessionVars];
    }

    /**
     * @param ConnectionEventArgs $args
     *
     * @throws DBALException
     */
    public function postConnect(ConnectionEventArgs $args)
    {
        if (count($this->_defaultSessionVars)) {
            array_change_key_case($this->_defaultSessionVars, CASE_UPPER);
            $vars = [];
            foreach ($this->_defaultSessionVars as $option => $value) {
                if ('CURRENT_SCHEMA' === $option) {
                    $vars[] = $option.' = '.$value;
                } else {
                    $vars[] = $option." = '".$value."'";
                }
            }
            $sql = 'ALTER SESSION SET '.implode(' ', $vars);
            $args->getConnection()->executeUpdate($sql);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents(): array
    {
        return [Events::postConnect];
    }
}
