<?php

declare(strict_types=1);
/**
 * /src/Rest/Traits/Methods/AbstractGenericMethods.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Traits\Methods;

use Symfony\Component\Form\Exception\AlreadySubmittedException;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use UnexpectedValueException;

/**
 * Trait AbstractFormMethods.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait AbstractFormMethods
{
    // @codingStandardsIgnoreStart
    /**
     * Method to process POST, PUT and PATCH action form within REST traits.
     *
     * @throws UnexpectedValueException
     * @throws NotFoundHttpException
     * @throws HttpException
     * @throws LogicException
     * @throws AlreadySubmittedException
     * @throws InvalidOptionsException
     */
    abstract public function processForm(
        Request $request,
        FormFactoryInterface $formFactory,
        string $method,
        ?int $id = null
    ): FormInterface;

    // @codingStandardsIgnoreEnd
}
