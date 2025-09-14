<?php

declare(strict_types=1);
/**
 * /src/Decorator/StopwatchDecorator.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Stopwatch;

use function array_filter;
use function is_object;
use ProxyManager\Factory\AccessInterceptorValueHolderFactory;
use ReflectionClass;
use ReflectionMethod;
use function str_contains;
use function str_starts_with;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Throwable;

/**
 * Class StopwatchDecorator.
 */
class StopwatchDecorator
{
    public function __construct(
        private AccessInterceptorValueHolderFactory $factory,
        private Stopwatch $stopwatch,
    ) {
    }

    public function decorate(object $service): object
    {
        $class = new ReflectionClass($service);
        $className = $class->getName();

        if (false === $class->getFileName()
            || $class->isFinal()
            || str_starts_with($class->getName(), 'ProxyManagerGeneratedProxy')
            || str_contains($class->getName(), 'RequestStack')
        ) {
            return $service;
        }

        [$prefixInterceptors, $suffixInterceptors] = $this->getPrefixAndSuffixInterceptors($class, $className);

        try {
            $output = $this->factory->createProxy($service, $prefixInterceptors, $suffixInterceptors);
        } catch (Throwable) {
            $output = $service;
        }

        return $output;
    }

    /**
     * @return array{0: array<string, \Closure>, 1: array<string, \Closure>}
     */
    private function getPrefixAndSuffixInterceptors(ReflectionClass $class, string $className): array
    {
        $prefixInterceptors = [];
        $suffixInterceptors = [];

        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
        $methods = array_filter($methods, static fn ($method): bool => !$method->isStatic() && !$method->isFinal());

        foreach ($methods as $method) {
            $methodName = $method->getName();
            $eventName = "{$class->getShortName()}->{$methodName}";

            $prefixInterceptors[$methodName] = function () use ($eventName, $className): void {
                $this->stopwatch->start($eventName, $className);
            };

            $suffixInterceptors[$methodName] = function (
                mixed $p,
                mixed $i,
                mixed $m,
                mixed $params,
                mixed &$returnValue
            ) use ($eventName): void {
                $this->stopwatch->stop($eventName);

                if (is_object($returnValue) && !$returnValue instanceof EntityInterface) {
                    $returnValue = $this->decorate($returnValue);
                }
            };
        }

        return [$prefixInterceptors, $suffixInterceptors];
    }
}
