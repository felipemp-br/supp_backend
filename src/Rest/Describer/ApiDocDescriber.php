<?php

declare(strict_types=1);
/**
 * /src/Rest/Describer/ApiDocDescriber.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Describer;

use Closure;
use Nelmio\ApiDocBundle\Describer\DescriberInterface;
use Nelmio\ApiDocBundle\Describer\ModelRegistryAwareInterface;
use Nelmio\ApiDocBundle\Describer\ModelRegistryAwareTrait;
use Nelmio\ApiDocBundle\Model\Model;
use Nelmio\ApiDocBundle\OpenApiPhp\Util;
use OpenApi\Annotations as OA;
use OpenApi\Annotations\OpenApi;
use OpenApi\Generator as OAGenerator;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Rest\Doc\RouteModel;
use SuppCore\AdministrativoBackend\Rules\RulesManager;
use SuppCore\AdministrativoBackend\Triggers\TriggersManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

use function array_filter;
use function array_map;
use function array_values;
use function explode;
use function mb_strrpos;

/**
 * Class ApiDocDescriber.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ApiDocDescriber implements DescriberInterface, ModelRegistryAwareInterface
{
    use ModelRegistryAwareTrait;
    private RouteCollection $routeCollection;
    private array $rules;
    private array $triggers;
    private OpenApi $api;

    public function __construct(
        private readonly ContainerInterface $container,
        private readonly RouterInterface $router,
        private readonly Rest $rest,
        private readonly RulesManager $rulesManager,
        private readonly TriggersManager $triggersManager
    ) {
    }

    /**
     * @throws ReflectionException
     */
    public function describe(OpenApi $api): void
    {
        $this->routeCollection = $this->router->getRouteCollection();
        $this->api = $api;
        $apiPaths = [];
        foreach ($api->paths as $pathItem) {
            $apiPaths[$pathItem->path] = $pathItem;
        }

        /**
         * @var RouteModel $routeModel *
         */
        foreach ($this->getRouteModels() as $routeModel) {
            if (!array_key_exists($routeModel->getRoute()->getPath(), $apiPaths)) {
                continue;
            }

            /** @var OA\PathItem $path */
            $path = $apiPaths[$routeModel->getRoute()->getPath()];

            $operation = Util::getOperation($path, $routeModel->getHttpMethod());

            if ($operation) {
                $this->rest->createDocs($operation, $routeModel);

                $controller = $this->container->get($routeModel->getController());
                if (isset($controller->noResource) || !$controller->getResource()->getDtoClass()) {
                    continue;
                }
                if ('put' === $routeModel->getHttpMethod()
                    || 'post' === $routeModel->getHttpMethod()
                    || 'delete' === $routeModel->getHttpMethod()
                ) {
                    $desc = $this->retornaDocRules(
                        $controller->getResource()->getDtoClass(),
                        $routeModel->getHttpMethod()
                    );
                    $desc .= $this->retornaDocTriggers(
                        $controller->getResource()->getDtoClass(),
                        $routeModel->getHttpMethod()
                    );

                    $operation->description = $desc;
                }
                $code = 200;
                if ('post' === $routeModel->getHttpMethod()) {
                    $code = 201;
                }

                if (OAGenerator::UNDEFINED === $operation->responses) {
                    continue;
                }

                foreach ($operation->responses as $response) {
                    if ($response->response === $code) {
                        $response->ref = $this->modelRegistry->register(
                            new Model(
                                new Type(
                                    Type::BUILTIN_TYPE_OBJECT,
                                    false,
                                    $controller->getResource()->getDtoClass()
                                ),
                            )
                        );
                    }
                }
            }
        }
    }

    /**
     * @throws ReflectionException
     */
    private function retornaDocRules(string $classe, string $operation): string
    {
        $fluxo = false;
        $retorno = '<b>RULES:</b>';

        $operations = [
            'post'   => ['beforeCreate', 'afterCreate'],
            'put'    => ['beforeUpdate', 'afterUpdate'],
            'patch'  => ['beforePatch', 'afterPatch'],
            'delete' => ['beforeDelete', 'afterDelete']
        ];

        if (isset($operations[$operation])) {
            $rules = array_unique(array_merge(
                $this->rulesManager->getRules($classe, $operations[$operation][0]),
                $this->rulesManager->getRules($classe, $operations[$operation][1])
            ));

            if ($rules) {
                $retorno .= implode('', array_map(fn($rule) => "<p>{$this->retornaDocSwagger($rule)}</p>", $rules));
                $fluxo = true;
            }
        }

        if ($fluxo) {
            return $retorno;
        }

        return '';
    }

    /**
     * @throws ReflectionException
     */
    private function retornaDocSwagger($classe): string
    {
        $descSwagger = false;
        $classeSwagger = false;
        $lazyClass = current(class_parents($classe));
        if (!$lazyClass) {
            $lazyClass = $classe;
        }
        $reflectionClass = new ReflectionClass($lazyClass);

        $comment_string = $reflectionClass->getDocComment();
        $pattern = '/((@descSwagger)|(@classeSwagger)).*/';
        $tags = [];
        preg_match_all($pattern, $comment_string, $tags);

        foreach ($tags[0] as $tag) {
            $swagger = explode('=', (string) $tag);
            if ('@descSwagger' === trim($swagger[0])) {
                $descSwagger = trim($swagger[1]);
            }
            if ('@classeSwagger' === trim($swagger[0])) {
                $classeSwagger = trim($swagger[1]);
            }
        }

        if (!$descSwagger) {
            $descSwagger = 'Não retornou a descrição';
        }
        if (!$classeSwagger) {
            $classeSwagger = 'Não retornou a classe';
        }

        return $classeSwagger.': '.$descSwagger;
    }

    /**
     * @throws ReflectionException
     */
    private function retornaDocTriggers(string $classe, string $operation): string
    {
        $fluxo = false;
        $retorno = '<b>TRIGGERS:</b>';

        $operations = [
            'post'   => ['beforeCreate', 'afterCreate'],
            'put'    => ['beforeUpdate', 'afterUpdate'],
            'patch'  => ['beforePatch', 'afterPatch'],
            'delete' => ['beforeDelete', 'afterDelete']
        ];

        if (isset($operations[$operation])) {
            $rules = array_unique(array_merge(
                $this->rulesManager->getRules($classe, $operations[$operation][0]),
                $this->rulesManager->getRules($classe, $operations[$operation][1])
            ));

            if ($rules) {
                $retorno .= implode('', array_map(fn($rule) => "<p>{$this->retornaDocSwagger($rule)}</p>", $rules));
                $fluxo = true;
            }
        }

        if ($fluxo) {
            return $retorno;
        }

        return '';
    }

    /**
     * @throws ReflectionException
     */
    private function getRouteModels(): array
    {
        $annotationFilterRoute = $this->getClosureAnnotationFilterRoute();

        $iterator = function (Route $route) use ($annotationFilterRoute): RouteModel {
            [$controller, $method] = explode(
                Constants::KEY_CONTROLLER_DELIMITER,
                (string) $route->getDefault(Constants::KEY_CONTROLLER)
            );

            $reflection = new ReflectionMethod($controller, $method);

            $methodMetadata = [];
            if ($reflection->getAttributes()) {
                foreach ($reflection->getAttributes() as $attribute) {
                    $methodMetadata[] = $attribute->newInstance();
                }
            }

            $controllerMetadata = [];
            if ($reflection->getDeclaringClass()->getAttributes()) {
                foreach ($reflection->getDeclaringClass()->getAttributes() as $attribute) {
                    $controllerMetadata[] = $attribute->newInstance();
                }
            }

            /** @var \Symfony\Component\Routing\Annotation\Route $routeAnnotation */
            $routeAnnotation = array_values(array_filter($controllerMetadata, $annotationFilterRoute))[0];

            $routeModel = new RouteModel();
            $routeModel->setController($controller);
            $routeModel->setMethod($method);
            $routeModel->setHttpMethod(mb_strtolower($route->getMethods()[0], 'UTF8'));
            $routeModel->setBaseRoute($routeAnnotation->getPath());
            $routeModel->setRoute($route);
            $routeModel->setMethodMetadata($methodMetadata);
            $routeModel->setControllerMetadata($controllerMetadata);

            return $routeModel;
        };

        $filter = fn (Route $route): bool => $this->routeFilter($route);

        return array_map($iterator, array_filter($this->routeCollection->all(), $filter));
    }

    /**
     * @throws ReflectionException
     */
    private function routeFilter(Route $route): bool
    {
        $output = false;

        if (!$route->hasDefault(Constants::KEY_CONTROLLER)
            || mb_strrpos((string) $route->getDefault(Constants::KEY_CONTROLLER), Constants::KEY_CONTROLLER_DELIMITER)
        ) {
            $output = true;
        }

        if ($output) {
            [$controller, $method] = explode(
                Constants::KEY_CONTROLLER_DELIMITER,
                (string) $route->getDefault(Constants::KEY_CONTROLLER)
            );

            if (!class_exists($controller)) {
                return false;
            }

            $reflection = new ReflectionMethod($controller, $method);

            foreach ($reflection->getAttributes() as $attribute) {
                if (RestApiDoc::class === $attribute->getName()) {
                    $metadata = $attribute->newInstance();
                    break;
                }
            }

            if (($metadata ?? null)?->disabled) {
                $output = false;
                foreach ($this->api->paths as $key => $value) {
                    if ($value->path === $route->getPath()) {
                        unset($this->api->paths[$key]);
                    }
                }
            }
        }

        return $this->routeFilterMethod($route, $output);
    }

    /**
     * @throws ReflectionException
     */
    private function routeFilterMethod(Route $route, bool $output): bool
    {
        if ($output) {
            [$controller, $method] = explode(
                Constants::KEY_CONTROLLER_DELIMITER,
                (string) $route->getDefault(Constants::KEY_CONTROLLER)
            );

            $reflection = new ReflectionMethod($controller, $method);

            foreach ($reflection->getAttributes() as $attribute) {
                if (RestApiDoc::class === $attribute->getName()) {
                    $metadata = $attribute->newInstance();
                    break;
                }
            }

            $output = (bool) $metadata ??= null;
        }

        return $output;
    }

    private function getClosureAnnotationFilterRoute(): Closure
    {
        /*
         * Simple filter lambda function to filter out all but Method class
         *
         * @param $annotation
         *
         * @return bool
         */
        return fn ($annotation): bool => $annotation instanceof \Symfony\Component\Routing\Annotation\Route;
    }
}
