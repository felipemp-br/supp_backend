<?php

declare(strict_types=1);
/**
 * /src/Utils/FilesystemService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

use Gaufrette\Filesystem;
use SuppCore\AdministrativoBackend\Gaufrette\Adapter\SafeLocal;

/**
 * Class FilesystemService.
 * @deprecated
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class FilesystemService implements FilesystemServiceInterface
{
    private SafeLocal $adapter;

    private ?Filesystem $filesytem = null;

    /**
     * FilesystemService constructor.
     *
     * @param SafeLocal $adapter
     */
    public function __construct(SafeLocal $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return Filesystem
     */
    public function get(): Filesystem
    {
        if (!$this->filesytem) {
            $this->filesytem = new Filesystem($this->adapter);
        }

        return $this->filesytem;
    }
}
