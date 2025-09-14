<?php

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions;

use Exception;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Responses\CompletionsResponse;

/**
 * InvalidCompletionsJsonResponseException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class InvalidCompletionsJsonResponseException extends Exception implements InteligenciaArtificalExceptionInterface
{
    /**
     * Constructor.
     *
     * @param CompletionsResponse $response
     */
    public function __construct(
        private readonly CompletionsResponse $response,
    ) {
        parent::__construct(
            'A inteligência artificial retornou um JSON inválido.',
            500
        );
    }

    /**
     * Return response.
     *
     * @return CompletionsResponse
     */
    public function getResponse(): CompletionsResponse
    {
        return $this->response;
    }
}
