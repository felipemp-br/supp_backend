<?php

declare(strict_types=1);
/**
 * /src/Rest/Traits/Actions/Admin/UndeleteAction.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Traits\Actions\Admin;

use LogicException;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Rest\Traits\Methods\UndeleteMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * Trait UndeleteAction.
 *
 * Trait to add 'undeleteAction' for REST controllers for 'ROLE_ADMIN' users.
 *
 * @see \SuppCore\AdministrativoBackend\Rest\Traits\Methods\UndeleteMethod for detailed documents.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait UndeleteAction
{
    // Traits
    use UndeleteMethod;

    /**
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{id}/undelete',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[IsGranted('ROLE_ADMIN')]
    #[RestApiDoc]
    public function undeleteAction(Request $request, int $id): Response
    {
        return $this->undeleteMethod($request, $id);
    }
}
