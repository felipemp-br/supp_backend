<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Rest\Traits\Actions\CoordenadorSetor;

use LogicException;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Rest\Traits\Methods\UpdateMethod;
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
 * Trait UpdateAction.
 *
 * Trait to add 'updateAction' for REST controllers for 'ROLE_COORDENADOR_SETOR' users.
 *
 * @see \SuppCore\AdministrativoBackend\Rest\Traits\Methods\UpdateMethod for detailed documents.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
trait UpdateAction
{
    // Traits
    use UpdateMethod;

    /**
     * @throws LogicException
     * @throws UnexpectedValueException
     * @throws Throwable
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws AlreadySubmittedException
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws MethodNotAllowedHttpException
     * @throws InvalidOptionsException
     */
    #[Route(
        path: '/{id}',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PUT']
    )]
    #[IsGranted('ROLE_COORDENADOR_SETOR')]
    #[RestApiDoc]
    public function updateAction(Request $request, FormFactoryInterface $formFactory, int $id): Response
    {
        return $this->updateMethod($request, $formFactory, $id);
    }
}
