<?php

declare(strict_types=1);
/**
 * /src/Rest/Traits/MethodValidator.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Traits;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Error;
use Exception;
use LogicException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Rest\ControllerInterface;
use SuppCore\AdministrativoBackend\Rest\ResponseHandlerInterface;
use SuppCore\AdministrativoBackend\Rest\RestResourceInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use Symfony\Component\Form\Exception\AlreadySubmittedException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Throwable;
use UnexpectedValueException;

use function array_key_exists;
use function class_implements;
use function in_array;
use function mb_strrpos;
use function mb_substr;
use function sprintf;

/**
 * Trait MethodValidator.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait RestMethodHelper
{
    /**
     * Method + DTO class names (key + value).
     *
     * @var string[]
     */
    protected static array $dtoClasses = [];

    /**
     * Method + Form type class names (key + value).
     *
     * @var string[]
     */
    protected static array $formTypes = [];

    protected ?RestResourceInterface $resource;

    protected ?ResponseHandlerInterface $responseHandler;

    /**
     * @throws UnexpectedValueException
     */
    public function getResource(): RestResourceInterface
    {
        if (!$this->resource instanceof RestResourceInterface) {
            throw new UnexpectedValueException('Resource service not set', 500);
        }

        return $this->resource;
    }

    /**
     * @throws UnexpectedValueException
     */
    public function getResponseHandler(): ResponseHandlerInterface
    {
        if (!$this->responseHandler instanceof ResponseHandlerInterface) {
            throw new UnexpectedValueException('ResponseHandler service not set', 500);
        }

        return $this->responseHandler;
    }

    /**
     * Getter method for used DTO class for current controller.
     *
     * @throws UnexpectedValueException
     */
    public function getDtoClass(?string $method = null): string
    {
        $dtoClass = null !== $method && array_key_exists($method, static::$dtoClasses)
            ? static::$dtoClasses[$method]
            : $this->getResource()->getDtoClass();

        if (!in_array(RestDtoInterface::class, class_implements($dtoClass), true)) {
            $message = sprintf(
                'Given DTO class \'%s\' is not implementing \'%s\' interface.',
                $dtoClass,
                RestDtoInterface::class
            );

            throw new UnexpectedValueException($message);
        }

        return $dtoClass;
    }

    /**
     * Getter method for used DTO class for current controller.
     *
     * @throws UnexpectedValueException
     */
    public function getFormTypeClass(?string $method = null): string
    {
        $method ??= '';
        $position = mb_strrpos($method, '::');

        if (false !== $position) {
            $method = mb_substr($method, $position + 2);
        }

        return array_key_exists($method, static::$formTypes)
            ? static::$formTypes[$method]
            : $this->getResource()->getFormTypeClass();
    }

    /**
     * Method to validate REST trait method.
     *
     * @param string[] $allowedHttpMethods
     *
     * @throws LogicException
     * @throws MethodNotAllowedHttpException
     */
    public function validateRestMethod(Request $request, array $allowedHttpMethods): void
    {
        // Make sure that we have everything we need to make this work
        if (!($this instanceof ControllerInterface)) {
            $message = sprintf(
                'You cannot use \'%s\' controller class with REST traits if that does not implement \'%s\'',
                static::class,
                ControllerInterface::class
            );

            throw new LogicException($message);
        }

        if (!in_array($request->getMethod(), $allowedHttpMethods, true)) {
            throw new MethodNotAllowedHttpException($allowedHttpMethods);
        }
    }

    /**
     * Method to handle possible REST method trait exception.
     *
     * @throws NotFoundHttpException
     */
    public function handleRestMethodException(Throwable $exception, ?int $id = null): Throwable
    {
        return $this->determineOutputAndStatusCodeForRestMethodException($exception);
    }

    /**
     * Method to process current criteria array.
     *
     * @SuppressWarnings("unused")
     *
     * @param mixed[] $criteria
     */
    public function processCriteria(array &$criteria): void
    {
    }

    /**
     * Method to process POST, PUT and PATCH action form within REST traits.
     *
     * @throws UnexpectedValueException
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
    ): FormInterface {
        $formType = $this->getFormTypeClass($method);

        // Create form, load possible entity data for form and handle request
        $form = $formFactory->createNamed(
            '',
            $formType,
            null,
            [
                'method' => $request->getMethod(),
                'form_mapper' => $this->formMapper,
            ]
        );

        $this->setDtoToForm($form, $id);

        $this->stopwatch->start($formType);
        $form->handleRequest($request);
        $this->stopwatch->stop($formType);

        if (!$form->isValid()) {
            $this->getResponseHandler()->handleFormError($form);
        }

        return $form;
    }

    public function processFormMapper(
        Request $request,
        string $method,
        ?int $id = null
    ): RestDtoInterface {
        $this->stopwatch->start($method.':'.$this->getDtoClass().':buildForm');
        $form = $this->formMapper->buildForm($this->getDtoClass(), $method);
        $this->stopwatch->stop($method.':'.$this->getDtoClass().':buildForm');

        // questão de performance, no upload, não pode passar o binario por dentro do objeto form
        $bynary = null;
        if ((ComponenteDigital::class === $this->getDtoClass()) && $request->get('conteudo')) {
            $bynary = $request->get('conteudo');
            $request->request->set('conteudo', 'bynary');
        }

        $this->setDtoToForm($form, $id);

        $this->stopwatch->start($method.':'.$this->getDtoClass().':handleRequest');
        $form->handleRequest($request);
        $this->stopwatch->stop($method.':'.$this->getDtoClass().':handleRequest');

        if (!$form->isValid()) {
            $this->getResponseHandler()->handleFormError($form);
        }

        // questão de performance, no upload, não pode passar o binario por dentro do objeto form
        if ($bynary) {
            $data = $form->getData();
            $data->setConteudo($bynary);

            return $data;
        }

        return $form->getData();
    }

    private function getExceptionCode(Throwable $exception): int
    {
        return 0 !== (int) $exception->getCode() ? (int) $exception->getCode() : Response::HTTP_BAD_REQUEST;
    }

    /**
     * @param Throwable|Exception $exception
     */
    private function determineOutputAndStatusCodeForRestMethodException($exception): Throwable
    {
        $code = $this->getExceptionCode($exception);

        if ($code < 100 || $code >= 600) {
            $code = 500;
        }

        if ($exception instanceof Error) {
            if ('prod' === $this->parameterBag->get('kernel.environment')) {
                $message = 'Houve um erro indeterminado!';
            } else {
                $message = $exception->getMessage().' - file '.$exception->getFile().' - line '.$exception->getLine();
            }
            $exception = new Exception($message, $exception->getCode(), $exception);
        }

        /** @var Exception $exception */
        $output = new HttpException($code, $exception->getMessage(), $exception, [], $code);

        if ($exception instanceof HttpException) {
            $output = $exception;
        } elseif ($exception instanceof NoResultException) {
            $code = Response::HTTP_NOT_FOUND;

            $output = new HttpException($code, 'Not found', $exception, [], $code);
        } elseif ($exception instanceof NonUniqueResultException) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;

            $output = new HttpException($code, $exception->getMessage(), $exception, [], $code);
        } elseif ($exception instanceof RuleException) {
            $code = Response::HTTP_UNPROCESSABLE_ENTITY;

            $output = new HttpException($code, $exception->getMessage(), $exception, [], $code);
        } elseif ($exception instanceof ValidatorException) {
            $code = Response::HTTP_UNPROCESSABLE_ENTITY;

            $output = new HttpException($code, $exception->getMessage(), $exception, [], $code);
        }

        return $output;
    }

    /**
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws NotFoundHttpException
     * @throws AlreadySubmittedException
     */
    private function setDtoToForm(FormInterface $form, ?int $id): void
    {
        $dtoClass = $form->getConfig()->getDataClass();

        if (null !== $id && null !== $dtoClass) {
            $form->setData($this->getResource()->getDtoForEntity($id, $dtoClass));
        }
    }
}
