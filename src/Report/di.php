<?php

declare(strict_types=1);

use App\Report\Service\SalesReportService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $container->parameters()
        ->set('reports_dir', '%kernel.project_dir%/var/reports');

    $services
        ->set(SalesReportService::class)
        ->arg('$reportsDir', '%reports_dir%');
};
