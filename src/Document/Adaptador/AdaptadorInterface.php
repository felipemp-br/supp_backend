<?php

declare(strict_types=1);
/**
 * /src/Document/Adaptador/AdaptadorInterface.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document\Adaptador;

/**
 * Interface AdaptadorInterface.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
interface AdaptadorInterface
{
    /**
     * @return string
     */
    public function supports(): string;

    /**
     * @param $document
     * @param $entity
     * @param array|null $context
     */
    public function process(&$document, $entity, ?array $context = null): void;

    /**
     * @return int
     */
    public function getOrder(): int;
}
