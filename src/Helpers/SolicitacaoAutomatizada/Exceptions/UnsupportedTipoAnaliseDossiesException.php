<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions;

use Exception;
use Throwable;

/**
 * UnsupportedTipoAnaliseDossiesException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class UnsupportedTipoAnaliseDossiesException extends Exception implements SolicitacaoAutomatizadaExceptionInterface
{
    /**
     * Constructor.
     *
     * @param string         $tipoAnaliseDossie
     * @param string         $siglaTipoSolicitacaoAutomatizada
     * @param Throwable|null $previous
     */
    public function __construct(
        string $tipoAnaliseDossie,
        string $siglaTipoSolicitacaoAutomatizada,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            sprintf(
                'Tipo de analise de dossie %s para o tipo de solicitação automatizada %s não suportado.',
                $tipoAnaliseDossie,
                $siglaTipoSolicitacaoAutomatizada
            ),
            400,
            $previous
        );
    }
}
