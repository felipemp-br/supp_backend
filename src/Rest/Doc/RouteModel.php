<?php

declare(strict_types=1);
/**
 * /src/Rest/Doc/RouteModel.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Doc;

use Symfony\Component\Routing\Route;

/**
 * Class RouteModel.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RouteModel
{
    private string $controller;

    private string $method;

    private string $httpMethod;

    private string $baseRoute;

    private Route $route;

    private array $methodMetadata;

    private array $controllerMetadata;

    public function getController(): string
    {
        return $this->controller;
    }

    public function setController(string $controller): self
    {
        $this->controller = $controller;

        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    public function setHttpMethod(string $httpMethod): self
    {
        $this->httpMethod = $httpMethod;

        return $this;
    }

    public function getBaseRoute(): string
    {
        return $this->baseRoute;
    }

    public function setBaseRoute(string $baseRoute): self
    {
        $this->baseRoute = $baseRoute;

        return $this;
    }

    public function getRoute(): Route
    {
        return $this->route;
    }

    public function setRoute(Route $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getMethodMetadata(): array
    {
        return $this->methodMetadata;
    }

    public function setMethodMetadata(array $methodMetadata): self
    {
        $this->methodMetadata = $methodMetadata;

        return $this;
    }

    public function getControllerMetadata(): array
    {
        return $this->controllerMetadata;
    }

    public function setControllerMetadata(array $controllerMetadata): self
    {
        $this->controllerMetadata = $controllerMetadata;

        return $this;
    }
}
