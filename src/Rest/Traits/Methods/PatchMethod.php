<?php

declare(strict_types=1);
/**
 * /src/Rest/Traits/Methods/PatchMethod.php.
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
 * Trait PatchMethod.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait PatchMethod
{
    // Traits
    use AbstractFormMethods;
    use AbstractGenericMethods;

    /**
     * Generic 'patchMethod' method for REST resources.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    public function patchMethod(
        Request $request,
        FormFactoryInterface $formFactory,
        int $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['PATCH'];

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
                ->update(
                    $id,
                    $this->processFormMapper($request, self::METHOD_PATCH, $id),
                    $transactionId,
                    true
                );

            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $data);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }
}
