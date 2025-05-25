<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\User\\', __DIR__ . '/{Service,Controller,Repository}/*')
        ->exclude([__DIR__ . '/*/Request'])
        ->tag('controller.service_arguments');
};
