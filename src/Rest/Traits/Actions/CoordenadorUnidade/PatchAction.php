<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Rest\Traits\Actions\CoordenadorUnidade;

use LogicException;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Rest\Traits\Methods\PatchMethod;
use Symfony\Component\Form\Exception\AlreadySubmittedException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;
use UnexpectedValueException;

/**
 * Trait PatchAction.
 *
 * Trait to add 'patchAction' for REST controllers for 'ROLE_COORDENADOR_UNIDADE' users.
 *
 * @see \SuppCore\AdministrativoBackend\Rest\Traits\Methods\PatchMethod for detailed documents.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
trait PatchAction
{
    // Traits
    use PatchMethod;

    /**
     * @throws LogicException
     * @throws UnexpectedValueException
     * @throws Throwable
     * @throws InvalidOptionsException
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws AlreadySubmittedException
     * @throws NotFoundHttpException
     * @throws MethodNotAllowedHttpException
     * @throws HttpException
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['PATCH'])]
    #[IsGranted('ROLE_COORDENADOR_UNIDADE')]
    #[RestApiDoc]
    public function patchAction(Request $request, FormFactoryInterface $formFactory, int $id): Response
    {
        return $this->patchMethod($request, $formFactory, $id);
    }
}
