<?php

declare(strict_types=1);
/**
 * /src/Rest/ResponseHandlerInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Interface ResponseHandlerInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface ResponseHandlerInterface
{
    /**
     * Constants for response output formats.
     *
     * @var string
     */
    public const FORMAT_JSON = 'json';
    public const FORMAT_XML = 'xml';

    public function __construct(SerializerInterface $serializer);

    /**
     * Getter for serializer.
     */
    public function getSerializer(): SerializerInterface;

    /**
     * Getter for current resource service.
     */
    public function getResource(): RestResourceInterface;

    /**
     * Setter for resource service.
     */
    public function setResource(RestResourceInterface $resource): self;

    public function createResponse(
        Request $request,
        $data,
        ?int $httpStatus = null,
        ?string $format = null,
        ?array $context = null
    ): Response;

    /**
     * Method to handle form errors.
     *
     * @throws HttpException
     */
    public function handleFormError(FormInterface $form): void;
}
