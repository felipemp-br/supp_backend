<?php

declare(strict_types=1);
/**
 * /src/Rest/ControllerInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest;

use LogicException;
use Symfony\Component\Form\Exception\AlreadySubmittedException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Throwable;
use UnexpectedValueException;

/**
 * Interface ControllerInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface ControllerInterface
{
    /**
     * @throws UnexpectedValueException
     */
    public function getResource(): RestResourceInterface;

    /**
     * @throws UnexpectedValueException
     */
    public function getResponseHandler(): ResponseHandlerInterface;

    /**
     * Getter method for used DTO class for current controller.
     *
     * @throws UnexpectedValueException
     */
    public function getDtoClass(?string $method = null): string;

    /**
     * Getter method for used DTO class for current controller.
     *
     * @throws UnexpectedValueException
     */
    public function getFormTypeClass(?string $method = null): string;

    /**
     * Method to validate REST trait method.
     *
     * @param string[] $allowedHttpMethods
     *
     * @throws LogicException
     * @throws MethodNotAllowedHttpException
     */
    public function validateRestMethod(Request $request, array $allowedHttpMethods): void;

    /**
     * Method to handle possible REST method trait exception.
     *
     * @throws HttpException
     */
    public function handleRestMethodException(Throwable $exception): Throwable;

    /**
     * Method to process current criteria array.
     *
     * @param mixed[] $criteria
     */
    public function processCriteria(array &$criteria): void;

    /**
     * Method to process POST, PUT and PATCH action form within REST traits.
     *
     * @throws NotFoundHttpException
     * @throws HttpException
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws AlreadySubmittedException
     * @throws InvalidOptionsException
     */
    public function processForm(
        Request $request,
        FormFactoryInterface $formFactory,
        string $method,
        ?int $id = null
    ): FormInterface;
}
