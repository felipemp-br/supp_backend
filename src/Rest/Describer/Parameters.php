<?php

declare(strict_types=1);
/**
 * /src/Rest/Describer/Parameters.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Describer;

use BackedEnum;
use OpenApi\Annotations as OA;
use OpenApi\Generator as OAGenerator;
use Psr\Cache\InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use SuppCore\AdministrativoBackend\Form\Driver\AttributesDriver;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\Doc\RouteModel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Environment as Twig_Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use UnexpectedValueException;

use function count;
use function explode;
use function in_array;

/**
 * Class Parameters.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Parameters
{
    /**
     * Specify used examples for this parameter.
     *
     * @var mixed[]
     */
    private static array $orderByExamples = [
        'order: {"principal": "ASC"}      => ORDER BY entity.principal ASC',
        'order: {"criadoEm": "DESC"}      => ORDER BY entity.criadoEm DESC ',
    ];

    /**
     * Specify used advanced examples for this parameter.
     *
     * @var mixed[]
     */
    private static array $orderByAdvancedExamples = [
        'order: {"principal": "DESC", "criadoEm": "DESC"}   => ORDER BY entity.principal ASC, entity.criadoEm DESC ',
    ];

    /**
     * Specify used advanced examples for this parameter.
     *
     * @var mixed[]
     */
    private static array $criteriaExamples = [
        'where: {"id": "eq:1"}                                            => WHERE entity.id = 1',
        'where: {"id": "neq:1"}                                           => WHERE entity.id != 1',
        'where: {"id": "like:1%}                                          => WHERE entity.id like ("1%")',
        'where: {"id": "notLike:1%"}                                      => WHERE entity.id not like ("1%")',
        'where: {"id": "gt:1"}                                            => WHERE entity.id > 1',
        'where: {"id": "gte:1"}                                           => WHERE entity.id >= 1',
        'where: {"id": "lt:1"}                                            => WHERE entity.id < 1',
        'where: {"id": "lte:1"}                                           => WHERE entity.id <= 1',
        'where: {"id": "in:1,2,3,4"}                                      => WHERE entity.id in (1,2,3,4)',
        'where: {"id": "notIn:1,2,3,4"}                                   => WHERE entity.id not in (1,2,3,4)',
        'where: {"id": "isNull"}                                          => WHERE entity.id isNull',
        'where: {"id": "isNotNull"}                                       => WHERE entity.id isNotNull',
        'where: {"orX": ["id": "eq:1"},{"uuid": "eq:2"}]}                 => WHERE entity.id = 1 or entity.id = 2',
    ];

    private readonly ContainerInterface $container;

    private readonly Twig_Environment $templateEngine;

    private readonly Responses $responses;

    private readonly AttributesDriver $attributesDriver;

    public function __construct(
        ContainerInterface $container,
        Twig_Environment $templateEngine,
        Responses $responses,
        AttributesDriver $attributesDriver
    ) {
        $this->container = $container;
        $this->templateEngine = $templateEngine;
        $this->responses = $responses;
        $this->attributesDriver = $attributesDriver;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function process(OA\Operation $operation, RouteModel $routeModel): void
    {
        $action = $routeModel->getMethod();

        if (in_array($action, [Rest::COUNT_ACTION, Rest::IDS_ACTION], true)) {
            $this->responses->add404($operation);
            $this->addParameterCriteria($operation);
            $this->addParameterContext($operation);
        } elseif (in_array($action, [Rest::DELETE_ACTION, Rest::PATCH_ACTION, Rest::UPDATE_ACTION], true)) {
            $this->changePathParameter($operation);
        } elseif (in_array($action, [Rest::FIND_ONE_ACTION, Rest::FIND_ACTION, Rest::SEARCH_ACTION], true)) {
            $this->processFind($operation, $routeModel);
        }

        if (in_array($action, [Rest::CREATE_ACTION, Rest::PATCH_ACTION, Rest::UPDATE_ACTION], true)) {
            $this->addParameterContext($operation);
            $this->addJsonBody($operation, $routeModel);
        }
    }

    /**
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    private function addJsonBody(OA\Operation $operation, RouteModel $routeModel): void
    {
        $controller = $this->container->get($routeModel->getController());

        $requestBody = [
            'required' => true,
            'description' => 'JSON payload',
            'request' => 'request',
            'content' => [
                'application/json' => new OA\MediaType(
                    [
                        'mediaType' => 'application/json',
                        'schema' => new OA\Schema(
                            [
                                'type' => 'object',
                                'example' => $this->getJsonExamples(
                                    $controller->getResource()->getDtoClass(),
                                    $routeModel->getHttpMethod()
                                ),
                                '_context' => $operation->_context,
                            ]
                        ),
                    ]
                ),
            ],
            '_context' => $operation->_context,
        ];

        $operation->requestBody = new OA\RequestBody($requestBody);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function processFind(OA\Operation $operation, RouteModel $routeModel): void
    {
        $action = $routeModel->getMethod();

        if (Rest::FIND_ONE_ACTION === $action) {
            $this->addParameterPopulate($operation, $routeModel);
            $this->addParameterContext($operation);
            $this->changePathParameter($operation);
        } elseif (Rest::FIND_ACTION === $action) {
            $this->addParameterOrderBy($operation);
            $this->addParameterLimit($operation);
            $this->addParameterOffset($operation);
            $this->addParameterContext($operation);
            $this->addParameterCriteria($operation);
            $this->addParameterPopulate($operation, $routeModel);
        } elseif (Rest::SEARCH_ACTION === $action) {
            $this->addParameterOrderBy($operation);
            $this->addParameterLimit($operation);
            $this->addParameterOffset($operation);
            $this->addParameterCriteria($operation);
        }
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function addParameterCriteria(OA\Operation $operation): void
    {
        $parameter = [
            'name' => 'where',
            'in' => 'query',
            'required' => false,
            'description' => $this->getDescription(
                'parameter_criteria.twig',
                [
                    'examples' => self::$criteriaExamples,
                ]
            ),
            'schema' => new OA\Schema(
                [
                    'type' => 'string',
                    'default' => '{"id": "eq:1"}',
                    '_context' => $operation->_context,
                ]
            ),
            '_context' => $operation->_context,
        ];

        if (OAGenerator::UNDEFINED === $operation->parameters) {
            $operation->parameters = [];
        }

        $operation->parameters[] = new OA\Parameter($parameter);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function addParameterOrderBy(OA\Operation $operation): void
    {
        $parameter = [
            'parameter' => 'order',
            'name' => 'order',
            'in' => 'query',
            'required' => false,
            'description' => $this->getDescription(
                'parameter_order.twig',
                [
                    'examples' => self::$orderByExamples,
                    'advancedExamples' => self::$orderByAdvancedExamples,
                ]
            ),
            'schema' => new OA\Schema(
                [
                    'schema' => 'order',
                    'type' => 'string',
                    'default' => '{"id":"DESC"}',
                    '_context' => $operation->_context,
                ]
            ),
            '_context' => $operation->_context,
        ];

        if (OAGenerator::UNDEFINED === $operation->parameters) {
            $operation->parameters = [];
        }

        $operation->parameters[] = new OA\Parameter($parameter);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function addParameterLimit(OA\Operation $operation): void
    {
        // Specify used  examples for this parameter
        static $examples = [
            'limit: 10',
        ];

        if (OAGenerator::UNDEFINED === $operation->parameters) {
            $operation->parameters = [];
        }

        $operation->parameters[] = $this->getLimitOffsetParameter(
            'limit',
            'parameter_limit.twig',
            $examples,
            $operation
        );
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function addParameterOffset(OA\Operation $operation): void
    {
        // Specify used  examples for this parameter
        static $examples = [
            'offset: 0',
        ];

        if (OAGenerator::UNDEFINED === $operation->parameters) {
            $operation->parameters = [];
        }

        $operation->parameters[] = $this->getLimitOffsetParameter(
            'offset',
            'parameter_offset.twig',
            $examples,
            $operation
        );
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function addParameterContext(OA\Operation $operation): void
    {
        // Specify used  examples for this parameter
        static $examples = [
            '{"context": "12345678"}',
        ];

        if (OAGenerator::UNDEFINED === $operation->parameters) {
            $operation->parameters = [];
        }

        $operation->parameters[] = $this->getLimitContextParameter(
            'context',
            'parameter_context.twig',
            $examples,
            $operation
        );
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function getLimitOffsetParameter(
        string $name,
        string $template,
        array $examples,
        OA\Operation $operation
    ): OA\Parameter {
        $default = 'limit' === $name ? '10' : '0';
        $parameter = [
            'parameter' => $name,
            'name' => $name,
            'in' => 'query',
            'required' => false,
            'description' => $this->getDescription(
                $template,
                [
                    'examples' => $examples,
                ]
            ),
            'schema' => new OA\Schema(
                [
                    'schema' => $name,
                    'type' => 'string',
                    'default' => $default,
                    '_context' => $operation->_context,
                ]
            ),
            '_context' => $operation->_context,
        ];

        return new OA\Parameter($parameter);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function getLimitContextParameter(
        string $name,
        string $template,
        array $examples,
        OA\Operation $operation
    ): OA\Parameter {
        $default = 'limit' === $name ? '10' : '';

        $parameter = [
            'parameter' => $name,
            'name' => $name,
            'in' => 'query',
            'required' => false,
            'description' => $this->getDescription(
                $template,
                [
                    'examples' => $examples,
                ]
            ),
            'schema' => new OA\Schema(
                [
                    'schema' => $name,
                    'type' => 'string',
                    'default' => $default,
                    '_context' => $operation->_context,
                ]
            ),
            '_context' => $operation->_context,
        ];

        return new OA\Parameter($parameter);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function addParameterPopulate(OA\Operation $operation, RouteModel $routeModel): void
    {
        /** @var Controller $controller */
        $controller = $this->container->get($routeModel->getController());

        $parameter = [
            'name' => 'populate',
            'in' => 'query',
            'required' => false,
            'description' => $this->getDescription(
                'parameter_populate.twig',
                [
                    'examples' => $this->getPopulateExamples($controller),
                    'associations' => $controller->getResource()->getAllAssociations(),
                ]
            ),
            'schema' => new OA\Schema(
                [
                    'type' => 'string',
                    'default' => '["populateAll"]',
                    '_context' => $operation->_context,
                ]
            ),
            '_context' => $operation->_context,
        ];

        if (OAGenerator::UNDEFINED === $operation->parameters) {
            $operation->parameters = [];
        }

        $operation->parameters[] = new OA\Parameter($parameter);
    }

    private function changePathParameter(OA\Operation $operation): void
    {
        /**
         * @var OA\Parameter $parameter
         */
        foreach ($operation->parameters as $parameter) {
            if ('path' !== $parameter->in) {
                continue;
            }

            $parameter->description = 'Identifier';
            $parameter->schema = new OA\Schema(
                [
                    'type' => 'string',
                    'default' => 'Identifier',
                    '_context' => $operation->_context,
                ]
            );
        }
    }

    /**
     * @return string[]
     *
     * @throws UnexpectedValueException
     */
    private function getPopulateExamples(Controller $controller): array
    {
        // Determine base name for resource serializer group
        explode('\\', $controller->getResource()->getEntityName());

        // Specify used  examples for this parameter
        $examples = [];
        $lista = 'populate: [';
        $total = count($controller->getResource()->getAllAssociations());
        $cont = 0;
        foreach ($controller->getResource()->getAllAssociations() as $association) {
            ++$cont;
            $lista = $lista.'"'.$association.'"';
            if ($cont < $total) {
                $lista .= ', ';
            }
        }
        $lista .= ']';
        $examples[] = $lista;

        return $examples;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function getDescription(string $template, array $data): string
    {
        return $this->templateEngine->render('Swagger/'.$template, $data);
    }

    /**
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    private function getJsonExamples(string $classeDTO, string $action): array
    {
        $attributes = $this->attributesDriver->getMetadata($classeDTO);
        $exampleJson = [];

        foreach ($attributes->getFields() as $field) {
            $permitido = false;
            if (empty($field->methods)) {
                $permitido = true;
            } else {
                foreach ($field->methods as $method) {
                    if ('createMethod' === $method->value && 'post' === $action) {
                        if (empty($method->roles)) {
                            $permitido = true;
                            break;
                        }
                        foreach ($method->roles as $role) {
                            if ('ROLE_USER' === $role) {
                                $permitido = true;
                                break;
                            }
                        }
                    }
                    if (('updateMethod' === $method->value || 'patchMethod' === $method->value) && 'put' === $action) {
                        if (empty($method->roles)) {
                            $permitido = true;
                            break;
                        }
                        foreach ($method->roles as $role) {
                            if ('ROLE_USER' === $role) {
                                $permitido = true;
                                break;
                            }
                        }
                    }
                    if ('deleteMethod' === $method->value && 'delete' === $action) {
                        if (empty($method->roles)) {
                            $permitido = true;
                            break;
                        }
                        foreach ($method->roles as $role) {
                            if ('ROLE_USER' === $role) {
                                $permitido = true;
                                break;
                            }
                        }
                    }
                }
            }

            if ($permitido) {
                $explode = explode('\\', $field->value);
                $tipo = str_replace('Type', '', $explode[count($explode) - 1]);
                switch ($tipo) {
                    case 'int':
                        $exampleJson[$field->name] = 1;
                        break;
                    case 'string':
                    case 'Text':
                        $exampleJson[$field->name] = $field->name;
                        break;
                    case 'Email':
                        $exampleJson[$field->name] = 'fulano@org.br';
                        break;
                    case 'bool':
                        $exampleJson[$field->name] = true;
                        break;
                    case 'Date':
                        $exampleJson[$field->name] = date('Y-m-d');
                        break;
                    case 'DateTime':
                        $exampleJson[$field->name] = date("Y-m-d\TH:i:s");
                        break;
                    case 'Enum':
                        $reflectionClass = new ReflectionClass($field->options['class']);
                        $exampleJson[$field->name] = implode(
                            '|',
                            array_map(
                                fn (BackedEnum $enum) => $enum->value,
                                $reflectionClass->getConstants()
                            )
                        );
                        break;
                    default:
                        $exampleJson[$field->name] = 1;
                }
            }
        }

        return $exampleJson;
    }
}
