<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Exceptions;

use Exception;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Enums\FinishReasonEnum;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificalExceptionInterface;

/**
 * GeminiReasonException.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeminiReasonException extends Exception implements InteligenciaArtificalExceptionInterface
{
    /**
     * Constructor.
     *
     * @param FinishReasonEnum $reason
     *
     */
    public function __construct(
        FinishReasonEnum $reason
    ) {
        parent::__construct(
            sprintf(
                'O client finalizou a geração de tokens retornando o código de erro: %s',
                $reason->value
            ),
            400
        );
    }
}
