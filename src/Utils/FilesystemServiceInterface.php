<?php

declare(strict_types=1);
/**
 * /src/Utils/FilesystemServiceInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

use Gaufrette\Filesystem;

/**
 * Class FilesystemService.
 * @deprecated
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface FilesystemServiceInterface
{
    /**
     * @return Filesystem
     */
    public function get(): Filesystem;
}
