<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Crypto;

use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 * Class CryptoServiceInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
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

    /**
     * @return int
     */
    public function getOrder(): int;

    /**
     * @param EntityInterface|null $entity
     * @return bool
     */
    public function supports(EntityInterface|null $entity): bool;

    /**
     * @return string
     */
    public function getClassname(): string;
}
