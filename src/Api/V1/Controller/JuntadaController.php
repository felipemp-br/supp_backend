<?php

declare(strict_types=1);
/**
 * /src/Controller/JuntadaController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use Exception;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoDocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoPessoaUsuarioResource;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use SuppCore\AdministrativoBackend\Transaction\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method JuntadaResource getResource()
 */
#[Route(path: '/v1/administrativo/juntada')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'Juntada')]
class JuntadaController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\Colaborador\CreateAction;
    use Actions\Colaborador\UpdateAction;
    use Actions\Colaborador\PatchAction;
    use Actions\Root\DeleteAction;
    use Actions\Colaborador\CountAction;

    public AuthorizationCheckerInterface $authorizationChecker;

    public TokenStorageInterface $tokenStorage;

    public DocumentoResource $documentoResource;

    public TipoDocumentoResource $tipoDocumentoResource;

    public JuntadaResource $juntadaResource;

    public ProcessoResource $processoResource;

    public TarefaResource $tarefaResource;

    public EspecieTarefaResource $especieTarefaResource;

    public VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource;

    public ComponenteDigitalResource $componenteDigitalResource;

    public function __construct(
        JuntadaResource $resource,
        ResponseHandler $responseHandler,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        DocumentoResource $documentoResource,
        TipoDocumentoResource $tipoDocumentoResource,
        JuntadaResource $juntadaResource,
        ProcessoResource $processoResource,
        TarefaResource $tarefaResource,
        EspecieTarefaResource $especieTarefaResource,
        ComponenteDigitalResource $componenteDigitalResource,
        VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource,
        protected SetorRepository $setorRepository,
    ) {
        $this->init($resource, $responseHandler);
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->documentoResource = $documentoResource;
        $this->tipoDocumentoResource = $tipoDocumentoResource;
        $this->juntadaResource = $juntadaResource;
        $this->processoResource = $processoResource;
        $this->tarefaResource = $tarefaResource;
        $this->especieTarefaResource = $especieTarefaResource;
        $this->vinculacaoPessoaUsuarioResource = $vinculacaoPessoaUsuarioResource;
        $this->componenteDigitalResource = $componenteDigitalResource;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception|Throwable
     */
    #[Route(
        path: '/{id}/sendEmail',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function sendEmailAction(Request $request, int $id): Response
    {
        $transactionId = $this->transactionManager->begin();

        $context = RequestHandler::getContext($request);

        foreach ($context as $name => $value) {
            $this->transactionManager->addContext(
                new Context($name, $value),
                $transactionId
            );
        }

        $entity = $this->getResource()->sendEmailMethod($request, $id, $transactionId, $context);

        $this->transactionManager->commit($transactionId);

        return $this->getResponseHandler()->createResponse($request, $entity);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    #[Route(
        path: '/{id}/protocoloNupExistente',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['POST']
    )]
    #[IsGranted('ROLE_USER')]
    #[RestApiDoc]
    public function protocoloNupExistenteAction(Request $request, int $id): Response
    {
        $allowedHttpMethods ??= ['POST'];
        $transactionId = $this->transactionManager->begin();

        $context = RequestHandler::getContext($request);

        foreach ($context as $name => $value) {
            $this->transactionManager->addContext(
                new Context($name, $value),
                $transactionId
            );
        }

        $entity = $this->getResource()->protocoloNupExistenteMethod($request, $id, $transactionId);

        $this->transactionManager->commit($transactionId);

        return $this->getResponseHandler()->createResponse($request, $entity);
    }
}
