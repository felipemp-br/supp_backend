<?php

declare(strict_types=1);
/**
 * /src/Gaufrete/Adapter/SafeLocal.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Gaufrette\Adapter;

use Gaufrette\Adapter\Local;
use RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class FormMetadata.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SafeLocal extends Local
{
    /**
     * SafeLocal constructor.
     *
     * @param ParameterBagInterface $params
     */
    public function __construct(ParameterBagInterface $params)
    {
        $directory = $params->get('supp_core.administrativo_backend.filesystem_directory');
        $create = $params->get('supp_core.administrativo_backend.filesystem_create');
        $mode = $params->get('supp_core.administrativo_backend.filesystem_mode');
        parent::__construct($directory, $create, $mode);
    }

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * @param string $key
     *
     * @return string
     */
    protected function computePath($key): string
    {
        $this->ensureDirectoryExists($this->directory);

        if (mb_strlen($key, 'UTF-8') < 5) {
            throw new RuntimeException('key tem menos do que 5 caracteres!');
        }

        return $this->normalizePath(
            $this->directory.'/'.mb_substr($key, 0, 1, 'UTF-8').'/'.mb_substr($key, 1, 1, 'UTF-8').'/'.mb_substr(
                $key,
                2,
                1,
                'UTF-8'
            ).'/'.mb_substr($key, 3, 1, 'UTF-8').'/'.$key
        );
    }
}
