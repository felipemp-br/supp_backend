<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Handlers;

use SuppCore\AdministrativoBackend\RegrasEtiqueta\Message\RegrasEtiquetaMessage;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AutoconfigureTag('supp_core.administrativo_backend.regras_etiqueta.handler')]
interface RegrasEtiquetaHandlerInterface
{
    /**
     * @param RegrasEtiquetaMessage $message
     * @return bool
     */
    public function support(RegrasEtiquetaMessage $message): bool;

    /**
     * @param RegrasEtiquetaMessage $message
     * @param string                $transactionId
     * @return void
     */
    public function handle(RegrasEtiquetaMessage $message, string $transactionId): void;

    /**
     * Retorna a ordem de execução.
     *
     * @return int
     */
    public function order(): int;
}
