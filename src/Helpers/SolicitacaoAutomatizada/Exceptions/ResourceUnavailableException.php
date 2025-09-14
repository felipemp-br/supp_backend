<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions;

use Exception;
use Throwable;

/**
 * Class ResourceUnavailableException.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ResourceUnavailableException extends Exception
{
    /**
     * ResourceUnavailableException constructor.
     * 
     * @param string    $resource
     * @param Throwable $previous
     */
    public function __construct(
        string $resource,
        Throwable $previous
    ) {
        parent::__construct(
            sprintf(
                '%s indisponível no momento, tente mais tarde.',
                $resource
            ),
            400, 
            $previous
        );
    }
}