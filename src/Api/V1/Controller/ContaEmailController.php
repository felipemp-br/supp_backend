<?php

declare(strict_types=1);
/**
 * /src/Controller/ContaEmailController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ContaEmailResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\EmailClient\EmailProcessoForm;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use SuppCore\AdministrativoBackend\Transaction\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method ContaEmailResource getResource()
 */
#[Route(path: '/v1/administrativo/conta_email')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'ContaEmail')]
class ContaEmailController extends Controller
{
    // Traits
    use Actions\Colaborador\FindOneAction;
    use Actions\Colaborador\FindAction;
    use Actions\CoordenadorUnidade\CreateAction;
    use Actions\CoordenadorUnidade\UpdateAction;
    use Actions\CoordenadorUnidade\PatchAction;
    use Actions\CoordenadorUnidade\DeleteAction;
    use Actions\Colaborador\CountAction;

    public function __construct(
        ContaEmailResource $resource,
        ResponseHandler $responseHandler,
        private readonly ProcessoResource $processoResource
    ) {
        $this->init($resource, $responseHandler);
    }

    /**
     * @throws Throwable
     */
    #[Route(path: '/processo_email_form', methods: ['POST'])]
    public function postProcessoFormAction(
        Request $request,
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

            $form = $this->formMapper->buildForm(EmailProcessoForm::class, self::METHOD_CREATE);
            $form->handleRequest($request);

            if (!$form->isValid()) {
                $this->getResponseHandler()->handleFormError($form);
            }

            $data = $this->resource->emailProcessoForm(
                $form->getData(),
                $transactionId
            );

            $this->transactionManager->commit($transactionId);
            $this->responseHandler->setResource($this->processoResource);

            return $this
                ->getResponseHandler()
                ->createResponse($request, $data);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }
}
