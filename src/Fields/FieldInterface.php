<?php

declare(strict_types=1);
/**
 * /src/Fields/FieldInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields;

/**
 * Interface FieldInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface FieldInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return array
     */
    public function getConfig(): array;

    /**
     * @param array $context
     * @param array $options
     *
     * @param $transactionId
     * @return null|string
     */
    public function render(string $transactionId, $context = [], $options = []): ?string;
}
