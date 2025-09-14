<?php

declare(strict_types=1);
/**
 * /src/Rest/Traits/Methods/FindOneMethod.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Traits\Methods;

use LogicException;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

/**
 * Trait IdsMethod.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait IdsMethod
{
    // Traits
    use AbstractGenericMethods;

    /**
     * Generic 'idsMethod' method for REST resources.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    public function idsMethod(Request $request, ?array $allowedHttpMethods = null): Response
    {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        // Determine used parameters
        $search = RequestHandler::getSearchTerms($request);

        try {
            $criteria = RequestHandler::getCriteria($request);

            $this->processCriteria($criteria);

            return $this
                ->getResponseHandler()
                ->createResponse($request, $this->getResource()->getIds($criteria, $search));
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }
}
