<?php

declare(strict_types=1);

use App\Product\EventListener\ExceptionListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\Product\\', __DIR__ . '/{Service,Controller,Repository}/*')
        ->exclude([__DIR__ . '/*/Request'])
        ->tag('controller.service_arguments');

    $services
        ->set(ExceptionListener::class)
        ->tag('kernel.event_listener', [
            'event' => 'kernel.exception',
            'method' => '__invoke',
        ]);
};
