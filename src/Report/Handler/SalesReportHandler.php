<?php

declare(strict_types=1);

namespace App\Report\Handler;

use App\Report\Message\SalesReportMessage;
use App\Report\Service\SalesReportService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SalesReportHandler
{
    public function __construct(private SalesReportService $reportService) {}

    public function __invoke(SalesReportMessage $message): void
    {
        $this->reportService->generate($message->uuid);
    }
}
