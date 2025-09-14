<?php

declare(strict_types=1);
/**
 * /src/Utils/CryptoServiceInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

/**
 * Class CryptoServiceInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 * @deprecated
 */
interface CryptoServiceInterface
{
    /**
     * @param string $plaintext
     *
     * @return string
     */
    public function encrypt(string $plaintext): string;

    /**
     * @param string $ciphertext
     *
     * @return string
     */
    public function decrypt(string $ciphertext): string;
}
