<?php

declare(strict_types=1);
/**
 * /src/Controller/DocumentoIAMetadataController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoIAMetadata;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoIAMetadataResource as Resource;
use SuppCore\AdministrativoBackend\Repository\DocumentoRepository;
use SuppCore\AdministrativoBackend\Repository\TipoDocumentoRepository;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method Resource getResource()
 */
#[Route(path: '/v1/administrativo/documento_ia_metadata')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'DocumentoIAMetadata')]
class DocumentoIAMetadataController extends Controller
{
    // Traits
    use Actions\Colaborador\FindOneAction;
    use Actions\Colaborador\FindAction;
    use Actions\Admin\CreateAction;
    use Actions\Admin\UpdateAction;
    use Actions\Admin\PatchAction;
    use Actions\Admin\DeleteAction;
    use Actions\Colaborador\CountAction;

    public function __construct(
        Resource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }

    #[Route(
        path: '/documento_classificado/{documentoId}/{nomeDocumentoPredito}',
        requirements: [
            'documentoId' => '\d+',
            'nome_documento_predito' => '^[a-zA-Z0-9]+$',
        ],
        methods: ['POST']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_COLABORADOR')]
    public function documentoClassificado(
        Request $request,
        DocumentoRepository $documentoRepository,
        TipoDocumentoRepository $tipoDocumentoRepository,
        int $documentoId,
        string $nomeDocumentoPredito
    ): Response {
        $allowedHttpMethods ??= ['POST'];
        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        $documento = $documentoRepository->find($documentoId);
        if (!$documento) {
            throw new NotFoundHttpException();
        }
        try {
            $transactionId = $this->transactionManager->begin();
            $dto = (new DocumentoIAMetadata())
                ->setDocumento($documento)
                ->setTipoDocumentoPredito(
                    $tipoDocumentoRepository->findTipoDocumentoFromDocumentoClassificado($nomeDocumentoPredito)
                );
            $entity = $this->getResource()->updateOrCreate(
                $dto,
                $transactionId
            );
            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $entity);
        } catch (Throwable $e) {
            throw $this->handleRestMethodException($e, $documentoId);
        }
    }
}
