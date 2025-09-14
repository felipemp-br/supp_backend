<?php

declare(strict_types=1);
/**
 * /src/Utils/CompressServiceInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

/**
 * Class CompressServiceInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface CompressServiceInterface
{
    /**
     * @param string $plaintext
     *
     * @return string|null
     */
    public function compress(string $plaintext): ?string;

    /**
     * @param string $compressedtext
     *
     * @return string|null
     */
    public function uncompress(string $compressedtext): ?string;
}
