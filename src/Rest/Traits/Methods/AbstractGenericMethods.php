<?php

declare(strict_types=1);
/**
 * /src/Rest/Traits/Methods/AbstractGenericMethods.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Traits\Methods;

use LogicException;
use SuppCore\AdministrativoBackend\Rest\ResponseHandlerInterface;
use SuppCore\AdministrativoBackend\Rest\RestResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;
use UnexpectedValueException;

/**
 * Trait AbstractGenericMethods.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait AbstractGenericMethods
{
    /**
     * @throws UnexpectedValueException
     */
    abstract public function getResource(): RestResourceInterface;

    /**
     * @throws UnexpectedValueException
     */
    abstract public function getResponseHandler(): ResponseHandlerInterface;

    /**
     * Method to validate REST trait method.
     *
     * @param string[] $allowedHttpMethods
     *
     * @throws LogicException
     * @throws MethodNotAllowedHttpException
     */
    abstract public function validateRestMethod(Request $request, array $allowedHttpMethods): void;

    /**
     * Method to process current criteria array.
     *
     * @param mixed[] $criteria
     */
    abstract public function processCriteria(array &$criteria): void;

    /**
     * Method to handle possible REST method trait exception.
     *
     * @throws HttpException
     */
    abstract public function handleRestMethodException(Throwable $exception, ?int $id = null): Throwable;
}
