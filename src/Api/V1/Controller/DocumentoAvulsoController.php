<?php

declare(strict_types=1);
/**
 * /src/Controller/DocumentoAvulsoController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use LogicException;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use SuppCore\AdministrativoBackend\Transaction\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method DocumentoAvulsoResource getResource()
 */
#[Route(path: '/v1/administrativo/documento_avulso')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'DocumentoAvulso')]
class DocumentoAvulsoController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\Colaborador\CreateAction;
    use Actions\Colaborador\UpdateAction;
    use Actions\Colaborador\PatchAction;
    use Actions\Colaborador\DeleteAction;
    use Actions\Colaborador\UndeleteAction;
    use Actions\User\CountAction;

    public function __construct(
        DocumentoAvulsoResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }

    /**
     * Endpoint action to remeter um documento avulso.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{id}/remeter',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function remeterAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['PATCH'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $documentoAvulsoResource = $this->getResource();
            $documentoAvulsoDTO = $documentoAvulsoResource->getDtoForEntity($id, DocumentoAvulso::class);
            $documentoAvulsoEntity = $documentoAvulsoResource->remeter($id, $documentoAvulsoDTO, $transactionId, true);

            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $documentoAvulsoEntity);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to change encerramento status.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{id}/toggle_encerramento',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[IsGranted('ROLE_USER')]
    #[RestApiDoc]
    public function toggleEncerramentoAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['PATCH'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $documentoAvulsoResource = $this->getResource();
            $documentoAvulsoDTO = $documentoAvulsoResource->getDtoForEntity($id, DocumentoAvulso::class);
            $documentoAvulsoEntity = $documentoAvulsoResource->toggleEncerramento(
                $id,
                $documentoAvulsoDTO,
                $transactionId,
                true
            );

            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $documentoAvulsoEntity);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to change lida status.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{id}/toggle_lida',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[IsGranted('ROLE_USER')]
    #[RestApiDoc]
    public function toggleLidaAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['PATCH'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $documentoAvulsoResource = $this->getResource();
            $documentoAvulsoDTO = $documentoAvulsoResource->getDtoForEntity($id, DocumentoAvulso::class);
            $documentoAvulsoEntity = $documentoAvulsoResource
                ->toggleLida($id, $documentoAvulsoDTO, $transactionId, true);

            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $documentoAvulsoEntity);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to distribuir um documento avulso.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{id}/distribuir',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function distribuirAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['PATCH'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $distribuicao = json_decode($request->getContent(), true);

            $documentoAvulsoDTO = $this->getResource()->getDtoForEntity($id, DocumentoAvulso::class);
            $documentoAvulsoEntity = $this->getResource()->distribuir(
                $id, $distribuicao['setorId'], $distribuicao['usuarioId'] ?? false, $documentoAvulsoDTO, $transactionId, false);

            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $documentoAvulsoEntity);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to reiterar um documento avulso.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{id}/reiterar',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function reiterarAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['PATCH'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $documentoAvulsoResource = $this->getResource();
            $documentoAvulsoDTO = $documentoAvulsoResource->getDtoForEntity($id, DocumentoAvulso::class);
            $documentoAvulsoEntity = $documentoAvulsoResource->reiterar($id, $documentoAvulsoDTO, $transactionId, true);

            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $documentoAvulsoEntity);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to change observação.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{id}/observacao',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[IsGranted('ROLE_USER')]
    #[RestApiDoc]
    public function observacaoAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['PATCH'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $documentoAvulsoResource = $this->getResource();
            /** DocumentoAvuslo $documentoAvulsoDTO */
            $documentoAvulsoDTO = $documentoAvulsoResource->getDtoForEntity($id, DocumentoAvulso::class);
            $documentoAvulsoDTO->setObservacao($request->get('observacao'));
            $documentoAvulsoEntity = $documentoAvulsoResource
                ->observacao($id, $documentoAvulsoDTO, $transactionId, true);

            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $documentoAvulsoEntity);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }
}
