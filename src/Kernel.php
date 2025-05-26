<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait {
        configureContainer as baseConfigureContainer;
        configureRoutes as baseConfigureRoutes;
    }

    protected function configureContainer(
        ContainerConfigurator $container,
        LoaderInterface $loader,
        ContainerBuilder $builder,
    ): void {
        $this->baseConfigureContainer($container, $loader, $builder);

        $srcDir = $this->getProjectDir() . '/src';

        $container->import($srcDir . '/**/{di}.php');
        if (file_exists($envConfig = $srcDir . "/di_{$this->environment}.php")) {
            $container->import($envConfig);
        }
    }

    private function configureRoutes(RoutingConfigurator $routes): void
    {
        $this->baseConfigureRoutes($routes);

        $srcDir = $this->getProjectDir() . '/src';

        $routes->import($srcDir . '/**/{routing}.php');
        if (file_exists($envRoutes = $srcDir . "/routing_{$this->environment}.php")) {
            $routes->import($envRoutes);
        }
    }
}
