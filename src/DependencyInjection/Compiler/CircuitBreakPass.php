<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DependencyInjection\Compiler;

use ReflectionClass;
use ReflectionException;
use SuppCore\AdministrativoBackend\CircuitBreaker\Model\CircuitBreak;
use SuppCore\AdministrativoBackend\CircuitBreaker\Model\CircuitBreakResource;
use SuppCore\AdministrativoBackend\CircuitBreaker\Services\CircuitBreakerService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Throwable;

/**
 * Class CircuitBreakPass.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CircuitBreakPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @return void
     *
     * @throws ReflectionException
     */
    public function process(ContainerBuilder $container): void
    {
        $circuitBreakerService = $container->getDefinition(CircuitBreakerService::class);
        foreach ($container->getDefinitions() as $definition) {
            try {
                if (!$definition->getClass() || $definition->isAbstract()) {
                    continue;
                }
                $reflectionClass = new ReflectionClass($definition->getClass());
                foreach ($reflectionClass->getAttributes(CircuitBreak::class) as $circuitBreak) {
                    /** @var CircuitBreak $circuitBreakInstance */
                    $circuitBreakInstance = $circuitBreak->newInstance();
                    $circuitBreakerService->addMethodCall(
                        'buildAndRegisterCircuitBreak',
                        [
                            $circuitBreakInstance->getServiceKey(),
                            $circuitBreakInstance->hasConfig()
                                ? $circuitBreakInstance->getConfig()->getTimeout()
                                : null,
                            $circuitBreakInstance->hasConfig()
                                ? $circuitBreakInstance->getConfig()->getThreshold()
                                : null,
                            $circuitBreakInstance->hasConfig()
                                ? $circuitBreakInstance->getConfig()->getExceptions()
                                : null,
                        ]
                    );
                }
                foreach ($reflectionClass->getAttributes(CircuitBreakResource::class) as $circuitBreakResource) {
                    /** @var CircuitBreakResource $circuitBreakResourceInstance */
                    $circuitBreakResourceInstance = $circuitBreakResource->newInstance();
                    $circuitBreakerService->addMethodCall(
                        'buildAndRegisterResource',
                        [
                            $circuitBreakResourceInstance->getResourceName(),
                            $circuitBreakResourceInstance->getServicesKeys()
                        ]
                    );
                }
            } catch (Throwable $e) {
            }
        }
    }
}
