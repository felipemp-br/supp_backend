<?php

declare(strict_types=1);
/**
 * /src/Rest/ResponseHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest;

use Exception;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;
use Symfony\Component\HttpKernel\Exception\HttpException;
use function array_pop;

/**
 * Class ResponseHandler.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
final class ResponseHandler implements ResponseHandlerInterface
{
    /**
     * Content types for supported response output formats.
     *
     * @var mixed[]
     */
    private array $contentTypes = [
        self::FORMAT_JSON => 'application/json',
        self::FORMAT_XML => 'application/xml',
    ];

    private SerializerInterface $serializer;

    private RestResourceInterface $resource;

    /**
     * ResponseHandler constructor.
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Getter for serializer.
     */
    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    /**
     * Getter for current resource service.
     */
    public function getResource(): RestResourceInterface
    {
        return $this->resource;
    }

    /**
     * Setter for resource service.
     */
    public function setResource(RestResourceInterface $resource): ResponseHandlerInterface
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Helper method to create response for request.
     *
     * @param mixed        $data
     * @param mixed[]|null $context
     *
     * @throws HttpException
     */
    public function createResponse(
        Request $request,
        $data,
        ?int $httpStatus = null,
        ?string $format = null,
        ?array $context = null
    ): Response {
        $httpStatus ??= 200;
        $format = $this->getFormat($request, $format);

        $populate = RequestHandler::getPopulate($request, $this->getResource());

        // Get response
        $response = $this->getResponse($data, $httpStatus, $format, $context, $populate);

        // Set content type
        $response->headers->set('Content-Type', $this->contentTypes[$format]);

        $response->setCache([
            'no_store' => true,
            'no_transform' => true,
        ]);

        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');

        return $response;
    }

    /**
     * Helper method to create response for request.
     *
     * @param mixed        $data
     * @param mixed[]|null $context
     *
     * @throws HttpException
     */
    public function createPdfResponse(
        Request $request,
        $data,
        ?int $httpStatus = null,
        ?string $format = null,
        ?array $context = null
    ): Response {
        $httpStatus ??= 200;
        $format = $this->getFormat($request, $format);

        $populate = RequestHandler::getPopulate($request, $this->getResource());

        // Get response
        $response = $this->getPdfResponse($data, $httpStatus, $format, $context, $populate);

        // Set content type
        $response->headers->set('Content-Type', $this->contentTypes[$format]);

        $response->setCache([
            'no_store' => true,
            'no_transform' => true,
        ]);

        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');

        return $response;
    }

    /**
     * Helper method to create response for request.
     *
     * @param mixed $data
     *
     * @throws HttpException
     */
    public function createCountResponse(
        Request $request,
        string $data,
        ?int $httpStatus = null,
        ?string $format = null
    ): Response {
        $httpStatus ??= 200;
        $format = $this->getFormat($request, $format);

        // Get response
        $response = $this->getCountResponse($data, $httpStatus);

        // Set content type
        $response->headers->set('Content-Type', $this->contentTypes[$format]);

        $response->setCache([
            'no_store' => true,
            'no_transform' => true,
        ]);

        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');

        return $response;
    }

    /**
     * Method to handle form errors.
     *
     * @throws HttpException
     */
    public function handleFormError(FormInterface $form): void
    {
        $errors = [];

        /** @var FormError $error */
        foreach ($form->getErrors(true) as $error) {
            $name = $error->getOrigin()->getName();

            $errors[$name][] = $error->getMessage();

            if (empty($name)) {
                array_pop($errors);

                $errors[] = $error->getMessage();
            }
        }

        throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, json_encode($errors));
    }

    private function getFormat(Request $request, ?string $format = null): string
    {
        return $format ?? (self::FORMAT_XML === $request->getContentTypeFormat() ? self::FORMAT_XML : self::FORMAT_JSON);
    }

    /**
     * @param mixed        $data
     * @param mixed[]|null $context
     * @param mixed[]|null $populate
     *
     * @throws HttpException
     */
    private function getResponse($data, int $httpStatus, string $format, ?array $context, ?array $populate): Response
    {
        try {
            $isPaginated = false;
            $total = 0;
            if (is_array($data) && isset($data['total'])) {
                $entities = $data['entities'];
                $total = $data['total'];
                $isPaginated = true;
            } else {
                $entities = [$data];
            }
            $resource = $this->getResource();
            $dtoClassName = $resource->getDtoClass();
            $dtoMapper = $resource->getDtoMapperManager()->getMapper($resource->getDtoClass());
            $dto = [];
            foreach ($entities as $entity) {
                if ($entity instanceof RestDtoInterface) {
                    $dto[] = $entity;
                } else {
                    $dto[] = $dtoMapper->convertEntityToDto($entity, $dtoClassName, $populate);
                }
            }
            $serializerContext = null;
            if (isset($context['groups'])) {
                $serializerContext = SerializationContext::create()->setGroups($context['groups']);
            }

            // Create new response
            $response = new Response();
            $response->setContent(
                $this->serializer->serialize(
                    $isPaginated ? ['entities' => $dto, 'total' => $total] : $dto[0],
                    $format,
                    $serializerContext
                )
            );
            $response->setStatusCode($httpStatus);
        } catch (Exception $exception) {
            $status = Response::HTTP_BAD_REQUEST;

            throw new HttpException($status, $exception->getMessage(), /*.' on line '.$exception->getLine().' of '.$exception->getFile(), */ $exception, [], $status);
        }

        return $response;
    }

    /**
     * @param mixed        $data
     * @param mixed[]|null $context
     * @param mixed[]|null $populate
     *
     * @throws HttpException
     */
    private function getPdfResponse($data, int $httpStatus, string $format, ?array $context, ?array $populate): Response
    {
        try {
            $dtoMapper = $this->getResource()->getDtoMapperManager()->getMapper('SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital');
            $dto = $dtoMapper->convertEntityToDto($data, 'SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital', $populate);
            $serializerContext = null;
            if (isset($context['groups'])) {
                $serializerContext = SerializationContext::create()->setGroups($context['groups']);
            }
            // Create new response
            $response = new Response();
            $response->setContent(
                $this->serializer->serialize(
                    $dto,
                    $format,
                    $serializerContext
                )
            );
            $response->setStatusCode($httpStatus);
        } catch (Exception $exception) {
            $status = Response::HTTP_BAD_REQUEST;

            throw new HttpException($status, $exception->getMessage(), /*.' on line '.$exception->getLine().' of '.$exception->getFile(), */ $exception, [], $status);
        }

        return $response;
    }

    /**
     * @param mixed $data
     *
     * @throws HttpException
     */
    private function getCountResponse($data, int $httpStatus): Response
    {
        try {
            // Create new response
            $response = new Response();
            $response->setContent($data);
            $response->setStatusCode($httpStatus);
        } catch (Exception $exception) {
            $status = Response::HTTP_BAD_REQUEST;

            throw new HttpException($status, $exception->getMessage(), /*.' on line '.$exception->getLine().' of '.$exception->getFile(), */ $exception, [], $status);
        }

        return $response;
    }
}
