<?php

declare(strict_types=1);
/**
 * /src/Rest/Traits/Methods/CreateMethod.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Traits\Methods;

use LogicException;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Transaction\Context;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

/**
 * Trait CreateMethod.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait CreateMethod
{
    // Traits
    use AbstractFormMethods;
    use AbstractGenericMethods;

    /**
     * Generic 'createMethod' method for REST resources.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    public function createMethod(
        Request $request,
        FormFactoryInterface $formFactory,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['POST'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        $context = RequestHandler::getContext($request);

        try {
            $transactionId = $this->transactionManager->begin();

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $data = $this
                ->getResource()
                ->create($this->processFormMapper($request, self::METHOD_CREATE), $transactionId, true);

            $this->transactionManager->commit($transactionId);

            $populate = RequestHandler::getPopulate($request, $this->getResource());

            if ([] !== $populate) {
                $data = $this->getResource()->findOne($data->getId(), $populate);
            }

            return $this
                ->getResponseHandler()
                ->createResponse($request, $data, Response::HTTP_CREATED);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }
}
