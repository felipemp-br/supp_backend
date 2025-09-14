<?php

declare(strict_types=1);
/**
 * /src/Kernel.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend;

use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\AvaliacaoManagerPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\CircuitBreakPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\ConfigurationManagerPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\DossieManagerPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\ProtocoloExternoManagerPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\TipoRelatorioManagerPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\TopicManagerPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\TrilhaTriagemManagerPass;
use function dirname;
use Exception;
use function is_file;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\AdaptadorManagerPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\CounterManagerPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\CryptoManagerPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\KafkaTopicManagerPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\FieldsManagerPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\StylesManagerPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\FilesystemManagerPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\MapperManagerPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\NUPProviderManagerPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\PipesManagerPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\RolesServicePass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\RulesManagerPass;
use SuppCore\AdministrativoBackend\DependencyInjection\Compiler\TriggersManagerPass;
use SuppCore\AdministrativoBackend\Stopwatch\StopwatchCompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * Class Kernel.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Kernel extends BaseKernel
{
    // Traits
    use MicroKernelTrait;

    /**
     * The extension point similar to the Bundle::build() method.
     *
     * Use this method to register compiler passes and manipulate the container during the building process.
     *
     * @param ContainerBuilder $container
     */
    protected function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RulesManagerPass());
        $container->addCompilerPass(new FieldsManagerPass());
        $container->addCompilerPass(new StylesManagerPass());
        $container->addCompilerPass(new TriggersManagerPass());
        $container->addCompilerPass(new AdaptadorManagerPass());
        $container->addCompilerPass(new CounterManagerPass());
        $container->addCompilerPass(new DossieManagerPass());
        $container->addCompilerPass(new PipesManagerPass());
        $container->addCompilerPass(new MapperManagerPass());
        $container->addCompilerPass(new RolesServicePass());
        $container->addCompilerPass(new TipoRelatorioManagerPass());
        $container->addCompilerPass(new KafkaTopicManagerPass());
        $container->addCompilerPass(new TopicManagerPass());
        $container->addCompilerPass(new ConfigurationManagerPass());
        $container->addCompilerPass(new AvaliacaoManagerPass());
        $container->addCompilerPass(new NUPProviderManagerPass());
        $container->addCompilerPass(new CryptoManagerPass());
        $container->addCompilerPass(new FilesystemManagerPass());
        $container->addCompilerPass(new ProtocoloExternoManagerPass());
        $container->addCompilerPass(new TrilhaTriagemManagerPass());
        $container->addCompilerPass(new CircuitBreakPass());

        if ('dev' === $this->environment) {
            $container->addCompilerPass(new StopwatchCompilerPass());
        }
    }

    /**
     * Configures the container.
     *
     * You can register extensions:
     *
     * $c->loadFromExtension('framework', array(
     *     'secret' => '%secret%'
     * ));
     *
     * Or services:
     *
     * $c->register('halloween', 'FooBundle\HalloweenProvider');
     *
     * Or parameters:
     *
     * $c->setParameter('halloween', 'lot of fun');
     *
     * @param ContainerConfigurator $container
     *
     * @throws Exception
     */
    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/{packages}/*.yaml');
        $container->import('../config/{packages}/'.$this->environment.'/*.yaml');

        if (is_file(dirname(__DIR__).'/config/services.yaml')) {
            $container->import('../config/services.yaml');
            $container->import('../config/{services}_'.$this->environment.'.yaml');

            return;
        }

        $container->import('../config/{services}.php');
    }

    /**
     * Add or import routes into your application.
     *
     *     $routes->import('config/routing.yml');
     *     $routes->add('/admin', 'AppBundle:Admin:dashboard', 'admin_dashboard');
     *
     * @param RoutingConfigurator $routes
     */
    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('../config/{routes}/'.$this->environment.'/*.yaml');
        $routes->import('../config/{routes}/*.yaml');

        is_file(dirname(__DIR__).'/config/routes.yaml')
            ? $routes->import('../config/routes.yaml')
            : $routes->import('../config/{routes}.php');
    }
}
