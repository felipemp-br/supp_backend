<?php

declare(strict_types=1);

/*
 * This file is part of the BeSimpleSoapBundle.
 *
 * (c) Christian Kerl <christian-kerl@web.de>
 * (c) Francis Besset <francis.besset@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SuppCore\AdministrativoBackend\BeSimple\SoapCommon;

use InvalidArgumentException;

/**
 * @author Francis Besset <francis.besset@gmail.com>
 */
class Cache
{
    const DISABLED = 0;
    const ENABLED = 1;

    const TYPE_NONE = WSDL_CACHE_NONE;
    const TYPE_DISK = WSDL_CACHE_DISK;
    const TYPE_MEMORY = WSDL_CACHE_MEMORY;
    const TYPE_DISK_MEMORY = WSDL_CACHE_BOTH;

    protected static array $types = [
        self::TYPE_NONE,
        self::TYPE_DISK,
        self::TYPE_MEMORY,
        self::TYPE_DISK_MEMORY,
    ];

    /**
     * @return array
     */
    public static function getTypes()
    {
        return self::$types;
    }

    /**
     * @return string
     */
    public static function isEnabled()
    {
        return self::iniGet('soap.wsdl_cache_enabled');
    }

    /**
     * @param $enabled
     */
    public static function setEnabled($enabled)
    {
        if (!in_array($enabled, [self::ENABLED, self::DISABLED], true)) {
            throw new InvalidArgumentException();
        }

        self::iniSet('soap.wsdl_cache_enabled', $enabled);
    }

    /**
     * @return string
     */
    public static function getType()
    {
        return self::iniGet('soap.wsdl_cache');
    }

    /**
     * @param $type
     */
    public static function setType($type)
    {
        if (!in_array($type, self::getTypes(), true)) {
            throw new InvalidArgumentException('The cache type is unknow');
        }

        self::iniSet('soap.wsdl_cache', $type);
    }

    /**
     * @return string
     */
    public static function getDirectory()
    {
        return self::iniGet('soap.wsdl_cache_dir');
    }

    /**
     * @param $directory
     */
    public static function setDirectory($directory)
    {
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        self::iniSet('soap.wsdl_cache_dir', $directory);
    }

    /**
     * @return string
     */
    public static function getLifetime()
    {
        return self::iniGet('soap.wsdl_cache_ttl');
    }

    /**
     * @param $lifetime
     */
    public static function setLifetime($lifetime)
    {
        self::iniSet('soap.wsdl_cache_ttl', $lifetime);
    }

    /**
     * @return string
     */
    public static function getLimit()
    {
        return self::iniGet('soap.wsdl_cache_limit');
    }

    /**
     * @param $limit
     */
    public static function setLimit($limit)
    {
        self::iniSet('soap.wsdl_cache_limit', $limit);
    }

    /**
     * @param $key
     *
     * @return string
     */
    protected static function iniGet($key)
    {
        return ini_get($key);
    }

    /**
     * @param $key
     * @param $value
     */
    protected static function iniSet($key, $value)
    {
        ini_set($key, $value);
    }
}
